import { openDB } from 'idb';

export const posDB = openDB('pos-db', 2, {
  upgrade(db, oldVersion) {

    // ===== v1 =====
    if (oldVersion < 1) {
      const productStore = db.createObjectStore('products', { keyPath: 'id' });
      productStore.createIndex('barcode', 'barcode');
      productStore.createIndex('name', 'name');
      productStore.createIndex('category_id', 'category_id');

      db.createObjectStore('categories', { keyPath: 'id' });
      db.createObjectStore('meta', { keyPath: 'key' });
    }

    // ===== v2 PROMOS (FIXED) =====
    if (oldVersion < 2) {
      const promoStore = db.createObjectStore('promos', {
        keyPath: 'product_id' // 🔥 INI KUNCINYA
      });

      promoStore.createIndex('promo_id', 'promo_id');
      promoStore.createIndex('type', 'type');
      promoStore.createIndex('active', 'active');
    }
  }
});

