/** ===================================================================
 *  INIT APLIKASI
 * =================================================================== */
document.addEventListener("DOMContentLoaded", initApp);

async function initApp() {

    // Pastikan DB siap
    await posDB.init();

    // Sync awal kalau online
    if (navigator.onLine) await posSync.manualSync();

    // Event navigasi
    document.querySelectorAll(".nav-btn").forEach(btn => {
        btn.addEventListener("click", () => switchPage(btn.dataset.page));
    });

    // Tombol dashboard → POS
    document.getElementById("go-pos").addEventListener("click", () => {
        switchPage("pos");
    });

    // Sync dari dashboard
    document.getElementById("btn-sync-dashboard").addEventListener("click", async () => {
        await posSync.manualSync();
        await updateDashboard();
        alert("Sync selesai!");
    });

    // Tombol checkout
    document.getElementById("btn-checkout").addEventListener("click", checkout);

    // Load awal
    loadProductsUI();
    loadTransactionsUI();
    updateDashboard();
}

/** ===================================================================
 *  PAGE SWITCHING
 * =================================================================== */
function switchPage(pageName) {
    document.querySelectorAll(".page").forEach(p => p.classList.remove("active"));
    document.getElementById("page-" + pageName).classList.add("active");

    document.querySelectorAll(".nav-btn").forEach(b => b.classList.remove("active"));
    document.querySelector(`.nav-btn[data-page="${pageName}"]`).classList.add("active");

    if (pageName === "dashboard") updateDashboard();
    if (pageName === "transactions") loadTransactionsUI();
    if (pageName === 'discounts') {
        loadPromos();
        loadPromoProducts();
    }
}

/** ===================================================================
 *  RENDER PRODUK
 * =================================================================== */
async function loadProductsUI() {
    const el = document.getElementById("product-list");
    el.innerHTML = "<p>Loading...</p>";

    const products = await posDB.getProducts();
    if (!products.length) {
        el.innerHTML = "<p>Tidak ada produk.</p>";
        return;
    }

    el.innerHTML = "";

    products.forEach(p => {

        // 🔥 Hitung diskon
        let finalPrice = Number(p.price);
        let discountLabel = "";

        if (p.discount_type === "percent") {
            finalPrice = p.price - (p.price * p.discount_value / 100);
            discountLabel = `-${p.discount_value}%`;
        }

        if (p.discount_type === "amount") {
            finalPrice = p.price - p.discount_value;
            discountLabel = `-Rp${Number(p.discount_value).toLocaleString()}`;
        }

        if (finalPrice < 0) finalPrice = 0;

        const stock = Number(p.stock ?? 0);
        const disabled = stock <= 0 ? "disabled" : "";

        // 🔥 Card produk dengan badge diskon
        const card = document.createElement("div");
        card.className = "product-card";

        card.innerHTML = `
            ${discountLabel ? `<div class="discount-badge">${discountLabel}</div>` : ''}
            
            <h3>${p.name}</h3>

            <p class="price">
                ${discountLabel ? `<span class="old-price">Rp ${Number(p.price).toLocaleString()}</span>` : ""}
                <span class="final-price">Rp ${Number(finalPrice).toLocaleString()}</span>
            </p>

            <p>Stok: ${stock}</p>

            <button class="btn-add-cart" ${disabled}>
                ${stock <= 0 ? "Out of Stock" : "Add to Cart"}
            </button>
        `;

        el.appendChild(card);

        if (stock > 0) {
            card.querySelector(".btn-add-cart").addEventListener("click", () => {
                addToCart({
                    ...p,
                    final_price: finalPrice
                });
            });
        }
    });
}


/** ===================================================================
 *  CART
 * =================================================================== */
let cart = [];

function addToCart(product) {
    let exist = cart.find(i => i.id == product.id);
    if (exist) exist.qty++;
    else cart.push({ ...product, qty: 1 });

    renderCart();
}

function renderCart() {
    const el = document.getElementById("cart-items");
    const subtotalEl = document.getElementById("subtotal");
    const discountEl = document.getElementById("discount");
    const totalEl = document.getElementById("total");

    if (!cart.length) {
        el.innerHTML = "<p>Belum ada item</p>";
        subtotalEl.textContent = "0";
        discountEl.textContent = "0";
        totalEl.textContent = "0";
        return;
    }

    el.innerHTML = "";
    let subtotal = 0;

    cart.forEach(item => {
        subtotal += item.qty * item.price;

        const row = document.createElement("div");
        row.className = "cart-item";
        row.innerHTML = `
            <span>${item.name} x${item.qty}</span>
            <span>Rp ${(item.qty * item.price).toLocaleString()}</span>
        `;
        el.appendChild(row);
    });

    subtotalEl.textContent = subtotal.toLocaleString();
    discountEl.textContent = "0";
    totalEl.textContent = subtotal.toLocaleString();
}

/** ===================================================================
 *  CHECKOUT (OFFLINE)
 * =================================================================== */
