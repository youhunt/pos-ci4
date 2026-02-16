import axios from 'axios'
import { getDB } from '@/db/idb'

export async function syncCategories()
{
    const res = await axios.get('/api/categories')

    const categories = res.data

    const db = await getDB()

    const tx = db.transaction('categories', 'readwrite')

    for (const category of categories)
    {
        await tx.store.put(category)
    }

    await tx.done

    console.log("✔ Categories synced:", categories.length)
}