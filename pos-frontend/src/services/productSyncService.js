import axios from 'axios'
import { getDB } from '@/db/idb'

export async function syncProducts()
{
    const res = await axios.get('/api/products')

    const products = res.data

    const db = await getDB()

    const tx = db.transaction('products', 'readwrite')

    for (const product of products)
    {
        await tx.store.put(product)
    }

    await tx.done

    console.log("✔ Products synced:", products.length)
}