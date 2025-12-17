import { defineStore } from "pinia";
import { fetchProducts } from "../api/pos";

export const useProductStore = defineStore("products", {
  state: () => ({
    items: [],
    loading: false,
  }),

  actions: {
    async loadProducts() {
      this.loading = true;

      // data test
      this.items = [
        {
          id: 4,
          name: "Teh Botol",
          sku: "TB001",
          price: 5000,
          stock: 50,
          barcode: "123456",
        },
        {
          id: 6,
          name: "Indomie Goreng",
          sku: "IM002",
          price: 3500,
          stock: 100,
          barcode: "234567",
        },
        {
          id: 7,
          name: "Kopi Hitam",
          sku: "KP003",
          price: 8000,
          stock: 30,
          barcode: "345678",
        },
      ];
      this.loading = false;

      //   try {
      //     const res = await fetchProducts();
      //     this.items = res.data;
      //   } finally {
      //     this.loading = false;
      //   }
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
  },
});
