<template>
  <div class="w-full relative pb-20">
    <!-- Ambient Glows -->
    <div class="pointer-events-none absolute top-10 left-1/4 w-[500px] h-[300px] bg-primary-container/10 rounded-full blur-[140px] -z-10"></div>
    <div class="pointer-events-none absolute top-64 right-10 w-[400px] h-[350px] bg-secondary/10 rounded-full blur-[130px] -z-10"></div>

    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin pt-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-outline mb-6">
        <router-link to="/" class="hover:text-primary transition-colors">Beranda</router-link>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <router-link to="/produk" class="hover:text-primary transition-colors">Produk Digital</router-link>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold truncate">{{ product?.title || 'Memuat...' }}</span>
      </nav>

      <!-- Loading State -->
      <div v-if="isLoading" class="py-24 text-center">
        <span class="material-symbols-outlined text-4xl text-primary-container animate-spin">progress_activity</span>
        <p class="text-sm text-on-surface-variant mt-3">Memuat detail produk digital...</p>
      </div>

      <!-- Not Found -->
      <div v-else-if="!product" class="py-20 text-center rounded-3xl bg-surface-container-low border border-outline-variant/25 p-10">
        <span class="material-symbols-outlined text-5xl text-outline mb-3">inventory_2</span>
        <h2 class="font-headline-md text-primary font-bold">Produk Tidak Ditemukan</h2>
        <p class="text-sm text-on-surface-variant mt-1">Produk mungkin sudah diarsipkan atau URL tidak valid.</p>
        <router-link
          to="/produk"
          class="mt-6 inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm"
        >
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
          Kembali ke Katalog
        </router-link>
      </div>

      <!-- Main Product Detail Grid -->
      <div v-else class="space-y-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          <!-- Left: Preview Visual & Highlights -->
          <div class="lg:col-span-7 space-y-6">
            <div class="relative rounded-3xl overflow-hidden bg-surface-container-low border border-outline-variant/30 shadow-2xl">
              <img
                :src="product.image"
                :alt="product.title"
                class="w-full h-[280px] sm:h-[420px] object-cover"
              />
              <div class="absolute top-4 left-4 flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-primary-container text-xs font-bold border border-primary-container/30">
                  {{ product.category }}
                </span>
                <span class="px-3 py-1 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-primary text-xs font-mono border border-outline-variant/30">
                  {{ product.version || 'v1.0.0' }}
                </span>
              </div>
              <a
                v-if="product.preview_url"
                :href="product.preview_url"
                target="_blank"
                rel="noopener noreferrer"
                class="absolute bottom-4 right-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-surface-container-lowest/90 hover:bg-surface-container-high text-primary-container text-xs font-bold border border-primary-container/40 backdrop-blur-md shadow-lg transition-all"
              >
                <span class="material-symbols-outlined text-[16px]">visibility</span>
                Live Demo
              </a>
            </div>

            <!-- Description & Highlights Card -->
            <div class="rounded-3xl bg-surface-container-low/90 border border-outline-variant/25 p-6 sm:p-8 space-y-6">
              <div>
                <h2 class="font-headline-sm text-primary font-bold mb-2">Deskripsi Produk</h2>
                <p class="font-body-md text-on-surface leading-relaxed whitespace-pre-line">
                  {{ product.description || 'Aset digital berkualitas tinggi yang dirancang untuk mempercepat alur kerja tim desain dan pengembangan produk Anda.' }}
                </p>
              </div>

              <div v-if="product.highlights && product.highlights.length > 0" class="pt-4 border-t border-outline-variant/20">
                <h3 class="font-headline-sm text-primary font-bold mb-3">Yang Akan Anda Dapatkan</h3>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <li
                    v-for="(point, idx) in product.highlights"
                    :key="idx"
                    class="flex items-start gap-2.5 text-sm text-on-surface"
                  >
                    <span class="material-symbols-outlined text-secondary text-[19px] shrink-0 mt-0.5">check_circle</span>
                    <span>{{ point }}</span>
                  </li>
                </ul>
              </div>

              <div v-if="product.tags && product.tags.length > 0" class="pt-4 border-t border-outline-variant/20 flex flex-wrap items-center gap-2">
                <span class="text-xs text-outline mr-1">Tags:</span>
                <span
                  v-for="tag in product.tags"
                  :key="tag"
                  class="px-3 py-1 rounded-full bg-surface-container-high text-primary text-xs font-medium border border-outline-variant/25"
                >
                  #{{ tag }}
                </span>
              </div>
            </div>
          </div>

          <!-- Right: Purchase & License Card (Sticky) -->
          <div class="lg:col-span-5 lg:sticky lg:top-24">
            <div class="rounded-3xl bg-surface-container-low/95 backdrop-blur-xl border border-outline-variant/35 p-6 sm:p-8 shadow-2xl space-y-6">
              <div class="space-y-2">
                <div class="flex items-center justify-between gap-2">
                  <span class="font-mono text-xs text-outline">SKU: {{ product.sku }}</span>
                  <div class="flex items-center gap-1 text-secondary text-xs font-bold">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="text-primary">{{ product.rating || 4.9 }}</span>
                    <span class="text-on-surface-variant font-normal">({{ product.downloads || 0 }} terjual)</span>
                  </div>
                </div>
                <h1 class="font-headline-lg text-2xl sm:text-3xl text-primary font-extrabold tracking-tight leading-tight">
                  {{ product.title }}
                </h1>
              </div>

              <!-- Price Box -->
              <div class="p-5 rounded-2xl bg-surface-container-lowest/90 border border-outline-variant/25 space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs text-on-surface-variant font-medium">Harga Lisensi</span>
                  <span
                    v-if="discountPercent > 0"
                    class="px-2.5 py-0.5 rounded-full bg-secondary/15 border border-secondary/35 text-secondary text-xs font-bold"
                  >
                    Hemat {{ discountPercent }}%
                  </span>
                </div>
                <div class="flex items-baseline gap-3">
                  <span class="font-headline-lg text-3xl text-primary font-extrabold">{{ formatRupiah(product.price) }}</span>
                  <span
                    v-if="product.original_price && product.original_price > product.price"
                    class="text-sm text-outline line-through"
                  >
                    {{ formatRupiah(product.original_price) }}
                  </span>
                </div>
                <p class="text-[11px] text-secondary flex items-center gap-1 pt-1">
                  <span class="material-symbols-outlined text-[14px]">bolt</span>
                  Sekali bayar • Akses unduh instan setelah pembayaran terverifikasi
                </p>
              </div>

              <!-- Specs Summary -->
              <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-xl bg-surface-container/70 border border-outline-variant/20">
                  <span class="text-outline block">Tipe Lisensi</span>
                  <span class="text-primary font-semibold mt-0.5 block truncate">{{ product.license_type || 'Commercial' }}</span>
                </div>
                <div class="p-3 rounded-xl bg-surface-container/70 border border-outline-variant/20">
                  <span class="text-outline block">Format &amp; Ukuran</span>
                  <span class="text-primary font-semibold mt-0.5 block truncate">
                    {{ product.file_size ? formatFileSize(product.file_size) : 'Digital Package' }}
                  </span>
                </div>
              </div>

              <!-- Action CTAs -->
              <div class="space-y-3 pt-1">
                <button
                  @click="buyNow"
                  type="button"
                  class="w-full py-3.5 px-6 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-label-lg font-bold shadow-[0_4px_24px_rgba(0,242,254,0.35)] hover:brightness-110 active:scale-[0.99] transition-all flex items-center justify-center gap-2"
                >
                  <span class="material-symbols-outlined text-[20px]">bolt</span>
                  <span>Beli Sekarang (Guest / Member)</span>
                </button>

                <button
                  @click="handleAddToCart"
                  type="button"
                  class="w-full py-3 px-6 rounded-full bg-surface-container-high hover:bg-surface-bright text-primary font-label-md font-bold border border-outline-variant/35 transition-all flex items-center justify-center gap-2"
                >
                  <span class="material-symbols-outlined text-[19px]">
                    {{ alreadyInCart ? 'shopping_bag' : 'add_shopping_cart' }}
                  </span>
                  <span>{{ alreadyInCart ? 'Sudah di Keranjang — Lihat Keranjang' : 'Tambah ke Keranjang' }}</span>
                </button>
              </div>

              <!-- Trust Footnotes -->
              <div class="pt-4 border-t border-outline-variant/20 space-y-2 text-xs text-on-surface-variant">
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[16px] text-secondary">verified_user</span>
                  <span>Pembayaran aman via QRIS, Virtual Account &amp; E-Wallet</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[16px] text-primary-container">mail</span>
                  <span>Invoice, License Key &amp; link download langsung tersedia</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[16px] text-secondary">person_off</span>
                  <span>Mendukung Guest Checkout tanpa wajib buat akun</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Related Products -->
        <section v-if="relatedProducts.length > 0" class="pt-6 border-t border-outline-variant/20 space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="font-headline-md text-primary font-bold">Produk Digital Lainnya</h2>
            <router-link to="/produk" class="text-xs font-bold text-primary-container hover:underline flex items-center gap-1">
              Lihat Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </router-link>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <router-link
              v-for="rel in relatedProducts"
              :key="rel.id"
              :to="`/produk/${rel.slug || rel.id}`"
              class="group rounded-2xl bg-surface-container-low border border-outline-variant/25 overflow-hidden hover:border-primary-container/40 transition-all flex flex-col"
            >
              <img :src="rel.image" :alt="rel.title" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300" />
              <div class="p-4 flex-1 flex flex-col justify-between gap-2">
                <div>
                  <span class="text-[11px] font-bold text-primary-container uppercase">{{ rel.category }}</span>
                  <h3 class="font-label-lg text-primary font-bold line-clamp-1 mt-0.5">{{ rel.title }}</h3>
                </div>
                <div class="font-headline-sm text-secondary font-extrabold">{{ formatRupiah(rel.price) }}</div>
              </div>
            </router-link>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { products, formatRupiah, formatFileSize } from '@/data/mockData';
