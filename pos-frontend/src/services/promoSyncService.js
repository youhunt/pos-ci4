import axios from 'axios'
import { getDB } from '@/db/idb'

export async function syncPromos()
{
    const res = await axios.get('/api/promos')

    const promos = res.data

    const db = await getDB()

    const tx = db.transaction('promos', 'readwrite')

    for (const promo of promos)
    {
        await tx.store.put(promo)
    }

    await tx.done

    console.log("✔ Promos synced:", promos.length)
}