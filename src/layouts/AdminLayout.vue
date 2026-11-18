<template>
  <div class="min-h-screen bg-background text-on-surface flex">
    <!-- Sidebar -->
    <aside 
      class="fixed inset-y-0 left-0 z-50 w-72 bg-surface-container-lowest border-r border-outline-variant/30 flex flex-col justify-between py-space-md transition-transform duration-300"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
      <div class="flex flex-col gap-space-md px-space-md">
        <!-- Logo & Brand Header -->
        <div class="flex items-center justify-between px-space-xs py-space-xs">
          <BrandLogo to="/admin" size="sm" subtitle="Admin Console" />
          <button @click="sidebarOpen = false" class="lg:hidden text-outline hover:text-on-surface">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>

        <!-- Quick Search -->
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Cari modul, ID pesanan..." 
            class="w-full bg-surface-container-low pl-9 pr-space-md py-space-xs rounded-full font-body-sm text-body-sm text-on-surface border border-outline-variant/30 focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container"
          />
        </div>

        <!-- Navigation Links -->
        <nav class="flex flex-col gap-1">
          <router-link 
            to="/admin" 
            class="flex items-center gap-space-sm px-space-md py-2.5 rounded-lg transition-all"
            :class="$route.path === '/admin' ? 'bg-surface-container-high text-primary font-bold border-l-2 border-primary-container' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
          >
            <span class="material-symbols-outlined text-[20px]">dashboard</span>
            <span class="font-label-md text-label-md">Overview</span>
          </router-link>

          <router-link 
            to="/admin/pesanan" 
            class="flex items-center gap-space-sm px-space-md py-2.5 rounded-lg transition-all"
            :class="$route.path === '/admin/pesanan' ? 'bg-surface-container-high text-primary font-bold border-l-2 border-primary-container' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
          >
            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
            <span class="font-label-md text-label-md">Pesanan & Proyek</span>
          </router-link>

          <router-link 
            to="/admin/produk" 
            class="flex items-center gap-space-sm px-space-md py-2.5 rounded-lg transition-all"
            :class="$route.path === '/admin/produk' ? 'bg-surface-container-high text-primary font-bold border-l-2 border-primary-container' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
          >
            <span class="material-symbols-outlined text-[20px]">inventory_2</span>
            <span class="font-label-md text-label-md">Produk Digital</span>
          </router-link>

          <router-link 
            to="/admin/transaksi" 
            class="flex items-center gap-space-sm px-space-md py-2.5 rounded-lg transition-all"
            :class="$route.path === '/admin/transaksi' ? 'bg-surface-container-high text-primary font-bold border-l-2 border-primary-container' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
          >
            <span class="material-symbols-outlined text-[20px]">receipt_long</span>
            <span class="font-label-md text-label-md">Transaksi & Keuangan</span>
          </router-link>

          <div class="my-2 border-t border-outline-variant/20"></div>

          <router-link 
            to="/" 
            class="flex items-center gap-space-sm px-space-md py-2.5 rounded-lg text-secondary hover:bg-surface-container transition-all"
          >
            <span class="material-symbols-outlined text-[20px]">open_in_new</span>
            <span class="font-label-md text-label-md font-medium">Lihat Website Utama</span>
          </router-link>
        </nav>
      </div>

      <!-- Bottom Profile Panel -->
      <div class="px-space-md space-y-2">
        <div class="p-space-sm rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-center justify-between gap-2">
          <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-8 h-8 rounded-full bg-primary-container/20 border border-primary-container/40 flex items-center justify-center text-primary-container font-bold text-xs shrink-0">
              {{ adminInitials }}
            </div>
            <div class="flex flex-col min-w-0">
              <span class="font-label-md text-label-md text-on-surface leading-tight font-semibold truncate">{{ user?.name || 'Admin AsetLab' }}</span>
              <span class="font-label-sm text-[11px] text-outline truncate">{{ user?.email || 'admin@asetlab.id' }}</span>
            </div>
          </div>
          <span class="w-2 h-2 rounded-full bg-secondary shrink-0"></span>
        </div>

        <button
          @click="handleLogout"
          type="button"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-surface-container hover:bg-error-container/30 text-on-surface-variant hover:text-error border border-outline-variant/20 transition-all text-xs font-semibold"
        >
          <span class="material-symbols-outlined text-[16px]">logout</span>
          <span>Keluar dari Admin Console</span>
        </button>
      </div>
    </aside>

    <!-- Overlay backdrop for mobile -->
    <div 
      v-if="sidebarOpen" 
      @click="sidebarOpen = false" 
      class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
    ></div>

    <!-- Main Wrapper -->
    <div class="flex-1 lg:pl-72 flex flex-col min-w-0 min-h-screen">
      <!-- Admin Topbar -->
      <header class="sticky top-0 z-30 h-16 bg-surface/80 backdrop-blur-xl border-b border-outline-variant/30 flex items-center justify-between px-space-md lg:px-space-lg">
        <div class="flex items-center gap-space-sm">
          <button 
            @click="sidebarOpen = !sidebarOpen" 
            class="lg:hidden p-2 text-on-surface-variant hover:text-on-surface rounded-lg hover:bg-surface-container"
          >
            <span class="material-symbols-outlined">menu</span>
          </button>
          
          <div class="hidden md:flex items-center gap-2 text-on-surface-variant text-sm">
            <span class="font-label-md text-primary font-bold">AsetLab System</span>
            <span class="text-outline">/</span>
            <span class="capitalize">{{ $route.name || 'Dashboard' }}</span>
          </div>
        </div>

        <div class="flex items-center gap-space-md">
          <!-- System Status Pill -->
          <div class="hidden sm:flex items-center gap-space-xs bg-surface-container px-space-sm py-1 rounded-full border border-outline-variant/30">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
            </span>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Live Real-time</span>
          </div>

          <!-- Notification Bell -->
          <button 
            @click="notifyClicked"
            class="relative p-space-xs text-on-surface-variant hover:text-on-surface transition-colors" 
            type="button"
          >
            <span class="material-symbols-outlined text-[22px]">notifications</span>
            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-primary-container ring-2 ring-surface"></span>
          </button>

          <div class="h-6 w-px bg-outline-variant/40"></div>

          <!-- Direct User Link -->
          <router-link to="/" class="hidden sm:flex items-center gap-1.5 text-xs text-secondary hover:underline">
            <span class="material-symbols-outlined text-[16px]">visibility</span>
            <span>Lihat Toko</span>
          </router-link>
        </div>
      </header>

      <!-- Sub Page Content -->
      <main class="flex-1 w-full bg-background p-space-md lg:p-space-lg">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import BrandLogo from '@/components/BrandLogo.vue';
import { useAuth } from '@/stores/auth';

const router = useRouter();
const { user, logout } = useAuth();

const sidebarOpen = ref(false);
const searchQuery = ref('');

const adminInitials = computed(() => {
  const name = user.value?.name || 'AD';
  return name
    .split(' ')
    .slice(0, 2)
    .map((p) => p[0])
    .join('')
    .toUpperCase();
});

function handleLogout() {
  logout();
  router.push('/login');
}

function notifyClicked() {
  alert('Notifikasi: Seluruh layanan dan gateway pembayaran normal.');
}
</script>
