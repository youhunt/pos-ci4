<template>
  <div>
    <div class="text-lg font-semibold mb-3">Total Belanja</div>

    <div class="mb-1 text-gray-600">
      Subtotal: Rp {{ totals.subtotal.toLocaleString('id-ID') }}
    </div>

    <div class="mb-1 text-gray-600">
      Diskon: Rp {{ totals.discount.toLocaleString('id-ID') }}
    </div>

    <div class="text-3xl font-bold mt-4 mb-6 text-green-600">
      Rp {{ totals.total.toLocaleString('id-ID') }}
    </div>

    <button
      class="w-full bg-blue-600 text-white py-4 text-xl rounded-lg"
      :disabled="isEmpty"
      @click="$emit('checkout')"
    >
      Bayar
    </button>
  </div>
</template>

<script setup>
defineProps({
  totals: {
    type: Object,
    required: true,
  },
  isEmpty: {
    type: Boolean,
    required: true
  },
});

defineEmits(["checkout"]);
</script>
