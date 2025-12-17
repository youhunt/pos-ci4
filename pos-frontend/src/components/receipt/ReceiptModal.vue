<template>
  <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white w-96 rounded-lg shadow-lg p-5">
      <h2 class="text-lg font-bold text-center mb-2">STRUK PEMBAYARAN</h2>

      <div class="text-sm text-gray-600 mb-3 text-center">
        <div>{{ receipt.transaction.invoice }}</div>
        <div>{{ receipt.transaction.created_at }}</div>
      </div>

      <hr class="my-2" />

      <!-- ITEMS -->
      <div
        v-for="item in receipt.items"
        :key="item.product_id"
        class="flex justify-between text-sm mb-1"
      >
        <span>{{ item.qty }} x {{ item.name }}</span>
        <span>Rp {{ item.subtotal.toLocaleString() }}</span>
      </div>

      <hr class="my-2" />

      <!-- TOTAL -->
      <div class="text-sm">
        <div class="flex justify-between">
          <span>Subtotal</span>
          <span>Rp {{ receipt.transaction.total_amount.toLocaleString() }}</span>
        </div>

        <div class="flex justify-between">
          <span>Diskon</span>
          <span>Rp {{ receipt.transaction.discount_amount.toLocaleString() }}</span>
        </div>

        <div class="flex justify-between font-bold text-lg mt-2">
          <span>Total</span>
          <span>Rp {{ receipt.transaction.total_amount.toLocaleString() }}</span>
        </div>
      </div>

      <hr class="my-2" />

      <!-- PAYMENT -->
      <div class="text-sm">
        <div class="flex justify-between">
          <span>Dibayar</span>
          <span>Rp {{ receipt.transaction.total_paid.toLocaleString() }}</span>
        </div>

        <div class="flex justify-between">
          <span>Status</span>
          <span class="capitalize">{{ receipt.transaction.payment_status }}</span>
        </div>
      </div>

      <button
        class="w-full bg-blue-600 text-white py-2 rounded mt-4"
        @click="$emit('close')"
      >
        Tutup
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  receipt: {
    type: Object,
    required: true,
  },
});

defineEmits(["close"]);
</script>