import { ProductsApi } from '@/services/api';
import { useCart } from '@/stores/cart';

const route = useRoute();
const router = useRouter();
const { addToCart, hasInCart, openCartDrawer } = useCart();

const product = ref(null);
const isLoading = ref(true);

async function loadProduct() {
  isLoading.value = true;
  const slugOrId = String(route.params.slug || '');

  // Cari dulu di store lokal agar instan
  const localMatch = products.value.find(
    (p) => p.slug === slugOrId || p.id === slugOrId
  );
  if (localMatch) {
    product.value = localMatch;
  }

  // Ambil versi terbaru dari backend API
  try {
    const res = slugOrId.startsWith('PROD-')
      ? await ProductsApi.getById(slugOrId)
      : await ProductsApi.getBySlug(slugOrId);
    if (res?.status === 'success' && res?.data) {
      product.value = res.data;
    }
  } catch {
    if (!localMatch) {
      product.value = null;
    }
  } finally {
    isLoading.value = false;
  }
}

const discountPercent = computed(() => {
  if (!product.value?.original_price || product.value.original_price <= product.value.price) {
    return 0;
  }
  return Math.round(
    ((product.value.original_price - product.value.price) / product.value.original_price) * 100
  );
});

const alreadyInCart = computed(() =>
  product.value ? hasInCart(product.value.id) : false
);

const relatedProducts = computed(() => {
  if (!product.value) return [];
  return products.value
    .filter((p) => p.status === 'Live' && p.id !== product.value.id)
    .slice(0, 3);
});

function handleAddToCart() {
  if (!product.value) return;
  if (alreadyInCart.value) {
    openCartDrawer();
  } else {
    addToCart(product.value, { openDrawer: true });
  }
}

function buyNow() {
  if (!product.value) return;
  addToCart(product.value, { openDrawer: false });
  router.push('/checkout');
}

onMounted(loadProduct);
watch(() => route.params.slug, loadProduct);
</script>
