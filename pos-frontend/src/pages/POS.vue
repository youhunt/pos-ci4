<template>
  <div class="hidden md:flex w-full h-full">

    <!-- LEFT PANEL -->
    <div class="w-3/12 bg-white border-r p-4 overflow-y-auto">

      <SearchBar v-model="ui.searchQuery" />

      <ProductGrid
        :products="filteredProducts"
        @select="addToCart"
      />

    </div>


    <!-- MIDDLE PANEL -->
    <div class="w-6/12 bg-gray-50 p-4 overflow-y-auto">

      <CartTable />

    </div>


    <!-- RIGHT PANEL -->
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


  <!-- PAYMENT -->
  <PaymentModal
    v-if="showPayment"
    :total="cart.total"
    @confirm="onConfirmPayment"
    @close="showPayment = false"
  />


  <!-- RECEIPT -->
  <ReceiptModal
    v-if="showReceipt"
    :receipt="receipt"
    @close="showReceipt = false"
  />


  <!-- MOBILE BLOCK -->
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

import { ref, computed, onMounted, onBeforeUnmount } from "vue";

import { useCartStore } from "../stores/cart";
import { useUIStore } from "../stores/ui";
import { useProductStore } from "../stores/productStore";
import { useCategoryStore } from "../stores/categoryStore";
import { usePromoStore } from "../stores/promoStore";

import { syncAll } from "../api/sync";

import { fetchReceipt } from "../api/pos";


import SearchBar from "../components/pos/SearchBar.vue";
import ProductGrid from "../components/pos/ProductGrid.vue";
import CartTable from "../components/pos/CartTable.vue";
import TotalsPanel from "../components/pos/TotalsPanel.vue";
import PaymentModal from "../components/pos/PaymentModal.vue";
import ReceiptModal from "../components/receipt/ReceiptModal.vue";
import { useRouter } from "vue-router";
import { getShopId } from "../utils/shop";

const router = useRouter();

// =========================
// STORES
// =========================

const products = useProductStore();
const cart = useCartStore();
const ui = useUIStore();
const categoryStore = useCategoryStore();
const promoStore = usePromoStore();


// =========================
// STATE
// =========================

const showPayment = ref(false);
const showReceipt = ref(false);
const receipt = ref(null);


// =========================
// POS INIT
// =========================

onMounted(async () => {

  console.log("POS INIT START");

  try {

     const shopId = await getShopId();
    // =====================================
    // LOAD LOCAL FIRST (INSTANT DISPLAY)
    // =====================================

    await categoryStore.loadFromLocal();

    await products.loadFromLocal();

    await promoStore.loadFromLocal();

    console.log("LOCAL DATA LOADED");


    // =====================================
    // SYNC BACKGROUND (INCREMENTAL)
    // =====================================

    syncAll(shopId)
      .then(async () => {

        console.log("SYNC COMPLETE");

        // tunggu IndexedDB commit selesai
        await new Promise(resolve => setTimeout(resolve, 200));

        await categoryStore.loadFromLocal(true);
        await products.loadFromLocal(true);
        await promoStore.loadFromLocal(true);

        console.log("STORE REFRESHED");

      })
      .catch(err => {

        console.error("SYNC FAILED:", err);

      });

  }
  catch (err)
  {
    console.error("POS INIT ERROR:", err);

    alert("Silakan login dulu");

    router.push("/login");
  }


  // BARCODE LISTENER

  document.addEventListener("keyup", onKeyup);

  document.addEventListener("paste", onPaste);

});


onBeforeUnmount(() => {

  document.removeEventListener("keyup", onKeyup);

  document.removeEventListener("paste", onPaste);

});

window.addEventListener("pos-sync-complete", async () => {

  await categoryStore.loadFromLocal(true);
  await products.loadFromLocal(true);
  await promoStore.loadFromLocal(true);

  console.log("STORE AUTO REFRESHED");

});

// =========================
// FILTER PRODUCTS
// =========================

const filteredProducts = computed(() => {

  if (!ui.searchQuery)
    return products.items;

  return products.search(ui.searchQuery);

});


// =========================
// CART ACTIONS
// =========================

function addToCart(product)
{
  cart.add(product);
}


function openPayment()
{
  if (cart.isEmpty)
  {
    alert("Cart masih kosong");
    return;
  }

  showPayment.value = true;
}


// =========================
// CHECKOUT
// =========================

async function onConfirmPayment(payment)
{

  try {

    const res = await cart.checkout({

      paid: payment.paid,
      change: payment.change

    });

    const receiptRes = await fetchReceipt(res.transaction_id);

    receipt.value = receiptRes.data;

    showReceipt.value = true;

    cart.clear();

    showPayment.value = false;

  }
  catch (err)
  {
    console.error("CHECKOUT ERROR:", err);

    alert("Gagal bayar");
  }

}


// =========================
// BARCODE SCANNER
// =========================

const barcodeBuffer = ref("");

function onKeyup(e)
{

  const tag = document.activeElement?.tagName;

  if (tag === "INPUT" || tag === "TEXTAREA")
    return;


  if (e.key === "Enter")
  {

    const code = barcodeBuffer.value.trim();

    barcodeBuffer.value = "";

    if (code.length >= 8)
      handleBarcode(code);

    return;

  }


  if (e.key >= "0" && e.key <= "9")
  {
    barcodeBuffer.value += e.key;
  }

}


function onPaste(e)
{

  const pasted = e.clipboardData.getData("text").trim();

  if (/^\d{8,}$/.test(pasted))
  {

    handleBarcode(pasted);

    barcodeBuffer.value = "";

    e.preventDefault();

  }

}


async function handleBarcode(barcode)
{

  const product = await products.findByBarcode(barcode);

  if (!product)
  {
    alert("Produk tidak ditemukan");
    return;
  }

  if (product.stock <= 0)
  {
    alert("Stok habis");
    return;
  }

  addToCart(product);

}

</script>