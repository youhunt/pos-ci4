<script setup>
import { ref } from 'vue';

const showMenu = ref(false);
</script>

<template>
  <!-- WRAPPER -->
  <div class="h-screen bg-gray-100 flex flex-col">

    <!-- ================= MOBILE HEADER ================= -->
    <header class="md:hidden bg-white border-b px-4 py-3 flex items-center justify-between">
      <div class="font-bold text-lg">POS Admin</div>
      <button
        class="text-2xl"
        @click="showMenu = true"
        aria-label="Open Menu"
      >
        ☰
      </button>
    </header>

    <!-- ================= BODY ================= -->
    <div class="flex flex-1 overflow-hidden">

      <!-- ===== DESKTOP SIDEBAR ===== -->
      <aside class="hidden md:flex w-64 bg-white border-r p-4 flex-col">
        <div class="font-bold text-xl mb-6">POS Admin</div>

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

        <router-link
          to="/pos"
          class="mt-auto block text-center bg-blue-600 text-white py-2 rounded"
        >
          Go to POS
        </router-link>
      </aside>

      <!-- ===== MAIN CONTENT ===== -->
      <main class="flex-1 overflow-y-auto p-4 md:p-6">
        <router-view />
      </main>
    </div>

    <!-- ================= MOBILE SIDEBAR DRAWER ================= -->
    <div
      v-if="showMenu"
      class="fixed inset-0 z-40 md:hidden bg-black/40"
      @click="showMenu = false"
    >
      <aside
        class="w-64 bg-white h-full p-4"
        @click.stop
      >
        <div class="font-bold text-xl mb-6">POS Admin</div>

        <nav class="space-y-2">
          <router-link
            to="/admin/dashboard"
            class="block p-2 rounded hover:bg-gray-200"
            @click="showMenu = false"
          >
            Dashboard
          </router-link>
          <router-link
            to="/admin/products"
            class="block p-2 rounded hover:bg-gray-200"
            @click="showMenu = false"
          >
            Products
          </router-link>
          <router-link
            to="/admin/reports"
            class="block p-2 rounded hover:bg-gray-200"
            @click="showMenu = false"
          >
            Reports
          </router-link>
          <router-link
            to="/admin/settings"
            class="block p-2 rounded hover:bg-gray-200"
            @click="showMenu = false"
          >
            Settings
          </router-link>
        </nav>

        <router-link
          to="/pos"
          class="block mt-6 text-center bg-blue-600 text-white py-2 rounded"
          @click="showMenu = false"
        >
          Go to POS
        </router-link>
      </aside>
    </div>

  </div>
</template>
