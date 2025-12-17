import { createRouter, createWebHistory } from "vue-router";

import AppLayout from "../layouts/AppLayout.vue";
import PosLayout from "../layouts/PosLayout.vue";

import POS from "../pages/POS.vue";
import Dashboard from "../pages/Dashboard.vue";
import Products from "../pages/Products.vue";
import Reports from "../pages/Reports.vue";
import Settings from "../pages/Settings.vue";
import Login from "../pages/Login.vue";

const routes = [
  {
    path: "/login",
    component: Login,
  },

  {
    path: "/",
    component: AppLayout,
    children: [
      { path: "", component: Dashboard },
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
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});
