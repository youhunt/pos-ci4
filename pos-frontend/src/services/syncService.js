import { syncProducts } from './productSyncService'
import { syncPromos } from './promoSyncService'
import { syncCategories } from './categorySyncService'
import { setMeta } from '@/db/idb'

export async function syncAll(options = {})
{
    const { silent = false } = options

    try
    {
        if (!silent)
            console.log("🔄 Sync ALL started")

        await syncCategories()

        await syncProducts()

        await syncPromos()

        await setMeta('lastSync', new Date().toISOString())

        if (!silent)
            console.log("✅ Sync ALL completed")

        return true
    }
    catch (err)
    {
        console.error("❌ Sync ALL failed", err)

        return false
    }
}