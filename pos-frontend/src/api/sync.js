import axios from 'axios';
import { posDB } from '../db/posDB';

const api = axios.create({
  baseURL: "http://localhost:8080/api",
  timeout: 10000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export async function syncProducts(shopId) {

  const db = await posDB;
  const meta = await db.get('meta', 'products_last_sync');

  const res = await api.get('/sync/products', {
    params: {
      shop_id: shopId,
      since: meta?.value || null
    }
  });

  const payload = res.data.payload;

  if (!payload || !Array.isArray(payload.items)) {
    console.warn('No product payload received', res.data);
    return;
  }

  const tx = db.transaction(['products', 'meta'], 'readwrite');

  for (const item of payload.items) {
    await tx.objectStore('products').put(item);
  }

  if (payload.last_sync) {
    await tx.objectStore('meta').put({
      key: 'products_last_sync',
      value: payload.last_sync
    });
  }

  await tx.done;

  console.log("PRODUCTS SYNCED:", payload.items.length);
}

export async function syncCategories(shopId) {

  const db = await posDB;
  const meta = await db.get('meta', 'categories_last_sync');

  const res = await api.get('/sync/categories', {
    params: {
      shop_id: shopId,
      since: meta?.value || null
    }
  });

  const payload = res.data.payload;

  if (!payload || !Array.isArray(payload.items)) {
    console.warn('No category payload received', res.data);
    return;
  }

  const tx = db.transaction(['categories', 'meta'], 'readwrite');

  for (const item of payload.items) {
    await tx.objectStore('categories').put(item);
  }

  if (payload.last_sync) {
    await tx.objectStore('meta').put({
      key: 'categories_last_sync',
      value: payload.last_sync
    });
  }

  await tx.done;

  console.log("CATEGORIES SYNCED:", payload.items.length);
}

export async function syncPromos(shopId) {

  const res = await api.get('/promos/active', {
    params: { shop_id: shopId }
  });

  const promoMap = res.data?.data || res.data;

  if (!promoMap || typeof promoMap !== 'object') {
    console.warn('No promo data');
    return;
  }

  const db = await posDB;
  const tx = db.transaction('promos', 'readwrite');
  const store = tx.objectStore('promos');

  for (const [productId, promo] of Object.entries(promoMap)) {

    await store.put({
      product_id: Number(productId),
      promo_id: Number(promo.promo_id),
      type: promo.type,
      value: Number(promo.value),
    });

  }

  await tx.done;

  console.log('PROMOS SYNCED:', Object.keys(promoMap).length);
}


// =========================
// SYNC ALL (TAMBAHAN)
// =========================
export async function syncAll(shopId) {

  try {

    console.log("SYNC ALL START");

    await syncCategories(shopId);

    await syncProducts(shopId);

    await syncPromos(shopId);

    console.log("SYNC ALL DONE");
    
    window.dispatchEvent(new Event("pos-sync-complete"));

  }
  catch (err)
  {
    console.error("SYNC ALL FAILED:", err);
    throw err;
  }

}