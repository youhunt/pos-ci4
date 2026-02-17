import { createRouter, createWebHistory } from "vue-router";

import AppLayout from "../layouts/AppLayout.vue";
import PosLayout from "../layouts/PosLayout.vue";

import POS from "../pages/POS.vue";
import Dashboard from "../pages/admin/Dashboard.vue";
import Products from "../pages/admin/Products.vue";
import Reports from "../pages/admin/Reports.vue";
import Settings from "../pages/admin/Settings.vue";
import Login from "../pages/Login.vue";

const routes = [
  {
    path: "/login",
    component: Login,
  },

  {
    path: "/admin",
    component: AppLayout,
    children: [
      { path: "dashboard", component: Dashboard },
      { path: "products", component: Products },
      { path: "reports", component: Reports },
      { path: "settings", component: Settings },
    ],
  },

  {
    path: "/pos",
    component: PosLayout,
    children: [{ path: "", component: POS }],
  },

  {
    path: "/:pathMatch(.*)*",
    redirect: "/pos",
  },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});

// ========================================
// AUTH GUARD (PAKAI localStorage)
// ========================================

router.beforeEach((to, from, next) => {
  const user = localStorage.getItem("user");

  // kalau belum login
  if (!user && to.path !== "/login") {
    return next("/login");
  }

  // kalau sudah login dan buka login page
  if (user && to.path === "/login") {
    const parsed = JSON.parse(user);

    if (parsed.role === "admin") {
      return next("/admin/dashboard");
    }

    return next("/pos");
  }

  next();
});
