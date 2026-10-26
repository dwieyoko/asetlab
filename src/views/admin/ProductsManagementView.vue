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
            <span class="font-label-sm text-label-sm text-outline">v2.4 CRUD System Live</span>
          </div>
          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold flex items-center gap-2">
            Katalog &amp; Inventori Produk Digital
          </h1>
          <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
            Kelola inventori, ubah harga lisensi, publikasi aset baru, dan update ketersediaan produk digital secara real-time.
          </p>
        </div>

        <div class="flex items-center gap-space-sm flex-wrap sm:flex-nowrap">
          <button 
            @click="triggerSync"
            class="px-space-md py-space-sm rounded-full bg-surface-container-high hover:bg-surface-variant text-on-surface font-label-md text-label-md flex items-center gap-2 transition-all duration-200" 
            type="button"
          >
            <span class="material-symbols-outlined text-[18px] text-tertiary-fixed-dim" :class="{ 'animate-spin': isSyncing }">cloud_sync</span>
            <span>{{ isSyncing ? 'Menyinkronkan...' : 'Sync Repo' }}</span>
          </button>
          
          <button 
            @click="openAddModal"
            class="px-space-lg py-space-sm rounded-full bg-gradient-to-r from-primary-container via-secondary-fixed to-secondary text-surface-container-lowest font-headline-sm text-label-lg font-bold shadow-lg shadow-primary-container/20 hover:shadow-primary-container/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 flex items-center gap-2" 
            type="button"
          >
            <span class="material-symbols-outlined font-bold text-[20px]">add_circle</span>
            <span>+ Tambah Produk Baru</span>
          </button>
        </div>
      </div>

      <!-- Quick Metric Glass Cards (Top Stats Reacting Live) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-space-md mb-space-lg">
        <!-- Stat Card 1 -->
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

        <!-- Stat Card 2 -->
        <div class="p-space-md rounded-2xl bg-surface-container-low/90 backdrop-blur-xl relative overflow-hidden group hover:bg-surface-container-high/90 transition-all duration-300">
          <div class="flex items-start justify-between mb-space-sm">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">2</span>
              <span class="font-label-md text-label-md text-outline">Total Unduhan &amp; Terjual</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm flex items-center gap-1 font-semibold">
              <span class="material-symbols-outlined text-[14px]">arrow_outward</span> Real-time
            </span>
          </div>
          <div class="flex items-baseline justify-between">
            <div>
              <div class="font-headline-lg text-headline-lg text-primary font-extrabold tracking-tight">
                {{ totalDownloads }} <span class="font-headline-sm text-headline-sm text-on-surface-variant font-normal">Files</span>
              </div>
              <p class="font-label-sm text-label-sm text-secondary flex items-center gap-1 mt-1">
                <span class="material-symbols-outlined text-[13px]">bolt</span> Terkurasi di ekosistem
              </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-[24px]">download_for_offline</span>
            </div>
          </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="p-space-md rounded-2xl bg-surface-container-low/90 backdrop-blur-xl relative overflow-hidden group hover:bg-surface-container-high/90 transition-all duration-300">
          <div class="flex items-start justify-between mb-space-sm">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">3</span>
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
            <div class="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center text-primary-container">
              <span class="material-symbols-outlined text-[24px]">payments</span>
            </div>
          </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="p-space-md rounded-2xl bg-surface-container-low/90 backdrop-blur-xl relative overflow-hidden group hover:bg-surface-container-high/90 transition-all duration-300">
          <div class="flex items-start justify-between mb-space-sm">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">4</span>
              <span class="font-label-md text-label-md text-outline">Rata-rata Rating</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary-container font-label-sm text-label-sm flex items-center gap-1 font-semibold">
              <span class="material-symbols-outlined text-[14px]">star</span> Kualitas
            </span>
          </div>
          <div class="flex items-baseline justify-between">
            <div>
              <div class="font-headline-lg text-headline-lg text-primary font-extrabold tracking-tight">4.92 / 5.0</div>
              <p class="font-label-sm text-label-sm text-outline mt-1">Ulasan pembeli terverifikasi</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-[24px]">verified</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Tabs & Live Search Bar -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-space-md p-space-sm rounded-2xl bg-surface-container-low mb-space-md">
        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto scrollbar-none pb-1 sm:pb-0">
          <button 
            v-for="cat in categories" 
            :key="cat"
            @click="activeCategory = cat"
            class="px-4 py-2 rounded-full font-label-md text-label-md transition-all shrink-0"
            :class="activeCategory === cat ? 'bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold shadow-md shadow-primary-container/20' : 'bg-surface-container text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high'"
          >
            {{ cat }}
          </button>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full sm:w-72">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Cari judul produk atau SKU..."
            class="w-full bg-surface-container-highest/60 pl-9 pr-4 py-2 rounded-full text-sm text-on-surface placeholder:text-outline border border-outline-variant/30 focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container"
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
                <th class="py-4 px-space-md">SKU / ID</th>
                <th class="py-4 px-space-md">Kategori</th>
                <th class="py-4 px-space-md">Harga Satuan</th>
                <th class="py-4 px-space-md">Terjual / Download</th>
                <th class="py-4 px-space-md">Status</th>
                <th class="py-4 px-space-md text-right">Aksi Manajemen</th>
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
                    <div class="flex flex-col">
                      <span class="font-label-md text-label-md text-on-surface font-semibold group-hover:text-primary transition-colors">
                        {{ item.title }}
                      </span>
                      <span class="font-label-sm text-label-sm text-outline flex items-center gap-1.5 mt-0.5">
                        <span class="px-1.5 py-0.2 rounded bg-surface-container-highest text-primary font-mono text-[11px]">{{ item.version }}</span>
                        <span>•</span>
                        <span>⭐ {{ item.rating }}</span>
                      </span>
                    </div>
                  </div>
                </td>

                <!-- Column 2: SKU / ID -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="font-mono text-primary font-medium text-xs">{{ item.sku }}</span>
                    <span class="font-label-sm text-[11px] text-outline">{{ item.id }}</span>
                  </div>
                </td>

                <!-- Column 3: Category -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-container/10 border border-primary-container/30 text-primary-container">
                    {{ item.category }}
                  </span>
                </td>

                <!-- Column 4: Price -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <span class="font-label-md text-label-md text-on-surface font-bold font-tabular">
                    {{ formatRupiah(item.price) }}
                  </span>
                </td>

                <!-- Column 5: Downloads -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex items-center gap-1.5 text-on-surface font-tabular">
                    <span class="material-symbols-outlined text-[16px] text-secondary">download</span>
                    <span class="font-bold">{{ item.downloads }}</span>
                    <span class="text-outline text-xs">lisensi</span>
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
                    <button 
                      @click="openEditModal(item)"
                      class="p-2 rounded-lg bg-surface-container hover:bg-primary-container/20 text-on-surface-variant hover:text-primary transition-colors"
                      title="Edit Produk"
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

              <!-- Empty State -->
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
            Data tersinkron otomatis ke LocalStorage
          </span>
        </div>
      </div>
    </div>

    <!-- ================= MODAL 1: TAMBAH PRODUK BARU ================= -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl bg-surface-container-high border border-outline-variant/40 p-6 md:p-8 shadow-2xl">
        <button @click="showAddModal = false" class="absolute top-5 right-5 text-outline hover:text-on-surface">
          <span class="material-symbols-outlined">close</span>
        </button>
        
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-full bg-primary-container/20 flex items-center justify-center text-primary-container">
            <span class="material-symbols-outlined text-[22px]">add_box</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-tertiary font-bold">Publikasi Aset Digital Baru</h3>
            <p class="text-xs text-outline">Lengkapi formulir untuk menambahkan produk ke katalog asetlab.id.</p>
          </div>
        </div>

        <form @submit.prevent="submitAddProduct" class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Produk Digital *</label>
            <input 
              v-model="newProduct.title" 
              required 
              type="text" 
              placeholder="e.g. Modern AI SaaS Dashboard UI Kit"
              class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Kategori *</label>
              <select 
                v-model="newProduct.category"
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
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
                v-model="newProduct.version" 
                required 
                type="text" 
                placeholder="v1.0.0"
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Harga Jual (IDR) *</label>
              <input 
                v-model.number="newProduct.price" 
                required 
                type="number" 
                min="0"
                step="1000"
                placeholder="299000"
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">SKU / Kode Unik (Opsional)</label>
              <input 
                v-model="newProduct.sku" 
                type="text" 
                placeholder="DS-CYBER-2026"
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Status Publikasi Awal</label>
            <div class="flex gap-4">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" value="Live" v-model="newProduct.status" class="accent-secondary" />
                <span class="text-sm">Live (Langsung Tampil di Toko)</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" value="Draft" v-model="newProduct.status" class="accent-secondary" />
                <span class="text-sm">Draft (Hanya Internal)</span>
              </label>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Deskripsi Singkat &amp; Fitur</label>
            <textarea 
              v-model="newProduct.description" 
              rows="3"
              placeholder="Jelaskan fitur utama, kompatibilitas software (Figma/Next.js/Tailwind), dll..."
              class="w-full bg-surface-container-low px-4 py-2 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
            ></textarea>
          </div>

          <div class="pt-4 flex justify-end gap-3 border-t border-outline-variant/30">
            <button @click="showAddModal = false" type="button" class="px-5 py-2.5 rounded-full text-sm text-outline hover:text-on-surface">
              Batal
            </button>
            <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm hover:brightness-110 shadow-lg shadow-primary-container/20">
              Simpan &amp; Rilis Produk
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= MODAL 2: EDIT PRODUK ================= -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl bg-surface-container-high border border-outline-variant/40 p-6 md:p-8 shadow-2xl">
        <button @click="showEditModal = false" class="absolute top-5 right-5 text-outline hover:text-on-surface">
          <span class="material-symbols-outlined">close</span>
        </button>
        
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined text-[22px]">edit_note</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-tertiary font-bold">Edit Detail Produk Digital</h3>
            <p class="text-xs text-outline">Perbarui harga, status, atau metadata produk {{ editingProduct.sku }}.</p>
          </div>
        </div>

        <form @submit.prevent="submitEditProduct" class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Produk *</label>
            <input 
              v-model="editingProduct.title" 
              required 
              type="text" 
              class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Kategori *</label>
              <select 
                v-model="editingProduct.category"
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
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
                v-model="editingProduct.version" 
                required 
                type="text" 
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Harga Jual (IDR) *</label>
              <input 
                v-model.number="editingProduct.price" 
                required 
                type="number" 
                min="0"
                step="1000"
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Status Publikasi</label>
              <select 
                v-model="editingProduct.status"
                class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
              >
                <option value="Live">Live (Tampil di Toko)</option>
                <option value="Draft">Draft (Arsip Internal)</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Deskripsi Singkat</label>
            <textarea 
              v-model="editingProduct.description" 
              rows="3"
              class="w-full bg-surface-container-low px-4 py-2 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"
            ></textarea>
          </div>

          <div class="pt-4 flex justify-end gap-3 border-t border-outline-variant/30">
            <button @click="showEditModal = false" type="button" class="px-5 py-2.5 rounded-full text-sm text-outline hover:text-on-surface">
              Batal
            </button>
            <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm hover:brightness-110 shadow-lg shadow-primary-container/20">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= MODAL 3: KONFIRMASI HAPUS ================= -->
    <div v-if="deletingProduct" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-md rounded-3xl bg-surface-container-high border border-outline-variant/40 p-6 md:p-8 shadow-2xl text-center">
        <div class="w-14 h-14 rounded-full bg-error-container/40 text-error flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-[32px]">warning</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-tertiary font-bold mb-2">Hapus Produk Ini?</h3>
        <p class="text-sm text-outline mb-6">
          Apakah Anda yakin ingin menghapus produk <strong class="text-on-surface">"{{ deletingProduct.title }}"</strong>? Tindakan ini akan menghapus aset dari katalog dan tidak dapat dibatalkan.
        </p>
        <div class="flex justify-center gap-3">
          <button @click="deletingProduct = null" class="px-5 py-2.5 rounded-full text-sm text-outline hover:text-on-surface">
            Batal
          </button>
          <button @click="executeDelete" class="px-6 py-2.5 rounded-full bg-error text-white font-bold text-sm hover:brightness-110 shadow-lg">
            Ya, Hapus Produk
          </button>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div 
      v-if="toastMessage" 
      class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-2xl bg-surface-container-highest border border-secondary/40 text-secondary text-sm font-semibold shadow-2xl flex items-center gap-2 animate-bounce"
    >
      <span class="material-symbols-outlined text-[20px]">check_circle</span>
      <span>{{ toastMessage }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { products, addProduct, updateProduct, deleteProduct, formatRupiah } from '@/data/mockData';

const categories = ['Semua', 'UI Kit', 'Source Code', 'Template & OS', '3D & Grafis'];
const activeCategory = ref('Semua');
const searchQuery = ref('');
const isSyncing = ref(false);
const toastMessage = ref('');

// Modals State
const showAddModal = ref(false);
const showEditModal = ref(false);
const deletingProduct = ref(null);

const newProduct = ref({
  title: '',
  category: 'UI Kit',
  price: 299000,
  sku: '',
  version: 'v1.0.0',
  status: 'Live',
  description: ''
});

const editingProduct = ref({
  id: '',
  title: '',
  category: 'UI Kit',
  price: 0,
  sku: '',
  version: 'v1.0.0',
  status: 'Live',
  description: ''
});

function showToast(msg) {
  toastMessage.value = msg;
  setTimeout(() => {
    toastMessage.value = '';
  }, 3000);
}

// Filtered products list
const filteredProducts = computed(() => {
  return products.value.filter(p => {
    const matchCategory = activeCategory.value === 'Semua' || p.category === activeCategory.value;
    const matchQuery = !searchQuery.value || 
      p.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
      p.sku.toLowerCase().includes(searchQuery.value.toLowerCase());
    return matchCategory && matchQuery;
  });
});

// Dynamic stats calculations
const liveCount = computed(() => products.value.filter(p => p.status === 'Live').length);
const draftCount = computed(() => products.value.filter(p => p.status === 'Draft').length);
const totalDownloads = computed(() => products.value.reduce((acc, p) => acc + (p.downloads || 0), 0));
const estimatedRevenue = computed(() => products.value.reduce((acc, p) => acc + ((p.downloads || 0) * (p.price || 0)), 0));

function resetFilters() {
  activeCategory.value = 'Semua';
  searchQuery.value = '';
}

function openAddModal() {
  newProduct.value = {
    title: '',
    category: 'UI Kit',
    price: 299000,
    sku: `DS-${Math.random().toString(36).substring(2, 7).toUpperCase()}`,
    version: 'v1.0.0',
    status: 'Live',
    description: ''
  };
  showAddModal.value = true;
}

function submitAddProduct() {
  addProduct(newProduct.value);
  showAddModal.value = false;
  showToast(`Produk "${newProduct.value.title}" berhasil ditambahkan!`);
}

function openEditModal(item) {
  editingProduct.value = { ...item };
  showEditModal.value = true;
}

function submitEditProduct() {
  updateProduct(editingProduct.value.id, editingProduct.value);
  showEditModal.value = false;
  showToast(`Perubahan produk "${editingProduct.value.title}" berhasil disimpan!`);
}

function toggleStatus(item) {
  const nextStatus = item.status === 'Live' ? 'Draft' : 'Live';
  updateProduct(item.id, { status: nextStatus });
  showToast(`Status "${item.title}" diubah menjadi ${nextStatus}`);
}

function confirmDelete(item) {
  deletingProduct.value = item;
}

function executeDelete() {
  if (deletingProduct.value) {
    const title = deletingProduct.value.title;
    deleteProduct(deletingProduct.value.id);
    deletingProduct.value = null;
    showToast(`Produk "${title}" telah dihapus.`);
  }
}

function triggerSync() {
  isSyncing.value = true;
  setTimeout(() => {
    isSyncing.value = false;
    showToast('Sinkronisasi repository berhasil. 100% token dan aset terverifikasi.');
  }, 1000);
}
</script>
