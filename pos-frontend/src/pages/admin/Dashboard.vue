<script setup>
import { onMounted, ref } from 'vue';
import { fetchDashboardSummary } from '../../api/dashboard';
import { formatRupiah } from '../../utils/format';

const summary = ref(null);

onMounted(async () => {
  const res = await fetchDashboardSummary(1);
  summary.value = res.data.data;
});
</script>

<template>
  <div class="grid grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Penjualan Hari Ini</div>
      <div class="text-xl font-bold">
        Rp {{ formatRupiah(summary?.total_sales) }}
      </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Transaksi</div>
      <div class="text-xl font-bold">
        {{ summary?.total_transactions }}
      </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Item Terjual</div>
      <div class="text-xl font-bold">
        {{ summary?.total_items }}
      </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Rata-rata Transaksi</div>
      <div class="text-xl font-bold">
        Rp {{ formatRupiah(summary?.avg_transaction) }}
      </div>
    </div>
  </div>
</template>
