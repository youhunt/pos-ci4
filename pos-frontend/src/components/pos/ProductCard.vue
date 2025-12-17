<template>
  <div
    class="border rounded-lg p-3 bg-white cursor-pointer shadow hover:shadow-md transition"
    @click="$emit('select', product)"
  >
    <div class="font-semibold text-gray-700">{{ product.name }}</div>
    <div class="text-sm text-gray-500">{{ product.sku }}</div>

    <div class="mt-2 font-bold text-blue-600">
      Rp {{ Number(product.price).toLocaleString() }}
    </div>

    <div class="text-xs text-gray-500 mt-1">Stok: {{ product.stock }}</div>
  </div>
</template>

<script setup>
defineProps({
  product: Object,
});
defineEmits(["select"]);
</script>
