const posSync = (() => {
  const SHOP_ID = 1; // ganti sesuai toko
  const PER_PAGE = 50;

  /** ======= Pull All Data ======= */
  async function pullAllData(since = '', page = 1) {
    try {
      const url = `/api/sync/pull?shop_id=${SHOP_ID}&since=${since}&page=${page}`;
      const res = await fetch(url);
      if (!res.ok) throw new Error('Gagal fetch sync data');
      const data = await res.json();

      // Simpan ke IndexedDB per object store
      if (data.products && data.products.length)
        for (let p of data.products) await posDB.saveProduct(p);

      if (data.stocks && data.stocks.length)
        for (let s of data.stocks) await posDB.saveStock(s);

      if (data.transactions && data.transactions.length)
        for (let t of data.transactions) await posDB.saveTransaction(t);

      if (data.promos && data.promos.length)
          for (let p of data.promos) await posDB.savePromo(p);

      if (data.promo_products && data.promo_products.length)
          for (let pp of data.promo_products) await posDB.savePromoProduct(pp);

      // Pagination: kalau masih ada halaman berikutnya
      if (data.pagination && data.pagination.page < Math.ceil(data.pagination.total / data.pagination.per_page)) {
        await pullAllData(since, page + 1);
      }

      return data.server_time || new Date().toISOString();

    } catch (err) {
      console.error('[Sync] Pull failed:', err);
      return since; // kembalikan timestamp lama kalau gagal
    }
  }

  /** ======= Push Transactions ======= */
  async function pushTransactions() {
    try {
      const pending = await posDB.getPendingTransactions();
      if (!pending.length) return;

      for (let tx of pending) {
        const res = await fetch(`/api/sync/push`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(tx)
        });

        if (res.ok) {
          const result = await res.json();
          // Update mapping local_id -> server_id
          await posDB.updateTransactionServerId(tx.local_id, result.server_id);
          await posDB.removePendingTransaction(tx.local_id);
        } else {
          console.warn('[Sync] Push failed for transaction', tx.local_id);
        }
      }
    } catch (err) {
      console.error('[Sync] Push transactions failed:', err);
    }
  }

  /** ======= Manual Sync ======= */
  async function manualSync() {
    if (!navigator.onLine) return console.warn('[Sync] Offline, cannot sync');

    // 1. Push pending transactions
    await pushTransactions();

    // 2. Pull all data
    const lastSync = await posDB.getLastSyncTime() || '';
    const serverTime = await pullAllData(lastSync);
    await posDB.setLastSyncTime(serverTime);
  }

  /** ======= Auto Sync on Online ======= */
  window.addEventListener('online', () => {
    console.log('[Sync] Browser back online, syncing...');
    manualSync();
  });

  return {
    pullAllData,
    pushTransactions,
    manualSync
  };
})();