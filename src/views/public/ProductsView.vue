<template>
  <div>
    <div class="flex flex-col w-full relative">
      <!-- Ambient cyber glow backdrop decorations -->
      <div class="pointer-events-none absolute top-10 left-1/4 -translate-x-1/2 w-[580px] h-[340px] bg-primary-container/10 rounded-full blur-[140px] -z-10"></div>
      <div class="pointer-events-none absolute top-72 right-10 w-[420px] h-[380px] bg-secondary/10 rounded-full blur-[130px] -z-10"></div>
      <div class="pointer-events-none absolute bottom-40 left-10 w-[500px] h-[300px] bg-surface-tint/5 rounded-full blur-[150px] -z-10"></div>

      <!-- Header & Discovery Area -->
      <section class="w-full px-margin-mobile md:px-margin pt-space-lg pb-space-xl">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center space-y-space-md">
          <!-- Overline Pill Badge -->
          <div class="inline-flex items-center gap-space-xs px-space-md py-space-xs rounded-full bg-surface-container-high/70 backdrop-blur-md shadow-md text-secondary font-label-md text-label-md">
            <span class="w-2 h-2 rounded-full bg-secondary shadow-[0_0_8px_#4edea3]"></span>
            <span>Akselerasi Produksi Digital</span>
            <span class="text-on-surface-variant font-normal">| {{ liveProducts.length }} Aset Terverifikasi</span>
          </div>

          <!-- Main Headline -->
          <h1 class="font-display-hero-mobile md:font-display-hero text-display-hero-mobile md:text-display-hero text-primary tracking-tight max-w-4xl">
            Produk Digital Siap Pakai untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-container via-secondary to-primary-fixed">Mempercepat Proyek</span> Anda
          </h1>
          <p class="font-body-lg text-body-lg max-w-2xl text-on-surface">
            Komponen UI, template web arsitektur Next.js &amp; Flutter, serta sistem operasi Notion terkurasi dengan standar rekayasa kelas dunia.
          </p>

          <!-- Neon Search Bar -->
          <div class="w-full max-w-3xl pt-space-md">
            <div class="relative flex items-center bg-surface-container-lowest/90 backdrop-blur-xl rounded-full shadow-[0_10px_35px_-5px_rgba(0,0,0,0.5),0_0_20px_rgba(0,242,254,0.15)] p-2 border border-outline-variant/30">
              <div class="pl-4 pr-2 flex items-center text-primary-container">
                <span class="material-symbols-outlined text-[24px]">search</span>
              </div>
              <input 
                v-model="searchQuery"
                class="w-full bg-transparent font-body-md text-body-md text-primary placeholder-on-surface-variant focus:outline-none" 
                placeholder="Cari dashboard, UI Kit, source code React, template Notion..." 
                type="text"
              >
              <button 
                v-if="searchQuery"
                @click="searchQuery = ''"
                class="mr-3 text-outline hover:text-on-surface"
              >
                <span class="material-symbols-outlined text-[20px]">close</span>
              </button>
              <button 
                class="hidden sm:inline-flex items-center justify-center px-space-lg py-space-sm rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-label-lg text-label-lg font-bold shadow-[0_0_16px_rgba(0,242,254,0.4)] hover:brightness-110 transition-all" 
                type="button"
              >
                Cari Aset
              </button>
            </div>
          </div>

          <!-- Category Filter Pills -->
          <div class="w-full max-w-5xl pt-space-sm flex flex-wrap items-center justify-center gap-space-xs md:gap-space-sm">
            <button 
              v-for="cat in categories" 
              :key="cat"
              @click="activeCategory = cat"
              type="button"
              class="px-space-md py-space-xs rounded-full font-label-md text-label-md font-semibold transition-all border"
              :class="activeCategory === cat 
                ? 'bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold shadow-[0_2px_12px_rgba(0,242,254,0.3)] border-transparent' 
                : 'bg-surface-container-high/90 text-primary hover:text-primary-container hover:bg-surface-bright border-outline-variant/30'"
            >
              {{ cat }}
            </button>
          </div>
        </div>
      </section>

      <!-- Filter & Sort Controls Ribbon -->
      <section class="w-full px-margin-mobile md:px-margin pb-space-lg">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-space-md bg-surface-container-low/60 backdrop-blur-md rounded-2xl p-space-md shadow-md border border-outline-variant/20">
          <div class="flex items-center gap-space-sm text-on-surface-variant font-label-md text-label-md">
            <span class="material-symbols-outlined text-primary-container text-[20px]">tune</span>
            <span>Menampilkan <strong class="text-primary font-bold">{{ filteredProducts.length }}</strong> dari {{ liveProducts.length }} Produk Terkurasi</span>
          </div>

          <div class="flex flex-wrap items-center gap-space-sm w-full sm:w-auto justify-end">
            <!-- Sort Dropdown -->
            <div class="relative inline-flex items-center bg-surface-container rounded-full px-space-md py-space-xs shadow-sm border border-outline-variant/30">
              <span class="font-label-sm text-label-sm text-outline mr-2">URUTKAN:</span>
              <select v-model="sortBy" class="bg-transparent font-label-md text-label-md text-primary focus:outline-none cursor-pointer pr-4 appearance-none">
                <option class="bg-surface-container-high text-primary" value="populer">Terpopuler</option>
                <option class="bg-surface-container-high text-primary" value="terbaru">Terbaru</option>
                <option class="bg-surface-container-high text-primary" value="harga-rendah">Harga Terendah</option>
                <option class="bg-surface-container-high text-primary" value="harga-tinggi">Harga Tertinggi</option>
                <option class="bg-surface-container-high text-primary" value="rating">Rating Tertinggi</option>
              </select>
              <span class="material-symbols-outlined text-[16px] text-on-surface-variant pointer-events-none ml-1">expand_more</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Product Catalog Grid (Dynamic from mockData.js products ref) -->
      <section class="w-full px-margin-mobile md:px-margin pb-space-xl">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
          <article 
            v-for="item in filteredProducts" 
            :key="item.id"
            class="group relative flex flex-col bg-surface-container-low/80 backdrop-blur-xl rounded-2xl p-space-md shadow-xl hover:shadow-[0_12px_32px_rgba(0,242,254,0.18)] transition-all duration-300 border border-outline-variant/20"
          >
            <!-- Card Image Cover -->
            <div class="relative w-full h-56 rounded-xl overflow-hidden bg-surface-container-highest">
              <img 
                :src="item.image" 
                :alt="item.title"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-transparent to-transparent opacity-80"></div>
              
              <!-- Badges -->
              <div class="absolute top-3 left-3 flex items-center gap-space-xs">
                <span class="px-space-sm py-0.5 rounded-full bg-secondary-container/90 backdrop-blur-md text-primary font-label-sm text-label-sm font-bold flex items-center gap-1 shadow-md">
                  <span class="material-symbols-outlined text-[14px]">local_fire_department</span> Best Seller
                </span>
                <span class="px-space-sm py-0.5 rounded-full bg-surface-container-high/90 backdrop-blur-md text-primary font-label-sm text-label-sm border border-outline-variant/30">
                  {{ item.version || 'v2.0' }}
                </span>
              </div>

              <!-- Quick preview action -->
              <button 
                @click="buyProduct(item)" 
                class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200 inline-flex items-center gap-1 px-space-md py-1 rounded-full bg-surface-container-high/90 text-primary-container font-label-sm text-label-sm backdrop-blur-md shadow-md hover:bg-surface-bright" 
                type="button"
              >
                <span class="material-symbols-outlined text-[16px]">visibility</span> Preview Live
              </button>
            </div>

            <!-- Content Area -->
            <div class="flex flex-col flex-1 pt-space-md space-y-space-sm">
              <div class="flex items-center justify-between">
                <span class="text-primary-container font-label-sm text-label-sm font-bold uppercase tracking-wider">{{ item.category }}</span>
                <div class="flex items-center gap-1 text-secondary font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span class="font-bold text-primary">{{ item.rating || 4.9 }}</span>
                  <span class="text-on-surface-variant font-medium">({{ item.downloads || 0 }} unduhan)</span>
                </div>
              </div>

              <h3 class="font-headline-sm text-headline-sm text-primary group-hover:text-primary-container transition-colors line-clamp-1 font-bold">
                {{ item.title }}
              </h3>

              <p class="font-body-sm text-body-sm line-clamp-2 text-on-surface">
                {{ item.description || 'Sistem komponen Figma dan template arsitektur modern siap pakai berstandar enterprise.' }}
              </p>

              <!-- Price & CTA -->
              <div class="pt-space-sm mt-auto border-t border-surface-container-highest/30">
                <div class="flex items-baseline justify-between mb-space-sm">
                  <div>
                    <span class="font-body-sm text-body-sm line-through text-outline font-medium">{{ formatRupiah(item.price * 1.5) }}</span>
                    <div class="font-headline-md text-headline-md text-primary font-extrabold">{{ formatRupiah(item.price) }}</div>
                  </div>
                  <span class="text-secondary font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-secondary/15 font-bold border border-secondary/30">Hemat 33%</span>
                </div>

                <div class="grid grid-cols-2 gap-space-xs pt-1">
                  <button 
                    @click="buyProduct(item)"
                    class="inline-flex items-center justify-center py-2 px-3 rounded-full bg-surface-container-high text-primary font-label-md text-label-md hover:bg-surface-bright transition-colors text-center border border-outline-variant/30 font-semibold" 
                    type="button"
                  >
                    Detail
                  </button>
                  <button 
                    @click="buyProduct(item)"
                    class="inline-flex items-center justify-center py-2 px-3 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-label-md text-label-md font-bold shadow-[0_2px_14px_rgba(0,242,254,0.3)] hover:brightness-110 transition-all" 
                    type="button"
                  >
                    Unduh Aset
                  </button>
                </div>
              </div>
            </div>
          </article>

          <!-- Empty State -->
          <div v-if="filteredProducts.length === 0" class="col-span-full py-16 text-center">
            <span class="material-symbols-outlined text-[48px] text-outline mb-3">inventory_2</span>
            <h3 class="font-headline-sm text-primary font-bold">Tidak ada produk yang cocok</h3>
            <p class="text-sm text-on-surface-variant mt-1">Coba kata kunci pencarian lain atau pilih kategori Semua.</p>
            <button 
              @click="activeCategory = 'Semua'; searchQuery = ''" 
              class="mt-4 px-5 py-2 rounded-full bg-surface-container-high text-primary font-label-md text-label-md hover:bg-surface-bright"
            >
              Reset Filter
            </button>
          </div>
        </div>
      </section>

      <!-- Developer Bundle Promotional Banner -->
      <section class="w-full px-margin-mobile md:px-margin pb-space-xl">
        <div class="max-w-7xl mx-auto relative overflow-hidden rounded-3xl bg-gradient-to-r from-surface-container-low via-surface-container to-surface-container-low p-space-lg md:p-space-xl shadow-2xl border border-outline-variant/30">
          <!-- Glow ambient background inside banner -->
          <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-primary-container/20 rounded-full blur-[100px] pointer-events-none"></div>
          <div class="absolute -left-10 -top-10 w-72 h-72 bg-secondary/15 rounded-full blur-[80px] pointer-events-none"></div>
          <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
            <!-- Text & Pitch -->
            <div class="lg:col-span-8 space-y-space-md">
              <div class="inline-flex items-center gap-space-xs px-space-md py-1 rounded-full bg-primary-container/20 text-primary-container font-label-md text-label-md font-bold shadow-sm">
                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                PENAWARAN SPESIAL AKHIR BULAN
              </div>
              <h2 class="font-headline-lg md:font-display-hero text-headline-lg md:text-headline-lg text-primary tracking-tight font-extrabold">
                Bundle Hemat Developer <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-container to-secondary">50% OFF</span>
              </h2>
              <p class="font-body-lg text-body-lg max-w-2xl text-on-surface">
                Dapatkan seluruh akses ke 18 template source code web, 25 sistem UI Kit Figma, dan update aset gratis selama 12 bulan penuh hanya dengan satu lisensi studio.
              </p>
              <div class="flex flex-wrap items-center gap-space-md pt-space-xs">
                <div class="flex items-center gap-2 text-primary font-label-md text-label-md">
                  <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
                  Lisensi Komersial Tidak Terbatas
                </div>
                <div class="flex items-center gap-2 text-primary font-label-md text-label-md">
                  <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
                  Akses Private GitHub Repository
                </div>
                <div class="flex items-center gap-2 text-primary font-label-md text-label-md">
                  <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
                  Dukungan Teknis Komunitas VIP
                </div>
              </div>
            </div>

            <!-- Call to action card & counter -->
            <div class="lg:col-span-4 flex flex-col items-center lg:items-end justify-center">
              <div class="w-full max-w-sm bg-surface-container-highest/80 backdrop-blur-xl rounded-2xl p-space-lg shadow-xl text-center space-y-space-md border border-outline-variant/30">
                <div>
                  <span class="font-body-sm text-body-sm text-primary uppercase tracking-wider font-semibold">Harga Akses Penuh</span>
                  <div class="flex items-center justify-center gap-2 mt-1">
                    <span class="font-body-md text-body-md line-through text-outline font-medium">Rp 1.999.000</span>
                    <span class="font-headline-lg text-headline-lg font-extrabold text-primary">Rp 999.000</span>
                  </div>
                  <p class="font-label-sm text-label-sm text-secondary mt-1 font-bold">Tersisa 14 Slot Lisensi Hari Ini</p>
                </div>
                <button class="w-full inline-flex items-center justify-center gap-2 py-space-sm px-space-lg rounded-full bg-gradient-to-r from-primary-container via-secondary to-primary-container text-surface-container-lowest font-label-lg text-label-lg font-bold shadow-[0_4px_24px_rgba(0,242,254,0.45)] hover:scale-[1.02] active:scale-[0.98] transition-all" type="button">
                  <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                  Klaim Bundle Sekarang
                </button>
                <span class="block font-label-sm text-label-sm text-on-surface font-medium">
                  Garansi 100% Uang Kembali 7 Hari
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Checkout Modal Simulation -->
    <div v-if="selectedProduct" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-md rounded-3xl bg-surface-container border border-outline-variant/40 p-6 md:p-8 shadow-2xl">
        <button @click="selectedProduct = null" class="absolute top-5 right-5 text-outline hover:text-on-surface">
          <span class="material-symbols-outlined">close</span>
        </button>
        <div class="w-12 h-12 rounded-2xl bg-primary-container/20 flex items-center justify-center text-primary-container mb-4">
          <span class="material-symbols-outlined text-[24px]">shopping_cart_checkout</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-primary font-bold mb-1">{{ selectedProduct.title }}</h3>
        <p class="text-xs text-outline mb-4">Lisensi Standar Komersial (Single Project/Agency) • SKU: {{ selectedProduct.sku }}</p>

        <div class="p-4 rounded-2xl bg-surface-container-low mb-6 flex justify-between items-center border border-outline-variant/20">
          <span class="text-sm text-on-surface-variant">Total Pembayaran:</span>
          <span class="text-xl font-bold text-secondary">{{ formatRupiah(selectedProduct.price) }}</span>
        </div>

        <div class="space-y-3">
          <button @click="confirmDownload" class="w-full py-3.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm hover:brightness-110 shadow-lg flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">bolt</span>
            Bayar Instan via QRIS / Virtual Account
          </button>
          <button @click="selectedProduct = null" class="w-full py-2.5 rounded-full text-sm text-outline hover:text-on-surface">Batal</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { products, formatRupiah } from '@/data/mockData';

