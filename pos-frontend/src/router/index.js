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
      { path: "", redirect: "dashboard" }, // ⬅️ INI
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

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const role  = localStorage.getItem('role');

  // =========================
  // 1. HALAMAN PUBLIK
  // =========================
  if (to.path === '/login') {
    // kalau sudah login, jangan balik ke login
    if (token) {
      return next(role === 'admin' ? '/admin/dashboard' : '/pos');
    }
    return next();
  }

  // =========================
  // 2. WAJIB LOGIN
  // =========================
  if (!token) {
    return next('/login');
  }

  // =========================
  // 3. ROLE CHECK
  // =========================
  const requiredRole = to.matched.find(r => r.meta?.role)?.meta?.role;

  if (requiredRole && role !== requiredRole) {
    // kasir nyasar ke admin → balik ke POS
    if (role === 'cashier') return next('/pos');

    // admin nyasar ke POS (boleh atau tidak, pilihan UX)
    if (role === 'admin') return next('/admin/dashboard');
  }

  next();
});
