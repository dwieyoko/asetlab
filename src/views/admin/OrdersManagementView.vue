<template>
  <div class="space-y-6 text-on-surface">
    <!-- Top Command Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
      <div class="flex flex-col gap-1">
        <div class="flex items-center gap-space-xs">
          <span class="font-label-sm text-label-sm uppercase tracking-widest text-primary-fixed-dim font-bold">asetlab // operations</span>
          <span class="inline-block w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
          <span class="font-label-sm text-label-sm text-outline">Realtime CRUD Sync Active</span>
        </div>
        <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">Pesanan &amp; Manajemen Proyek</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Kendali terpusat workflow produksi, pipeline invoice, alokasi tim, dan monitoring SLA jasa kreatif.</p>
      </div>

      <!-- Quick Actions & View Switcher -->
      <div class="flex flex-wrap items-center gap-space-sm">
        <div class="flex items-center p-1 rounded-full bg-surface-container-lowest shadow-inner border border-outline-variant/20">
          <button 
            id="viewListBtn" 
            type="button" 
            @click="currentView = 'table'" 
            :class="currentView === 'table' ? 'bg-surface-container text-primary shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
            class="flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md transition-all duration-200"
          >
            <span class="material-symbols-outlined text-[18px]">table_rows</span>
            <span>Tabel</span>
          </button>
          <button 
            id="viewKanbanBtn" 
            type="button" 
            @click="currentView = 'kanban'" 
            :class="currentView === 'kanban' ? 'bg-surface-container text-primary shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
            class="flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md transition-all duration-200"
          >
            <span class="material-symbols-outlined text-[18px]">view_kanban</span>
            <span>Kanban Board</span>
          </button>
        </div>

        <button 
          @click="openAddModal"
          class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-primary-container via-secondary-fixed to-secondary text-surface-container-lowest font-label-md text-label-md font-bold shadow-lg shadow-primary-container/20 hover:shadow-primary-container/40 hover:scale-[1.02] active:scale-[0.98] transition-all" 
          type="button"
        >
          <span class="material-symbols-outlined text-[20px] font-bold">add_circle</span>
          <span>+ Buat Pesanan Baru</span>
        </button>
      </div>
    </div>

    <!-- KPI Metrics Carousel/Grid (Reacting live to reactive orders) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
      <!-- Stat 1: Pending -->
      <div class="relative overflow-hidden rounded-2xl bg-surface-container-low p-space-md flex flex-col justify-between shadow-lg border border-outline-variant/20 hover:border-error/40 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-surface-container-high flex items-center justify-center font-label-sm text-label-sm text-error font-bold">1</span>
            <span class="font-label-md text-label-md text-on-surface-variant">Pesanan Pending</span>
          </div>
          <span class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-error-container/30 text-error flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Perlu Tinjauan
          </span>
        </div>
        <div>
          <div class="flex items-baseline gap-2">
            <span class="font-headline-lg text-headline-lg font-bold text-primary">{{ pendingCount }}</span>
            <span class="font-body-sm text-body-sm text-outline">pesanan antre</span>
          </div>
          <div class="mt-2 flex items-center gap-1.5 text-secondary font-label-sm text-label-sm">
            <span class="material-symbols-outlined text-[16px]">priority_high</span>
            <span>Konfirmasi SLA &lt; 2 jam kerja</span>
          </div>
        </div>
      </div>

      <!-- Stat 2: Active Projects -->
      <div class="relative overflow-hidden rounded-2xl bg-surface-container-low p-space-md flex flex-col justify-between shadow-lg border border-outline-variant/20 hover:border-primary-container/40 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-surface-container-high flex items-center justify-center font-label-sm text-label-sm text-primary-container font-bold">2</span>
            <span class="font-label-md text-label-md text-on-surface-variant">Proyek Berjalan</span>
          </div>
          <span class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/15 text-primary-container font-bold">In-Progress</span>
        </div>
        <div>
          <div class="flex items-baseline gap-2">
            <span class="font-headline-lg text-headline-lg font-bold text-primary">{{ inProgressCount }}</span>
            <span class="font-body-sm text-body-sm text-outline">modul aktif</span>
          </div>
          <div class="mt-2 flex items-center gap-1.5 text-primary-fixed-dim font-label-sm text-label-sm">
            <span class="material-symbols-outlined text-[16px]">terminal</span>
            <span>Sprint &amp; integrasi API berjalan</span>
          </div>
        </div>
      </div>

      <!-- Stat 3: Pipeline Valuation -->
      <div class="relative overflow-hidden rounded-2xl bg-surface-container-low p-space-md flex flex-col justify-between shadow-lg border border-outline-variant/20 hover:border-secondary/40 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-surface-container-high flex items-center justify-center font-label-sm text-label-sm text-secondary font-bold">3</span>
            <span class="font-label-md text-label-md text-on-surface-variant">Pipeline Valuasi</span>
          </div>
          <span class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-secondary/15 text-secondary font-bold">Total Nilai</span>
        </div>
        <div>
          <div class="flex items-baseline gap-2">
            <span class="font-headline-lg text-headline-lg font-bold text-primary">{{ formatRupiah(totalPipelineValue) }}</span>
          </div>
          <div class="mt-2 flex items-center gap-1.5 text-secondary font-label-sm text-label-sm">
            <span class="material-symbols-outlined text-[16px]">account_balance_wallet</span>
            <span>Dari {{ orders.length }} total pesanan</span>
          </div>
        </div>
      </div>

      <!-- Stat 4: CSAT & Completed -->
      <div class="relative overflow-hidden rounded-2xl bg-surface-container-low p-space-md flex flex-col justify-between shadow-lg border border-outline-variant/20 hover:border-secondary/40 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-surface-container-high flex items-center justify-center font-label-sm text-label-sm text-secondary font-bold">4</span>
            <span class="font-label-md text-label-md text-on-surface-variant">Proyek Tuntas</span>
          </div>
          <span class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-secondary/10 text-secondary font-bold">{{ completedCount }} Selesai</span>
        </div>
        <div>
          <div class="flex items-baseline gap-2">
            <span class="font-headline-lg text-headline-lg font-bold text-primary">4.96</span>
            <span class="font-body-sm text-body-sm text-outline">/ 5.0 Rating CSAT</span>
          </div>
          <div class="mt-2 flex items-center gap-1 text-secondary">
            <span v-for="i in 5" :key="i" class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="font-label-sm text-label-sm text-outline ml-1">100% On-time</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Filters & Search Ribbon -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-space-md p-space-sm rounded-2xl bg-surface-container-low border border-outline-variant/20">
      <!-- Status Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto scrollbar-none pb-1 md:pb-0">
        <button 
          v-for="tab in statusTabs" 
          :key="tab.value"
          @click="activeStatusTab = tab.value"
          class="flex items-center gap-2 px-4 py-2 rounded-full font-label-md text-label-md transition-all whitespace-nowrap"
          :class="activeStatusTab === tab.value 
            ? 'bg-primary-container/20 text-primary-container border border-primary-container/40 font-bold shadow-sm' 
            : 'bg-surface-container-high/60 text-on-surface-variant hover:text-on-surface hover:bg-surface-container'"
          type="button"
        >
          <span>{{ tab.label }}</span>
          <span 
            class="px-2 py-0.5 rounded-full text-label-sm font-bold text-xs"
            :class="activeStatusTab === tab.value ? 'bg-primary-container text-surface-container-lowest' : 'bg-surface-container-highest text-outline'"
          >
            {{ tab.count }}
          </span>
        </button>
      </div>

      <!-- Search Input -->
      <div class="relative min-w-[260px] md:w-72">
        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
        <input 
          v-model="searchQuery"
          type="text" 
          placeholder="Cari klien, proyek, ID..."
          class="w-full bg-surface-container-lowest/80 pl-10 pr-4 py-2 rounded-full border border-outline-variant/30 text-sm text-primary placeholder-outline focus:outline-none focus:border-primary-container/60 transition-colors"
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

    <!-- VIEW 1: DATA TABLE VIEW (Primary View) -->
    <div v-show="currentView === 'table'" class="flex flex-col gap-space-md">
      <div class="rounded-2xl bg-surface-container-low overflow-hidden shadow-xl border border-outline-variant/20">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-surface-container-lowest/80 text-outline uppercase font-label-sm text-label-sm tracking-wider border-b border-surface-container-highest/30">
                <th class="py-4 px-space-md">ID Pesanan</th>
                <th class="py-4 px-space-md">Klien / Perusahaan</th>
                <th class="py-4 px-space-md">Paket / Layanan</th>
                <th class="py-4 px-space-md">PIC Tim</th>
                <th class="py-4 px-space-md">Deadline &amp; Progress</th>
                <th class="py-4 px-space-md">Nilai Kontrak</th>
                <th class="py-4 px-space-md">Status Proyek</th>
                <th class="py-4 px-space-md text-right">Aksi Cepat</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-highest/20 font-body-sm text-body-sm">
              <tr 
                v-for="order in filteredOrders" 
                :key="order.id"
                class="hover:bg-surface-container/60 transition-colors group"
              >
                <!-- ID -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <span class="font-mono text-primary font-bold">{{ order.id }}</span>
                    <span 
                      class="w-2 h-2 rounded-full" 
                      :class="order.status === 'Sedang Dikerjakan' ? 'bg-primary-container animate-pulse' : 'bg-outline'"
                      :title="order.status"
                    ></span>
                  </div>
                  <span class="font-label-sm text-label-sm text-outline block">{{ order.scope || 'Custom Development' }}</span>
                </td>

                <!-- Client -->
                <td class="py-4 px-space-md">
                  <div class="flex items-center gap-3">
                    <img 
                      v-if="order.clientAvatar" 
                      :src="order.clientAvatar" 
                      :alt="order.client"
                      class="w-9 h-9 rounded-full object-cover border border-outline-variant/30 flex-shrink-0"
                    />
                    <div v-else class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center text-primary-container font-headline-sm font-bold flex-shrink-0">
                      {{ order.client.substring(0, 2).toUpperCase() }}
                    </div>
                    <div class="flex flex-col min-w-0">
                      <span class="font-label-md text-label-md text-on-surface font-semibold truncate">{{ order.client }}</span>
                      <span class="font-label-sm text-label-sm text-outline truncate">{{ order.clientEmail }}</span>
                    </div>
                  </div>
                </td>

                <!-- Project Title -->
                <td class="py-4 px-space-md">
                  <div class="flex flex-col max-w-[240px]">
                    <span class="font-label-md text-label-md text-tertiary font-semibold truncate">{{ order.project }}</span>
                    <span class="font-label-sm text-label-sm text-outline line-clamp-1">{{ order.scope }}</span>
                  </div>
                </td>

                <!-- Assignee -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-primary-fixed-dim">group</span>
                    <span class="font-label-md text-label-md text-on-surface-variant">{{ order.assignee || 'Tim Arsitek AsetLab' }}</span>
                  </div>
                </td>

                <!-- Deadline & Progress -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex flex-col gap-1 min-w-[130px]">
                    <span class="font-label-sm text-label-sm text-on-surface flex items-center gap-1">
                      <span class="material-symbols-outlined text-[15px] text-primary-container">event</span>
                      {{ order.deadline }}
                    </span>
                    <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden">
                      <div 
                        class="h-full rounded-full transition-all duration-500"
                        :class="order.progress === 100 ? 'bg-secondary' : 'bg-primary-container'"
                        :style="{ width: `${order.progress || 10}%` }"
                      ></div>
                    </div>
                    <span class="font-mono text-[11px] text-outline text-right">{{ order.progress || 0 }}% selesai</span>
                  </div>
                </td>

                <!-- Nilai Invoice -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-primary font-bold">{{ formatRupiah(order.budget) }}</span>
                    <span class="font-label-sm text-label-sm text-secondary">Termin Terjadwal</span>
                  </div>
                </td>

                <!-- Status -->
                <td class="py-4 px-space-md whitespace-nowrap">
                  <span 
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-sm text-label-sm font-semibold"
                    :class="getStatusBadgeClass(order.status)"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDotClass(order.status)"></span>
                    {{ order.status }}
                  </span>
                </td>

                <!-- Aksi Cepat -->
                <td class="py-4 px-space-md whitespace-nowrap text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <!-- Quick Advance Status -->
                    <button 
                      @click="cycleStatus(order)"
                      class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary-container transition-colors"
                      title="Ubah Status Workflow"
                      type="button"
                    >
                      <span class="material-symbols-outlined text-[18px]">sync_alt</span>
                    </button>
                    <!-- Edit -->
                    <button 
                      @click="openEditModal(order)"
                      class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-secondary transition-colors"
                      title="Edit Detail Pesanan"
                      type="button"
                    >
                      <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <!-- Delete -->
                    <button 
                      @click="confirmDelete(order)"
                      class="p-2 rounded-full hover:bg-error-container/20 text-on-surface-variant hover:text-error transition-colors"
                      title="Hapus Pesanan"
                      type="button"
                    >
                      <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="filteredOrders.length === 0">
                <td colspan="8" class="py-12 text-center text-outline">
                  <div class="flex flex-col items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[36px] text-outline">inbox</span>
                    <p class="font-label-md text-label-md text-on-surface">Tidak ada pesanan yang sesuai filter.</p>
                    <button @click="resetFilters" class="text-xs text-primary-container hover:underline">Reset Filter</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Table Bottom Metadata -->
        <div class="flex flex-col sm:flex-row items-center justify-between px-space-md py-space-sm bg-surface-container-lowest/60 border-t border-surface-container-highest/20 gap-space-sm">
          <span class="font-label-sm text-label-sm text-outline">
            Menampilkan {{ filteredOrders.length }} dari {{ orders.length }} total pesanan
          </span>
          <div class="flex items-center gap-2">
            <span class="font-label-sm text-label-sm text-outline">Sinkronisasi otomatis ke LocalStorage</span>
          </div>
        </div>
      </div>
    </div>

    <!-- VIEW 2: KANBAN BOARD VIEW -->
    <div v-show="currentView === 'kanban'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-space-md">
      <!-- Col 1: Pending & Konfirmasi -->
      <div class="flex flex-col gap-space-sm bg-surface-container-low p-space-md rounded-2xl shadow-lg border border-outline-variant/20">
        <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/20">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-error"></span>
            <span class="font-label-md text-label-md text-primary font-bold">Menunggu Konfirmasi</span>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-outline">
            {{ getOrdersByStatus('Menunggu Konfirmasi').length }}
          </span>
        </div>
        
        <div class="flex flex-col gap-3 min-h-[150px]">
          <div 
            v-for="card in getOrdersByStatus('Menunggu Konfirmasi')"
            :key="card.id"
            class="p-space-sm rounded-xl bg-surface-container hover:bg-surface-container-high transition-all shadow-md flex flex-col gap-2 border border-outline-variant/20 group"
          >
            <div class="flex items-center justify-between">
              <span class="font-mono text-[11px] text-primary-fixed-dim font-bold">{{ card.id }}</span>
              <span class="px-2 py-0.5 rounded-full bg-error/15 text-error text-[10px] font-bold">Unverified</span>
            </div>
            <h4 class="font-label-md text-label-md text-primary font-semibold">{{ card.client }}</h4>
            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ card.project }}</p>
            <div class="flex items-center justify-between pt-2 border-t border-surface-container-highest/30">
              <span class="font-label-sm text-label-sm font-bold text-secondary">{{ formatRupiah(card.budget) }}</span>
              <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100">
                <button @click="openEditModal(card)" class="p-1 hover:text-primary text-outline" title="Edit">
                  <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
                <button @click="cycleStatus(card)" class="p-1 hover:text-primary-container text-outline" title="Proses ke Pengerjaan">
                  <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </button>
              </div>
            </div>
          </div>
          <div v-if="getOrdersByStatus('Menunggu Konfirmasi').length === 0" class="text-center py-8 text-xs text-outline">
            Tidak ada pesanan antre
          </div>
        </div>
      </div>

      <!-- Col 2: Development / Produksi -->
      <div class="flex flex-col gap-space-sm bg-surface-container-low p-space-md rounded-2xl shadow-lg border border-outline-variant/20">
        <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/20">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-primary-container"></span>
            <span class="font-label-md text-label-md text-primary font-bold">Sedang Dikerjakan</span>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-outline">
            {{ getOrdersByStatus('Sedang Dikerjakan').length }}
          </span>
        </div>

        <div class="flex flex-col gap-3 min-h-[150px]">
          <div 
            v-for="card in getOrdersByStatus('Sedang Dikerjakan')"
            :key="card.id"
            class="p-space-sm rounded-xl bg-surface-container hover:bg-surface-container-high transition-all shadow-md flex flex-col gap-2 border-l-2 border-primary-container group"
          >
            <div class="flex items-center justify-between">
              <span class="font-mono text-[11px] text-primary-fixed-dim font-bold">{{ card.id }}</span>
              <span class="px-2 py-0.5 rounded-full bg-primary-container/20 text-primary-container text-[10px] font-bold">In-Dev</span>
            </div>
            <h4 class="font-label-md text-label-md text-primary font-semibold">{{ card.client }}</h4>
            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ card.project }}</p>
            <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden mt-1">
              <div class="bg-primary-container h-full rounded-full" :style="{ width: `${card.progress || 20}%` }"></div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-surface-container-highest/30">
              <span class="font-label-sm text-label-sm font-bold text-secondary">{{ formatRupiah(card.budget) }}</span>
              <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100">
                <button @click="openEditModal(card)" class="p-1 hover:text-primary text-outline" title="Edit">
                  <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
                <button @click="cycleStatus(card)" class="p-1 hover:text-tertiary-fixed-dim text-outline" title="Kirim ke Review">
                  <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </button>
              </div>
            </div>
          </div>
          <div v-if="getOrdersByStatus('Sedang Dikerjakan').length === 0" class="text-center py-8 text-xs text-outline">
            Tidak ada proyek aktif
          </div>
        </div>
      </div>

      <!-- Col 3: Design Review -->
      <div class="flex flex-col gap-space-sm bg-surface-container-low p-space-md rounded-2xl shadow-lg border border-outline-variant/20">
        <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/20">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-tertiary-fixed-dim"></span>
            <span class="font-label-md text-label-md text-primary font-bold">Dalam Review Klien</span>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-outline">
            {{ getOrdersByStatus('Review').length }}
          </span>
        </div>

        <div class="flex flex-col gap-3 min-h-[150px]">
          <div 
            v-for="card in getOrdersByStatus('Review')"
            :key="card.id"
            class="p-space-sm rounded-xl bg-surface-container hover:bg-surface-container-high transition-all shadow-md flex flex-col gap-2 border-l-2 border-tertiary-fixed-dim group"
          >
            <div class="flex items-center justify-between">
              <span class="font-mono text-[11px] text-tertiary-fixed-dim font-bold">{{ card.id }}</span>
              <span class="px-2 py-0.5 rounded-full bg-tertiary-fixed-dim/20 text-tertiary-fixed-dim text-[10px] font-bold">Feedback</span>
            </div>
            <h4 class="font-label-md text-label-md text-primary font-semibold">{{ card.client }}</h4>
            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ card.project }}</p>
            <div class="flex items-center justify-between pt-2 border-t border-surface-container-highest/30">
              <span class="font-label-sm text-label-sm font-bold text-secondary">{{ formatRupiah(card.budget) }}</span>
              <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100">
                <button @click="openEditModal(card)" class="p-1 hover:text-primary text-outline" title="Edit">
                  <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
                <button @click="cycleStatus(card)" class="p-1 hover:text-secondary text-outline" title="Tandai Selesai">
                  <span class="material-symbols-outlined text-[16px]">check_circle</span>
                </button>
              </div>
            </div>
          </div>
          <div v-if="getOrdersByStatus('Review').length === 0" class="text-center py-8 text-xs text-outline">
            Tidak ada proyek dalam review
          </div>
        </div>
      </div>

      <!-- Col 4: Selesai -->
      <div class="flex flex-col gap-space-sm bg-surface-container-low p-space-md rounded-2xl shadow-lg border border-outline-variant/20">
        <div class="flex items-center justify-between pb-2 border-b border-surface-container-highest/20">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
            <span class="font-label-md text-label-md text-primary font-bold">Tuntas &amp; Diserahkan</span>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-secondary font-bold">
            {{ getOrdersByStatus('Selesai').length }}
          </span>
        </div>

        <div class="flex flex-col gap-3 min-h-[150px]">
          <div 
            v-for="card in getOrdersByStatus('Selesai')"
            :key="card.id"
            class="p-space-sm rounded-xl bg-surface-container hover:bg-surface-container-high transition-all shadow-md flex flex-col gap-2 border-l-2 border-secondary group"
          >
            <div class="flex items-center justify-between">
              <span class="font-mono text-[11px] text-secondary font-bold">{{ card.id }}</span>
              <span class="px-2 py-0.5 rounded-full bg-secondary/20 text-secondary text-[10px] font-bold">Delivered</span>
            </div>
            <h4 class="font-label-md text-label-md text-primary font-semibold">{{ card.client }}</h4>
            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ card.project }}</p>
            <div class="flex items-center justify-between pt-2 border-t border-surface-container-highest/30">
              <span class="font-label-sm text-label-sm font-bold text-secondary">{{ formatRupiah(card.budget) }}</span>
              <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100">
                <button @click="openEditModal(card)" class="p-1 hover:text-primary text-outline" title="Edit">
                  <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
                <button @click="confirmDelete(card)" class="p-1 hover:text-error text-outline" title="Hapus">
                  <span class="material-symbols-outlined text-[16px]">delete</span>
                </button>
              </div>
            </div>
          </div>
          <div v-if="getOrdersByStatus('Selesai').length === 0" class="text-center py-8 text-xs text-outline">
            Belum ada proyek berstatus selesai
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL 1: BUAT PESANAN BARU (CREATE) -->
    <div v-if="showNewOrderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-xl rounded-3xl bg-surface-container border border-outline-variant/40 p-6 md:p-8 shadow-2xl">
        <button @click="showNewOrderModal = false" class="absolute top-5 right-5 text-outline hover:text-on-surface">
          <span class="material-symbols-outlined">close</span>
        </button>

        <div class="flex items-center gap-3 mb-6">
          <div class="w-12 h-12 rounded-2xl bg-primary-container/20 flex items-center justify-center text-primary-container">
            <span class="material-symbols-outlined text-[24px]">post_add</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Buat Kontrak / Pesanan Baru</h3>
            <p class="text-xs text-outline">Registrasi pesanan jasa atau proyek kustom ke sistem pipeline</p>
          </div>
        </div>

        <form @submit.prevent="submitCreateOrder" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Klien / Perusahaan *</label>
              <input v-model="newOrder.client" required type="text" placeholder="PT Contoh Digital" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Email Klien</label>
              <input v-model="newOrder.clientEmail" type="email" placeholder="corporate@contoh.id" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Proyek *</label>
            <input v-model="newOrder.project" required type="text" placeholder="Revamp Web Portal & Mobile App" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Paket Jasa / Scope</label>
              <input v-model="newOrder.scope" type="text" placeholder="Fullstack Web, Next.js, API" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">PIC Tim / Assignee</label>
              <input v-model="newOrder.assignee" type="text" placeholder="Bramantyo & Tim Frontend" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nilai Kontrak (IDR) *</label>
              <input v-model.number="newOrder.budget" required type="number" step="500000" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Target Deadline</label>
              <input v-model="newOrder.deadline" type="date" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Status Awal</label>
            <select v-model="newOrder.status" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
              <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
              <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
              <option value="Review">Dalam Review</option>
              <option value="Selesai">Selesai</option>
            </select>
          </div>

          <div class="pt-3 flex justify-end gap-3">
            <button @click="showNewOrderModal = false" type="button" class="px-5 py-2.5 rounded-full text-sm text-outline hover:text-on-surface">Batal</button>
            <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm hover:brightness-110 shadow-lg shadow-primary-container/20">
              Simpan Pesanan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 2: EDIT PESANAN (UPDATE) -->
    <div v-if="showEditModal && editingOrder" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-xl rounded-3xl bg-surface-container border border-outline-variant/40 p-6 md:p-8 shadow-2xl">
        <button @click="showEditModal = false" class="absolute top-5 right-5 text-outline hover:text-on-surface">
          <span class="material-symbols-outlined">close</span>
        </button>

        <div class="flex items-center gap-3 mb-6">
          <div class="w-12 h-12 rounded-2xl bg-secondary/20 flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined text-[24px]">edit_document</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Edit Pesanan // {{ editingOrder.id }}</h3>
            <p class="text-xs text-outline">Perbarui progres, alokasi PIC, nilai kontrak, atau status pesanan</p>
          </div>
        </div>

        <form @submit.prevent="submitEditOrder" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Klien</label>
              <input v-model="editingOrder.client" required type="text" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Email Klien</label>
              <input v-model="editingOrder.clientEmail" type="email" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Proyek</label>
            <input v-model="editingOrder.project" required type="text" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Paket Jasa / Scope</label>
              <input v-model="editingOrder.scope" type="text" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">PIC Tim</label>
              <input v-model="editingOrder.assignee" type="text" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nilai Kontrak (IDR)</label>
              <input v-model.number="editingOrder.budget" required type="number" step="500000" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Deadline</label>
              <input v-model="editingOrder.deadline" type="date" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1">Progress (%)</label>
              <input v-model.number="editingOrder.progress" type="number" min="0" max="100" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Status Proyek</label>
            <select v-model="editingOrder.status" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-secondary">
              <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
              <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
              <option value="Review">Dalam Review</option>
              <option value="Selesai">Selesai</option>
            </select>
          </div>

          <div class="pt-3 flex justify-end gap-3">
            <button @click="showEditModal = false" type="button" class="px-5 py-2.5 rounded-full text-sm text-outline hover:text-on-surface">Batal</button>
            <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-secondary to-primary-container text-surface-container-lowest font-bold text-sm hover:brightness-110 shadow-lg shadow-secondary/20">
              Perbarui Pesanan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 3: CONFIRM DELETE (DELETE) -->
    <div v-if="deletingOrder" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-md rounded-3xl bg-surface-container border border-error/30 p-6 md:p-8 shadow-2xl text-center">
        <div class="w-14 h-14 rounded-2xl bg-error/15 text-error flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-[32px]">warning</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-primary font-bold mb-2">Hapus Pesanan?</h3>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-6">
          Apakah Anda yakin ingin menghapus pesanan <strong class="text-primary font-bold">{{ deletingOrder.id }} - {{ deletingOrder.client }}</strong>? Tindakan ini akan menghapus data dari sistem dan LocalStorage.
        </p>

        <div class="flex items-center justify-center gap-3">
          <button @click="deletingOrder = null" type="button" class="px-5 py-2.5 rounded-full text-sm text-outline hover:text-on-surface">
            Batal
          </button>
          <button @click="executeDelete" type="button" class="px-6 py-2.5 rounded-full bg-error text-white font-bold text-sm hover:brightness-110 shadow-lg shadow-error/20">
            Ya, Hapus
          </button>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div 
      v-if="toastMessage" 
      class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-surface-container-high/95 backdrop-blur-xl border border-secondary/40 text-on-surface shadow-2xl shadow-secondary/10 transition-all transform animate-bounce"
    >
      <span class="material-symbols-outlined text-secondary text-[22px]">check_circle</span>
      <span class="text-sm font-medium">{{ toastMessage }}</span>
      <button @click="toastMessage = ''" class="ml-2 text-outline hover:text-on-surface">
        <span class="material-symbols-outlined text-[16px]">close</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { orders, addOrder, updateOrder, deleteOrder, formatRupiah } from '@/data/mockData';