const searchQuery = ref('');
const activeCategory = ref('Semua');
const sortBy = ref('populer');
const selectedProduct = ref(null);

const categories = ['Semua', 'UI Kit', 'Source Code', 'Template & OS', '3D & Grafis'];

// Only show live products to public visitors
const liveProducts = computed(() => {
  return products.value.filter(p => p.status === 'Live');
});

const filteredProducts = computed(() => {
  let list = liveProducts.value.filter(p => {
    const matchCat = activeCategory.value === 'Semua' || p.category === activeCategory.value;
    const q = searchQuery.value.toLowerCase().trim();
    const matchQ = !q || 
      p.title.toLowerCase().includes(q) || 
      (p.description && p.description.toLowerCase().includes(q)) || 
      (p.sku && p.sku.toLowerCase().includes(q));
    return matchCat && matchQ;
  });

  // Sorting
  if (sortBy.value === 'terbaru') {
    list = [...list].reverse();
  } else if (sortBy.value === 'harga-rendah') {
    list = [...list].sort((a, b) => a.price - b.price);
  } else if (sortBy.value === 'harga-tinggi') {
    list = [...list].sort((a, b) => b.price - a.price);
  } else if (sortBy.value === 'rating') {
    list = [...list].sort((a, b) => (b.rating || 0) - (a.rating || 0));
  } else {
    // populer by downloads
    list = [...list].sort((a, b) => (b.downloads || 0) - (a.downloads || 0));
  }

  return list;
});

function buyProduct(item) {
  selectedProduct.value = item;
}

function confirmDownload() {
  alert(`Pembayaran berhasil! Tautan unduhan & lisensi untuk "${selectedProduct.value.title}" telah dikirim ke email Anda.`);
  selectedProduct.value = null;
}
</script>
