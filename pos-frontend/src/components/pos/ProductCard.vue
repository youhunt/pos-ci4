<template>
  <div
    class="border rounded-lg p-3 bg-white shadow transition"
    :class="{
      'cursor-pointer hover:shadow-md': product.stock > 0,
      'opacity-50 cursor-not-allowed': product.stock <= 0
    }"
    @click="handleClick"
  >
    <div class="font-semibold text-gray-700">
      {{ product.name }}
    </div>

    <div class="text-sm text-gray-500">
      {{ product.sku }}
    </div>

    <div class="mt-2 font-bold text-blue-600">
      Rp {{ Number(product.price).toLocaleString('id-ID') }}
    </div>

    <div
      class="text-xs mt-1"
      :class="product.stock > 0 ? 'text-gray-500' : 'text-red-600 font-semibold'"
    >
      {{ product.stock > 0 ? `Stok: ${product.stock}` : 'Stok habis' }}
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['select']);

function handleClick() {
  if (props.product.stock <= 0) {
    return; // ❌ tidak boleh dijual
  }

  emit('select', props.product);
}
</script>

