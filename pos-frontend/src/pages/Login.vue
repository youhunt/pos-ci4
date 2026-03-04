<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const username = ref("");
const password = ref("");
const loading = ref(false);

async function login() {

  if (!username.value || !password.value) {
    alert("Username & password wajib diisi");
    return;
  }

  loading.value = true;

  try {
    const res = await fetch("http://localhost:8080/api/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        username: username.value,
        password: password.value,
      }),
    });

    const result = await res.json();

    if (!result.status) {
      alert(result.message);
      loading.value = false;
      return;
    }

    // 🔥 Simpan sebagai "user" karena router guard baca ini
    localStorage.setItem("user", JSON.stringify(result.data));

    if (result.data.role === "admin") {
      router.push("/admin/dashboard");
    } else {
      router.push("/pos");
    }

  } catch (err) {
    console.error(err);
    alert("Server tidak bisa diakses");
  }

  loading.value = false;

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
