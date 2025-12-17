// Clean, modular, offline‑first POS app.js
// ----------------------------------------
// This contains: IndexedDB handler, API sync, promo sync, cart, UI render.
// SAFE TO PLACE IN public/app.js

(() => {
  const DB_NAME = "posdbv1";
  const DB_VERSION = 1;
  let dbInstance = null;

  const STORES = ["products", "promos", "cart", "promoProducts", "meta"];

  const BASE_URL = "http://localhost:8080";
  const SHOP_ID = 1;
  const SYNC_TIMEOUT_MS = 5000;

  let PRODUCTS = [];
  let PROMOS = [];
  let CART = [];
  let PROMO_PRODUCTS = [];

  // ============================================================
  // IndexedDB
  // ============================================================
  function ensureDB() {
    if (dbInstance) return Promise.resolve(dbInstance);
    return initDB();
  }

  function initDB() {
    return new Promise((resolve, reject) => {
      const req = indexedDB.open(DB_NAME, DB_VERSION);

      req.onerror = (e) => reject(e);

      req.onupgradeneeded = (e) => {
        const db = e.target.result;
        for (const s of STORES) {
          if (!db.objectStoreNames.contains(s)) {
            db.createObjectStore(s, { keyPath: "id" });
          }
        }
      };

      req.onsuccess = (e) => {
        dbInstance = e.target.result;
        resolve(dbInstance);
      };
    });
  }

  function dbTransaction(store, mode = "readonly") {
    if (!dbInstance) throw new Error("DB not initialized");
    if (!dbInstance.objectStoreNames.contains(store)) {
      throw new Error("ObjectStore not found: " + store);
    }
    return dbInstance.transaction(store, mode).objectStore(store);
  }

  function dbGetAll(store) {
    return new Promise(async (resolve, reject) => {
      try {
        await ensureDB();
        const obj = dbTransaction(store, "readonly");
        const req = obj.getAll();
        req.onsuccess = () => resolve(req.result || []);
        req.onerror = (e) => reject(e);
      } catch (err) {
        reject(err);
      }
    });
  }

  function dbPut(store, item) {
    return new Promise(async (resolve, reject) => {
      try {
        await ensureDB();
        const obj = dbTransaction(store, "readwrite");
        const req = obj.put(item);
        req.onsuccess = () => resolve(true);
        req.onerror = (e) => reject(e);
      } catch (err) {
        reject(err);
      }
    });
  }

  function dbClear(store) {
    return new Promise(async (resolve, reject) => {
      try {
        await ensureDB();
        const obj = dbTransaction(store, "readwrite");
        const req = obj.clear();
        req.onsuccess = () => resolve();
        req.onerror = (e) => reject(e);
      } catch (err) {
        reject(err);
      }
    });
  }

  // ============================================================
  // API Helper
  // ============================================================
  async function fetchWithTimeout(
    url,
    options = {},
    timeout = SYNC_TIMEOUT_MS
  ) {
    return new Promise((resolve, reject) => {
      const controller = new AbortController();
      const id = setTimeout(() => controller.abort(), timeout);
      fetch(url, { ...options, signal: controller.signal })
        .then((res) => {
          clearTimeout(id);
          resolve(res);
        })
        .catch((err) => {
          clearTimeout(id);
          reject(err);
        });
    });
  }

  // ============================================================
  // UI RENDER
  // ============================================================
  function renderProducts() {
    const el = document.getElementById("product-list");
    if (!el) return;
    el.innerHTML = PRODUCTS.map(
      (p) => `
            <div class="item" onclick="addToCart(${p.id})">
                <div>${p.name}</div>
                <div><del>${p.price_before}</del> <b>${p.price_after}</b></div>
                <div>${p.discount_label || ""}</div>
            </div>
        `
    ).join("");
  }

  function renderCart() {
    const el = document.getElementById("cart-items");
    const totalEl = document.getElementById("cart-total");
    if (!el) return;

    let total = 0;
    el.innerHTML = CART.map((c) => {
      const p = PRODUCTS.find((x) => x.id === c.id);
      const price = p?.price_after || 0;
      total += price * c.qty;
      return `<div>${p?.name ?? "?"} x ${c.qty} = ${price * c.qty}</div>`;
    }).join("");

    if (totalEl) totalEl.textContent = total;
  }

  function renderPromosActive() {
    const el = document.getElementById("promo-list");
    if (!el) return;
    el.innerHTML = PROMOS.map(
      (p) => `
            <div class="promo">${p.name} - ${p.status}</div>
        `
    ).join("");
  }

  function renderPromoProducts() {
    const el = document.getElementById("promo-products");
    if (!el) return;
    el.innerHTML = PROMO_PRODUCTS.map(
      (pr) => `
            <div>${pr.product_id} : ${pr.discount_label}</div>
        `
    ).join("");
  }

  // ============================================================
  // CART
  // ============================================================
  async function addToCart(id) {
    const exist = CART.find((c) => c.id === id);
    if (exist) exist.qty++;
    else CART.push({ id, qty: 1 });
    await dbPut("cart", { id, qty: exist ? exist.qty : 1 });
    renderCart();
  }

  async function checkout() {
    const payload = {
      shop_id: SHOP_ID,
      items: CART,
    };

    try {
      const res = await fetchWithTimeout(`${BASE_URL}/api/pos/checkout`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });

      if (!res.ok) throw new Error("server error");
      await dbClear("cart");
      CART = [];
      renderCart();
    } catch (err) {
      console.warn("Checkout offline → simpan pending");
      await addPendingPush(payload);
    }
  }

  async function addPendingPush(payload) {
    const meta = await dbGetAll("meta");
    const pending = meta.find((m) => m.id === "pendingPushes")?.value || [];
    pending.push({ id: Date.now(), payload });
    await dbPut("meta", { id: "pendingPushes", value: pending });
  }

  // ============================================================
  // SYNC FROM SERVER
  // ============================================================
  async function syncServerToLocal() {
    try {
      const [productsRes, promoRes, ppRes] = await Promise.all([
        fetch(`${BASE_URL}/api/promo/products?shop_id=${SHOP_ID}`),
        fetch(`${BASE_URL}/api/promo/active?shop_id=${SHOP_ID}`),
        fetch(`${BASE_URL}/api/promo/products?shop_id=${SHOP_ID}`),
      ]);

      const products = await productsRes.json();
      const promos = await promoRes.json();
      const promoProducts = await ppRes.json();

      await dbClear("products");
      for (const p of products) await dbPut("products", p);

      await dbClear("promos");
      for (const p of promos) await dbPut("promos", p);

      await dbClear("promoProducts");
      for (const p of promoProducts) await dbPut("promoProducts", p);

      console.log("Sync OK");
    } catch (err) {
      console.log("Sync gagal (offline?)", err);
    }
  }

  async function tryPushPending() {
    const meta = await dbGetAll("meta");
    const pMeta = meta.find((m) => m.id === "pendingPushes");
    if (!pMeta || !Array.isArray(pMeta.value) || pMeta.value.length === 0)
      return;

    const pendingList = [...pMeta.value];
    let stillPending = [];

    for (const item of pendingList) {
      try {
        const res = await fetchWithTimeout(`${BASE_URL}/api/pos/checkout`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(item.payload),
        });

        if (!res.ok) stillPending.push(item);
        else console.log("Push pending OK", item.id);
      } catch (err) {
        stillPending.push(item);
      }
    }

    await dbPut("meta", { id: "pendingPushes", value: stillPending });
  }

  // ============================================================
  // LOAD INITIAL DATA
  // ============================================================
  async function loadInitialData() {
    console.log("LOAD A – products");
    PRODUCTS = await dbGetAll("products");

    console.log("LOAD B – promos");
    PROMOS = await dbGetAll("promos");

    console.log("LOAD C – cart");
    CART = await dbGetAll("cart");

    console.log("LOAD D – promoProducts");
    PROMO_PRODUCTS = await dbGetAll("promoProducts");

    console.log("LOAD E – mapping done");

    PRODUCTS = PRODUCTS.map((p) => ({
      id: p.id,
      name: p.name,
      price_before: p.price_before ?? p.price ?? 0,
      price_after: p.price_after ?? p.price ?? 0,
      discount_label: p.discount_label || "",
    }));

    console.log("renderProducts()");
    renderProducts();
    console.log("renderPromosActive()");
    renderPromosActive();
    console.log("renderPromoProducts()");
    renderPromoProducts();
    console.log("renderCart()");
    renderCart();
  }

  // ============================================================
  // START
  // ============================================================
  async function startPOS() {
    await ensureDB();
    await syncServerToLocal();
    await tryPushPending();
    await loadInitialData();
  }

  document.addEventListener("DOMContentLoaded", startPOS);

  // expose for onclick
  window.addToCart = addToCart;
})();
