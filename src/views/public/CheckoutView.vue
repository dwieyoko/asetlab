<template>
  <div class="w-full relative pb-20">
    <div class="pointer-events-none absolute top-10 right-1/4 w-[480px] h-[300px] bg-primary-container/10 rounded-full blur-[140px] -z-10"></div>

    <div class="max-w-6xl mx-auto px-margin-mobile md:px-margin pt-8 space-y-8">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-secondary">Checkout Produk Digital</span>
          <h1 class="font-headline-lg text-primary font-extrabold mt-1">Selesaikan Pesanan Anda</h1>
          <p class="text-sm text-on-surface-variant">
            {{ isAuthenticated ? `Masuk sebagai ${user?.name} (${user?.email})` : 'Guest Checkout aktif — tidak perlu daftar akun untuk langsung membeli.' }}
          </p>
        </div>
        <router-link
          to="/produk"
          class="inline-flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary"
        >
          <span class="material-symbols-outlined text-[16px]">arrow_back</span>
          Tambah Produk Lain
        </router-link>
      </div>

      <!-- Empty Cart State -->
      <div v-if="items.length === 0" class="rounded-3xl bg-surface-container-low border border-outline-variant/25 p-12 text-center">
        <span class="material-symbols-outlined text-5xl text-outline mb-3">shopping_cart</span>
        <h2 class="font-headline-md text-primary font-bold">Keranjang Belanja Masih Kosong</h2>
        <p class="text-sm text-on-surface-variant mt-1">Pilih minimal satu produk digital terlebih dahulu sebelum melakukan checkout.</p>
        <router-link
          to="/produk"
          class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-bold text-sm"
        >
          Jelajahi Katalog Produk
        </router-link>
      </div>

      <!-- Checkout Grid -->
      <form v-else @submit.prevent="submitCheckout" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Buyer Info & Payment Method -->
        <div class="lg:col-span-7 space-y-6">
          <!-- Error Banner -->
          <div v-if="errorMessage" class="p-4 rounded-2xl bg-error-container/30 border border-error/40 text-error text-sm flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[20px] shrink-0">error</span>
            <span>{{ errorMessage }}</span>
          </div>

          <!-- Step 1: Data Pembeli (Guest / Member) -->
          <div class="rounded-3xl bg-surface-container-low border border-outline-variant/25 p-6 sm:p-8 space-y-5">
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-full bg-primary-container/20 text-primary-container font-bold text-xs flex items-center justify-center">1</span>
                <h2 class="font-headline-sm text-primary font-bold">Informasi Pembeli &amp; Pengiriman Aset</h2>
              </div>
              <router-link
                v-if="!isAuthenticated"
                to="/login?redirect=/checkout"
                class="text-xs text-primary-container hover:underline font-semibold"
              >
                Sudah punya akun? Masuk
              </router-link>
            </div>

            <div class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Nama Lengkap *</label>
                <input
                  v-model="buyer.name"
                  type="text"
                  required
                  placeholder="Nama lengkap atau nama studio Anda"
                  class="w-full bg-surface-container-lowest px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
                />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Alamat Email *</label>
                  <input
                    v-model="buyer.email"
                    type="email"
                    required
                    placeholder="email@domain.com"
                    class="w-full bg-surface-container-lowest px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
                  />
                  <p class="text-[11px] text-outline mt-1">Invoice, license key &amp; link unduhan dikirim ke email ini.</p>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Nomor WhatsApp *</label>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-2.5 rounded-lg bg-surface-container-lowest border border-outline-variant/40 text-xs font-bold text-primary shrink-0">+62</span>
                    <input
                      v-model="buyer.whatsapp"
                      type="tel"
                      required
                      placeholder="81234567890"
                      class="w-full bg-surface-container-lowest px-3.5 py-2.5 rounded-lg border border-outline-variant/40 text-sm text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 2: Metode Pembayaran -->
          <div class="rounded-3xl bg-surface-container-low border border-outline-variant/25 p-6 sm:p-8 space-y-5">
            <div class="flex items-center gap-2.5">
              <span class="w-7 h-7 rounded-full bg-secondary/20 text-secondary font-bold text-xs flex items-center justify-center">2</span>
              <h2 class="font-headline-sm text-primary font-bold">Pilih Metode Pembayaran</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label
                v-for="method in paymentMethods"
                :key="method.id"
                class="cursor-pointer p-4 rounded-2xl border transition-all flex items-start gap-3"
                :class="selectedPaymentMethod === method.label
                  ? 'bg-primary-container/10 border-primary-container shadow-[0_0_16px_rgba(0,242,254,0.15)]'
                  : 'bg-surface-container-lowest/70 border-outline-variant/30 hover:border-outline-variant'"
              >
                <input
                  v-model="selectedPaymentMethod"
                  type="radio"
                  :value="method.label"
                  class="mt-1 accent-primary-container"
                />
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between gap-2">
                    <span class="font-label-md text-primary font-bold">{{ method.label }}</span>
                    <span
                      v-if="method.badge"
                      class="px-2 py-0.5 rounded-full bg-secondary/15 text-secondary text-[10px] font-bold"
                    >
                      {{ method.badge }}
                    </span>
                  </div>
                  <p class="text-[11px] text-outline mt-0.5">{{ method.desc }}</p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Right Column: Order Summary & Coupon -->
        <div class="lg:col-span-5 lg:sticky lg:top-24">
          <div class="rounded-3xl bg-surface-container-low border border-outline-variant/35 p-6 sm:p-7 shadow-2xl space-y-5">
            <h2 class="font-headline-sm text-primary font-bold">Ringkasan Pesanan ({{ items.length }})</h2>

            <!-- Itemized List -->
            <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
              <div
                v-for="item in items"
                :key="item.id"
                class="flex items-center gap-3 p-3 rounded-xl bg-surface-container-lowest/80 border border-outline-variant/20"
              >
                <img :src="item.image" :alt="item.title" class="w-12 h-12 rounded-lg object-cover shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold text-primary truncate">{{ item.title }}</p>
                  <p class="text-[11px] text-outline truncate">{{ item.license_type }}</p>
                </div>
                <div class="text-right shrink-0">
                  <p class="text-xs font-bold text-secondary">{{ formatRupiah(item.price) }}</p>
                  <button
                    @click="removeFromCart(item.id)"
                    type="button"
                    class="text-[10px] text-outline hover:text-error"
                  >
                    Hapus
                  </button>
                </div>
              </div>
            </div>

            <!-- Coupon Input -->
            <div class="pt-3 border-t border-outline-variant/20 space-y-2">
              <label class="block text-xs font-semibold text-on-surface-variant">Kode Promo / Kupon</label>
              <div class="flex items-center gap-2">
                <input
                  v-model="couponInput"
                  type="text"
                  placeholder="Contoh: LAUNCH20"
                  class="flex-1 bg-surface-container-lowest px-3.5 py-2 rounded-lg border border-outline-variant/40 text-xs uppercase font-mono text-primary placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"
                />
                <button
                  @click="applyCoupon"
                  :disabled="checkingCoupon || !couponInput.trim()"
                  type="button"
                  class="px-3.5 py-2 rounded-lg bg-surface-container-high hover:bg-surface-bright text-primary text-xs font-bold border border-outline-variant/30 transition-colors disabled:opacity-50"
                >
                  {{ checkingCoupon ? '...' : 'Pakai' }}
                </button>
              </div>
              <p v-if="couponMessage" class="text-[11px]" :class="appliedCoupon ? 'text-secondary' : 'text-error'">
                {{ couponMessage }}
              </p>
            </div>

            <!-- Totals -->
            <div class="pt-3 border-t border-outline-variant/20 space-y-2 text-sm">
              <div class="flex justify-between text-on-surface-variant">
                <span>Subtotal</span>
                <span class="font-semibold text-on-surface">{{ formatRupiah(subtotal) }}</span>
              </div>
              <div v-if="discountAmount > 0" class="flex justify-between text-secondary">
                <span>Diskon Kupon ({{ appliedCoupon?.code }})</span>
                <span class="font-bold">- {{ formatRupiah(discountAmount) }}</span>
              </div>
              <div class="flex justify-between text-on-surface-variant">
                <span>Biaya Layanan</span>
                <span class="text-secondary font-semibold">Gratis</span>
              </div>
              <div class="pt-3 border-t border-outline-variant/30 flex justify-between items-baseline">
                <span class="font-label-lg text-primary font-bold">Total Pembayaran</span>
                <span class="font-headline-md text-2xl text-primary font-extrabold">{{ formatRupiah(finalTotal) }}</span>
              </div>
            </div>

            <button
              type="submit"
              :disabled="isSubmitting"
              class="w-full py-3.5 px-6 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-label-lg font-bold shadow-[0_4px_24px_rgba(0,242,254,0.35)] hover:brightness-110 transition-all flex items-center justify-center gap-2 disabled:opacity-60"
            >
              <span class="material-symbols-outlined text-[20px]">{{ isSubmitting ? 'progress_activity' : 'lock' }}</span>
              <span>{{ isSubmitting ? 'Membuat Invoice...' : 'Buat Pesanan & Bayar Sekarang' }}</span>
            </button>

            <p class="text-[11px] text-outline text-center">
              Dengan melanjutkan, Anda menyetujui ketentuan lisensi produk digital AsetLab.
            </p>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useCart, saveRecentInvoice } from '@/stores/cart';
