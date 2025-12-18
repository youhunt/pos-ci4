import axios from 'axios';
import { posDB } from '../db/posDB';

const api = axios.create({
  baseURL: "http://localhost:8080/api",
  timeout: 10000, // ⏱️ 10 detik, biar gak freeze
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

  // 🔴 FIX DI SINI
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
}

