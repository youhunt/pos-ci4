const posDB = (() => {
  const DB_NAME = 'pos_pwa_db';
  const DB_VERSION = 2;
  let db;
  let readyPromise = null;

  /** ======= Init DB ======= */
  function init() {
    if (readyPromise) return readyPromise;

    readyPromise = new Promise((resolve, reject) => {
      const request = indexedDB.open(DB_NAME, DB_VERSION);

      request.onupgradeneeded = (event) => {
        db = event.target.result;

        // Object store untuk produk
        if (!db.objectStoreNames.contains('products')) {
          const store = db.createObjectStore('products', { keyPath: 'id' });
          store.createIndex('name', 'name', { unique: false });
        }

        // Object store untuk stok
        if (!db.objectStoreNames.contains('stocks')) {
          const store = db.createObjectStore('stocks', { keyPath: 'id' });
          store.createIndex('product_id', 'product_id', { unique: false });
        }

        // Object store untuk transaksi
        if (!db.objectStoreNames.contains('transactions')) {
          const store = db.createObjectStore('transactions', { keyPath: 'local_id' });
          store.createIndex('created_at', 'created_at', { unique: false });
        }

        // Object store untuk pending push transaksi
        if (!db.objectStoreNames.contains('pending_transactions')) {
          db.createObjectStore('pending_transactions', { keyPath: 'local_id', autoIncrement: true });
        }

        // Object store untuk metadata (misal last_sync)
        if (!db.objectStoreNames.contains('meta')) {
          db.createObjectStore('meta', { keyPath: 'key' });
        }

        if (!db.objectStoreNames.contains('promos')) {
        db.createObjectStore('promos', { keyPath: 'id' });
        }

        if (!db.objectStoreNames.contains('promo_products')) {
            db.createObjectStore('promo_products', { keyPath: 'id' });
        }
      };

      request.onsuccess = (event) => {
        db = event.target.result;
        console.log('[DB] IndexedDB initialized');
        resolve(db);
      };

      request.onerror = (event) => {
        console.error('IndexedDB error:', event.target.errorCode);
        reject(event.target.errorCode);
      };
    });

    return readyPromise;
  }

  /** ======= Helper ======= */
  async function getTransaction(storeNames, mode = 'readonly') {
    await init(); // pastikan db siap
    return db.transaction(storeNames, mode);
  }

  async function savePromo(promo) {
    return this.put('promos', promo);
  }

  async function savePromoProduct(pp) {
      return this.put('promo_products', pp);
  }

  async function getPromos() {
      return this.getAll('promos');
  }

  async function getPromoProducts() {
      return this.getAll('promo_products');
  }

  /** ======= Products ======= */
  async function saveProduct(product) {
    const tx = await getTransaction(['products'], 'readwrite');
    tx.objectStore('products').put(product);
    return tx.complete;
  }

  async function getProducts() {
    return new Promise(async (resolve) => {
      const tx = await getTransaction(['products']);
      const store = tx.objectStore('products');
      const req = store.getAll();
      req.onsuccess = () => resolve(req.result);
    });
  }

  /** ======= Stocks ======= */
  async function saveStock(stock) {
    const tx = await getTransaction(['stocks'], 'readwrite');
    tx.objectStore('stocks').put(stock);
    return tx.complete;
  }

  async function getStocks() {
    return new Promise(async (resolve) => {
      const tx = await getTransaction(['stocks']);
      const store = tx.objectStore('stocks');
      const req = store.getAll();
      req.onsuccess = () => resolve(req.result);
    });
  }

  /** ======= Transactions ======= */
 async function saveTransaction(txn) {
    return new Promise((resolve, reject) => {
      const tx = db.transaction(['transactions'], 'readwrite');
      const store = tx.objectStore('transactions');
      const req = store.put(txn);

      req.onsuccess = () => resolve(true);
      req.onerror = () => reject(req.error);
    });
  }

  async function getTransactions() {
    return new Promise(async (resolve) => {
      const tx = await getTransaction(['transactions']);
      const store = tx.objectStore('transactions');
      const req = store.getAll();
      req.onsuccess = () => resolve(req.result);
    });
  }

  /** ======= Pending Transactions ======= */
  async function addPendingTransaction(txn) {
    const tx = await getTransaction(['pending_transactions'], 'readwrite');
    tx.objectStore('pending_transactions').put(txn);
    return tx.complete;
  }

  async function getPendingTransactions() {
    return new Promise(async (resolve) => {
      const tx = await getTransaction(['pending_transactions']);
      const store = tx.objectStore('pending_transactions');
      const req = store.getAll();
      req.onsuccess = () => resolve(req.result);
    });
  }

  async function removePendingTransaction(local_id) {
    const tx = await getTransaction(['pending_transactions'], 'readwrite');
    tx.objectStore('pending_transactions').delete(local_id);
    return tx.complete;
  }

  async function updateTransactionServerId(local_id, server_id) {
    const tx = await getTransaction(['pending_transactions'], 'readwrite');
    const store = tx.objectStore('pending_transactions');
    const req = store.get(local_id);
    req.onsuccess = () => {
      const txn = req.result;
      if (txn) {
        txn.server_id = server_id;
        store.put(txn);
      }
    };
    return tx.complete;
  }

  /** ======= Metadata ======= */
  async function setLastSyncTime(timestamp) {
    const tx = await getTransaction(['meta'], 'readwrite');
    tx.objectStore('meta').put({ key: 'last_sync', value: timestamp });
    return tx.complete;
  }

  async function getLastSyncTime() {
    return new Promise(async (resolve) => {
      const tx = await getTransaction(['meta']);
      const req = tx.objectStore('meta').get('last_sync');
      req.onsuccess = () => resolve(req.result ? req.result.value : null);
    });
  }

  return {
    init,
    saveProduct,
    getProducts,
    saveStock,
    getStocks,
    saveTransaction,
    getTransactions,
    addPendingTransaction,
    getPendingTransactions,
    removePendingTransaction,
    updateTransactionServerId,
    setLastSyncTime,
    getLastSyncTime
  };
})();