const currentView = ref('table');
const activeStatusTab = ref('Semua');
const searchQuery = ref('');
const toastMessage = ref('');
let toastTimeout = null;

// Modal States
const showNewOrderModal = ref(false);
const showEditModal = ref(false);
const editingOrder = ref(null);
const deletingOrder = ref(null);

const newOrder = ref({
  client: '',
  clientEmail: '',
  project: '',
  scope: 'Fullstack Web Application',
  assignee: 'Bramantyo & Tim',
  budget: 25000000,
  deadline: new Date(Date.now() + 14 * 86400000).toISOString().slice(0, 10),
  status: 'Menunggu Konfirmasi'
});

// Toast notification trigger
function showToast(msg) {
  toastMessage.value = msg;
  if (toastTimeout) clearTimeout(toastTimeout);
  toastTimeout = setTimeout(() => {
    toastMessage.value = '';
  }, 3500);
}

// Top KPI metrics
const pendingCount = computed(() => orders.value.filter(o => o.status === 'Menunggu Konfirmasi').length);
const inProgressCount = computed(() => orders.value.filter(o => o.status === 'Sedang Dikerjakan').length);
const reviewCount = computed(() => orders.value.filter(o => o.status === 'Review').length);
const completedCount = computed(() => orders.value.filter(o => o.status === 'Selesai').length);
const totalPipelineValue = computed(() => orders.value.reduce((acc, o) => acc + (o.budget || 0), 0));