import { useAuth } from '@/stores/auth';
import { CouponsApi, CheckoutApi } from '@/services/api';
import { formatRupiah } from '@/data/mockData';

const router = useRouter();
const route = useRoute();
const { items, subtotal, removeFromCart, clearCart } = useCart();
const { user, isAuthenticated } = useAuth();

const buyer = ref({
  name: user.value?.name || '',
  email: user.value?.email || '',
  whatsapp: (user.value?.whatsapp || '').replace(/^62/, ''),
});

const paymentMethods = [
  { id: 'qris', label: 'QRIS Dinamis', badge: 'Termurah & Instan', desc: 'Scan via GoPay, OVO, DANA, ShopeePay, BCA/Mandiri Mobile' },
  { id: 'bca_va', label: 'BCA Virtual Account', badge: 'Otomatis', desc: 'Verifikasi otomatis 24 jam via m-BCA / KlikBCA' },
  { id: 'mandiri_va', label: 'Mandiri Virtual Account', badge: '', desc: 'Bayar mudah lewat Livin by Mandiri' },
  { id: 'bri_va', label: 'BRI / BNI Virtual Account', badge: '', desc: 'Transfer virtual account bank Himbara' },
];

const selectedPaymentMethod = ref('QRIS Dinamis');
const couponInput = ref('');
const appliedCoupon = ref(null);
const discountAmount = ref(0);
const couponMessage = ref('');
const checkingCoupon = ref(false);
const isSubmitting = ref(false);
const errorMessage = ref('');

