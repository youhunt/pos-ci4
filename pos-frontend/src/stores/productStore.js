import { defineStore } from 'pinia';
import { posDB } from '../db/posDB';

export const useProductStore = defineStore('products', {
  state: () => ({
    items: [],
    loading: false
  }),

  actions: {
    async loadFromLocal() {
      this.loading = true;
      const db = await posDB;
      this.items = await db.getAll('products');
      this.loading = false;
    },

    async findByBarcode(barcode) {
      const db = await posDB;
      return db.getFromIndex('products', 'barcode', barcode);
    },

    search(q) {
      q = q.toLowerCase();
      return this.items.filter(
        (p) =>
          p.name.toLowerCase().includes(q) ||
          p.sku.toLowerCase().includes(q) ||
          (p.barcode && p.barcode.includes(q))
      );
    },
  }
});
