<template>
  <div class="flex w-full h-full bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-white border-b p-4 flex justify-between items-center">
      <div class="font-bold text-lg">POS Admin</div>
      <button
        class="text-2xl"
        @click="showMenu = true"
      >
        ☰
      </button>
    </div>
    <!-- Sidebar -->
    <aside
        v-if="showMenu"
        class="fixed inset-0 bg-black/40 z-40 md:hidden"
        @click="showMenu = false"
      >
      <!-- Mobile Header -->
      <div class="md:hidden bg-white border-b p-4 flex justify-between items-center">
        <div class="font-bold text-lg">POS Admin</div>
        <button
          class="text-2xl"
          @click="showMenu = true"
        >
          ☰
        </button>
      </div>

      <div class="w-64 bg-white h-full p-4" @click.stop>
        <nav class="space-y-2">
          <router-link to="/admin/dashboard" class="block p-2 rounded hover:bg-gray-200">
            Dashboard
          </router-link>
          <router-link to="/admin/products" class="block p-2 rounded hover:bg-gray-200">
            Products
          </router-link>
          <router-link to="/admin/reports" class="block p-2 rounded hover:bg-gray-200">
            Reports
          </router-link>
          <router-link to="/admin/settings" class="block p-2 rounded hover:bg-gray-200">
            Settings
          </router-link>
        </nav>
      </div>
      <router-link to="/pos" class="block mt-6 text-center bg-blue-600 text-white py-2 rounded">
        Go to POS
      </router-link>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-4 md:p-6 overflow-y-auto">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const showMenu = ref(false);
</script>