const finalTotal = computed(() => Math.max(0, subtotal.value - discountAmount.value));

async function applyCoupon() {
  const code = couponInput.value.trim().toUpperCase();
  if (!code) return;
  checkingCoupon.value = true;
  couponMessage.value = '';
  try {
    const res = await CouponsApi.validate(code, subtotal.value);
    if (res?.status === 'success' && res?.data?.valid) {
      appliedCoupon.value = res.data.coupon;
      discountAmount.value = Number(res.data.discount_amount || 0);
      couponMessage.value = `Kupon ${code} berhasil diterapkan! Hemat ${formatRupiah(discountAmount.value)}.`;
    }
  } catch (err) {
    // Fallback validasi demo lokal jika backend PHP sedang offline saat dev Vite
    if (!err?.status && (code === 'LAUNCH20' || code === 'HEMAT50K')) {
      const disc = code === 'LAUNCH20' ? Math.round(subtotal.value * 0.2) : Math.min(subtotal.value, 50000);
      appliedCoupon.value = { code };
      discountAmount.value = disc;
      couponMessage.value = `Kupon ${code} diterapkan (mode dev lokal): hemat ${formatRupiah(disc)}.`;
    } else {
      appliedCoupon.value = null;
      discountAmount.value = 0;
      couponMessage.value = err?.message || 'Kode kupon tidak valid.';
    }
  } finally {
    checkingCoupon.value = false;
  }
}

async function submitCheckout() {
  errorMessage.value = '';
  if (items.value.length === 0) return;

  isSubmitting.value = true;
  try {
    const cleanWa = buyer.value.whatsapp.replace(/[^0-9]/g, '').replace(/^0/, '');
    const payload = {
      buyer_name: buyer.value.name.trim(),
      buyer_email: buyer.value.email.trim().toLowerCase(),
      buyer_whatsapp: cleanWa ? `62${cleanWa}` : '',
      payment_method: selectedPaymentMethod.value,
      coupon_code: appliedCoupon.value?.code || '',
      product_ids: items.value.map((i) => i.id),
    };

    const res = await CheckoutApi.create(payload);
    if (res?.status === 'success' && res?.data) {
      const order = res.data;
      saveRecentInvoice(order);
      clearCart();
      router.push({
        name: 'Invoice',
        params: { invoice: order.invoice_number },
        query: { token: order.access_token },
      });
    }
  } catch (err) {
    errorMessage.value = err?.message || 'Gagal membuat pesanan. Pastikan backend API aktif.';
  } finally {
    isSubmitting.value = false;
  }
}

onMounted(() => {
  if (typeof route.query.coupon === 'string' && route.query.coupon.trim()) {
    couponInput.value = route.query.coupon.trim().toUpperCase();
    applyCoupon();
  }
});
</script>
