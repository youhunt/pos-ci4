<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const username = ref("");
const password = ref("");
const loading = ref(false);

function login() {
  if (!username.value || !password.value) {
    alert("Username & password wajib diisi");
    return;
  }

  loading.value = true;

  /**
   * 🔴 SEMENTARA (DEV MODE)
   * ganti ini nanti dengan API call CI4
   */
  setTimeout(() => {
    // contoh user
    if (username.value === "admin") {
      localStorage.setItem("token", "dev-token");
      localStorage.setItem("role", "admin");
      router.push("/admin/dashboard");
    } else {
      localStorage.setItem("token", "dev-token");
      localStorage.setItem("role", "cashier");
      router.push("/pos");
    }

    loading.value = false;
  }, 500);
}
</script>

<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded shadow w-80">
      <h1 class="text-xl font-bold mb-4">Login</h1>

      <input
        v-model="username"
        class="w-full p-2 mb-3 border rounded"
        placeholder="Username"
      />

      <input
        v-model="password"
        class="w-full p-2 mb-3 border rounded"
        placeholder="Password"
        type="password"
      />

      <button
        class="w-full bg-blue-600 text-white p-2 rounded"
        @click="login"
        :disabled="loading"
      >
        {{ loading ? "Logging in..." : "Login" }}
      </button>
    </div>
  </div>
</template>
