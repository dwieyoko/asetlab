<template>
  <Teleport to="body">
    <!-- Backdrop -->
    <div
      v-if="isOpen"
      @click="closeCartDrawer"
      class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm transition-opacity"
    ></div>

    <!-- Drawer Panel -->
    <aside
      class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-surface-container-lowest border-l border-outline-variant/30 shadow-2xl flex flex-col transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >
      <!-- Header -->
      <div class="px-6 py-5 border-b border-outline-variant/25 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-primary-container/15 border border-primary-container/30 flex items-center justify-center text-primary-container">
            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-primary font-bold leading-tight">Keranjang Aset</h3>
            <p class="text-xs text-outline">{{ count }} produk digital dipilih</p>
          </div>
        </div>
        <button
          @click="closeCartDrawer"
          type="button"
          class="p-2 rounded-full text-outline hover:text-on-surface hover:bg-surface-container transition-colors"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <!-- Items List -->
      <div class="flex-1 overflow-y-auto p-6 space-y-4">
        <div v-if="items.length === 0" class="h-full flex flex-col items-center justify-center text-center py-12">
          <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center text-outline mb-4">
            <span class="material-symbols-outlined text-[32px]">shopping_cart</span>
          </div>
          <h4 class="font-headline-sm text-primary font-bold">Keranjang masih kosong</h4>
          <p class="text-xs text-on-surface-variant mt-1 max-w-xs">
            Jelajahi UI Kit, Source Code, dan Template premium untuk mempercepat proyek digital Anda.
          </p>
          <router-link
            to="/produk"
            @click="closeCartDrawer"
            class="mt-5 px-5 py-2.5 rounded-full bg-surface-container-high hover:bg-surface-bright text-primary text-xs font-bold border border-outline-variant/30 transition-colors"
          >
            Lihat Katalog Produk
          </router-link>
        </div>

        <div
          v-for="item in items"
          :key="item.id"
          class="p-3.5 rounded-2xl bg-surface-container-low border border-outline-variant/25 flex gap-3.5 items-start group"
        >
          <img
            :src="item.image"
            :alt="item.title"
            class="w-16 h-16 rounded-xl object-cover bg-surface-container-high shrink-0 border border-outline-variant/20"
          />
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <router-link
                :to="`/produk/${item.slug || item.id}`"
                @click="closeCartDrawer"
                class="font-label-md text-primary font-bold line-clamp-1 hover:text-primary-container transition-colors"
              >
                {{ item.title }}
              </router-link>
              <button
                @click="removeFromCart(item.id)"
                type="button"
                class="text-outline hover:text-error p-0.5 transition-colors shrink-0"
                title="Hapus dari keranjang"
              >
                <span class="material-symbols-outlined text-[18px]">delete</span>
              </button>
            </div>
            <p class="text-[11px] text-outline mt-0.5">{{ item.category }} • {{ item.license_type }}</p>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="font-label-lg text-secondary font-extrabold">{{ formatRupiah(item.price) }}</span>
              <span
                v-if="item.original_price && item.original_price > item.price"
                class="text-[11px] text-outline line-through"
              >
                {{ formatRupiah(item.original_price) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Summary & Checkout Button -->
      <div v-if="items.length > 0" class="p-6 border-t border-outline-variant/25 bg-surface-container-low/80 space-y-4">
        <div class="space-y-1.5">
          <div class="flex items-center justify-between text-xs text-on-surface-variant">
            <span>Subtotal ({{ count }} lisensi)</span>
            <span class="font-semibold text-on-surface">{{ formatRupiah(subtotal) }}</span>
          </div>
          <div class="flex items-center justify-between text-xs text-secondary">
            <span>Pengiriman Produk Digital</span>
            <span class="font-bold">Instan • Gratis</span>
          </div>
          <div class="pt-2 border-t border-outline-variant/20 flex items-center justify-between">
            <span class="font-label-md text-primary font-bold">Total Sementara</span>
            <span class="font-headline-sm text-primary font-extrabold">{{ formatRupiah(subtotal) }}</span>
          </div>
        </div>

        <button
          @click="goToCheckout"
          type="button"
          class="w-full py-3.5 px-6 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-label-lg font-bold shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:brightness-110 transition-all flex items-center justify-center gap-2"
        >
          <span>Lanjut ke Checkout</span>
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>

        <p class="text-[11px] text-outline text-center flex items-center justify-center gap-1">
          <span class="material-symbols-outlined text-[14px] text-secondary">verified_user</span>
          Bisa Guest Checkout tanpa daftar akun
        </p>
      </div>
    </aside>
  </Teleport>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useCart } from '@/stores/cart';
import { formatRupiah } from '@/data/mockData';

const router = useRouter();
const { items, isOpen, count, subtotal, removeFromCart, closeCartDrawer } = useCart();

function goToCheckout() {
  closeCartDrawer();
  router.push('/checkout');
}
</script>
