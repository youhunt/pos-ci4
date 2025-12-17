<template>
  <div class="flex w-full h-full bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r p-4">
      <div class="font-bold text-xl mb-6">POS Admin</div>

      <nav class="space-y-2">
        <router-link to="/" class="block p-2 rounded hover:bg-gray-200">
          Dashboard
        </router-link>
        <router-link to="/products" class="block p-2 rounded hover:bg-gray-200">
          Products
        </router-link>
        <router-link to="/reports" class="block p-2 rounded hover:bg-gray-200">
          Reports
        </router-link>
        <router-link to="/settings" class="block p-2 rounded hover:bg-gray-200">
          Settings
        </router-link>
      </nav>

      <router-link
        to="/pos"
        class="block mt-6 text-center bg-blue-600 text-white py-2 rounded"
      >
        Go to POS
      </router-link>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-6 overflow-auto">
      <router-view />
    </main>
  </div>
</template>
