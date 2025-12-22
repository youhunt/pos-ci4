import { defineStore } from "pinia";
import { fetchActivePromos } from "@/api/promo";

export const usePromoStore = defineStore("promo", {
  state: () => ({
    promos: [],
  }),

  actions: {
    async load() {
      const res = await fetchActivePromos();
      this.promos = res.data;
    },

    getPromoForProduct(productId) {
      return this.promos.find(
        p => p.type === "product" && p.product_id === productId
      );
    }
  }
});
