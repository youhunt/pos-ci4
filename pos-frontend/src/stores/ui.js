import { defineStore } from "pinia";

export const useUIStore = defineStore("ui", {
  state: () => ({
    searchQuery: "",
    showPayment: false,
  }),
});
