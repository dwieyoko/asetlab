import { createRouter, createWebHistory } from 'vue-router';
import PublicLayout from '@/layouts/PublicLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

import HomeView from '@/views/public/HomeView.vue';
import ServicesView from '@/views/public/ServicesView.vue';
import ProductsView from '@/views/public/ProductsView.vue';
import PortfolioView from '@/views/public/PortfolioView.vue';

import DashboardOverviewView from '@/views/admin/DashboardOverviewView.vue';
import OrdersManagementView from '@/views/admin/OrdersManagementView.vue';
import ProductsManagementView from '@/views/admin/ProductsManagementView.vue';
import TransactionsView from '@/views/admin/TransactionsView.vue';

import LoginView from '@/views/auth/LoginView.vue';
import RegisterView from '@/views/auth/RegisterView.vue';

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
        path: 'portofolio',
        name: 'Portfolio',
        component: PortfolioView,
      },
    ],
  },
  {
    path: '/login',
    alias: '/masuk',
    name: 'Login',
    component: LoginView,
  },
  {
    path: '/register',
    alias: '/daftar',
    name: 'Register',
    component: RegisterView,
  },
  {
    path: '/admin',
    component: AdminLayout,
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

export default router;