async function checkout() {
    if (cart.length === 0) {
        alert("Keranjang kosong!");
        return;
    }

    const paymentMethod = document.getElementById('payment-method').value;

    const total = cart.reduce((a, b) => a + (b.qty * b.price), 0);

    // === FORMAT WAJIB SAMA PERSIS DENGAN API SERVER ===
    const txn = {
        id: null,                // server akan isi
        local_id: "local-" + Date.now(),

        shop_id: "1",            // sementara hardcode
        user_id: "2",            // sementara hardcode

        invoice: null,           // server yang generate

        total_amount: String(total),
        discount_amount: "0.00",
        total_paid: String(total),

        payment_status: "paid",

        created_at: new Date().toISOString(),
        updated_at: null,
        synced_at: null,

        synced: false,          // FLAG TRANSAKSI OFFLINE

        items: cart.map(item => ({
            id: null,
            transaction_id: null,
            product_id: String(item.id),
            qty: String(item.qty),
            price: String(item.price),
            subtotal: String(item.price * item.qty)
        })),

        payments: [
            {
                id: null,
                transaction_id: null,
                method: paymentMethod,
                amount: String(total),
                created_at: new Date().toISOString()
            }
        ]
    };

    // SIMPAN KE IndexedDB
    await posDB.saveTransaction(txn);

    alert("Transaksi offline tersimpan!");

    cart = [];
    renderCart();
    renderPendingTransactions();
}

/** ===================================================================
 *  RENDER TRANSAKSI PENDING
 * =================================================================== */
async function renderPendingTransactions() {
  const tx = db.transaction(['transactions'], 'readonly');
  const store = tx.objectStore('transactions');

  const req = store.getAll();

  req.onsuccess = () => {
    const data = req.result || [];
    const list = document.getElementById('pending-list');
    if (!list) return;

    list.innerHTML = '';

    data.forEach(t => {
      const row = document.createElement('div');
      row.className = 'pending-item';
      row.innerHTML = `
        <div class="left">
          <div class="inv">${t.invoice || '(local)'}</div>
          <div class="date">${t.created_at || '-'}</div>
        </div>
        <div class="right">
          <div class="amount">Rp ${Number(t.total_amount).toLocaleString()}</div>
        </div>
      `;
      list.appendChild(row);
    });
  };
}

/** ===================================================================
 *  TRANSAKSI LIST
 * =================================================================== */
async function loadTransactionsUI() {
    const el = document.getElementById("transaction-list");
    el.innerHTML = "<p>Loading...</p>";

    const tx = await posDB.getTransactions();

    if (!tx.length) {
        el.innerHTML = "<p>Belum ada transaksi.</p>";
        return;
    }

    el.innerHTML = "";

    tx.forEach(t => {
        const div = document.createElement("div");
        div.className = "transaction-card";
        div.innerHTML = `
            <h4>${t.invoice ?? t.local_id}</h4>
            <p>Total: Rp ${Number(t.total_amount).toLocaleString()}</p>
            <p>Status: ${t.synced_at ? "Synced" : "Pending"}</p>
            <p>Tanggal: ${t.created_at}</p>
        `;
        el.appendChild(div);
    });
}

/** ===================================================================
 *  DASHBOARD
 * =================================================================== */
async function updateDashboard() {
    const products = await posDB.getProducts();
    const pending = await posDB.getPendingTransactions();
    const allTx = await posDB.getTransactions();

    document.getElementById("sum-products").textContent = products.length;
    document.getElementById("sum-pending").textContent = pending.length;

    const today = new Date().toISOString().slice(0, 10);

    const totalToday = allTx
        .filter(t => t.created_at?.startsWith(today))
        .reduce((sum, t) => sum + Number(t.total_amount || 0), 0);

    document.getElementById("sum-today").textContent =
        "Rp " + totalToday.toLocaleString("id-ID");
}

/** ===================================================================
 *  PROMO CALCULATION
 * =================================================================== */
function calculatePrice(product, promos, promoProducts) {
    const now = new Date();
    let discount = 0;

    // 1. Promo langsung ke produk
    const pp = promoProducts.find(x => x.product_id == product.id);
    if (pp) discount = pp.discount_value;

    // 2. Promo kategori
    const catPromo = promos.find(p =>
        p.type === 'category' &&
        p.category_id == product.category_id &&
        new Date(p.start_date) <= now &&
        new Date(p.end_date) >= now
    );

    if (catPromo) {
        if (catPromo.mode === 'percent') {
            discount = product.price * (catPromo.value / 100);
        } else if (catPromo.mode === 'fixed') {
            discount = catPromo.value;
        }
    }

    const finalPrice = Math.max(0, product.price - discount);
    return { discount, finalPrice };
}

async function loadPromos() {
    const res = await fetch('/api/promo/active');
    const promos = await res.json();

    let html = '';
    promos.forEach(p => {
        html += `
            <div class="promo-card">
                <h4>${p.name}</h4>
                <p>${p.type} - ${p.value}</p>
                <small>Berlaku: ${p.start_date} → ${p.end_date}</small>
            </div>
        `;
    });

    document.getElementById('promo-list').innerHTML = html || "<p>Tidak ada promo aktif</p>";
}

async function loadPromoProducts() {
    const res = await fetch('/api/promo/products');
    const products = await res.json();

    let html = '';
    products.forEach(p => {
        html += `
            <div class="promo-product">
                <h4>${p.name}</h4>
                <p>Harga: Rp ${p.price}</p>
            </div>
        `;
    });

    document.getElementById('promo-products').innerHTML = html || "<p>Tidak ada produk diskon</p>";
}
/** ===================================================================*/