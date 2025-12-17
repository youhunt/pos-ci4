<template>
  <input
    :value="modelValue"
    @input="onInput"
    placeholder="Cari produk atau scan barcode..."
    class="w-full p-3 border rounded-lg shadow-sm mb-4 focus:ring focus:ring-blue-300"
  />
</template>

<script setup>
const props = defineProps({
  modelValue: String,
});

const emit = defineEmits(["update:modelValue"]);

function onInput(e) {
  emit("update:modelValue", e.target.value);
}
</script>
