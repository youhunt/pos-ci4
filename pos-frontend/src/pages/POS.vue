<template>
  <div class="hidden md:flex w-full h-full">
    <!-- SELURUH POS -->
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

</div>

<div class="md:hidden h-screen flex items-center justify-center bg-gray-100">
  <div class="text-center p-6">
    <h2 class="text-xl font-bold mb-2">POS Tidak Tersedia</h2>
    <p class="text-gray-600">
      Gunakan tablet atau desktop untuk mengakses POS.
    </p>
  </div>
</div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { onBeforeUnmount } from "vue";
// import { useProductStore } from "../stores/product";
import { useCartStore } from "../stores/cart";
import { useUIStore } from "../stores/ui";
import { ref } from "vue";
import { fetchReceipt } from "../api/pos";
import { syncProducts, syncCategories } from '../api/sync';
import { useProductStore } from '../stores/productStore';
import { useCategoryStore } from '../stores/categoryStore';
import { syncPromos } from '../api/sync'
import { usePromoStore } from '../stores/promo'

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
//const productStore = useProductStore();
const categoryStore = useCategoryStore();
const promoStore = usePromoStore()

onMounted(async () => {
  const shopId = 1;

  // 1. Sync dari server
  await syncCategories(shopId);
  await syncProducts(shopId);
  await syncPromos(shopId)
  await promoStore.loadFromLocal()

  console.log('PROMOS READY:', promoStore.items)

  // 2. Load dari local DB
  await categoryStore.loadFromLocal();
  await products.loadFromLocal();

  //window.addEventListener("keydown", onKeydown);
  document.addEventListener("keyup", onKeyup);
  document.addEventListener("paste", onPaste);

});

onBeforeUnmount(() => {
  document.removeEventListener("keyup", onKeyup);
  document.removeEventListener("paste", onPaste);
});

const filteredProducts = computed(() => {
  if (!ui.searchQuery) return products.items;
  return products.search(ui.searchQuery);
});

function addToCart(p) {
  cart.add(p);
}

function openPayment() {
  if (cart.isEmpty) {
    alert("Cart masih kosong");
    return;
  }
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

// ================================
// BARCODE SCANNER (keyboard wedge)
// ================================
const barcodeBuffer = ref("");
let barcodeTimer = null;

function onKeydown(e) {
  // Abaikan kalau sedang ngetik di input / textarea
  const tag = document.activeElement?.tagName;
  if (tag === "INPUT" || tag === "TEXTAREA") return;

  // ENTER = barcode selesai
  if (e.key === "Enter") {
    e.preventDefault();

    const code = barcodeBuffer.value.trim();
    barcodeBuffer.value = "";

    if (code) {
      handleBarcode(code);
    }
    return;
  }

  // Terima karakter barcode
  if (/^[a-zA-Z0-9]$/.test(e.key)) {
    barcodeBuffer.value += e.key;

    // clearTimeout(barcodeTimer);
    // barcodeTimer = setTimeout(() => {
    //   barcodeBuffer.value = "";
    // }, 50); // scanner cepat
  }
}

function onKeyup(e) {
  const tag = document.activeElement?.tagName;
  const isInput = tag === "INPUT" || tag === "TEXTAREA";

  // ENTER = proses barcode
  if (e.key === "Enter" || e.key === "NumpadEnter") {
    e.preventDefault();

    const code = barcodeBuffer.value.trim();
    barcodeBuffer.value = "";

    if (code.length >= 8) {
      handleBarcode(code);
    }

    return;
  }

  // terima angka saja
  if (e.key >= "0" && e.key <= "9") {
    barcodeBuffer.value += e.key;
  }
}

function onPaste(e) {
  const pasted = (e.clipboardData || window.clipboardData)
    .getData("text")
    .trim();

  console.log("PASTE DETECTED:", pasted);

  // hanya terima angka barcode
  if (/^\d{8,}$/.test(pasted)) {
    handleBarcode(pasted);

    barcodeBuffer.value = "";

    e.preventDefault();
  }
}

async function handleBarcode(barcode) {

  const product = await products.findByBarcode(barcode);

  if (!product) {
    alert("Produk tidak ditemukan");
    return;
  }

  if (product.stock <= 0) {
    alert("Stok produk habis");
    return;
  }

  addToCart(product);
}

</script>
