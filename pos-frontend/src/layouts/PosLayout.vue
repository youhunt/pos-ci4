<template>
  <div class="w-full h-full flex flex-col bg-gray-50">
    <!-- TOP BAR -->
    <div
      class="flex items-center justify-between px-4 py-2 bg-blue-600 text-white"
    >
      <div class="font-semibold">POS Mode</div>

      <router-link to="/" class="bg-white text-blue-600 px-3 py-1 rounded">
        ⬅ Keluar POS
      </router-link>
    </div>

    <!-- POS CONTENT -->
    <div class="flex-1 overflow-hidden">
      <router-view />
    </div>
  </div>
</template>
