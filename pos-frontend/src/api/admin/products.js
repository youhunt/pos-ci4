import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8080/api",
  timeout: 10000, // ⏱️ 10 detik, biar gak freeze
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export function fetchProducts(shopId, q = '') {
  return api.get('/admin/products', {
    params: { shop_id: shopId, q }
  });
}

export function createProduct(payload) {
  return api.post('/admin/products', payload);
}

export function updateProduct(id, payload) {
  return api.put(`/admin/products/${id}`, payload);
}
