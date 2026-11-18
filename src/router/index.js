import { createRouter, createWebHistory } from 'vue-router';
import PublicLayout from '@/layouts/PublicLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

import HomeView from '@/views/public/HomeView.vue';
import ServicesView from '@/views/public/ServicesView.vue';
import ProductsView from '@/views/public/ProductsView.vue';
import ProductDetailView from '@/views/public/ProductDetailView.vue';
import CheckoutView from '@/views/public/CheckoutView.vue';
import InvoiceView from '@/views/public/InvoiceView.vue';
import PortfolioView from '@/views/public/PortfolioView.vue';

import DashboardOverviewView from '@/views/admin/DashboardOverviewView.vue';
import OrdersManagementView from '@/views/admin/OrdersManagementView.vue';
import ProductsManagementView from '@/views/admin/ProductsManagementView.vue';
import TransactionsView from '@/views/admin/TransactionsView.vue';

import LoginView from '@/views/auth/LoginView.vue';
import RegisterView from '@/views/auth/RegisterView.vue';
import CustomerLibraryView from '@/views/account/CustomerLibraryView.vue';

import { isAuthenticated, isAdmin, authChecked, verifySession } from '@/stores/auth';

const routes = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      {
        path: '',
        name: 'Home',
        component: HomeView,
      },
      {
        path: 'layanan',
        name: 'Services',
        component: ServicesView,
      },
      {
        path: 'produk',
        name: 'Products',
        component: ProductsView,
      },
      {
        path: 'produk/:slug',
        name: 'ProductDetail',
        component: ProductDetailView,
      },
      {
        path: 'checkout',
        name: 'Checkout',
        component: CheckoutView,
      },
      {
        path: 'transaksi/:invoice',
        name: 'Invoice',
        component: InvoiceView,
      },
      {
        path: 'portofolio',
        name: 'Portfolio',
        component: PortfolioView,
      },
      {
        path: 'akun',
        name: 'CustomerLibrary',
        component: CustomerLibraryView,
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/login',
    alias: '/masuk',
    name: 'Login',
    component: LoginView,
    meta: { guestOnly: true },
  },
  {
    path: '/register',
    alias: '/daftar',
    name: 'Register',
    component: RegisterView,
    meta: { guestOnly: true },
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: '',
        name: 'Overview',
        component: DashboardOverviewView,
      },
      {
        path: 'pesanan',
        name: 'Pesanan & Proyek',
        component: OrdersManagementView,
      },
      {
        path: 'produk',
        name: 'Katalog Produk Digital',
        component: ProductsManagementView,
      },
      {
        path: 'transaksi',
        name: 'Transaksi & Keuangan',
        component: TransactionsView,
      },
    ],
  },
  {
    path: '/dashboard',
    redirect: '/admin',
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth' };
    }
    return { top: 0, behavior: 'smooth' };
  },
});

// Global Navigation Guard untuk RBAC (Admin vs Client vs Guest)
router.beforeEach(async (to) => {
  if (!authChecked.value && isAuthenticated.value) {
    await verifySession();
  }

  const requiresAdmin = to.matched.some((r) => r.meta.requiresAdmin);
  const requiresAuth = to.matched.some((r) => r.meta.requiresAuth);
  const guestOnly = to.matched.some((r) => r.meta.guestOnly);

  if (requiresAdmin) {
    if (!isAuthenticated.value) {
      return { name: 'Login', query: { redirect: to.fullPath } };
    }
    if (!isAdmin.value) {
      return { name: 'CustomerLibrary' };
    }
  }

  if (requiresAuth && !isAuthenticated.value) {
    return { name: 'Login', query: { redirect: to.fullPath } };
  }

  if (guestOnly && isAuthenticated.value) {
    return isAdmin.value ? { path: '/admin' } : { path: '/akun' };
  }

  return true;
});

export default router;
