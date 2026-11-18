<template>
  <div class="w-full relative pb-20">
    <div class="max-w-4xl mx-auto px-margin-mobile md:px-margin pt-10 space-y-6">
      <!-- Loading -->
      <div v-if="isLoading" class="py-24 text-center">
        <span class="material-symbols-outlined text-4xl text-primary-container animate-spin">progress_activity</span>
        <p class="text-sm text-on-surface-variant mt-3">Memuat rincian invoice...</p>
      </div>

      <!-- Error -->
      <div v-else-if="errorMessage" class="rounded-3xl bg-surface-container-low border border-error/35 p-10 text-center space-y-3">
        <span class="material-symbols-outlined text-5xl text-error">receipt_long</span>
        <h2 class="font-headline-md text-primary font-bold">Invoice Tidak Dapat Diakses</h2>
        <p class="text-sm text-on-surface-variant max-w-md mx-auto">{{ errorMessage }}</p>
        <router-link
          to="/produk"
          class="inline-flex items-center gap-2 mt-4 px-6 py-2.5 rounded-full bg-surface-container-high text-primary text-sm font-bold"
        >
          Kembali ke Katalog
        </router-link>
      </div>

      <!-- Invoice Card -->
      <div v-else-if="order" class="rounded-3xl bg-surface-container-low border border-outline-variant/30 overflow-hidden shadow-2xl">
        <!-- Status Banner -->
        <div
          class="p-6 sm:p-8 border-b border-outline-variant/25 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
          :class="statusBannerClass"
        >
          <div>
            <span class="font-mono text-xs uppercase tracking-widest text-outline">Nomor Invoice</span>
            <h1 class="font-headline-md text-primary font-extrabold font-mono mt-0.5">{{ order.invoice_number }}</h1>
            <p class="text-xs text-on-surface-variant mt-1">
              Pembeli: <strong class="text-on-surface">{{ order.buyer_name }}</strong> ({{ order.buyer_email }})
            </p>
          </div>
          <div class="flex flex-col sm:items-end gap-1">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold" :class="statusPillClass">
              <span class="w-2 h-2 rounded-full" :class="statusDotClass"></span>
              {{ statusLabel }}
            </span>
            <span class="text-[11px] text-outline">
              {{ order.payment_method || 'QRIS Dinamis' }} • Gateway: <strong class="uppercase">{{ order.payment_gateway }}</strong>
            </span>
          </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
          <!-- Action Feedback Banner -->
          <div
            v-if="actionNotice.message"
            class="p-3.5 rounded-2xl text-xs font-semibold flex items-center gap-2 border"
            :class="actionNotice.type === 'error' ? 'bg-error-container/30 border-error/40 text-error' : 'bg-secondary/15 border-secondary/35 text-secondary'"
          >
            <span class="material-symbols-outlined text-[18px]">{{ actionNotice.type === 'error' ? 'error' : 'check_circle' }}</span>
            <span>{{ actionNotice.message }}</span>
          </div>

          <!-- ================= PENDING PAYMENT SECTION ================= -->
          <div
            v-if="order.status === 'pending'"
            class="p-6 rounded-2xl bg-surface-container-lowest/90 border border-amber-500/35 space-y-5"
          >
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-300 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[22px]">payments</span>
                </div>
                <div>
                  <h3 class="font-label-lg text-primary font-bold">Selesaikan Pembayaran</h3>
                  <p class="text-xs text-on-surface-variant mt-0.5">
                    Total tagihan <strong class="text-secondary text-sm">{{ formatRupiah(order.total_amount) }}</strong>.
                    Status diperbarui otomatis setiap 5 detik begitu pembayaran masuk.
                  </p>
                </div>
              </div>
              <span v-if="order.expired_at" class="text-[11px] font-mono text-amber-300 bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/25 shrink-0">
                Batas: {{ order.expired_at }}
              </span>
            </div>

            <!-- Midtrans Snap CTA -->
            <div v-if="order.payment_gateway === 'midtrans'" class="flex flex-wrap items-center gap-3">
              <button
                @click="payWithMidtransSnap"
                :disabled="isPaying"
                type="button"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:brightness-110 disabled:opacity-60"
              >
                <span class="material-symbols-outlined text-[19px]">qr_code_scanner</span>
                <span>{{ isPaying ? 'Membuka Midtrans Snap...' : 'Bayar Sekarang via Midtrans (QRIS / VA)' }}</span>
              </button>
              <a
                v-if="order.payment_url"
                :href="order.payment_url"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-surface-container-high text-primary text-xs font-bold border border-outline-variant/30"
              >
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                Buka Halaman Midtrans
              </a>
            </div>

            <!-- Tripay Checkout CTA -->
            <div v-else-if="order.payment_gateway === 'tripay' && order.payment_url" class="flex flex-wrap items-center gap-3">
              <a
                :href="order.payment_url"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:brightness-110"
              >
                <span class="material-symbols-outlined text-[19px]">open_in_new</span>
                <span>Lanjut Bayar via Tripay ({{ order.payment_method }})</span>
              </a>
            </div>

            <!-- Built-in Sandbox Simulator Mode -->
            <div
              v-else-if="order.payment_gateway === 'simulator'"
              class="p-4 rounded-xl bg-primary-container/10 border border-primary-container/30 space-y-3"
            >
              <div class="flex items-center gap-2 text-primary-container text-xs font-bold">
                <span class="material-symbols-outlined text-[18px]">science</span>
                <span>Mode Simulator Pembayaran Aktif (Testing End-to-End)</span>
              </div>
              <p class="text-xs text-on-surface-variant">
                Gateway pembayaran sedang dalam mode simulator di <code class="text-primary">api/secrets.php</code>. Klik tombol di bawah untuk mensimulasikan webhook pelunasan otomatis &amp; menerbitkan License Key + akses unduhan file privat.
              </p>
              <div class="flex flex-wrap items-center gap-3">
                <button
                  @click="runSimulation('paid')"
                  :disabled="isPaying"
                  type="button"
                  class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-xs shadow-lg hover:brightness-110 disabled:opacity-60"
                >
                  <span class="material-symbols-outlined text-[17px]">verified</span>
                  <span>{{ isPaying ? 'Memproses...' : 'Simulasikan Bayar Lunas Sekarang' }}</span>
                </button>
                <button
                  @click="runSimulation('expired')"
                  :disabled="isPaying"
                  type="button"
                  class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-surface-container-high hover:bg-error-container/30 text-on-surface-variant hover:text-error text-xs font-semibold border border-outline-variant/30"
                >
                  Simulasikan Kedaluwarsa
                </button>
              </div>
            </div>

            <!-- Utility Actions (Copy Link, Refresh, Admin Manual Confirm) -->
            <div class="pt-3 border-t border-outline-variant/20 flex flex-wrap items-center gap-3">
              <button
                @click="copyInvoiceLink"
                type="button"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-surface-container-high hover:bg-surface-bright text-primary text-xs font-bold border border-outline-variant/30"
              >
                <span class="material-symbols-outlined text-[16px]">content_copy</span>
                {{ copiedLink ? 'Link Tersalin ✓' : 'Salin Link Invoice Privat' }}
              </button>
              <button
                @click="loadInvoice(false)"
                type="button"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-surface-container text-secondary text-xs font-bold border border-secondary/30"
              >
                <span class="material-symbols-outlined text-[16px]">refresh</span>
                Cek Status Sekarang
              </button>
              <button
                v-if="isAdmin"
                @click="confirmManualAsAdmin"
                :disabled="isPaying"
                type="button"
                class="ml-auto inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-secondary/20 hover:bg-secondary/30 text-secondary text-xs font-bold border border-secondary/40"
              >
                <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                Konfirmasi Lunas (Admin)
              </button>
            </div>
          </div>

          <!-- ================= PAID & SECURE DOWNLOAD SECTION ================= -->
          <div
            v-if="order.status === 'paid'"
            class="p-6 rounded-2xl bg-secondary/10 border border-secondary/35 space-y-4"
          >
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-secondary/20 text-secondary flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[26px]">verified</span>
                </div>
                <div>
                  <h3 class="font-headline-sm text-primary font-bold">Pembayaran Lunas! Aset Digital Siap Diunduh</h3>
                  <p class="text-xs text-on-surface-variant">
                    Lisensi unik Anda telah aktif. Unduhan dilayani aman dari penyimpanan privat AsetLab.
                  </p>
                </div>
              </div>
              <button
                @click="copyInvoiceLink"
                type="button"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-surface-container-lowest/80 text-primary text-xs font-semibold border border-outline-variant/30 shrink-0"
              >
                <span class="material-symbols-outlined text-[15px]">bookmark</span>
                {{ copiedLink ? 'Link Disalin ✓' : 'Simpan Link Unduhan' }}
              </button>
            </div>

            <div v-if="order.licenses && order.licenses.length > 0" class="space-y-3">
              <div
                v-for="lic in order.licenses"
                :key="lic.license_key"
                class="p-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
              >
                <div class="space-y-1 min-w-0">
                  <div class="flex items-center gap-2 flex-wrap">
                    <p class="font-label-lg text-primary font-bold truncate">{{ lic.product_title }}</p>
                    <span class="px-2 py-0.5 rounded bg-surface-container-high text-[10px] font-mono text-primary">{{ lic.version || 'v1.0' }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <code class="text-xs font-mono text-secondary bg-secondary/10 px-2.5 py-0.5 rounded-md border border-secondary/25">
                      {{ lic.license_key }}
                    </code>
                    <button
                      @click="copyText(lic.license_key)"
                      type="button"
                      class="text-[11px] text-outline hover:text-primary inline-flex items-center gap-0.5"
                      title="Salin License Key"
                    >
                      <span class="material-symbols-outlined text-[14px]">content_copy</span>
                      <span>{{ copiedKey === lic.license_key ? 'Tersalin' : 'Salin' }}</span>
                    </button>
                  </div>
                  <p class="text-[11px] text-outline">
                    Kuota Unduhan: <strong class="text-on-surface">{{ lic.download_count }} / {{ lic.max_downloads }}</strong>
                    <template v-if="lic.file_name"> • {{ lic.file_name }} ({{ formatFileSize(lic.file_size) }})</template>
                  </p>
                </div>

                <div class="shrink-0">
                  <a
                    v-if="lic.has_file"
                    :href="getDownloadHref(lic.license_key)"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest text-xs font-bold shadow-[0_4px_16px_rgba(0,242,254,0.3)] hover:brightness-110 transition-all"
                  >
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    <span>Unduh File Privat</span>
                  </a>
                  <span
                    v-else
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-semibold"
                  >
                    <span class="material-symbols-outlined text-[16px]">hourglass_top</span>
                    File belum diunggah admin
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Items Table -->
          <div class="space-y-3">
            <h3 class="font-label-lg text-primary font-bold">Rincian Produk Digital</h3>
            <div class="divide-y divide-outline-variant/20 rounded-2xl bg-surface-container-lowest/70 border border-outline-variant/20">
              <div
                v-for="item in order.items"
                :key="item.id"
                class="p-4 flex items-center justify-between gap-4"
              >
                <div class="min-w-0">
                  <p class="font-label-md text-primary font-bold truncate">{{ item.product_title }}</p>
                  <p class="text-xs text-outline">{{ item.product_sku }} • {{ item.license_type }}</p>
                </div>
                <span class="font-label-md text-on-surface font-bold shrink-0">{{ formatRupiah(item.price) }}</span>
              </div>
            </div>
          </div>

          <!-- Totals Breakdown -->
          <div class="pt-4 border-t border-outline-variant/20 space-y-2 text-sm">
            <div class="flex justify-between text-on-surface-variant">
              <span>Subtotal</span>
              <span>{{ formatRupiah(order.subtotal) }}</span>
            </div>
            <div v-if="order.discount_amount > 0" class="flex justify-between text-secondary">
              <span>Diskon Kupon ({{ order.coupon_code }})</span>
              <span>- {{ formatRupiah(order.discount_amount) }}</span>
            </div>
            <div class="flex justify-between items-baseline pt-2 border-t border-outline-variant/20">
              <span class="font-bold text-primary">Total Pembayaran</span>
              <span class="font-headline-md text-secondary font-extrabold">{{ formatRupiah(order.total_amount) }}</span>
            </div>
          </div>

          <!-- Guest to Member Conversion Banner / Member Library Link -->
          <div
            v-if="!isAuthenticated"
            class="p-4 rounded-2xl bg-surface-container border border-primary-container/25 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
          >
            <div>
              <p class="text-xs font-bold text-primary">Simpan semua lisensi produk Anda secara permanen</p>
              <p class="text-[11px] text-on-surface-variant">
                Daftar gratis dengan email <strong>{{ order.buyer_email }}</strong> — seluruh pembelian guest Anda otomatis masuk ke Library Akun.
              </p>
            </div>
            <router-link
              to="/register"
              class="px-4 py-2 rounded-full bg-primary-container/20 text-primary-container border border-primary-container/40 text-xs font-bold text-center shrink-0 hover:bg-primary-container/30"
            >
              Buat Akun Gratis
            </router-link>
          </div>
          <div v-else class="flex justify-end">
            <router-link
              to="/akun"
              class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-container hover:underline"
            >
              <span class="material-symbols-outlined text-[16px]">library_books</span>
              Buka Library &amp; Riwayat Akun Saya
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { CheckoutApi, PaymentApi, buildSecureDownloadUrl } from '@/services/api';
import { useAuth } from '@/stores/auth';
import { formatRupiah, formatFileSize } from '@/data/mockData';

const route = useRoute();
const { isAuthenticated, isAdmin } = useAuth();

const order = ref(null);
const isLoading = ref(true);
const isPaying = ref(false);
const errorMessage = ref('');
const actionNotice = ref({ message: '', type: 'success' });
const copiedLink = ref(false);
const copiedKey = ref('');
let pollTimer = null;

const accessToken = computed(() =>
  typeof route.query.token === 'string' ? route.query.token : (order.value?.access_token || '')
);

const statusLabel = computed(() => {
  switch (order.value?.status) {
    case 'paid': return 'Lunas / Berhasil';
    case 'expired': return 'Kedaluwarsa';
    case 'failed': return 'Gagal';
    case 'refunded': return 'Dikembalikan';
    default: return 'Menunggu Pembayaran';
  }
});

const statusBannerClass = computed(() => {
  if (order.value?.status === 'paid') return 'bg-secondary/10';
  if (order.value?.status === 'expired' || order.value?.status === 'failed') return 'bg-error-container/20';
  return 'bg-surface-container-lowest/70';
});

const statusPillClass = computed(() => {
  if (order.value?.status === 'paid') return 'bg-secondary/15 text-secondary border border-secondary/35';
  if (order.value?.status === 'expired' || order.value?.status === 'failed') return 'bg-error-container/30 text-error border border-error/40';
  return 'bg-amber-500/15 text-amber-300 border border-amber-500/35';
});

const statusDotClass = computed(() => {
  if (order.value?.status === 'paid') return 'bg-secondary';
  if (order.value?.status === 'expired' || order.value?.status === 'failed') return 'bg-error';
  return 'bg-amber-400 animate-pulse';
});

function showNotice(message, type = 'success') {
  actionNotice.value = { message, type };
  setTimeout(() => {
    actionNotice.value = { message: '', type: 'success' };
  }, 4500);
}

function stopPolling() {
  if (pollTimer) {
    clearInterval(pollTimer);
    pollTimer = null;
  }
}

function startPollingIfPending() {
  stopPolling();
  if (order.value?.status === 'pending') {
    pollTimer = setInterval(() => {
      loadInvoice(true);
    }, 5000);
  }
}

async function loadInvoice(silent = false) {
  if (!silent) {
    isLoading.value = true;
    errorMessage.value = '';
  }
  try {
    const invoice = String(route.params.invoice || '');
    const res = await CheckoutApi.getInvoice(invoice, accessToken.value);
    if (res?.status === 'success' && res?.data) {
      const prevStatus = order.value?.status;
      order.value = res.data;
      if (prevStatus === 'pending' && order.value.status === 'paid') {
        showNotice('Pembayaran Anda baru saja terkonfirmasi lunas! File siap diunduh.', 'success');
      }
      if (order.value.status !== 'pending') {
        stopPolling();
      } else if (!pollTimer) {
        startPollingIfPending();
      }
    } else if (!silent) {
      errorMessage.value = res?.message || 'Invoice tidak ditemukan.';
    }
  } catch (err) {
    if (!silent) {
      errorMessage.value = err?.message || 'Gagal memuat invoice.';
    }
  } finally {
    if (!silent) {
      isLoading.value = false;
    }
  }
}

function loadMidtransSnapScript(clientKey, isProduction) {
  return new Promise((resolve, reject) => {
    if (window.snap) {
      resolve(window.snap);
      return;
    }
    const existing = document.getElementById('midtrans-snap-js');
    if (existing) {
      existing.addEventListener('load', () => resolve(window.snap));
      return;
    }
    const script = document.createElement('script');
    script.id = 'midtrans-snap-js';
    script.src = isProduction
      ? 'https://app.midtrans.com/snap/snap.js'
      : 'https://app.sandbox.midtrans.com/snap/snap.js';
    script.setAttribute('data-client-key', clientKey);
    script.onload = () => resolve(window.snap);
    script.onerror = () => reject(new Error('Gagal memuat script Midtrans Snap'));
    document.head.appendChild(script);
  });
}

async function payWithMidtransSnap() {
  if (!order.value) return;
  const cfg = order.value.gateway_config || {};
  if (!order.value.snap_token || !cfg.midtrans_client_key) {
    if (order.value.payment_url) {
      window.open(order.value.payment_url, '_blank');
    } else {
      showNotice('Snap token belum tersedia. Periksa MIDTRANS_CLIENT_KEY di api/secrets.php.', 'error');
    }
    return;
  }

  isPaying.value = true;
  try {
    const snap = await loadMidtransSnapScript(cfg.midtrans_client_key, Boolean(cfg.midtrans_is_production));
    snap.pay(order.value.snap_token, {
      onSuccess: () => {
        showNotice('Pembayaran diterima! Memverifikasi status pelunasan...', 'success');
        loadInvoice(true);
      },
      onPending: () => {
        showNotice('Instruksi pembayaran dibuat. Halaman ini akan update otomatis saat lunas.', 'success');
        loadInvoice(true);
      },
      onError: () => {
        showNotice('Pembayaran gagal diproses oleh gateway.', 'error');
      },
      onClose: () => {
        loadInvoice(true);
      },
    });
  } catch (err) {
    if (order.value.payment_url) {
      window.open(order.value.payment_url, '_blank');
    } else {
      showNotice(err?.message || 'Gagal membuka Midtrans Snap.', 'error');
    }
  } finally {
    isPaying.value = false;
  }
}

async function runSimulation(outcome) {
  if (!order.value) return;
  isPaying.value = true;
  try {
    const res = await PaymentApi.simulate(order.value.invoice_number, accessToken.value, outcome);
    showNotice(res?.message || 'Status simulasi diperbarui.', 'success');
    await loadInvoice(true);
  } catch (err) {
    showNotice(err?.message || 'Gagal menjalankan simulasi pembayaran.', 'error');
  } finally {
    isPaying.value = false;
  }
}

async function confirmManualAsAdmin() {
  if (!order.value) return;
  isPaying.value = true;
  try {
    const res = await PaymentApi.adminConfirm(order.value.invoice_number, 'paid');
    showNotice(res?.message || 'Pembayaran berhasil dikonfirmasi oleh Admin.', 'success');
    await loadInvoice(true);
  } catch (err) {
    showNotice(err?.message || 'Gagal mengonfirmasi pembayaran.', 'error');
  } finally {
    isPaying.value = false;
  }
}

function getDownloadHref(licenseKey) {
  return buildSecureDownloadUrl(licenseKey, accessToken.value);
}

async function copyInvoiceLink() {
  try {
    await navigator.clipboard.writeText(window.location.href);
    copiedLink.value = true;
    setTimeout(() => {
      copiedLink.value = false;
    }, 2500);
  } catch {
    // ignore
  }
}

async function copyText(val) {
  try {
    await navigator.clipboard.writeText(val);
    copiedKey.value = val;
    setTimeout(() => {
      copiedKey.value = '';
    }, 2000);
  } catch {
    // ignore
  }
}

onMounted(loadInvoice);
onUnmounted(stopPolling);
</script>
