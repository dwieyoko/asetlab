<template>
  <div class="space-y-6 text-on-surface">
    <!-- Ambient Backdrop Glows -->
    <div class="relative w-full">
      <div class="absolute -top-12 left-1/4 w-96 h-96 bg-primary-container/5 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -top-10 right-10 w-80 h-80 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>

      <!-- Header & Action Row -->
      <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-space-md mb-space-lg">
        <div class="flex flex-col gap-1">
          <div class="flex items-center gap-space-xs">
            <span class="font-label-sm text-label-sm uppercase tracking-widest text-primary-fixed-dim font-bold">Admin Inventory Engine</span>
            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
            <span class="font-label-sm text-label-sm text-outline">Private Storage &amp; MySQL Sync</span>
          </div>
          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold flex items-center gap-2">
            Katalog &amp; Inventori Produk Digital
          </h1>
          <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
            Unggah file aset digital ke folder privat DomaiNesia, atur harga promo, lisensi, dan publikasi produk ke toko.
          </p>
        </div>

        <div class="flex items-center gap-space-sm flex-wrap sm:flex-nowrap">
          <button
            @click="triggerSync"
            :disabled="isSyncing"
            class="px-4 py-2.5 rounded-lg bg-surface-container-high hover:bg-surface-variant text-on-surface font-label-md text-label-md flex items-center gap-2 transition-all duration-200 disabled:opacity-60 border border-outline-variant/30 shadow-sm"
            type="button"
          >
            <span class="material-symbols-outlined text-[18px] text-tertiary-fixed-dim" :class="{ 'animate-spin': isSyncing }">cloud_sync</span>
            <span>{{ isSyncing ? 'Menyinkronkan...' : 'Sync Database' }}</span>
          </button>

          <button
            @click="openAddModal"
            class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-container via-secondary-fixed to-secondary text-surface-container-lowest font-headline-sm text-label-lg font-bold shadow-md shadow-primary-container/20 hover:shadow-primary-container/30 hover:brightness-105 active:scale-[0.98] transition-all duration-200 flex items-center gap-2"
            type="button"
          >
            <span class="material-symbols-outlined font-bold text-[20px]">add_circle</span>
            <span>+ Tambah Produk Baru</span>
          </button>
        </div>
      </div>

      <!-- Quick Metric Glass Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-space-md mb-space-lg">
        <div class="p-space-md rounded-2xl bg-surface-container-low/90 backdrop-blur-xl relative overflow-hidden group hover:bg-surface-container-high/90 transition-all duration-300">
          <div class="flex items-start justify-between mb-space-sm">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">1</span>
              <span class="font-label-md text-label-md text-outline">Total Koleksi</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm flex items-center gap-1 font-semibold">
              <span class="material-symbols-outlined text-[14px]">inventory</span> Live
            </span>
          </div>
          <div class="flex items-baseline justify-between">
            <div>
              <div class="font-headline-lg text-headline-lg text-primary font-extrabold tracking-tight">
                {{ products.length }} <span class="font-headline-sm text-headline-sm text-on-surface-variant font-normal">Item</span>
              </div>
              <p class="font-label-sm text-label-sm text-outline mt-1">
                {{ liveCount }} Aktif • {{ draftCount }} Draf
              </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center text-primary-container">
              <span class="material-symbols-outlined text-[24px]">dataset</span>
            </div>
          </div>
        </div>

        <div class="p-space-md rounded-2xl bg-surface-container-low/90 backdrop-blur-xl relative overflow-hidden group hover:bg-surface-container-high/90 transition-all duration-300">
          <div class="flex items-start justify-between mb-space-sm">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">2</span>
              <span class="font-label-md text-label-md text-outline">File Aset Terunggah</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-primary-container/10 text-primary-container font-label-sm text-label-sm flex items-center gap-1 font-semibold">
              <span class="material-symbols-outlined text-[14px]">lock</span> Privat
            </span>
          </div>
          <div class="flex items-baseline justify-between">
            <div>
              <div class="font-headline-lg text-headline-lg text-primary font-extrabold tracking-tight">
                {{ withFileCount }} <span class="font-headline-sm text-headline-sm text-on-surface-variant font-normal">/ {{ products.length }}</span>
              </div>
              <p class="font-label-sm text-label-sm text-secondary flex items-center gap-1 mt-1">
                <span class="material-symbols-outlined text-[13px]">shield</span> Terproteksi .htaccess
              </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-[24px]">folder_zip</span>
            </div>
          </div>
        </div>

        <div class="p-space-md rounded-2xl bg-surface-container-low/90 backdrop-blur-xl relative overflow-hidden group hover:bg-surface-container-high/90 transition-all duration-300">
          <div class="flex items-start justify-between mb-space-sm">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">3</span>
              <span class="font-label-md text-label-md text-outline">Total Unduhan &amp; Terjual</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm flex items-center gap-1 font-semibold">
              <span class="material-symbols-outlined text-[14px]">arrow_outward</span> Real-time
            </span>
          </div>
          <div class="flex items-baseline justify-between">
            <div>
              <div class="font-headline-lg text-headline-lg text-primary font-extrabold tracking-tight">
                {{ totalDownloads }} <span class="font-headline-sm text-headline-sm text-on-surface-variant font-normal">Lisensi</span>
              </div>
              <p class="font-label-sm text-label-sm text-outline mt-1">Akumulasi lisensi aktif</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center text-primary-container">
              <span class="material-symbols-outlined text-[24px]">download_for_offline</span>
            </div>
          </div>
        </div>

        <div class="p-space-md rounded-2xl bg-surface-container-low/90 backdrop-blur-xl relative overflow-hidden group hover:bg-surface-container-high/90 transition-all duration-300">
          <div class="flex items-start justify-between mb-space-sm">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">4</span>
              <span class="font-label-md text-label-md text-outline">Estimasi Valuasi GMV</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm flex items-center gap-1 font-semibold">
              <span class="material-symbols-outlined text-[14px]">trending_up</span> Akumulasi
            </span>
          </div>
          <div class="flex items-baseline justify-between">
            <div>
              <div class="font-headline-lg text-headline-lg text-primary font-extrabold tracking-tight">
                {{ formatRupiah(estimatedRevenue) }}
              </div>
              <p class="font-label-sm text-label-sm text-outline mt-1">Total revenue produk</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-[24px]">payments</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Tabs & Live Search Bar -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-space-md p-3 rounded-xl bg-surface-container-low border border-outline-variant/20 mb-space-md">
        <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto scrollbar-none pb-1 sm:pb-0">
          <button
            v-for="cat in categories"
            :key="cat"
            @click="activeCategory = cat"
            class="px-3.5 py-1.5 rounded-lg font-label-md text-xs transition-all shrink-0"
            :class="activeCategory === cat ? 'bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold shadow-sm' : 'bg-surface-container text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high'"
          >
            {{ cat }}
          </button>
        </div>

        <div class="relative w-full sm:w-72">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari judul produk atau SKU..."
            class="w-full bg-surface-container pl-9 pr-4 py-2 rounded-lg text-xs text-on-surface placeholder:text-outline border border-outline-variant/30 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
          />
          <button
            v-if="searchQuery"
            @click="searchQuery = ''"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface"
          >
            <span class="material-symbols-outlined text-[16px]">close</span>
          </button>
        </div>
      </div>

      <!-- Dynamic Inventory Table -->
      <div class="rounded-2xl bg-surface-container-low overflow-hidden shadow-xl border border-outline-variant/20">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-surface-container-lowest/80 text-outline uppercase font-label-sm text-label-sm tracking-wider">
                <th class="py-4 px-space-md">Produk &amp; Versi</th>
                <th class="py-4 px-space-md">File Aset Privat</th>
                <th class="py-4 px-space-md">Kategori</th>
                <th class="py-4 px-space-md">Harga Jual</th>
                <th class="py-4 px-space-md">Terjual</th>
                <th class="py-4 px-space-md">Status</th>
                <th class="py-4 px-space-md text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-highest/20 font-body-sm text-body-sm">
              <tr
                v-for="item in filteredProducts"
                :key="item.id"
                class="hover:bg-surface-container/60 transition-colors group"
              >
                <!-- Column 1: Image & Title -->
                <td class="py-4 px-space-md">
                  <div class="flex items-center gap-3">
                    <img
                      :src="item.image"
                      :alt="item.title"
                      class="w-12 h-12 rounded-xl object-cover bg-surface-container-high border border-outline-variant/30 shrink-0 shadow-md"
                    />
                    <div class="flex flex-col min-w-0">
                      <span class="font-label-md text-label-md text-on-surface font-semibold group-hover:text-primary transition-colors truncate max-w-[260px]">
                        {{ item.title }}
                      </span>
                      <span class="font-label-sm text-label-sm text-outline flex items-center gap-1.5 mt-0.5">
                        <span class="px-1.5 py-0.2 rounded bg-surface-container-highest text-primary font-mono text-[11px]">{{ item.version }}</span>
                        <span class="font-mono text-[11px]">{{ item.sku }}</span>
                      </span>
                    </div>
                  </div>
                </td>

                <!-- Column 2: Private File Status -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div v-if="item.has_file || item.file_path" class="flex flex-col">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-secondary">
                      <span class="material-symbols-outlined text-[15px]">verified</span>
                      File Privat Siap
                    </span>
                    <span class="text-[11px] text-outline truncate max-w-[180px]" :title="item.file_name || item.file_path">
                      {{ item.file_name || item.file_path }}
                      <template v-if="item.file_size">({{ formatFileSize(item.file_size) }})</template>
                    </span>
                  </div>
                  <button
                    v-else
                    @click="openEditModal(item)"
                    type="button"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 text-[11px] font-semibold hover:bg-amber-500/20 transition-colors"
                  >
                    <span class="material-symbols-outlined text-[14px]">upload_file</span>
                    Belum Upload File
                  </button>
                </td>

                <!-- Column 3: Category -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-container/10 border border-primary-container/30 text-primary-container">
                    {{ item.category }}
                  </span>
                </td>

                <!-- Column 4: Price -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface font-bold font-tabular">
                      {{ formatRupiah(item.price) }}
                    </span>
                    <span
                      v-if="item.original_price && item.original_price > item.price"
                      class="text-[11px] text-outline line-through"
                    >
                      {{ formatRupiah(item.original_price) }}
                    </span>
                  </div>
                </td>

                <!-- Column 5: Downloads -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex items-center gap-1.5 text-on-surface font-tabular">
                    <span class="material-symbols-outlined text-[16px] text-secondary">download</span>
                    <span class="font-bold">{{ item.downloads }}</span>
                  </div>
                </td>

                <!-- Column 6: Status Toggle -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <button
                    @click="toggleStatus(item)"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-sm text-label-sm transition-all cursor-pointer"
                    :class="item.status === 'Live' ? 'bg-secondary/15 text-secondary border border-secondary/30 hover:bg-secondary/25' : 'bg-surface-container-highest text-outline border border-outline-variant/30 hover:text-on-surface'"
                    :title="'Klik untuk mengubah menjadi ' + (item.status === 'Live' ? 'Draft' : 'Live')"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="item.status === 'Live' ? 'bg-secondary animate-pulse' : 'bg-outline'"></span>
                    <span>{{ item.status }}</span>
                  </button>
                </td>

                <!-- Column 7: Actions -->
                <td class="py-4 px-space-md whitespace-nowrap text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <router-link
                      :to="`/produk/${item.slug || item.id}`"
                      target="_blank"
                      class="p-2 rounded-lg bg-surface-container hover:bg-secondary/20 text-on-surface-variant hover:text-secondary transition-colors"
                      title="Lihat Halaman Produk"
                    >
                      <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    </router-link>
                    <button
                      @click="openEditModal(item)"
                      class="p-2 rounded-lg bg-surface-container hover:bg-primary-container/20 text-on-surface-variant hover:text-primary transition-colors"
                      title="Edit Produk & Upload File"
                    >
                      <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <button
                      @click="confirmDelete(item)"
                      class="p-2 rounded-lg bg-surface-container hover:bg-error-container/40 text-on-surface-variant hover:text-error transition-colors"
                      title="Hapus Produk"
                    >
                      <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="filteredProducts.length === 0">
                <td colspan="7" class="py-12 text-center text-outline">
                  <div class="flex flex-col items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-4xl text-outline/50">inventory_2</span>
                    <p class="font-label-md">Tidak ada produk yang cocok dengan pencarian atau filter.</p>
                    <button @click="resetFilters" class="text-xs text-primary underline mt-1">Reset Filter</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="p-space-md bg-surface-container-lowest/60 border-t border-outline-variant/20 flex flex-col sm:flex-row items-center justify-between gap-space-sm text-xs text-outline">
          <span>Menampilkan {{ filteredProducts.length }} dari total {{ products.length }} produk digital</span>
          <span class="text-secondary flex items-center gap-1 font-medium">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
            Storage privat DomaiNesia aktif (`storage/private/products/`)
          </span>
        </div>
      </div>
    </div>

    <!-- ================= MODAL FORM: TAMBAH / EDIT PRODUK DIGITAL ================= -->
    <div v-if="showFormModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-3xl max-h-[92vh] overflow-y-auto rounded-2xl bg-surface-container-high border border-outline-variant/30 p-6 md:p-8 shadow-2xl">
        <button @click="closeFormModal" class="absolute top-5 right-5 text-outline hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors">
          <span class="material-symbols-outlined">close</span>
        </button>

        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-lg bg-primary-container/20 flex items-center justify-center text-primary-container">
            <span class="material-symbols-outlined text-[22px]">{{ isEditMode ? 'edit_note' : 'add_box' }}</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-tertiary font-bold">
              {{ isEditMode ? 'Edit Detail Produk Digital' : 'Publikasi Produk Digital Baru' }}
            </h3>
            <p class="text-xs text-outline">
              {{ isEditMode ? `Perbarui harga, status, atau metadata produk ${formState.sku}.` : 'Lengkapi informasi produk dan unggah file aset digital ke folder privat.' }}
            </p>
          </div>
        </div>

        <!-- Inline Error -->
        <div v-if="formError" class="mb-4 p-3 rounded-lg bg-error-container/30 border border-error/40 text-error text-xs flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">error</span>
          <span>{{ formError }}</span>
        </div>

        <form @submit.prevent="submitForm" class="space-y-4">
          <!-- Title & License -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Produk Digital *</label>
              <input
                v-model="formState.title"
                required
                type="text"
                placeholder="e.g. Modern AI SaaS Dashboard UI Kit"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">SKU / Kode Unik</label>
              <input
                v-model="formState.sku"
                type="text"
                placeholder="DS-CYBER-2026"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 font-mono transition-all"
              />
            </div>
          </div>

          <!-- Category, Version, License -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Kategori *</label>
              <select
                v-model="formState.category"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              >
                <option value="UI Kit">UI Kit</option>
                <option value="Source Code">Source Code</option>
                <option value="Template & OS">Template & OS</option>
                <option value="3D & Grafis">3D & Grafis</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Versi Rilis *</label>
              <input
                v-model="formState.version"
                required
                type="text"
                placeholder="v1.0.0"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Tipe Lisensi</label>
              <input
                v-model="formState.license_type"
                type="text"
                placeholder="Commercial Single Project"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>
          </div>

          <!-- Price, Original Price, Status -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Harga Jual (IDR) *</label>
              <input
                v-model.number="formState.price"
                required
                type="number"
                min="0"
                step="1000"
                placeholder="299000"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Harga Normal / Coret (Opsional)</label>
              <input
                v-model.number="formState.original_price"
                type="number"
                min="0"
                step="1000"
                placeholder="450000"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Status Publikasi</label>
              <select
                v-model="formState.status"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              >
                <option value="Live">Live (Tampil di Toko)</option>
                <option value="Draft">Draft (Arsip Internal)</option>
              </select>
            </div>
          </div>

          <!-- Upload Zone: Cover Image & Private Digital Product File -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
            <!-- Cover Image Upload -->
            <div class="p-4 rounded-xl bg-surface-container-low/90 border border-outline-variant/30 space-y-3">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-primary flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[16px] text-primary-container">image</span>
                  Cover Thumbnail Produk
                </label>
                <span class="text-[10px] text-outline">JPG/PNG/WEBP • Max 5MB</span>
              </div>

              <div v-if="formState.image" class="relative w-full h-28 rounded-lg overflow-hidden bg-surface-container border border-outline-variant/20">
                <img :src="formState.image" alt="Preview Cover" class="w-full h-full object-cover" />
              </div>

              <div class="flex items-center gap-2">
                <label class="flex-1 cursor-pointer inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-lg bg-surface-container hover:bg-surface-container-highest border border-outline-variant/40 text-xs font-semibold text-primary transition-colors">
                  <span class="material-symbols-outlined text-[16px]">upload</span>
                  <span>{{ uploadingImage ? 'Mengunggah...' : 'Upload Gambar' }}</span>
                  <input type="file" accept="image/png,image/jpeg,image/webp,image/gif" class="hidden" :disabled="uploadingImage" @change="handleImageUpload" />
                </label>
              </div>
              <input
                v-model="formState.image"
                type="text"
                placeholder="Atau tempel URL gambar /uploads/products/..."
                class="w-full bg-surface-container px-3 py-2 rounded-lg border border-outline-variant/30 text-xs text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>

            <!-- Private Digital File Upload -->
            <div class="p-4 rounded-xl bg-surface-container-low/90 border border-secondary/30 space-y-3">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-secondary flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[16px]">folder_zip</span>
                  File Produk Digital (Privat)
                </label>
                <span class="text-[10px] text-outline">.ZIP/.FIG/.PDF • Max 100MB</span>
              </div>

              <div
                v-if="formState.file_path"
                class="p-3 rounded-lg bg-secondary/10 border border-secondary/30 flex items-start gap-2.5"
              >
                <span class="material-symbols-outlined text-secondary text-[20px] shrink-0">verified_user</span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-bold text-primary truncate">{{ formState.file_name || formState.file_path }}</p>
                  <p class="text-[11px] text-secondary">
                    Tersimpan aman di storage privat
                    <template v-if="formState.file_size"> • {{ formatFileSize(formState.file_size) }}</template>
                  </p>
                </div>
              </div>
              <div v-else class="p-3 rounded-lg bg-surface-container border border-dashed border-outline-variant/40 text-center">
                <p class="text-xs text-outline">Belum ada file aset yang diunggah.</p>
                <p class="text-[10px] text-outline mt-0.5">Pembeli akan menerima file ini setelah transaksi lunas.</p>
              </div>

              <label class="w-full cursor-pointer inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-lg bg-secondary/15 hover:bg-secondary/25 border border-secondary/40 text-xs font-bold text-secondary transition-colors">
                <span class="material-symbols-outlined text-[17px]">{{ uploadingFile ? 'progress_activity' : 'cloud_upload' }}</span>
                <span>{{ uploadingFile ? 'Mengunggah ke Storage Privat...' : (formState.file_path ? 'Ganti File Produk Digital' : 'Pilih & Upload File Produk') }}</span>
                <input
                  type="file"
                  accept=".zip,.rar,.7z,.pdf,.fig,.sketch,.xd,.ai,.psd,.blend,.epub,.tar,.gz,.json,.md,.txt"
                  class="hidden"
                  :disabled="uploadingFile"
                  @change="handleProductFileUpload"
                />
              </label>
            </div>
          </div>

          <!-- Preview URL & Tags -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">URL Demo / Preview Live (Opsional)</label>
              <input
                v-model="formState.preview_url"
                type="url"
                placeholder="https://demo.asetlab.web.id/fintech-ui"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Tags (pisahkan dengan koma)</label>
              <input
                v-model="tagsInput"
                type="text"
                placeholder="Figma, Tailwind, SaaS, Dark Mode"
                class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
              />
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Deskripsi Lengkap Produk</label>
            <textarea
              v-model="formState.description"
              rows="3"
              placeholder="Jelaskan fitur utama, kompatibilitas software (Figma/Next.js/Tailwind), dan manfaat untuk pembeli..."
              class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
            ></textarea>
          </div>

          <!-- Highlights (1 per line) -->
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Highlight Fitur Utama (1 poin per baris)</label>
            <textarea
              v-model="highlightsInput"
              rows="3"
              placeholder="240+ Komponen Figma Auto-Layout&#10;Design Token JSON Siap Tailwind CSS&#10;Free Update Versi Minor Selamanya"
              class="w-full bg-surface-container-low px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
            ></textarea>
          </div>

          <div class="pt-4 flex justify-end gap-2.5 border-t border-outline-variant/30">
            <button @click="closeFormModal" type="button" class="px-4 py-2.5 rounded-lg text-sm font-medium text-outline hover:text-on-surface hover:bg-surface-container transition-all">
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSaving || uploadingImage || uploadingFile"
              class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm hover:brightness-110 shadow-md shadow-primary-container/20 active:scale-[0.99] transition-all disabled:opacity-60"
            >
              {{ isSaving ? 'Menyimpan...' : (isEditMode ? 'Simpan Perubahan' : 'Simpan & Rilis Produk') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= MODAL KONFIRMASI HAPUS ================= -->
    <div v-if="deletingProduct" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-md rounded-2xl bg-surface-container-high border border-outline-variant/30 p-6 md:p-8 shadow-2xl text-center">
        <div class="w-12 h-12 rounded-xl bg-error-container/40 text-error flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-[28px]">warning</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-tertiary font-bold mb-2">Hapus Produk Ini?</h3>
        <p class="text-sm text-outline mb-6">
          Apakah Anda yakin ingin menghapus produk <strong class="text-on-surface">"{{ deletingProduct.title }}"</strong>? Jika produk pernah dibeli, sebaiknya ubah status ke Draft.
        </p>
        <div class="flex justify-center gap-3">
          <button @click="deletingProduct = null" class="px-4 py-2.5 rounded-lg text-sm font-medium text-outline hover:text-on-surface hover:bg-surface-container transition-all">
            Batal
          </button>
          <button @click="executeDelete" class="px-5 py-2.5 rounded-lg bg-error text-white font-bold text-sm hover:brightness-110 shadow-md transition-all">
            Ya, Hapus Produk
          </button>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div
      v-if="toast.message"
      class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-2xl bg-surface-container-highest text-sm font-semibold shadow-2xl flex items-center gap-2 border"
      :class="toast.type === 'error' ? 'border-error/40 text-error' : 'border-secondary/40 text-secondary'"
    >
      <span class="material-symbols-outlined text-[20px]">{{ toast.type === 'error' ? 'error' : 'check_circle' }}</span>
      <span>{{ toast.message }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import {
  products,
  addProduct,
  updateProduct,
  deleteProduct,
  syncWithRemoteDatabase,
  formatRupiah,
  formatFileSize,
} from '@/data/mockData';
import { UploadApi } from '@/services/api';

const categories = ['Semua', 'UI Kit', 'Source Code', 'Template & OS', '3D & Grafis'];
const activeCategory = ref('Semua');
const searchQuery = ref('');
const isSyncing = ref(false);
const isSaving = ref(false);
const uploadingImage = ref(false);
const uploadingFile = ref(false);
const toast = ref({ message: '', type: 'success' });

const showFormModal = ref(false);
const isEditMode = ref(false);
const formError = ref('');
const deletingProduct = ref(null);

const formState = ref({});
const tagsInput = ref('');
const highlightsInput = ref('');

function showToast(msg, type = 'success') {
  toast.value = { message: msg, type };
  setTimeout(() => {
    toast.value = { message: '', type: 'success' };
  }, 3500);
}

const filteredProducts = computed(() => {
  return products.value.filter((p) => {
    const matchCategory = activeCategory.value === 'Semua' || p.category === activeCategory.value;
    const q = searchQuery.value.toLowerCase().trim();
    const matchQuery =
      !q ||
      p.title.toLowerCase().includes(q) ||
      (p.sku && p.sku.toLowerCase().includes(q));
    return matchCategory && matchQuery;
  });
});

const liveCount = computed(() => products.value.filter((p) => p.status === 'Live').length);
const draftCount = computed(() => products.value.filter((p) => p.status === 'Draft').length);
const withFileCount = computed(() => products.value.filter((p) => p.has_file || p.file_path).length);
const totalDownloads = computed(() => products.value.reduce((acc, p) => acc + (p.downloads || 0), 0));
const estimatedRevenue = computed(() =>
  products.value.reduce((acc, p) => acc + (p.downloads || 0) * (p.price || 0), 0)
);

function resetFilters() {
  activeCategory.value = 'Semua';
  searchQuery.value = '';
}

function openAddModal() {
  isEditMode.value = false;
  formError.value = '';
  formState.value = {
    id: '',
    title: '',
    sku: `ASET-${Math.random().toString(36).substring(2, 7).toUpperCase()}`,
    category: 'UI Kit',
    price: 299000,
    original_price: 450000,
    version: 'v1.0.0',
    license_type: 'Commercial Single Project',
    status: 'Live',
    preview_url: '',
    image: '',
    file_path: '',
    file_name: '',
    file_size: 0,
    description: '',
  };
  tagsInput.value = 'UI Kit, Modern';
  highlightsInput.value = '';
  showFormModal.value = true;
}

function openEditModal(item) {
  isEditMode.value = true;
  formError.value = '';
  formState.value = {
    ...item,
    original_price: item.original_price || '',
    preview_url: item.preview_url || '',
    file_path: item.file_path || '',
    file_name: item.file_name || '',
    file_size: item.file_size || 0,
  };
  tagsInput.value = Array.isArray(item.tags) ? item.tags.join(', ') : '';
  highlightsInput.value = Array.isArray(item.highlights) ? item.highlights.join('\n') : '';
  showFormModal.value = true;
}

function closeFormModal() {
  showFormModal.value = false;
  formError.value = '';
}

async function handleImageUpload(event) {
  const file = event.target.files?.[0];
  if (!file) return;
  formError.value = '';
  uploadingImage.value = true;
  try {
    const res = await UploadApi.uploadImage(file);
    if (res?.data?.url) {
      formState.value.image = res.data.url;
      showToast('Cover gambar berhasil diunggah!');
    }
  } catch (err) {
    formError.value = err?.message || 'Gagal mengunggah gambar (pastikan backend PHP aktif & login sebagai admin).';
  } finally {
    uploadingImage.value = false;
    event.target.value = '';
  }
}

async function handleProductFileUpload(event) {
  const file = event.target.files?.[0];
  if (!file) return;
  formError.value = '';
  uploadingFile.value = true;
  try {
    const res = await UploadApi.uploadProductFile(file, isEditMode.value ? formState.value.id : '');
    if (res?.data?.file_path) {
      formState.value.file_path = res.data.file_path;
      formState.value.file_name = res.data.file_name;
      formState.value.file_size = res.data.file_size;
      formState.value.has_file = true;
      showToast(`File "${res.data.file_name}" berhasil disimpan di folder privat!`);
    }
  } catch (err) {
    formError.value = err?.message || 'Gagal mengunggah file produk ke storage privat.';
  } finally {
    uploadingFile.value = false;
    event.target.value = '';
  }
}

async function submitForm() {
  formError.value = '';
  isSaving.value = true;
  try {
    const payload = {
      ...formState.value,
      price: Number(formState.value.price) || 0,
      original_price: formState.value.original_price ? Number(formState.value.original_price) : null,
      tags: tagsInput.value
        .split(',')
        .map((t) => t.trim())
        .filter(Boolean),
      highlights: highlightsInput.value
        .split('\n')
        .map((h) => h.trim())
        .filter(Boolean),
    };

    if (isEditMode.value) {
      const res = await updateProduct(formState.value.id, payload);
      showFormModal.value = false;
      showToast(
        res?.synced
          ? `Perubahan "${payload.title}" tersimpan ke database.`
          : `Perubahan "${payload.title}" tersimpan secara lokal (backend offline).`
      );
    } else {
      const res = await addProduct(payload);
      showFormModal.value = false;
      showToast(
        res?.synced
          ? `Produk "${payload.title}" berhasil dipublikasikan ke database.`
          : `Produk "${payload.title}" disimpan di mode lokal.`
      );
    }
  } catch (err) {
    formError.value = err?.message || 'Gagal menyimpan produk.';
  } finally {
    isSaving.value = false;
  }
}

async function toggleStatus(item) {
  const nextStatus = item.status === 'Live' ? 'Draft' : 'Live';
  try {
    await updateProduct(item.id, { status: nextStatus });
    showToast(`Status "${item.title}" diubah menjadi ${nextStatus}`);
  } catch (err) {
    showToast(err?.message || 'Gagal mengubah status produk.', 'error');
  }
}

function confirmDelete(item) {
  deletingProduct.value = item;
}

async function executeDelete() {
  if (!deletingProduct.value) return;
  const title = deletingProduct.value.title;
  try {
    await deleteProduct(deletingProduct.value.id);
    deletingProduct.value = null;
    showToast(`Produk "${title}" telah dihapus.`);
  } catch (err) {
    deletingProduct.value = null;
    showToast(err?.message || 'Gagal menghapus produk.', 'error');
  }
}

async function triggerSync() {
  isSyncing.value = true;
  try {
    await syncWithRemoteDatabase();
    showToast('Sinkronisasi data produk dengan database selesai.');
  } catch {
    showToast('Gagal menghubungi database server.', 'error');
  } finally {
    isSyncing.value = false;
  }
}
</script>
