/**
 * service-worker.js
 * 
 * Service Worker untuk PWA
 * - Cache assets dan file penting (app shell)
 * - Bisa di-extend untuk cache data API (products)
 * - Offline-first strategy
 */

const CACHE_NAME = 'pos-pwa-cache-v1';
const ASSETS_TO_CACHE = [
  '/',                     // index.html
  '/pwa/index.html',
  '/pwa/css/style.css',
  '/pwa/js/db.js',
  '/pwa/js/app.js',
  '/pwa/manifest.json',
  '/pwa/assets/icons/icon-192.png',
  '/pwa/assets/icons/icon-512.png',
];

// Install event → cache assets
self.addEventListener('install', (event) => {
  console.log('[SW] Installing...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[SW] Caching assets...');
        return cache.addAll(ASSETS_TO_CACHE);
      })
  );
});

// Activate event → bersihkan cache lama
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating...');
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.filter(key => key !== CACHE_NAME)
            .map(key => caches.delete(key))
      );
    })
  );
});

// Fetch event → offline-first
self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;

  const requestUrl = new URL(event.request.url);

  // Cek apakah request ke API products
  if (requestUrl.pathname.startsWith('/api/products')) {
    event.respondWith(
      caches.open(DATA_CACHE_NAME).then(async (cache) => {
        try {
          const response = await fetch(event.request);
          cache.put(event.request, response.clone()); // simpan ke cache
          return response;
        } catch (err) {
          // offline fallback → ambil dari cache
          const cachedResponse = await cache.match(event.request);
          if (cachedResponse) return cachedResponse;
          return new Response(JSON.stringify([]), { headers: { 'Content-Type': 'application/json' } });
        }
      })
    );
    return; // jangan lanjut ke default fetch
  }

  // default fetch untuk file statis
  event.respondWith(
    caches.match(event.request).then(cachedRes => {
      if (cachedRes) return cachedRes;
      return fetch(event.request)
        .then(networkRes => {
          return caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, networkRes.clone());
            return networkRes;
          });
        })
        .catch(() => {
          if (event.request.destination === 'document') {
            return caches.match('/pwa/index.html');
          }
        });
    })
  );
});
