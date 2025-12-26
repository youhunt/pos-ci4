<template>
  <table class="w-full text-left border-collapse">
    <thead>
      <tr class="border-b bg-gray-100">
        <th class="p-2">Produk</th>
        <th class="p-2">Qty</th>
        <th class="p-2">Total</th>
        <th class="p-2"></th>
      </tr>
    </thead>

    <tbody>
      <tr
        v-for="item in cart.items"
        :key="item.product_id"
        class="border-b"
      >
        <td class="p-2">
          {{ item.product.name }}

          <!-- INFO PROMO -->
          <div
            v-if="getPromo(item)"
            class="text-xs text-red-600"
          >
            Promo -{{ getPromo(item).value }}%
          </div>
        </td>

        <td class="p-2">
          <div class="flex items-center space-x-2">
            <button
              @click="cart.decrease(item.product_id)"
              class="px-3 py-1 bg-gray-200 rounded text-xl"
            >
              −
            </button>

            <span class="px-3">{{ item.qty }}</span>

            <button
              @click="cart.increase(item.product_id)"
              class="px-3 py-1 bg-gray-200 rounded text-xl"
            >
              +
            </button>
          </div>
        </td>

        <td class="p-2">
          Rp {{ (item.qty * item.price).toLocaleString('id-ID') }}

          <!-- DISKON (ESTIMASI) -->
          <div
            v-if="getDiscount(item) > 0"
            class="text-xs text-red-600"
          >
            -Rp {{ getDiscount(item).toLocaleString('id-ID') }}
          </div>
        </td>

        <td class="p-2 text-right">
          <button
            @click="cart.remove(item.product_id)"
            class="text-red-500 hover:text-red-700"
          >
            Hapus
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
import { useCartStore } from '../../stores/cart'
import { usePromoStore } from '../../stores/promoStore'

const cart = useCartStore()
const promoStore = usePromoStore()

function getPromo(item) {
  return promoStore.getByProduct(item.product_id)
}

function getDiscount(item) {
  const promo = getPromo(item)
  if (!promo) return 0

  if (promo.type === 'percent') {
    return item.price * item.qty * (promo.value / 100)
  }

  return 0
}

</script>
