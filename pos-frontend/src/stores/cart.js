import { defineStore } from "pinia";
import { checkoutCart } from "../api/pos";

export const useCartStore = defineStore("cart", {
  state: () => ({
    items: [],
    loading: false,
  }),

  getters: {
    subtotal(state) {
      return state.items.reduce(
        (sum, i) => sum + i.qty * Number(i.price || 0),
        0
      );
    },

    discount() {
      return 0;
    },

    total() {
      return this.subtotal - this.discount;
    },

    isEmpty(state) {
      return state.items.length === 0;
    },
  },

  actions: {
    add(product) {
      const row = this.items.find((i) => i.product_id === product.id);
      if (row) row.qty++;
      else {
        this.items.push({
          product_id: product.id,
          qty: 1,
          price: Number(product.price),
          product,
        });
      }
    },

    remove(productId) {
      this.items = this.items.filter((i) => i.product_id !== productId);
    },

    increase(productId) {
      const row = this.items.find((i) => i.product_id === productId);
      if (row) row.qty++;
    },

    decrease(productId) {
      const row = this.items.find((i) => i.product_id === productId);
      if (!row) return;
      row.qty > 1 ? row.qty-- : this.remove(productId);
    },

    async checkout(payment) {
      if (this.items.length === 0) {
        throw new Error("Cart kosong");
      }

      const payload = {
        shop_id: 1,
        paid: payment.paid,
        change: payment.change,
        payment_method: "cash",
        items: this.items.map((i) => ({
          product_id: i.product_id,
          qty: i.qty,
        })),
      };

      console.log("CHECKOUT PAYLOAD:", payload);

      this.loading = true;
      try {
        const res = await checkoutCart(payload);
        return res.data;
      } finally {
        this.loading = false;
      }
    },

    clear() {
      this.items = [];
    },
  },
});
