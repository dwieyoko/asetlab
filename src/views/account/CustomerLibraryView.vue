<template>
  <div class="w-full relative pb-20">
    <div class="pointer-events-none absolute top-10 left-1/4 w-[480px] h-[280px] bg-primary-container/10 rounded-full blur-[140px] -z-10"></div>

    <div class="max-w-6xl mx-auto px-margin-mobile md:px-margin pt-8 space-y-8">
      <!-- Profile Header -->
      <div class="rounded-3xl bg-surface-container-low border border-outline-variant/30 p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-container/25 to-secondary/25 border border-primary-container/40 flex items-center justify-center text-primary font-headline-sm font-extrabold text-xl">
            {{ userInitials }}
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="font-headline-md text-primary font-extrabold">{{ user?.name || 'Anggota AsetLab' }}</h1>
              <span class="px-2.5 py-0.5 rounded-full bg-secondary/15 text-secondary text-[11px] font-bold uppercase border border-secondary/30">
                {{ user?.role === 'admin' ? 'Admin' : 'Member' }}
              </span>
            </div>
            <p class="text-xs text-on-surface-variant mt-0.5">{{ user?.email }}</p>
          </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
          <router-link
            v-if="isAdmin"
            to="/admin"
            class="px-4 py-2 rounded-full bg-secondary/15 text-secondary border border-secondary/35 text-xs font-bold inline-flex items-center gap-1.5"
          >
            <span class="material-symbols-outlined text-[16px]">dashboard</span>
            Panel Admin
          </router-link>
          <router-link
            to="/produk"
            class="px-4 py-2 rounded-full bg-surface-container-high hover:bg-surface-bright text-primary border border-outline-variant/30 text-xs font-bold inline-flex items-center gap-1.5"
          >
            <span class="material-symbols-outlined text-[16px]">storefront</span>
            Belanja Produk
          </router-link>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <button
          @click="activeTab = 'library'"
          type="button"
          class="px-5 py-2.5 rounded-full text-xs font-bold transition-all inline-flex items-center gap-2 shrink-0"
          :class="activeTab === 'library'
            ? 'bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest shadow-md'
            : 'bg-surface-container-low text-on-surface-variant hover:text-primary border border-outline-variant/25'"
        >
          <span class="material-symbols-outlined text-[18px]">folder_zip</span>
          <span>Library Produk Saya ({{ licenses.length }})</span>
        </button>

        <button
          @click="activeTab = 'orders'"
          type="button"
          class="px-5 py-2.5 rounded-full text-xs font-bold transition-all inline-flex items-center gap-2 shrink-0"
          :class="activeTab === 'orders'
            ? 'bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest shadow-md'
            : 'bg-surface-container-low text-on-surface-variant hover:text-primary border border-outline-variant/25'"
        >
          <span class="material-symbols-outlined text-[18px]">receipt_long</span>
          <span>Riwayat Transaksi ({{ orders.length }})</span>
        </button>

        <button
          @click="activeTab = 'security'"
          type="button"
          class="px-5 py-2.5 rounded-full text-xs font-bold transition-all inline-flex items-center gap-2 shrink-0"
          :class="activeTab === 'security'
            ? 'bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest shadow-md'
            : 'bg-surface-container-low text-on-surface-variant hover:text-primary border border-outline-variant/25'"
        >
          <span class="material-symbols-outlined text-[18px]">shield_lock</span>
          <span>Keamanan Akun</span>
        </button>
      </div>

      <!-- Loading -->
      <div v-if="isLoading" class="py-16 text-center">
        <span class="material-symbols-outlined text-4xl text-primary-container animate-spin">progress_activity</span>
        <p class="text-sm text-on-surface-variant mt-2">Memuat data akun Anda...</p>
      </div>

      <!-- TAB 1: DIGITAL LIBRARY -->
      <div v-else-if="activeTab === 'library'" class="space-y-4">
        <div v-if="licenses.length === 0" class="rounded-3xl bg-surface-container-low border border-outline-variant/25 p-12 text-center">
          <span class="material-symbols-outlined text-5xl text-outline mb-3">inventory_2</span>
          <h3 class="font-headline-sm text-primary font-bold">Belum Ada Produk di Library</h3>
          <p class="text-sm text-on-surface-variant mt-1 max-w-md mx-auto">
            Produk digital yang sudah Anda beli dan terverifikasi lunas akan muncul di sini lengkap dengan License Key &amp; tombol unduhan.
          </p>
          <router-link
            to="/produk"
            class="mt-5 inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-xs"
          >
            Jelajahi Katalog Produk
          </router-link>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <article
            v-for="lic in licenses"
            :key="lic.license_key"
            class="rounded-3xl bg-surface-container-low border border-outline-variant/30 overflow-hidden shadow-lg flex flex-col"
          >
            <div class="p-5 flex gap-4 items-start">
              <img
                v-if="lic.image"
                :src="lic.image"
                :alt="lic.product_title"
                class="w-20 h-20 rounded-2xl object-cover bg-surface-container-high shrink-0 border border-outline-variant/25"
              />
              <div class="min-w-0 flex-1 space-y-1">
                <div class="flex items-center gap-2">
                  <span class="text-[11px] font-bold uppercase text-primary-container">{{ lic.category || 'Aset Digital' }}</span>
                  <span class="px-2 py-0.5 rounded bg-surface-container-high text-[10px] font-mono text-primary">{{ lic.version || 'v1.0' }}</span>
                </div>
                <h3 class="font-headline-sm text-primary font-bold line-clamp-1">{{ lic.product_title }}</h3>
                <p class="text-[11px] text-outline">{{ lic.license_type || 'Commercial License' }} • Invoice {{ lic.invoice_number }}</p>
              </div>
            </div>

            <div class="px-5 pb-5 space-y-4 mt-auto">
              <!-- License Key Box -->
              <div class="p-3 rounded-xl bg-surface-container-lowest border border-outline-variant/25 flex items-center justify-between gap-2">
                <div class="min-w-0">
                  <span class="text-[10px] uppercase tracking-wider text-outline block">License Key</span>
                  <code class="text-xs font-mono text-secondary font-bold">{{ lic.license_key }}</code>
                </div>
                <button
                  @click="copyLicense(lic.license_key)"
                  type="button"
                  class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-xs text-primary font-semibold shrink-0"
                >
                  {{ copiedKey === lic.license_key ? 'Tersalin ✓' : 'Salin' }}
                </button>
              </div>

              <!-- Download Quota -->
              <div class="space-y-1">
                <div class="flex justify-between text-[11px] text-outline">
                  <span>Kuota Unduhan</span>
                  <span class="text-on-surface font-semibold">{{ lic.download_count }} / {{ lic.max_downloads }}x</span>
                </div>
                <div class="w-full h-1.5 rounded-full bg-surface-container-highest overflow-hidden">
                  <div
                    class="h-full bg-gradient-to-r from-primary-container to-secondary rounded-full"
                    :style="{ width: `${Math.min(100, (lic.download_count / Math.max(1, lic.max_downloads)) * 100)}%` }"
                  ></div>
                </div>
              </div>

              <!-- Download CTA -->
              <div class="flex items-center justify-between gap-3 pt-1">
                <span class="text-[11px] text-outline truncate">
                  {{ lic.file_name ? `${lic.file_name} (${formatFileSize(lic.file_size)})` : 'Paket Digital' }}
                </span>
                <a
                  v-if="lic.has_file"
                  :href="getDownloadHref(lic)"
                  class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest text-xs font-bold shadow hover:brightness-110 shrink-0"
                >
                  <span class="material-symbols-outlined text-[17px]">download</span>
                  Unduh File
                </a>
                <span v-else class="text-xs text-amber-300 shrink-0">Menunggu upload file</span>
              </div>
            </div>
          </article>
        </div>
      </div>

      <!-- TAB 2: ORDER HISTORY -->
      <div v-else-if="activeTab === 'orders'" class="rounded-3xl bg-surface-container-low border border-outline-variant/25 overflow-hidden">
        <div v-if="orders.length === 0" class="p-12 text-center">
          <span class="material-symbols-outlined text-5xl text-outline mb-3">receipt_long</span>
          <h3 class="font-headline-sm text-primary font-bold">Belum Ada Riwayat Transaksi</h3>
          <p class="text-sm text-on-surface-variant mt-1">Riwayat checkout dan invoice produk digital Anda akan tercatat otomatis di sini.</p>
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-surface-container-lowest/80 text-outline uppercase text-[11px] tracking-wider border-b border-outline-variant/20">
                <th class="py-3.5 px-5">Invoice</th>
                <th class="py-3.5 px-5">Tanggal</th>
                <th class="py-3.5 px-5">Item</th>
                <th class="py-3.5 px-5">Metode</th>
                <th class="py-3.5 px-5 text-right">Total</th>
                <th class="py-3.5 px-5 text-center">Status</th>
                <th class="py-3.5 px-5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/15 text-xs">
              <tr v-for="ord in orders" :key="ord.id" class="hover:bg-surface-container/50">
                <td class="py-4 px-5 font-mono font-bold text-primary">{{ ord.invoice_number }}</td>
                <td class="py-4 px-5 text-on-surface-variant">{{ ord.created_at }}</td>
                <td class="py-4 px-5 text-on-surface">{{ ord.item_count }} produk</td>
                <td class="py-4 px-5 text-on-surface-variant">{{ ord.payment_method || '-' }}</td>
                <td class="py-4 px-5 text-right font-bold text-on-surface">{{ formatRupiah(ord.total_amount) }}</td>
                <td class="py-4 px-5 text-center">
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold text-[11px]"
                    :class="ord.status === 'paid'
                      ? 'bg-secondary/15 text-secondary'
                      : ord.status === 'pending'
                        ? 'bg-amber-500/15 text-amber-300'
                        : 'bg-error-container/30 text-error'"
                  >
                    {{ ord.status === 'paid' ? 'Lunas' : ord.status === 'pending' ? 'Menunggu' : ord.status }}
                  </span>
                </td>
                <td class="py-4 px-5 text-right">
                  <router-link
                    :to="{ name: 'Invoice', params: { invoice: ord.invoice_number }, query: { token: ord.access_token } }"
                    class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-full bg-surface-container-high hover:bg-surface-bright text-primary font-bold"
                  >
                    {{ ord.status === 'pending' ? 'Bayar' : 'Lihat' }}
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TAB 3: ACCOUNT SECURITY (CHANGE PASSWORD) -->
      <div v-else class="max-w-xl rounded-3xl bg-surface-container-low border border-outline-variant/25 p-6 sm:p-8 space-y-5">
        <div>
          <h2 class="font-headline-sm text-primary font-bold">Perbarui Kata Sandi</h2>
          <p class="text-xs text-on-surface-variant mt-1">Gunakan kombinasi minimal 8 karakter untuk menjaga keamanan lisensi produk Anda.</p>
        </div>

        <div
          v-if="pwdFeedback.message"
          class="p-3.5 rounded-xl text-xs font-semibold border"
          :class="pwdFeedback.type === 'error' ? 'bg-error-container/30 border-error/40 text-error' : 'bg-secondary/15 border-secondary/35 text-secondary'"
        >
          {{ pwdFeedback.message }}
        </div>

        <form @submit.prevent="submitChangePassword" class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Kata Sandi Saat Ini</label>
            <input
              v-model="pwdForm.current"
              type="password"
              required
              class="w-full bg-surface-container-lowest px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
            />
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Kata Sandi Baru (min. 8 karakter)</label>
            <input
              v-model="pwdForm.next"
              type="password"
              required
              minlength="8"
              class="w-full bg-surface-container-lowest px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
            />
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <input
              v-model="pwdForm.confirm"
              type="password"
              required
              minlength="8"
              class="w-full bg-surface-container-lowest px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
            />
          </div>
          <button
            type="submit"
            :disabled="savingPwd"
            class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm hover:brightness-110 shadow-md shadow-primary-container/20 active:scale-[0.99] transition-all disabled:opacity-60"
          >
            {{ savingPwd ? 'Menyimpan...' : 'Simpan Kata Sandi Baru' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { AccountApi, AuthApi, buildSecureDownloadUrl } from '@/services/api';
import { useAuth } from '@/stores/auth';
import { formatRupiah, formatFileSize } from '@/data/mockData';

const { user, isAdmin } = useAuth();

const activeTab = ref('library');
const isLoading = ref(true);
const licenses = ref([]);
const orders = ref([]);
const copiedKey = ref('');

const pwdForm = ref({ current: '', next: '', confirm: '' });
const savingPwd = ref(false);
const pwdFeedback = ref({ message: '', type: 'success' });

const userInitials = computed(() => {
  const name = user.value?.name || 'AL';
  return name
    .split(' ')
    .slice(0, 2)
    .map((p) => p[0])
    .join('')
    .toUpperCase();
});

async function loadAccountData() {
  isLoading.value = true;
  try {
    const res = await AccountApi.getOverview();
    if (res?.status === 'success' && res?.data) {
      licenses.value = res.data.licenses || [];
      orders.value = res.data.orders || [];
    }
  } catch (err) {
    console.warn('Gagal memuat library akun:', err?.message);
  } finally {
    isLoading.value = false;
  }
}

function getDownloadHref(lic) {
  return buildSecureDownloadUrl(lic.license_key, lic.access_token || '');
}

async function copyLicense(key) {
  try {
    await navigator.clipboard.writeText(key);
    copiedKey.value = key;
    setTimeout(() => {
      copiedKey.value = '';
    }, 2000);
  } catch {
    // ignore
  }
}

async function submitChangePassword() {
  pwdFeedback.value = { message: '', type: 'success' };
  if (pwdForm.value.next !== pwdForm.value.confirm) {
    pwdFeedback.value = { message: 'Konfirmasi kata sandi baru tidak cocok.', type: 'error' };
    return;
  }
  savingPwd.value = true;
  try {
    const res = await AuthApi.changePassword(pwdForm.value.current, pwdForm.value.next);
    pwdFeedback.value = { message: res?.message || 'Kata sandi berhasil diperbarui.', type: 'success' };
    pwdForm.value = { current: '', next: '', confirm: '' };
  } catch (err) {
    pwdFeedback.value = { message: err?.message || 'Gagal memperbarui kata sandi.', type: 'error' };
  } finally {
    savingPwd.value = false;
  }
}

onMounted(loadAccountData);
</script>
