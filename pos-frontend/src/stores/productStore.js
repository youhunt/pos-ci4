import { defineStore } from 'pinia';
import { posDB } from '../db/posDB';

export const useProductStore = defineStore('products', {

  state: () => ({
    items: [],
    loading: false,
    lastLoadedAt: null
  }),

  actions: {

    // =========================
    // LOAD FROM INDEXEDDB
    // =========================
    async loadFromLocal(force = false)
    {
      this.loading = true;

      try {

        const db = await posDB;

        const items = await db.getAll('products');

        // 🔥 FIX REACTIVITY: replace reference properly
        this.items = [];

        this.items = [...items];

        this.lastLoadedAt = Date.now();

        console.log("PRODUCT STORE LOADED:", this.items.length);

      }
      catch (err)
      {
        console.error("LOAD PRODUCTS FAILED:", err);
      }

      this.loading = false;

    },


    // =========================
    // FIND BY BARCODE
    // =========================
    async findByBarcode(barcode)
    {
      const db = await posDB;

      const product = await db.getFromIndex('products', 'barcode', barcode);

      return product || null;
    },


    // =========================
    // SEARCH
    // =========================
    search(q)
    {
      if (!q) return this.items;

      q = q.toLowerCase();

      return this.items.filter(p =>
        p.name?.toLowerCase().includes(q) ||
        p.sku?.toLowerCase().includes(q) ||
        p.barcode?.includes(q)
      );
    },


    // =========================
    // FORCE REFRESH
    // =========================
    async refresh()
    {
      await this.loadFromLocal(true);
    }

  }

});