<template>
  <div
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
  >
    <div class="bg-white w-96 rounded-lg shadow-lg p-6">
      <h2 class="text-xl font-bold mb-4">Pembayaran</h2>

      <div class="mb-3">
        <div class="text-gray-600">Total Belanja</div>
        <div class="text-2xl font-bold text-green-600">
          Rp {{ total.toLocaleString() }}
        </div>
      </div>

      <div class="mb-3">
        <label class="block text-gray-600 mb-1">Uang Diterima</label>
        <input
          type="number"
          v-model.number="paid"
          class="w-full p-3 border rounded text-lg"
          autofocus
        />
      </div>

      <div class="mb-4">
        <div class="text-gray-600">Kembalian</div>
        <div
          class="text-xl font-bold"
          :class="change < 0 ? 'text-red-500' : 'text-blue-600'"
        >
          Rp {{ change.toLocaleString() }}
        </div>
      </div>

      <div class="flex gap-2">
        <button class="flex-1 bg-gray-300 py-3 rounded" @click="$emit('close')">
          Batal
        </button>

        <button
          class="flex-1 bg-blue-600 text-white py-3 rounded disabled:opacity-50"
          :disabled="paid < total || loading"
          @click="confirm"
        >
          {{ loading ? "Memproses..." : "Konfirmasi" }}
        </button>

      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";

const props = defineProps({
  total: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(["close", "confirm"]);

const paid = ref(props.total);
const loading = ref(false);

const change = computed(() => paid.value - props.total);

async function confirm() {
  if (loading.value) return;

  loading.value = true;

  try {
    // 🔹 tunggu parent menyelesaikan proses (checkout / payment)
    await emit("confirm", {
      paid: paid.value,
      change: change.value,
    });

    // 🔹 JANGAN close di sini
    // parent yang akan close setelah sukses
  } catch (e) {
    alert("Gagal memproses pembayaran");
  } finally {
    loading.value = false;
  }
}
</script>

