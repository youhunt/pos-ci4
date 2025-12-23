import { defineStore } from 'pinia'
import { posDB } from '../db/posDB'

export const usePromoStore = defineStore('promos', {
  state: () => ({
    items: [],
    loading: false,
  }),

  actions: {
    async loadFromLocal() {
      this.loading = true
      const db = await posDB
      this.items = await db.getAll('promos')
      console.log('PROMOS IN DB:', this.items)
      this.loading = false
    },

    getPromoForProduct(productId) {
      return this.items.find(
        p =>
          p.active &&
          p.type === 'product' &&
          p.product_id === productId &&
          this.isPromoActiveNow(p)
      )
    },

    isPromoActiveNow(promo) {
      const now = new Date()

      // cek tanggal
      if (promo.start_date && new Date(promo.start_date) > now) return false
      if (promo.end_date && new Date(promo.end_date) < now) return false

      // cek hari
      if (promo.weekdays) {
        const today = now.getDay() === 0 ? 7 : now.getDay()
        const days = promo.weekdays.split(',').map(Number)
        if (!days.includes(today)) return false
      }

      // cek jam
      if (promo.start_time && promo.end_time) {
        const time = now.toTimeString().slice(0, 5)
        if (time < promo.start_time || time > promo.end_time) return false
      }

      return true
    }
  }
})
