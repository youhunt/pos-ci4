<script setup>
import { ref, onMounted } from 'vue';
import { fetchProducts, updateProduct } from '../../api/admin/products';
import { formatRupiah } from '../../utils/format';

const products = ref([]);
const search = ref('');

async function load() {
  const res = await fetchProducts(1, search.value);
  products.value = res.data.data;
}

function toggleActive(p) {
  updateProduct(p.id, {
    ...p,
    is_active: p.is_active ? 0 : 1
  }).then(load);
}

onMounted(load);
</script>

<template>
  <div>
    <h1 class="text-xl font-bold mb-4">Products</h1>

    <input
      v-model="search"
      @input="load"
      placeholder="Cari produk..."
      class="border p-2 mb-4 w-1/3"
    />

    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th>Nama</th>
          <th>SKU</th>
          <th>Harga</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in products" :key="p.id">
          <td>{{ p.name }}</td>
          <td>{{ p.sku }}</td>
          <td>Rp {{ formatRupiah(p.price) }}</td>
          <td>
            <span
              :class="p.is_active ? 'text-green-600' : 'text-red-600'"
            >
              {{ p.is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
          </td>
          <td>
            <button
              class="text-blue-600"
              @click="toggleActive(p)"
            >
              {{ p.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
