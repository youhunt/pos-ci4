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
    path: '/admin',
    component: AppLayout,
    children: [
      { path: 'dashboard', component: Dashboard },
      { path: 'products', component: Products },
      { path: 'reports', component: Reports },
      { path: 'settings', component: Settings },
    ]
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

import axios from "axios";

router.beforeEach(async (to, from, next) => {

  // halaman publik
  if (to.path === "/login") {

    try {

      await axios.get("/api/user/me");

      return next("/admin/dashboard");

    }
    catch {

      return next();

    }

  }

  // halaman protected
  try {

    const res = await axios.get("/api/user/me");

    const role = res.data?.data?.role ?? res.data?.role;

    // role check optional
    if (to.path.startsWith("/admin") && role !== "admin") {
      return next("/pos");
    }

    next();

  }
  catch {

    next("/login");

  }

});

