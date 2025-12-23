import { openDB } from 'idb';

export const posDB = openDB('pos-db', 2, {
  upgrade(db, oldVersion) {

    // ===== v1 =====
    if (oldVersion < 1) {
      if (!db.objectStoreNames.contains('products')) {
        const store = db.createObjectStore('products', { keyPath: 'id' });
        store.createIndex('barcode', 'barcode');
        store.createIndex('name', 'name');
        store.createIndex('category_id', 'category_id');
      }

      if (!db.objectStoreNames.contains('categories')) {
        db.createObjectStore('categories', { keyPath: 'id' });
      }

      if (!db.objectStoreNames.contains('meta')) {
        db.createObjectStore('meta', { keyPath: 'key' });
      }
    }

    // ===== v2 (PROMOS) =====
    if (oldVersion < 2) {
      if (!db.objectStoreNames.contains('promos')) {
        const promoStore = db.createObjectStore('promos', { keyPath: 'id' });
        promoStore.createIndex('product_id', 'product_id');
        promoStore.createIndex('category_id', 'category_id');
        promoStore.createIndex('active', 'active');
      }
    }
  }
});
