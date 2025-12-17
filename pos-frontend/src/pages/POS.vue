<template>
  <div class="flex w-full h-full text-lg">
    <!-- LEFT PANEL: Produk -->
    <div class="w-3/12 bg-white border-r p-4 overflow-y-auto">
      <SearchBar v-model="ui.searchQuery" />

      <ProductGrid :products="filteredProducts" @select="addToCart" />
    </div>

    <!-- MIDDLE PANEL: Cart -->
    <div class="w-6/12 bg-gray-50 p-4 overflow-y-auto">
      <CartTable />
    </div>

    <!-- RIGHT PANEL: Totals -->
    <div class="w-3/12 bg-white border-l p-4">
      <TotalsPanel
        :totals="{
          subtotal: cart.subtotal,
          discount: cart.discount,
          total: cart.total
        }"
        :is-empty="cart.isEmpty"
        @checkout="openPayment"
        />
    </div>
  </div>
<PaymentModal
  v-if="showPayment"
  :total="cart.total"
  @confirm="onConfirmPayment"
  @close="showPayment = false"
/>
<ReceiptModal
  v-if="showReceipt"
  :receipt="receipt"
  @close="showReceipt = false"
/>

</template>

<script setup>
import { computed, onMounted } from "vue";
import { useProductStore } from "../stores/product";
import { useCartStore } from "../stores/cart";
import { useUIStore } from "../stores/ui";
import { ref } from "vue";
import { fetchReceipt } from "../api/pos";

import SearchBar from "../components/pos/SearchBar.vue";
import ProductGrid from "../components/pos/ProductGrid.vue";
import CartTable from "../components/pos/CartTable.vue";
import TotalsPanel from "../components/pos/TotalsPanel.vue";
import PaymentModal from "../components/pos/PaymentModal.vue";
import ReceiptModal from "../components/receipt/ReceiptModal.vue";

const products = useProductStore();
const cart = useCartStore();
const ui = useUIStore();
const showPayment = ref(false);
const receipt = ref(null);
const showReceipt = ref(false);

onMounted(() => {
  products.loadProducts();
});

const filteredProducts = computed(() => {
  if (!ui.searchQuery) return products.items;
  return products.search(ui.searchQuery);
});

function addToCart(p) {
  cart.add(p);
}

function openPayment() {
  showPayment.value = true;
}

async function onConfirmPayment(payment) {
  console.log("ON CONFIRM PAYMENT:", payment); // ⬅️ WAJIB muncul

  try {
    const res = await cart.checkout({
      paid: payment.paid,
      change: payment.change,
    });

    const receiptRes = await fetchReceipt(res.transaction_id);

    receipt.value = receiptRes.data;
    showReceipt.value = true;

    cart.clear();
    showPayment.value = false;

  } catch (e) {
    console.error("CHECKOUT ERROR:", e);
    alert("Gagal bayar");
  }
}

</script>
