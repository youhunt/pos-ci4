<script setup>
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

async function logout() {
  try {
    await axios.post("/api/logout");
  } catch (e) {}

  localStorage.removeItem("user");
  router.push("/login");
}
</script>
<template>
  <div class="w-full h-full flex flex-col bg-gray-50">
    <!-- TOP BAR -->
    <div
      class="flex items-center justify-between px-4 py-2 bg-blue-600 text-white"
    >
      <div class="font-semibold">POS Mode</div>

      <router-link to="/admin/dashboard" class="bg-white text-blue-600 px-3 py-1 rounded">
        ⬅ Keluar POS
      </router-link>
      <button
          @click="logout"
          class="bg-red-500 text-white px-3 py-1 rounded"
        >
          Logout
      </button>
    </div>

    <!-- POS CONTENT -->
    <div class="flex-1 overflow-hidden">
      <router-view />
    </div>
  </div>
</template>
