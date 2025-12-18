import { defineStore } from 'pinia';
import { posDB } from '../db/posDB';

export const useCategoryStore = defineStore('categories', {
  state: () => ({
    categories: []
  }),

  actions: {
    async loadFromLocal() {
      const db = await posDB;
      this.categories = await db.getAll('categories');
    }
  }
});
