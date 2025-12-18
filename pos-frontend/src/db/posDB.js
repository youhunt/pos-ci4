import { openDB } from 'idb';

export const posDB = openDB('pos-db', 1, {
  upgrade(db) {
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
});
