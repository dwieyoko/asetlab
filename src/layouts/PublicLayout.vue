<template>
  <div class="min-h-screen flex flex-col bg-background text-on-surface">
    <!-- Fixed Navigation Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-xl border-b border-outline-variant/30 shadow-[0_1px_8px_rgba(0,0,0,0.4)]">
      <div class="h-20 w-full px-margin-mobile md:px-margin flex items-center justify-between">
        <!-- Brand Logo -->
        <div class="flex items-center gap-space-md">
          <BrandLogo to="/" size="md" />
        </div>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-space-lg">
          <router-link 
            to="/" 
            class="transition-colors font-label-lg text-label-lg px-space-md py-space-xs rounded-full"
            :class="$route.path === '/' ? 'bg-surface-container-high text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
          >
            Beranda
          </router-link>
          <router-link 
            to="/layanan" 
            class="transition-colors font-label-lg text-label-lg px-space-md py-space-xs rounded-full"
            :class="$route.path.startsWith('/layanan') ? 'bg-surface-container-high text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
          >
            Layanan
          </router-link>
          <router-link 
            to="/produk" 
            class="transition-colors font-label-lg text-label-lg px-space-md py-space-xs rounded-full"
            :class="$route.path.startsWith('/produk') ? 'bg-surface-container-high text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
          >
            Produk Digital
          </router-link>
          <router-link 
            to="/portofolio" 
            class="transition-colors font-label-lg text-label-lg px-space-md py-space-xs rounded-full"
            :class="$route.path.startsWith('/portofolio') ? 'bg-surface-container-high text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
          >
            Portofolio
          </router-link>
        </nav>

        <!-- Right Action Buttons -->
        <div class="flex items-center gap-space-sm sm:gap-space-md">
          <!-- Login Link -->
          <router-link 
            to="/login" 
            class="hidden md:inline-flex items-center gap-1 px-3.5 py-1.5 rounded-full text-on-surface-variant hover:text-white hover:bg-surface-container font-label-md transition-colors"
          >
            <span class="material-symbols-outlined text-[18px]">lock</span>
            <span>Masuk</span>
          </router-link>

          <!-- Quick Switcher to Backoffice Admin -->
          <router-link 
            to="/admin" 
            class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-surface-container-high hover:bg-surface-container-highest text-secondary text-label-sm font-semibold border border-secondary/20 transition-all hover:scale-105"
            title="Akses Portal Dashboard Admin"
          >
            <span class="material-symbols-outlined text-[16px]">dashboard</span>
            <span>Dashboard</span>
          </router-link>

          <a 
            href="#konsultasi" 
            class="inline-flex items-center justify-center px-space-lg py-space-sm rounded-full bg-gradient-to-r from-primary-container to-secondary text-on-primary font-label-lg text-label-lg font-bold shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:brightness-110 transition-all duration-300"
          >
            Mulai Kolaborasi
          </a>

          <!-- Mobile Menu Button -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="lg:hidden p-2 text-on-surface rounded-lg hover:bg-surface-container"
          >
            <span class="material-symbols-outlined">{{ mobileMenuOpen ? 'close' : 'menu' }}</span>
          </button>
        </div>
      </div>

      <!-- Mobile Dropdown -->
      <div v-if="mobileMenuOpen" class="lg:hidden bg-surface-container-high border-b border-outline-variant/30 px-6 py-4 flex flex-col gap-3">
        <router-link @click="mobileMenuOpen = false" to="/" class="py-2 text-on-surface hover:text-primary">Beranda</router-link>
        <router-link @click="mobileMenuOpen = false" to="/layanan" class="py-2 text-on-surface hover:text-primary">Layanan</router-link>
        <router-link @click="mobileMenuOpen = false" to="/produk" class="py-2 text-on-surface hover:text-primary">Produk Digital</router-link>
        <router-link @click="mobileMenuOpen = false" to="/portofolio" class="py-2 text-on-surface hover:text-primary">Portofolio</router-link>
        <router-link @click="mobileMenuOpen = false" to="/login" class="py-2 text-on-surface hover:text-primary flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">lock</span> Masuk ke Akun
        </router-link>
        <router-link @click="mobileMenuOpen = false" to="/register" class="py-2 text-[#4edea3] hover:underline flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">person_add</span> Daftar Akun Baru
        </router-link>
        <router-link @click="mobileMenuOpen = false" to="/admin" class="py-2 text-secondary font-semibold flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">dashboard</span> Portal Dashboard Admin
        </router-link>
      </div>
    </header>

    <!-- Main View with Top Spacing for Fixed Nav -->
    <main class="flex-1 pt-20 w-full overflow-hidden">
      <router-view />
    </main>

    <!-- Global Footer -->
    <footer class="w-full bg-surface-container-lowest border-t border-outline-variant/20 pt-space-xl pb-space-lg mt-auto">
      <div class="w-full px-margin-mobile md:px-margin">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-space-xl">
          <div class="space-y-space-md">
            <div class="flex items-center gap-space-sm">
              <BrandLogo to="/" size="md" />
            </div>
            <p class="font-body-md text-body-md text-on-surface leading-relaxed">
              Platform akselerasi aset digital dan studio solusi web terdepan di Indonesia. Menghadirkan infrastruktur teknologi masa depan.
            </p>
            <div class="flex items-center gap-2 text-on-surface-variant text-sm">
              <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
              <span>Sistem Operasional Aktif</span>
            </div>
          </div>

          <div class="space-y-space-sm">
            <h4 class="font-label-lg text-label-lg text-primary tracking-wide">Layanan & Produk</h4>
            <ul class="space-y-space-xs">
              <li><router-link to="/layanan" class="font-body-md text-body-md text-on-surface hover:text-primary transition-colors">Web Engineering</router-link></li>
              <li><router-link to="/layanan" class="font-body-md text-body-md text-on-surface hover:text-primary transition-colors">Social Media Growth</router-link></li>
              <li><router-link to="/produk" class="font-body-md text-body-md text-on-surface hover:text-primary transition-colors">Aset Digital Eksklusif</router-link></li>
              <li><router-link to="/produk" class="font-body-md text-body-md text-on-surface hover:text-primary transition-colors">UI/UX Architecture</router-link></li>
            </ul>
          </div>

          <div class="space-y-space-sm">
            <h4 class="font-label-lg text-label-lg text-primary tracking-wide">Portal Akses</h4>
            <ul class="space-y-space-xs">
              <li><router-link to="/portofolio" class="font-body-md text-body-md text-on-surface hover:text-primary transition-colors">Portofolio Klien</router-link></li>
              <li><router-link to="/admin" class="font-body-md text-body-md text-secondary hover:underline transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">admin_panel_settings</span> Dashboard Overview</router-link></li>
              <li><router-link to="/admin/pesanan" class="font-body-md text-body-md text-on-surface hover:text-primary transition-colors">Manajemen Proyek</router-link></li>
              <li><router-link to="/admin/transaksi" class="font-body-md text-body-md text-on-surface hover:text-primary transition-colors">Laporan Finansial</router-link></li>
            </ul>
          </div>

          <div class="space-y-space-sm">
            <h4 class="font-label-lg text-label-lg text-primary tracking-wide">Berlangganan Newsletter</h4>
            <p class="font-body-sm text-body-sm text-on-surface">Dapatkan update aset digital terkurasi dan tren teknologi mingguan langsung di inbox Anda.</p>
            <form @submit.prevent="subscribeNewsletter" class="flex items-center gap-space-xs pt-space-xs">
              <input 
                v-model="newsletterEmail" 
                type="email" 
                required
                placeholder="Alamat email Anda..." 
                class="w-full bg-surface-container px-space-md py-space-sm rounded-full font-body-sm text-body-sm text-on-surface border border-outline-variant/30 focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container"
              />
              <button 
                type="submit" 
                class="px-space-md py-space-sm rounded-full bg-primary-container text-on-primary font-label-md text-label-md font-bold hover:brightness-110 transition-all shrink-0"
              >
                {{ subscribed ? 'Terkirim ✓' : 'Kirim' }}
              </button>
            </form>
          </div>
        </div>

        <div class="pt-space-lg border-t border-outline-variant/15 flex flex-col sm:flex-row items-center justify-between gap-space-md text-on-surface font-body-sm text-body-sm">
          <span>© 2026 asetlab.id. Seluruh hak cipta dilindungi.</span>
          <span class="text-on-surface-variant flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-primary-container"></span>
            Didesain dengan presisi Obsidian Cyberpulse.
          </span>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import BrandLogo from '@/components/BrandLogo.vue';

const mobileMenuOpen = ref(false);
const newsletterEmail = ref('');
const subscribed = ref(false);

function subscribeNewsletter() {
  if (newsletterEmail.value) {
    subscribed.value = true;
    setTimeout(() => {
      newsletterEmail.value = '';
      subscribed.value = false;
    }, 3000);
  }
}
</script>