// Status Tabs with dynamic counts
const statusTabs = computed(() => [
  { label: 'Semua Pesanan', value: 'Semua', count: orders.value.length },
  { label: 'Menunggu Konfirmasi', value: 'Menunggu Konfirmasi', count: pendingCount.value },
  { label: 'Sedang Dikerjakan', value: 'Sedang Dikerjakan', count: inProgressCount.value },
  { label: 'Dalam Review', value: 'Review', count: reviewCount.value },
  { label: 'Selesai', value: 'Selesai', count: completedCount.value }
]);

// Filtered orders
const filteredOrders = computed(() => {
  return orders.value.filter(o => {
    const matchStatus = activeStatusTab.value === 'Semua' || o.status === activeStatusTab.value;
    const q = searchQuery.value.toLowerCase().trim();
    const matchQuery = !q || 
      o.id.toLowerCase().includes(q) || 
      o.client.toLowerCase().includes(q) || 
      o.project.toLowerCase().includes(q) || 
      (o.scope && o.scope.toLowerCase().includes(q)) || 
      (o.assignee && o.assignee.toLowerCase().includes(q));
    return matchStatus && matchQuery;
  });
});

function getOrdersByStatus(status) {
  return orders.value.filter(o => o.status === status);
}

function resetFilters() {
  activeStatusTab.value = 'Semua';
  searchQuery.value = '';
}

