<template>
  <div class="space-y-6">
    <div class="flex flex-col w-full">
      <div class="flex flex-col gap-space-lg w-full max-w-[1600px] mx-auto">
        <!-- Top Action Bar & Page Title -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-space-md">
          <div class="flex flex-col gap-space-xs">
            <div class="flex items-center gap-space-xs">
              <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest bg-secondary/10 px-2 py-0.5 rounded-full">Financial Audit</span>
              <span class="text-outline text-xs">•</span>
              <span class="font-label-sm text-label-sm text-outline">asetlab.id Live Ledger</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-extrabold flex items-center gap-3">
              Laporan Transaksi &amp; Keuangan
              <span class="h-2.5 w-2.5 rounded-full bg-secondary shadow-[0_0_12px_#4edea3]"></span>
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
              Arsip mutasi real-time dari database MySQL, verifikasi gateway pembayaran, serta kalkulasi margin bersih.
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-space-sm">
            <button
              @click="refreshTransactions"
              :disabled="isLoading"
              type="button"
              class="flex items-center gap-space-xs bg-surface-container-high hover:bg-surface-variant text-on-surface px-space-md py-2.5 rounded-full font-label-md text-label-md shadow-sm transition-all duration-200 disabled:opacity-60"
            >
              <span class="material-symbols-outlined text-[18px] text-secondary" :class="{ 'animate-spin': isLoading }">sync</span>
              <span>{{ isLoading ? 'Memuat...' : 'Sinkronkan Mutasi' }}</span>
            </button>

            <button
              @click="exportCsv"
              type="button"
              class="flex items-center gap-space-xs bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest px-5 py-2.5 rounded-full font-label-md text-label-md font-bold shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:brightness-110 transition-all"
            >
              <span class="material-symbols-outlined text-[18px]">download</span>
              <span>Unduh Laporan CSV</span>
            </button>
          </div>
        </div>

        <!-- 4 Dynamic Financial KPI Panels -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
          <!-- KPI 1: Pemasukan Kotor -->
          <div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md border border-outline-variant/20 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-space-xs">
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">1</span>
                <span class="font-label-md text-label-md text-on-surface-variant">Pemasukan Kotor</span>
              </div>
              <span class="font-label-sm text-label-sm text-secondary bg-secondary/10 px-2 py-0.5 rounded-full font-semibold">Lunas</span>
            </div>
            <div class="mt-2 flex flex-col">
              <span class="font-headline-md text-headline-md text-primary tracking-tight font-extrabold font-tabular">
                {{ formatRupiah(summary.gross_revenue) }}
              </span>
              <span class="font-body-sm text-body-sm text-outline mt-1">
                Dari {{ summary.success_count }} transaksi berhasil
              </span>
            </div>
          </div>

          <!-- KPI 2: Pendapatan Bersih -->
          <div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md border border-outline-variant/20 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-space-xs">
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-secondary font-bold">2</span>
                <span class="font-label-md text-label-md text-on-surface-variant">Pendapatan Bersih</span>
              </div>
              <span class="font-label-sm text-label-sm text-primary-container bg-primary-container/10 px-2 py-0.5 rounded-full font-semibold">
                Net {{ netMarginPercent }}%
              </span>
            </div>
            <div class="mt-2 flex flex-col">
              <span class="font-headline-md text-headline-md text-secondary tracking-tight font-extrabold font-tabular">
                {{ formatRupiah(summary.net_revenue) }}
              </span>
              <span class="font-body-sm text-body-sm text-outline mt-1">
                Total fee gateway: {{ formatRupiah(summary.total_fees) }}
              </span>
            </div>
          </div>

          <!-- KPI 3: Menunggu Pembayaran -->
          <div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md border border-outline-variant/20 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-space-xs">
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-outline font-bold">3</span>
                <span class="font-label-md text-label-md text-on-surface-variant">Menunggu / Piutang</span>
              </div>
              <span class="font-label-sm text-label-sm text-amber-300 bg-amber-500/10 px-2 py-0.5 rounded-full font-semibold">
                {{ summary.pending_count }} pending
              </span>
            </div>
            <div class="mt-2 flex flex-col">
              <span class="font-headline-md text-headline-md text-on-surface tracking-tight font-extrabold font-tabular">
                {{ formatRupiah(summary.pending_amount) }}
              </span>
              <span class="font-body-sm text-body-sm text-outline mt-1">
                Menunggu pelunasan atau konfirmasi
              </span>
            </div>
          </div>

          <!-- KPI 4: Success Rate -->
          <div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md border border-outline-variant/20 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-space-xs">
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-secondary font-bold">4</span>
                <span class="font-label-md text-label-md text-on-surface-variant">Rasio Berhasil</span>
              </div>
              <span class="font-label-sm text-label-sm text-secondary bg-secondary/10 px-2 py-0.5 rounded-full font-semibold">Live</span>
            </div>
            <div class="mt-2 flex flex-col">
              <span class="font-headline-md text-headline-md text-on-surface tracking-tight font-extrabold font-tabular">
                {{ summary.success_rate }}%
              </span>
              <span class="font-body-sm text-body-sm text-outline mt-1">
                {{ summary.success_count }} lunas • {{ summary.failed_count }} gagal/expired
              </span>
            </div>
          </div>
        </div>

        <!-- Payment Channel Distribution + Webhook Status -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-md">
          <div class="lg:col-span-2 bg-surface-container-low rounded-xl p-space-lg border border-outline-variant/20 space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <span class="font-label-sm text-outline uppercase tracking-wider">Distribusi Metode Pembayaran</span>
                <h2 class="font-headline-sm text-on-surface font-bold">Performa Kanal Pembayaran</h2>
              </div>
              <span class="material-symbols-outlined text-primary-container text-[22px]">donut_small</span>
            </div>

            <div v-if="channelBreakdown.length === 0" class="py-6 text-center text-xs text-outline">
              Belum ada data kanal pembayaran.
            </div>
            <div v-else class="space-y-3">
              <div v-for="ch in channelBreakdown" :key="ch.name" class="space-y-1">
                <div class="flex justify-between items-center text-xs">
                  <span class="text-on-surface font-semibold">{{ ch.name }} ({{ ch.count }} trx)</span>
                  <span class="text-secondary font-bold font-tabular">{{ formatRupiah(ch.total) }} ({{ ch.percent }}%)</span>
                </div>
                <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                  <div
                    class="bg-gradient-to-r from-primary-container to-secondary h-full rounded-full transition-all duration-500"
                    :style="{ width: `${ch.percent}%` }"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-surface-container-low rounded-xl p-space-lg border border-outline-variant/20 flex flex-col justify-between gap-4">
            <div class="space-y-2">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-secondary animate-pulse"></span>
                <span class="font-label-md text-primary font-bold">Endpoint Webhook Aktif</span>
              </div>
              <p class="text-xs text-on-surface-variant leading-relaxed">
                Daftarkan URL webhook berikut pada dashboard Midtrans atau Tripay agar verifikasi pembayaran dan pengiriman lisensi berjalan otomatis:
              </p>
              <div class="space-y-2 pt-1">
                <div class="p-2.5 rounded-lg bg-surface-container-lowest border border-outline-variant/25">
                  <span class="text-[10px] text-outline uppercase block">Midtrans Notification URL</span>
                  <code class="text-[11px] text-primary-container break-all">/api/webhook_payment.php?gateway=midtrans</code>
                </div>
                <div class="p-2.5 rounded-lg bg-surface-container-lowest border border-outline-variant/25">
                  <span class="text-[10px] text-outline uppercase block">Tripay Callback URL</span>
                  <code class="text-[11px] text-secondary break-all">/api/webhook_payment.php?gateway=tripay</code>
                </div>
              </div>
            </div>
            <p class="text-[11px] text-outline">
              Total entri tersinkron: <strong class="text-on-surface">{{ ledgerList.length }} transaksi</strong>
            </p>
          </div>
        </div>

        <!-- Detailed Ledger & Mutations Table -->
        <div class="bg-surface-container-low rounded-xl shadow-xl border border-outline-variant/20 flex flex-col overflow-hidden">
          <!-- Filter Bar -->
          <div class="p-space-md sm:p-space-lg flex flex-col lg:flex-row lg:items-center justify-between gap-space-md bg-surface-container/40 border-b border-outline-variant/20">
            <div>
              <div class="flex items-center gap-2">
                <h2 class="font-headline-sm text-on-surface font-bold">Riwayat Transaksi &amp; Mutasi</h2>
                <span class="bg-surface-container-highest text-primary-fixed text-label-sm px-2.5 py-0.5 rounded-full font-semibold">Live DB</span>
              </div>
              <span class="font-body-sm text-outline">
                Menampilkan {{ filteredTransactions.length }} dari {{ ledgerList.length }} transaksi
              </span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
              <select
                v-model="statusFilter"
                class="bg-surface-container text-on-surface text-xs font-semibold px-3.5 py-2 rounded-full border border-outline-variant/30 focus:outline-none focus:border-primary-container"
              >
                <option value="Semua">Status: Semua</option>
                <option value="Berhasil">Berhasil / Lunas</option>
                <option value="Menunggu">Menunggu Bayar</option>
                <option value="Gagal">Gagal / Kedaluwarsa</option>
              </select>

              <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-outline text-[16px]">search</span>
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Cari invoice, klien, produk..."
                  class="bg-surface-container pl-8 pr-4 py-2 rounded-full text-xs text-on-surface placeholder:text-outline/70 border border-outline-variant/30 focus:outline-none focus:border-primary-container w-52 sm:w-64"
                />
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-surface-container-lowest/70 text-outline font-label-sm uppercase tracking-wider border-b border-outline-variant/20">
                  <th class="py-3.5 px-space-md">Invoice &amp; ID</th>
                  <th class="py-3.5 px-space-md">Waktu</th>
                  <th class="py-3.5 px-space-md">Pelanggan</th>
                  <th class="py-3.5 px-space-md">Metode</th>
                  <th class="py-3.5 px-space-md">Rincian</th>
                  <th class="py-3.5 px-space-md text-right">Nominal</th>
                  <th class="py-3.5 px-space-md text-right">Fee</th>
                  <th class="py-3.5 px-space-md text-center">Status</th>
                  <th class="py-3.5 px-space-md text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-surface-container-highest/30 font-body-sm text-xs">
                <tr
                  v-for="trx in filteredTransactions"
                  :key="trx.id"
                  class="hover:bg-surface-container/50 transition-colors"
                >
                  <td class="py-3.5 px-space-md whitespace-nowrap">
                    <div class="flex flex-col">
                      <span class="font-mono font-bold text-primary">{{ trx.ref_invoice || trx.refInvoice }}</span>
                      <span class="text-[11px] text-outline font-mono">{{ trx.id }}</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-space-md whitespace-nowrap text-on-surface-variant">
                    {{ trx.date || trx.created_at }}
                  </td>
                  <td class="py-3.5 px-space-md">
                    <div class="flex flex-col">
                      <span class="text-on-surface font-semibold">{{ trx.client }}</span>
                      <span v-if="trx.client_email" class="text-[11px] text-outline">{{ trx.client_email }}</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-space-md whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 bg-surface-container px-2.5 py-1 rounded-full text-[11px] text-on-surface">
                      {{ trx.method }}
                    </span>
                  </td>
                  <td class="py-3.5 px-space-md max-w-[220px]">
                    <span class="text-on-surface font-medium line-clamp-1">{{ trx.category }}</span>
                  </td>
                  <td class="py-3.5 px-space-md text-right whitespace-nowrap font-bold text-on-surface font-tabular">
                    {{ formatRupiah(trx.amount) }}
                  </td>
                  <td class="py-3.5 px-space-md text-right whitespace-nowrap text-outline font-tabular">
                    {{ trx.fee_amount > 0 ? `- ${formatRupiah(trx.fee_amount)}` : 'Rp 0' }}
                  </td>
                  <td class="py-3.5 px-space-md text-center whitespace-nowrap">
                    <span
                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold"
                      :class="trx.status === 'Berhasil'
                        ? 'bg-secondary/15 text-secondary'
                        : trx.status === 'Menunggu'
                          ? 'bg-amber-500/15 text-amber-300'
                          : 'bg-error-container/30 text-error'"
                    >
                      <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="trx.status === 'Berhasil' ? 'bg-secondary' : trx.status === 'Menunggu' ? 'bg-amber-400 animate-pulse' : 'bg-error'"
                      ></span>
                      {{ trx.status }}
                    </span>
                  </td>
                  <td class="py-3.5 px-space-md text-center whitespace-nowrap">
                    <div class="flex items-center justify-center gap-1">
                      <button
                        v-if="trx.status === 'Menunggu' && trx.order_id"
                        @click="confirmManualPayment(trx)"
                        :disabled="confirmingId === trx.id"
                        type="button"
                        class="px-2.5 py-1 rounded-full bg-secondary/15 hover:bg-secondary/30 text-secondary text-[11px] font-bold border border-secondary/35 transition-colors disabled:opacity-50"
                        title="Konfirmasi Pembayaran Lunas & Terbitkan Lisensi"
                      >
                        {{ confirmingId === trx.id ? '...' : 'Set Lunas' }}
                      </button>
                      <button
                        @click="selectedWebhookTrx = trx"
                        type="button"
                        class="p-1.5 rounded-md hover:bg-surface-container-highest text-outline hover:text-primary transition-colors"
                        title="Lihat Detail & Log Webhook"
                      >
                        <span class="material-symbols-outlined text-[18px]">data_object</span>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr v-if="filteredTransactions.length === 0">
                  <td colspan="9" class="py-12 text-center text-outline">
                    Tidak ada transaksi yang sesuai filter.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail / Webhook Payload -->
    <div v-if="selectedWebhookTrx" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
      <div class="relative w-full max-w-xl rounded-3xl bg-surface-container-high border border-outline-variant/40 p-6 shadow-2xl space-y-4">
        <button @click="selectedWebhookTrx = null" class="absolute top-5 right-5 text-outline hover:text-on-surface">
          <span class="material-symbols-outlined">close</span>
        </button>
        <div>
          <span class="text-xs font-mono text-secondary uppercase">Transaction Inspector</span>
          <h3 class="font-headline-sm text-primary font-bold">{{ selectedWebhookTrx.ref_invoice || selectedWebhookTrx.refInvoice }}</h3>
        </div>
        <pre class="p-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/25 text-xs font-mono text-on-surface overflow-x-auto max-h-80">{{ JSON.stringify(selectedWebhookTrx, null, 2) }}</pre>
      </div>
    </div>

    <!-- Toast -->
    <div
      v-if="toast.message"
      class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-2xl bg-surface-container-highest text-xs font-semibold shadow-2xl flex items-center gap-2 border"
      :class="toast.type === 'error' ? 'border-error/40 text-error' : 'border-secondary/40 text-secondary'"
    >
      <span class="material-symbols-outlined text-[18px]">{{ toast.type === 'error' ? 'error' : 'check_circle' }}</span>
      <span>{{ toast.message }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { transactions, formatRupiah } from '@/data/mockData';
import { TransactionsApi, PaymentApi } from '@/services/api';

const ledgerList = ref([...transactions.value]);
const isLoading = ref(false);
const statusFilter = ref('Semua');
const searchQuery = ref('');
const confirmingId = ref('');
const selectedWebhookTrx = ref(null);
const toast = ref({ message: '', type: 'success' });

function showToast(message, type = 'success') {
  toast.value = { message, type };
  setTimeout(() => {
    toast.value = { message: '', type: 'success' };
  }, 3500);
}

async function refreshTransactions() {
  isLoading.value = true;
  try {
    const res = await TransactionsApi.getAll();
    if (res?.status === 'success' && Array.isArray(res.data)) {
      ledgerList.value = res.data;
      transactions.value = res.data;
    }
  } catch {
    ledgerList.value = [...transactions.value];
  } finally {
    isLoading.value = false;
  }
}

const summary = computed(() => {
  let gross = 0;
  let fees = 0;
  let net = 0;
  let pendingAmount = 0;
  let pendingCount = 0;
  let successCount = 0;
  let failedCount = 0;

  for (const t of ledgerList.value) {
    const amt = Number(t.amount || 0);
    const fee = Number(t.fee_amount || 0);
    const netAmt = Number(t.net_amount || Math.max(0, amt - fee));
    if (t.status === 'Berhasil') {
      gross += amt;
      fees += fee;
      net += netAmt;
      successCount++;
    } else if (t.status === 'Menunggu') {
      pendingAmount += amt;
      pendingCount++;
    } else {
      failedCount++;
    }
  }

  const total = ledgerList.value.length;
  return {
    gross_revenue: gross,
    total_fees: fees,
    net_revenue: net,
    pending_amount: pendingAmount,
    pending_count: pendingCount,
    success_count: successCount,
    failed_count: failedCount,
    success_rate: total > 0 ? Number(((successCount / total) * 100).toFixed(1)) : 100,
  };
});

const netMarginPercent = computed(() => {
  if (summary.value.gross_revenue <= 0) return '100.0';
  return ((summary.value.net_revenue / summary.value.gross_revenue) * 100).toFixed(1);
});

const channelBreakdown = computed(() => {
  const map = new Map();
  let grandTotal = 0;
  for (const t of ledgerList.value) {
    if (t.status !== 'Berhasil') continue;
    const key = t.method || 'Lainnya';
    const amt = Number(t.amount || 0);
    const prev = map.get(key) || { name: key, count: 0, total: 0 };
    prev.count += 1;
    prev.total += amt;
    map.set(key, prev);
    grandTotal += amt;
  }
  return Array.from(map.values())
    .sort((a, b) => b.total - a.total)
    .slice(0, 5)
    .map((item) => ({
      ...item,
      percent: grandTotal > 0 ? Number(((item.total / grandTotal) * 100).toFixed(1)) : 0,
    }));
});

const filteredTransactions = computed(() => {
  return ledgerList.value.filter((t) => {
    const matchStatus = statusFilter.value === 'Semua' || t.status === statusFilter.value;
    const q = searchQuery.value.toLowerCase().trim();
    const inv = String(t.ref_invoice || t.refInvoice || '').toLowerCase();
    const matchQ =
      !q ||
      inv.includes(q) ||
      String(t.client || '').toLowerCase().includes(q) ||
      String(t.category || '').toLowerCase().includes(q) ||
      String(t.method || '').toLowerCase().includes(q);
    return matchStatus && matchQ;
  });
});

async function confirmManualPayment(trx) {
  const invoice = trx.ref_invoice || trx.refInvoice;
  if (!invoice) return;
  confirmingId.value = trx.id;
  try {
    const res = await PaymentApi.adminConfirm(invoice, 'paid', trx.method || 'Konfirmasi Manual Admin');
    showToast(res?.message || `Invoice ${invoice} berhasil dilunaskan & lisensi diterbitkan.`);
    await refreshTransactions();
  } catch (err) {
    showToast(err?.message || 'Gagal mengonfirmasi pembayaran.', 'error');
  } finally {
    confirmingId.value = '';
  }
}

function exportCsv() {
  const headers = ['ID', 'Invoice', 'Tanggal', 'Pelanggan', 'Email', 'Rincian', 'Metode', 'Nominal', 'Fee', 'Netto', 'Status'];
  const rows = filteredTransactions.value.map((t) => [
    t.id,
    t.ref_invoice || t.refInvoice || '',
    t.date || t.created_at || '',
    t.client || '',
    t.client_email || '',
    t.category || '',
    t.method || '',
    Number(t.amount || 0),
    Number(t.fee_amount || 0),
    Number(t.net_amount || 0),
    t.status || '',
  ]);

  const csvContent = [headers, ...rows]
    .map((row) => row.map((cell) => `"${String(cell).replace(/"/g, '""')}"`).join(','))
    .join('\n');

  const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `laporan-transaksi-asetlab-${new Date().toISOString().slice(0, 10)}.csv`;
  a.click();
  URL.revokeObjectURL(url);
  showToast('Laporan CSV berhasil diunduh.');
}

onMounted(refreshTransactions);
</script>
