<script setup>
import { onMounted, ref } from 'vue';
import { fetchDashboardSummary } from '../../api/dashboard';
import { formatRupiah } from '../../utils/format';
import { getShopId } from '../../utils/shop';
import { useRouter } from "vue-router";

const router = useRouter();

const summary = ref(null);

onMounted(async () => {
  
  const shopId = await getShopId();
  
  try {  
    const res = await fetchDashboardSummary(shopId);
    summary.value = res.data.data;
  }
  catch (err)
  {
    console.error("POS INIT ERROR:", err);

    alert("Silakan login dulu");

    router.push("/login");
  }
});
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
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
