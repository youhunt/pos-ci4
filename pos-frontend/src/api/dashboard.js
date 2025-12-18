import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8080/api",
  timeout: 10000, // ⏱️ 10 detik, biar gak freeze
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export function fetchDashboardSummary(shopId) {
  return api.get('/dashboard/summary', {
    params: { shop_id: shopId }
  });
}