// Status Badges
function getStatusBadgeClass(status) {
  switch (status) {
    case 'Menunggu Konfirmasi':
      return 'bg-error-container/30 text-error';
    case 'Sedang Dikerjakan':
      return 'bg-primary-container/20 text-primary-container';
    case 'Review':
      return 'bg-tertiary-fixed-dim/20 text-tertiary-fixed-dim';
    case 'Selesai':
      return 'bg-secondary/20 text-secondary';
    default:
      return 'bg-surface-container-highest text-outline';
  }
}

function getStatusDotClass(status) {
  switch (status) {
    case 'Menunggu Konfirmasi':
      return 'bg-error';
    case 'Sedang Dikerjakan':
      return 'bg-primary-container animate-pulse';
    case 'Review':
      return 'bg-tertiary-fixed-dim';
    case 'Selesai':
      return 'bg-secondary';
    default:
      return 'bg-outline';
  }
}

// Cycle status through workflow: Menunggu -> Sedang Dikerjakan -> Review -> Selesai
function cycleStatus(order) {
  const flow = ['Menunggu Konfirmasi', 'Sedang Dikerjakan', 'Review', 'Selesai'];
  const currentIndex = flow.indexOf(order.status);
  const nextStatus = currentIndex >= 0 && currentIndex < flow.length - 1 ? flow[currentIndex + 1] : flow[0];
  const nextProgress = nextStatus === 'Selesai' ? 100 : nextStatus === 'Review' ? 85 : nextStatus === 'Sedang Dikerjakan' ? 50 : 10;
  
  updateOrder(order.id, { status: nextStatus, progress: nextProgress });
  showToast(`Pesanan ${order.id} dimajukan ke: ${nextStatus}`);
}

