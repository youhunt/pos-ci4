// src/stores/promoStore.js
import { defineStore } from 'pinia'
import { posDB } from '../db/posDB'

export const usePromoStore = defineStore('promos', {
  state: () => ({
    items: new Map(), // product_id → promo
    loaded: false
  }),

  actions: {
    async loadFromLocal() {
      const db = await posDB
      const all = await db.getAll('promos')

      this.items = new Map()
      for (const promo of all) {
        this.items.set(promo.product_id, promo)
      }

      this.loaded = true
      console.log('PROMOS LOADED:', this.items.size)
    },

    getByProduct(productId) {
      return this.items.get(Number(productId)) || null
    }
  }
})
