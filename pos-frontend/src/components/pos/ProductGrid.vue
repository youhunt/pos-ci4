<template>
  <div class="grid grid-cols-2 gap-3">
    <ProductCard
      v-for="p in products"
      :key="p.id"
      :product="p"
      @select="$emit('select', p)"
    />
  </div>
</template>

<script setup>
import ProductCard from "./ProductCard.vue";

defineProps({
  products: Array,
});
defineEmits(["select"]);
</script>