// CRUD: Create
function openAddModal() {
  newOrder.value = {
    client: '',
    clientEmail: '',
    project: '',
    scope: 'Fullstack Web Application',
    assignee: 'Bramantyo & Tim',
    budget: 25000000,
    deadline: new Date(Date.now() + 14 * 86400000).toISOString().slice(0, 10),
    status: 'Menunggu Konfirmasi'
  };
  showNewOrderModal.value = true;
}

function submitCreateOrder() {
  const created = addOrder(newOrder.value);
  showNewOrderModal.value = false;
  showToast(`Pesanan baru "${created.id} - ${created.client}" berhasil dibuat!`);
}

// CRUD: Update
function openEditModal(order) {
  editingOrder.value = { ...order };
  showEditModal.value = true;
}

function submitEditOrder() {
  if (editingOrder.value) {
    updateOrder(editingOrder.value.id, editingOrder.value);
    showEditModal.value = false;
    showToast(`Pesanan ${editingOrder.value.id} berhasil diperbarui!`);
  }
}

// CRUD: Delete
function confirmDelete(order) {
  deletingOrder.value = order;
}

function executeDelete() {
  if (deletingOrder.value) {
    const id = deletingOrder.value.id;
    const client = deletingOrder.value.client;
    deleteOrder(id);
    deletingOrder.value = null;
    showToast(`Pesanan ${id} (${client}) berhasil dihapus.`);
  }
}
</script>
