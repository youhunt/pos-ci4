import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8080/api",
  timeout: 10000, // ⏱️ 10 detik, biar gak freeze
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

/**
 * RESPONSE INTERCEPTOR (DEBUG FRIENDLY)
 */
api.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error("API ERROR:", error.response || error.message);

    // lempar ulang biar bisa ditangkap di store / UI
    return Promise.reject(error);
  }
);

/**
 * =====================
 * API FUNCTIONS
 * =====================
 */

export function fetchProducts() {
  return api.get("/products");
}

export function calculateCart(payload) {
  console.log("API CALCULATE PAYLOAD:", payload);
  return api.post("/pos/calculate", payload);
}

export function checkoutCart(payload) {
  console.log("API CHECKOUT PAYLOAD:", payload);
  return api.post("/pos/checkout", payload);
}

/**
 * RECEIPT
 */
export function fetchReceipt(trxId) {
  return api.get(`/pos/${trxId}/receipt`);
}
