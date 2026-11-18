<template>
  <div class="space-y-6">
    <div class="flex flex-col w-full gap-space-lg">
      <!-- Top Section: Header & Top Stats -->
      <div class="flex flex-col gap-space-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-xs">
          <div>
            <h1 class="font-headline-md text-headline-md text-primary tracking-tight font-bold">Dashboard Overview</h1>
            <p class="font-body-sm text-body-sm text-outline">Ringkasan performa penjualan produk digital, mutasi keuangan, dan pipeline proyek terkini.</p>
          </div>
          <div class="flex items-center gap-3 self-start sm:self-auto">
            <span class="font-label-sm text-label-sm text-secondary flex items-center gap-1 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span> Live Database Sync
            </span>
            <button
              @click="loadDashboard"
              :disabled="isLoading"
              type="button"
              class="px-3 py-1.5 rounded-full bg-surface-container-high hover:bg-surface-bright text-xs text-primary font-semibold inline-flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[15px]" :class="{ 'animate-spin': isLoading }">refresh</span>
              Refresh
            </button>
          </div>
        </div>

        <!-- 4 Dynamic Stats Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-space-md">
          <!-- Card 1: Total Pendapatan Lunas -->
          <div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl border border-outline-variant/20">
            <div class="flex items-center justify-between gap-space-xs mb-space-sm">
              <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-primary-container font-bold">1</span>
                <span class="font-label-md text-on-surface-variant font-medium">Pendapatan Lunas</span>
              </div>
              <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary font-label-sm font-semibold">
                Real-time
              </span>
            </div>
            <div>
              <div class="font-headline-md font-bold text-tertiary tracking-tight font-tabular">{{ formatRupiah(kpi.gross_revenue) }}</div>
              <p class="font-body-sm text-outline mt-1">Netto: {{ formatRupiah(kpi.net_revenue) }}</p>
            </div>
          </div>

          <!-- Card 2: Produk Terjual -->
          <div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl border border-outline-variant/20">
            <div class="flex items-center justify-between gap-space-xs mb-space-sm">
              <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-primary-container font-bold">2</span>
                <span class="font-label-md text-on-surface-variant font-medium">Total Unduhan / Lisensi</span>
              </div>
              <span class="px-2 py-0.5 rounded-full bg-primary-container/15 text-primary-container font-label-sm font-semibold">
                {{ kpi.live_products }} Live
              </span>
            </div>
            <div>
              <div class="font-headline-md font-bold text-tertiary tracking-tight font-tabular">
                {{ kpi.total_downloads }} <span class="font-headline-sm font-normal text-on-surface-variant">lisensi</span>
              </div>
              <p class="font-body-sm text-outline mt-1">Dari {{ kpi.total_products }} produk katalog</p>
            </div>
          </div>

          <!-- Card 3: Proyek Jasa Aktif -->
          <div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl border border-outline-variant/20">
            <div class="flex items-center justify-between gap-space-xs mb-space-sm">
              <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-primary-container font-bold">3</span>
                <span class="font-label-md text-on-surface-variant font-medium">Proyek Jasa Aktif</span>
              </div>
              <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
            </div>
            <div>
              <div class="font-headline-md font-bold text-tertiary tracking-tight font-tabular">
                {{ kpi.active_projects }} <span class="font-headline-sm font-normal text-on-surface-variant">proyek</span>
              </div>
              <p class="font-body-sm text-outline mt-1">{{ kpi.new_consultations }} lead konsultasi baru</p>
            </div>
          </div>

          <!-- Card 4: Menunggu Konfirmasi -->
          <div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl border border-outline-variant/20">
            <div class="flex items-center justify-between gap-space-xs mb-space-sm">
              <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-primary-container font-bold">4</span>
                <span class="font-label-md text-on-surface-variant font-medium">Menunggu Bayar</span>
              </div>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-300 font-label-sm font-semibold">
                Pending
              </span>
            </div>
            <div>
              <div class="font-headline-md font-bold text-tertiary tracking-tight font-tabular">
                {{ kpi.pending_trx_count }} <span class="font-headline-sm font-normal text-on-surface-variant">invoice</span>
              </div>
              <p class="font-body-sm text-outline mt-1">Nilai: {{ formatRupiah(kpi.pending_amount) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Bento Split: Transaksi Terbaru (65%) & Project Progress (35%) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg">
        <!-- Left Column: Transaksi & Pesanan Terbaru (Live Data) -->
        <div class="lg:col-span-8 flex flex-col rounded-2xl bg-surface-container-low/90 backdrop-blur-md p-space-md lg:p-space-lg shadow-xl border border-outline-variant/20 overflow-hidden">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-space-md gap-space-xs">
            <div>
              <h2 class="font-headline-sm text-headline-sm text-primary font-bold">Transaksi Terbaru</h2>
              <p class="font-body-sm text-outline">Mutasi pembelian produk digital &amp; termin proyek masuk</p>
            </div>
            <div class="relative">
              <select
                v-model="trxFilter"
                class="appearance-none bg-surface-container-high text-on-surface font-label-md py-1.5 pl-3 pr-8 rounded-full border border-outline-variant/30 focus:outline-none focus:border-primary-container cursor-pointer text-xs"
              >
                <option value="Semua">Semua Status</option>
                <option value="Berhasil">Berhasil</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Gagal">Gagal</option>
              </select>
              <span class="material-symbols-outlined absolute right-2.5 top-2 pointer-events-none text-outline text-[16px]">expand_more</span>
            </div>
          </div>

          <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-outline-variant/20 text-outline uppercase text-[11px] tracking-wider">
                  <th class="pb-3 pt-1">Invoice</th>
                  <th class="pb-3 pt-1">Pelanggan</th>
                  <th class="pb-3 pt-1">Item / Layanan</th>
                  <th class="pb-3 pt-1 text-right">Nominal</th>
                  <th class="pb-3 pt-1 text-center">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-outline-variant/10 text-xs">
                <tr
                  v-for="trx in filteredRecentTrx"
                  :key="trx.id"
                  class="hover:bg-surface-container/60 transition-colors"
                >
                  <td class="py-3.5 pr-3 font-mono font-medium text-tertiary whitespace-nowrap">
                    {{ trx.ref_invoice || trx.refInvoice }}
                  </td>
                  <td class="py-3.5 pr-3">
                    <div class="flex items-center gap-2">
                      <div class="w-6 h-6 rounded-full bg-surface-container-highest flex items-center justify-center font-bold text-[10px] text-primary shrink-0">
                        {{ getInitials(trx.client) }}
                      </div>
                      <span class="text-on-surface font-medium truncate max-w-[140px]">{{ trx.client }}</span>
                    </div>
                  </td>
                  <td class="py-3.5 pr-3">
                    <span class="text-on-surface-variant line-clamp-1 max-w-[180px]">{{ trx.category }}</span>
                  </td>
                  <td class="py-3.5 pr-3 text-right font-bold text-primary font-tabular whitespace-nowrap">
                    {{ formatRupiah(trx.amount) }}
                  </td>
                  <td class="py-3.5 text-center whitespace-nowrap">
                    <span
                      class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold"
                      :class="trx.status === 'Berhasil'
                        ? 'bg-secondary/10 border border-secondary/30 text-secondary'
                        : trx.status === 'Menunggu'
                          ? 'bg-amber-500/10 border border-amber-500/30 text-amber-300'
                          : 'bg-error-container/25 border border-error/30 text-error'"
                    >
                      <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="trx.status === 'Berhasil' ? 'bg-secondary' : trx.status === 'Menunggu' ? 'bg-amber-400 animate-pulse' : 'bg-error'"
                      ></span>
                      {{ trx.status }}
                    </span>
                  </td>
                </tr>

                <tr v-if="filteredRecentTrx.length === 0">
                  <td colspan="5" class="py-10 text-center text-outline">Belum ada transaksi untuk filter ini.</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-auto pt-space-md flex items-center justify-between text-outline text-xs border-t border-outline-variant/15">
            <span>Menampilkan {{ filteredRecentTrx.length }} transaksi terbaru</span>
            <router-link to="/admin/transaksi" class="text-primary-container hover:underline font-semibold flex items-center gap-1">
              Lihat Semua Laporan <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </router-link>
          </div>
        </div>

        <!-- Right Column: Live Project Progress -->
        <div class="lg:col-span-4 flex flex-col rounded-2xl bg-surface-container-low/90 backdrop-blur-md p-space-md lg:p-space-lg shadow-xl border border-outline-variant/20 justify-between">
          <div>
            <div class="flex items-center justify-between mb-space-xs">
              <h2 class="font-headline-sm text-primary font-bold">Progres Proyek Klien</h2>
              <span class="material-symbols-outlined text-outline text-[20px]">view_kanban</span>
            </div>
            <p class="font-body-sm text-outline mb-space-md">Status pengerjaan proyek kustom aktif</p>

            <div class="flex flex-col gap-space-sm">
              <div
                v-for="proj in projectCards"
                :key="proj.id"
                class="p-space-sm rounded-xl bg-surface-container/70 border border-outline-variant/25 flex flex-col gap-2 hover:bg-surface-container transition-all"
              >
                <div class="flex items-center justify-between gap-2">
                  <span class="font-label-md text-on-surface font-semibold truncate">{{ proj.project }}</span>
                  <span
                    class="px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0"
                    :class="proj.status === 'Selesai'
                      ? 'bg-secondary/15 text-secondary'
                      : proj.status === 'Review'
                        ? 'bg-amber-500/15 text-amber-300'
                        : 'bg-primary-container/15 text-primary-container'"
                  >
                    {{ proj.status }}
                  </span>
                </div>
                <div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
                  <div
                    class="h-1.5 rounded-full transition-all"
                    :class="proj.progress >= 100 ? 'bg-secondary' : 'bg-primary-container'"
                    :style="{ width: `${Math.min(100, proj.progress || 0)}%` }"
                  ></div>
                </div>
                <div class="flex items-center justify-between text-outline text-[11px]">
                  <span class="truncate">{{ proj.client }}</span>
                  <span class="text-tertiary font-mono font-bold">{{ proj.progress || 0 }}%</span>
                </div>
              </div>
            </div>
          </div>

          <router-link
            to="/admin/pesanan"
            class="mt-space-md w-full py-2.5 px-4 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-label-md font-bold flex items-center justify-center gap-2 shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:brightness-110 transition-all"
          >
            <span class="material-symbols-outlined text-[18px]">add_task</span>
            Kelola Pesanan &amp; Proyek
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { products, orders, transactions, formatRupiah } from '@/data/mockData';
import { DashboardApi } from '@/services/api';

const isLoading = ref(false);
const trxFilter = ref('Semua');
const remoteSummary = ref(null);

async function loadDashboard() {
  isLoading.value = true;
  try {
    const res = await DashboardApi.getSummary();
    if (res?.status === 'success' && res?.data) {
      remoteSummary.value = res.data;
    }
  } catch {
    // Fallback ke store lokal saat dev tanpa backend PHP
  } finally {
    isLoading.value = false;
  }
}

const kpi = computed(() => {
  if (remoteSummary.value?.kpis) {
    return remoteSummary.value.kpis;
  }
  const paidTrx = transactions.value.filter((t) => t.status === 'Berhasil');
  const pendingTrx = transactions.value.filter((t) => t.status === 'Menunggu');
  const gross = paidTrx.reduce((acc, t) => acc + Number(t.amount || 0), 0);
  const net = paidTrx.reduce((acc, t) => acc + Number(t.net_amount ?? t.amount ?? 0), 0);
  const pendingAmt = pendingTrx.reduce((acc, t) => acc + Number(t.amount || 0), 0);

  return {
    gross_revenue: gross,
    net_revenue: net,
    pending_amount: pendingAmt,
    paid_count: paidTrx.length,
    pending_trx_count: pendingTrx.length,
    total_products: products.value.length,
    live_products: products.value.filter((p) => p.status === 'Live').length,
    total_downloads: products.value.reduce((acc, p) => acc + Number(p.downloads || 0), 0),
    active_projects: orders.value.filter((o) => o.status === 'Sedang Dikerjakan' || o.status === 'Review').length,
    pending_projects: orders.value.filter((o) => o.status === 'Menunggu Konfirmasi').length,
    new_consultations: 0,
  };
});

const recentTrx = computed(() => {
  if (remoteSummary.value?.recent_transactions) {
    return remoteSummary.value.recent_transactions;
  }
  return transactions.value.slice(0, 8);
});

const filteredRecentTrx = computed(() => {
  if (trxFilter.value === 'Semua') return recentTrx.value;
  return recentTrx.value.filter((t) => t.status === trxFilter.value);
});

const projectCards = computed(() => {
  if (remoteSummary.value?.active_projects) {
    return remoteSummary.value.active_projects;
  }
  return orders.value.slice(0, 4);
});

function getInitials(name) {
  return String(name || 'CL')
    .split(' ')
    .slice(0, 2)
    .map((p) => p[0])
    .join('')
    .toUpperCase();
}

onMounted(loadDashboard);
</script>
