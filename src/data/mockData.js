import { ref, watch } from 'vue';
import { ProductsApi, OrdersApi, TransactionsApi } from '@/services/api';
import { isAdmin } from '@/stores/auth';

const DEFAULT_PRODUCTS = [
  {
    id: 'PROD-001',
    slug: 'fintech-crypto-saas-dashboard-ui-kit',
    sku: 'DS-CYBERPULSE-25',
    title: 'Fintech & Crypto SaaS Dashboard UI Kit',
    category: 'UI Kit',
    price: 349000,
    original_price: 520000,
    downloads: 412,
    rating: 4.9,
    status: 'Live',
    version: 'v2.4.0',
    license_type: 'Commercial Agency License',
    description: 'Design system Figma lengkap dengan 240+ komponen terautentikasi token, dark mode Obsidian Cyberpulse, dan dokumentasi auto-layout.',
    highlights: [
      '240+ Komponen Figma Auto-Layout Responsif',
      'Design Token JSON Siap Integrasi Tailwind CSS',
      'Dark & Light Mode Variable Ready',
      'Free Update Versi Minor Selamanya'
    ],
    preview_url: '',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg9T4DTRaBkFvrg4pFnhR2FgqglJ4EpxLOjsVpsi1Fq54VWbRkdS-DNQBw27OBFPqcGKygWzveoIyWU-pyyvPRgVroCHVK_EzXkPn-9Thj7ZksV_eQTkNivC5Y6_65ZhjVQBlquaRBZoJGHxXw4YCx98VAmpdZlpJcpAd9-E9-mobxP1EEp0meRavidw2lJICGSZYNFZE7_fQMC8hfRgkDnWTf6cOy0t0wWxQt0ebR7xqGMgdGOAGopg',
    file_path: null,
    file_name: null,
    file_size: 0,
    has_file: false,
    tags: ['Figma', 'Tailwind', 'SaaS', 'Dark Mode']
  },
  {
    id: 'PROD-002',
    slug: 'saas-landing-page-nextjs-tailwind',
    sku: 'TMPL-NEXTJS-LANDING',
    title: 'SaaS Landing Page Next.js & Tailwind',
    category: 'Source Code',
    price: 299000,
    original_price: 450000,
    downloads: 328,
    rating: 4.8,
    status: 'Live',
    version: 'v1.8.2',
    license_type: 'Commercial Developer License',
    description: 'Template modern Next.js 14 App Router dengan optimasi SEO 100/100, komponen modular, dan integrasi analitik siap pakai.',
    highlights: [
      'Next.js App Router + TypeScript Clean Code',
      'Skor Lighthouse SEO & Performa 99+',
      '15+ Section Siap Konversi & Animasi Halus',
      'Dokumentasi Deploy Vercel & VPS Lengkap'
    ],
    preview_url: '',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDEjS290740Qc3P7-bU9o1pLp02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4',
    file_path: null,
    file_name: null,
    file_size: 0,
    has_file: false,
    tags: ['Next.js', 'React', 'Tailwind']
  },
  {
    id: 'PROD-003',
    slug: 'asetlab-design-system-token-library',
    sku: 'DS-TOKEN-LIB-ASET',
    title: 'Asetlab Design System & Token Library',
    category: 'UI Kit',
    price: 499000,
    original_price: 750000,
    downloads: 189,
    rating: 5.0,
    status: 'Live',
    version: 'v3.0.0',
    license_type: 'Studio Unlimited License',
    description: 'Master token library untuk Flutter, React, Vue, dan Tailwind CSS lengkap dengan panduan tipografi dan skala warna.',
    highlights: [
      'Multi-platform Tokens (CSS, Tailwind, Flutter)',
      'Standar Kontras Aksesibilitas WCAG AA',
      'Komponen Form, Tabel & Modal Enterprise'
    ],
    preview_url: '',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD28K8p02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4e7R8s9',
    file_path: null,
    file_name: null,
    file_size: 0,
    has_file: false,
    tags: ['Design System', 'Tokens', 'Figma']
  },
  {
    id: 'PROD-004',
    slug: 'ecommerce-mobile-app-ui-starter',
    sku: 'APP-ECOMMERCE-STARTER',
    title: 'E-Commerce Mobile App UI Starter',
    category: 'UI Kit',
    price: 279000,
    original_price: 420000,
    downloads: 541,
    rating: 4.7,
    status: 'Live',
    version: 'v1.2.0',
    license_type: 'Commercial Single Project',
    description: 'Desain mobile app e-commerce komprehensif dengan 60+ layar mulai dari onboarding, katalog, checkout, hingga tracking paket.',
    highlights: [
      '60+ Layar iOS & Android Siap Handoff',
      'Alur Checkout & Payment Siap Pakai',
      'Prototype Interaktif Figma Terstruktur'
    ],
    preview_url: '',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHCeZo0BiPaq-zAbEFfp48AYriqBbCnJu2PTpOd4Kxnk9PtH3tvMmfdPqpsJVs2HDnsX0uFUiCY7YPnAuBO6wamOZfu_hQkCAZtadSIgVM-JhjdI3DiSkJpNvTI25BdCVWDfPWBWVdUumuQypcEbzeZDUFoUZh_D61XjJJKUtFMWreDUuxX3PKpmHpaNAlnl_ofhEjLxP0RpfN4b-u29NuK0DwYyPHNtPnatNlV5MmVJlceiYzwjmfQ',
    file_path: null,
    file_name: null,
    file_size: 0,
    has_file: false,
    tags: ['Mobile', 'iOS', 'Android']
  },
  {
    id: 'PROD-005',
    slug: 'notion-startup-operating-system-2025',
    sku: 'NOTION-STARTUP-OS',
    title: 'Notion Startup Operating System 2025',
    category: 'Template & OS',
    price: 189000,
    original_price: 299000,
    downloads: 875,
    rating: 4.9,
    status: 'Live',
    version: 'v2.1.0',
    license_type: 'Personal & Team License',
    description: 'Sistem operasional Notion untuk startup, product roadmap, sprint tracking, CRM klien, dan manajemen cashflow.',
    highlights: [
      'Workspace All-in-One Startup & Agency',
      'Template OKR, Sprint Backlog & CRM Klien',
      'Panduan Duplikasi & Setup 5 Menit'
    ],
    preview_url: '',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A',
    file_path: null,
    file_name: null,
    file_size: 0,
    has_file: false,
    tags: ['Notion', 'Productivity', 'Startup']
  },
  {
    id: 'PROD-006',
    slug: '3d-cybernetic-luminescent-icon-pack',
    sku: '3D-CYBER-ICON-PACK',
    title: '3D Cybernetic Luminescent Icon Pack',
    category: '3D & Grafis',
    price: 249000,
    original_price: 390000,
    downloads: 160,
    rating: 4.8,
    status: 'Draft',
    version: 'v1.0.0',
    license_type: 'Commercial Agency License',
    description: 'Koleksi 80 icon 3D format PNG resolusi tinggi transparan & Blender source file yang mudah diubah warnanya.',
    highlights: [
      '80 Icon 3D PNG 3000x3000px Transparan',
      'File Source .blend Tersedia & Editable',
      '3 Sudut Kamera Berbeda Siap Landing Page'
    ],
    preview_url: '',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5PTQIeTVaAT6R-XDADGuRAXzRdY5qLwkE-w4zgfmQhUNvA24iCkA7Aq7jxZUBu9d0QM54WTYmfgOQEeSAoEFx3fUtLK7voMIsF5ZwGua8la5IsqJKtcUe5bWrUe6pRJ7U1zUGVpfeaLXw0hCKvqpguYqqbcY5S9VRGyyB9yJZmoerCmYi_18vjd0whHp2PHDKO6O4CSDRHft0YciAY441DQx1Gc411N_fXlFml-s2HmR-coZUxCUc8Q',
    file_path: null,
    file_name: null,
    file_size: 0,
    has_file: false,
    tags: ['3D', 'Icons', 'Blender']
  }
];

const DEFAULT_ORDERS = [
  {
    id: 'ORD-2025-089',
    client: 'PT FinPay Nusantara',
    clientEmail: 'corporate@finpay.id',
    clientAvatar: '',
    project: 'SaaS Crypto Tracking Platform',
    scope: 'Full-Stack Web App',
    assignee: 'Bramantyo & Rizky',
    budget: 48500000,
    deadline: '2026-10-28',
    progress: 75,
    status: 'Sedang Dikerjakan'
  },
  {
    id: 'ORD-2025-090',
    client: 'KopiKultur Indonesia',
    clientEmail: 'tech@kopikultur.co.id',
    clientAvatar: '',
    project: 'Omnichannel E-Commerce & Loyalty',
    scope: 'Web & API Integration',
    assignee: 'Siti Andini',
    budget: 32000000,
    deadline: '2026-11-05',
    progress: 40,
    status: 'Sedang Dikerjakan'
  },
  {
    id: 'ORD-2025-091',
    client: 'LogistiKu Global',
    clientEmail: 'operation@logistiku.id',
    clientAvatar: '',
    project: 'B2B Fleet Management Dashboard',
    scope: 'UI/UX & Frontend Architecture',
    assignee: 'Bramantyo W.',
    budget: 65000000,
    deadline: '2026-11-12',
    progress: 15,
    status: 'Menunggu Konfirmasi'
  },
  {
    id: 'ORD-2025-092',
    client: 'TechNusa Summit 2026',
    clientEmail: 'event@technusa.id',
    clientAvatar: '',
    project: 'Event Landing Page & Ticketing Gateway',
    scope: 'Landing Page & Payment Hook',
    assignee: 'Tim Frontend',
    budget: 25000000,
    deadline: '2026-10-20',
    progress: 100,
    status: 'Selesai'
  },
  {
    id: 'ORD-2025-093',
    client: 'Nexa Brand Studio',
    clientEmail: 'hello@nexabrand.com',
    clientAvatar: '',
    project: 'Social Media Growth & Creative Automation',
    scope: 'Creative Direction & Ads Strategy',
    assignee: 'Siti Andini',
    budget: 18500000,
    deadline: '2026-10-30',
    progress: 60,
    status: 'Review'
  }
];

function slugifyLocal(text) {
  return String(text || '')
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

function normalizeProduct(p) {
  return {
    ...p,
    slug: p.slug || slugifyLocal(p.title || p.id),
    price: Number(p.price || 0),
    original_price: p.original_price ? Number(p.original_price) : null,
    downloads: Number(p.downloads || 0),
    rating: Number(p.rating || 5.0),
    license_type: p.license_type || 'Commercial Single Project',
    highlights: Array.isArray(p.highlights) ? p.highlights : [],
    tags: Array.isArray(p.tags) ? p.tags : [],
    file_size: Number(p.file_size || 0),
    has_file: Boolean(p.has_file || p.file_path),
  };
}

function loadFromStorage(key, fallback) {
  try {
    const saved = localStorage.getItem(key);
    if (saved) {
      return JSON.parse(saved);
    }
  } catch (e) {
    console.error('Failed to load from localStorage:', e);
  }
  return fallback;
}

export const products = ref(loadFromStorage('asetlab_products', DEFAULT_PRODUCTS).map(normalizeProduct));
export const orders = ref(loadFromStorage('asetlab_orders', DEFAULT_ORDERS));
export const remoteSyncError = ref('');

if (typeof window !== 'undefined') {
  watch(products, (val) => {
    try {
      localStorage.setItem('asetlab_products', JSON.stringify(val));
    } catch (e) {
      console.error(e);
    }
  }, { deep: true });

  watch(orders, (val) => {
    try {
      localStorage.setItem('asetlab_orders', JSON.stringify(val));
    } catch (e) {
      console.error(e);
    }
  }, { deep: true });
}

// CRUD Operations: Produk Digital (API-first dengan fallback dev lokal)
export async function addProduct(newProduct) {
  const payload = normalizeProduct({
    id: newProduct.id || `PROD-${Date.now().toString().slice(-6)}`,
    slug: newProduct.slug || slugifyLocal(newProduct.title),
    sku: newProduct.sku || `SKU-${Date.now().toString().slice(-6)}`,
    title: newProduct.title,
    category: newProduct.category || 'UI Kit',
    price: Number(newProduct.price) || 0,
    original_price: newProduct.original_price ? Number(newProduct.original_price) : null,
    downloads: 0,
    rating: 5.0,
    status: newProduct.status || 'Live',
    version: newProduct.version || 'v1.0.0',
    license_type: newProduct.license_type || 'Commercial Single Project',
    description: newProduct.description || '',
    highlights: Array.isArray(newProduct.highlights) ? newProduct.highlights : [],
    preview_url: newProduct.preview_url || '',
    image: newProduct.image || DEFAULT_PRODUCTS[0].image,
    file_path: newProduct.file_path || null,
    file_name: newProduct.file_name || null,
    file_size: Number(newProduct.file_size || 0),
    tags: Array.isArray(newProduct.tags) && newProduct.tags.length > 0 ? newProduct.tags : [newProduct.category || 'UI Kit'],
  });

  try {
    const res = await ProductsApi.create(payload);
    const created = normalizeProduct(res?.data || payload);
    products.value.unshift(created);
    return { product: created, synced: true };
  } catch (err) {
    // Jika server merespons 4xx (validasi/auth), lempar error agar admin tahu!
    if (err?.status && err.status >= 400 && err.status < 500) {
      throw err;
    }
    products.value.unshift(payload);
    return { product: payload, synced: false, warning: err?.message };
  }
}

export async function updateProduct(id, updatedFields) {
  const index = products.value.findIndex(p => p.id === id);
  if (index === -1) return null;

  try {
    const res = await ProductsApi.update(id, updatedFields);
    const updated = normalizeProduct(res?.data || { ...products.value[index], ...updatedFields });
    products.value[index] = updated;
    return { product: updated, synced: true };
  } catch (err) {
    if (err?.status && err.status >= 400 && err.status < 500) {
      throw err;
    }
    const merged = normalizeProduct({ ...products.value[index], ...updatedFields });
    products.value[index] = merged;
    return { product: merged, synced: false, warning: err?.message };
  }
}

export async function deleteProduct(id) {
  const index = products.value.findIndex(p => p.id === id);
  if (index === -1) return null;

  try {
    await ProductsApi.delete(id);
    const deleted = products.value.splice(index, 1)[0];
    return { product: deleted, synced: true };
  } catch (err) {
    if (err?.status && err.status >= 400 && err.status < 500) {
      throw err;
    }
    const deleted = products.value.splice(index, 1)[0];
    return { product: deleted, synced: false, warning: err?.message };
  }
}

// CRUD Operations: Projek / Pesanan
export function addOrder(newOrder) {
  const id = `ORD-2026-${String(orders.value.length + 95).padStart(3, '0')}`;
  const order = {
    id,
    client: newOrder.client,
    clientEmail: newOrder.clientEmail || 'client@company.id',
    clientAvatar: newOrder.clientAvatar || '',
    project: newOrder.project,
    scope: newOrder.scope || 'Custom Web Development',
    assignee: newOrder.assignee || 'Tim Arsitek AsetLab',
    budget: Number(newOrder.budget) || 0,
    deadline: newOrder.deadline || new Date().toISOString().slice(0, 10),
    progress: Number(newOrder.progress) || 10,
    status: newOrder.status || 'Menunggu Konfirmasi'
  };
  orders.value.unshift(order);
  OrdersApi.create(order).catch(() => {});
  return order;
}

export function updateOrder(id, updatedFields) {
  const index = orders.value.findIndex(o => o.id === id);
  if (index !== -1) {
    orders.value[index] = { ...orders.value[index], ...updatedFields };
    OrdersApi.update(id, updatedFields).catch(() => {});
    return orders.value[index];
  }
  return null;
}

export function deleteOrder(id) {
  const index = orders.value.findIndex(o => o.id === id);
  if (index !== -1) {
    const deleted = orders.value.splice(index, 1)[0];
    OrdersApi.delete(id).catch(() => {});
    return deleted;
  }
  return null;
}

/**
 * Sinkronisasi data dari database DomaiNesia jika backend API tersedia
 */
export async function syncWithRemoteDatabase() {
  remoteSyncError.value = '';
  try {
    const params = isAdmin.value ? { scope: 'admin' } : {};
    const resProducts = await ProductsApi.getAll(params);
    if (resProducts && Array.isArray(resProducts.data) && resProducts.data.length > 0) {
      products.value = resProducts.data.map(normalizeProduct);
    }
  } catch (e) {
    // Backend offline / mode lokal dev
  }

  if (isAdmin.value) {
    try {
      const resOrders = await OrdersApi.getAll();
      if (resOrders && Array.isArray(resOrders.data)) {
        orders.value = resOrders.data;
      }
    } catch (e) {
      // Backend offline / mode lokal dev
    }

    try {
      const resTrx = await TransactionsApi.getAll();
      if (resTrx && Array.isArray(resTrx.data)) {
        transactions.value = resTrx.data;
      }
    } catch (e) {
      // Backend offline / mode lokal dev
    }
  }
}

if (typeof window !== 'undefined') {
  syncWithRemoteDatabase();
}

export const transactions = ref([
  {
    id: 'TRX-94821',
    refInvoice: 'INV/20261022/ASET/9041',
    date: '22 Okt 2026, 14:32 WIB',
    client: 'PT FinPay Nusantara',
    category: 'Termin II (Milestone Proyek)',
    method: 'BCA Virtual Account',
    amount: 24250000,
    fee_amount: 4000,
    net_amount: 24246000,
    status: 'Berhasil'
  },
  {
    id: 'TRX-94820',
    refInvoice: 'INV/20261022/DIGI/1049',
    date: '22 Okt 2026, 12:15 WIB',
    client: 'Budi Santoso (Studio Dev)',
    category: 'Pembelian Lisensi UI Kit',
    method: 'QRIS Dinamis',
    amount: 349000,
    fee_amount: 2443,
    net_amount: 346557,
    status: 'Berhasil'
  },
  {
    id: 'TRX-94819',
    refInvoice: 'INV/20261021/DIGI/1048',
    date: '21 Okt 2026, 19:40 WIB',
    client: 'Dian Permana',
    category: 'Next.js Landing Template',
    method: 'Midtrans Mandiri',
    amount: 299000,
    fee_amount: 4000,
    net_amount: 295000,
    status: 'Berhasil'
  },
  {
    id: 'TRX-94818',
    refInvoice: 'INV/20261021/ASET/9040',
    date: '21 Okt 2026, 16:05 WIB',
    client: 'LogistiKu Global',
    category: 'DP 30% Perancangan Sistem',
    method: 'Corporate Escrow',
    amount: 19500000,
    fee_amount: 0,
    net_amount: 19500000,
    status: 'Menunggu'
  },
  {
    id: 'TRX-94817',
    refInvoice: 'INV/20261020/DIGI/1047',
    date: '20 Okt 2026, 11:22 WIB',
    client: 'Ahmad Fauzi',
    category: 'Notion Startup OS 2025',
    method: 'GoPay / QRIS',
    amount: 189000,
    fee_amount: 1323,
    net_amount: 187677,
    status: 'Berhasil'
  }
]);

export function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(Number(number || 0));
}

export function formatFileSize(bytes) {
  const b = Number(bytes || 0);
  if (b <= 0) return '0 B';
  const units = ['B', 'KB', 'MB', 'GB'];
  const i = Math.min(units.length - 1, Math.floor(Math.log(b) / Math.log(1024)));
  return `${(b / Math.pow(1024, i)).toFixed(i === 0 ? 0 : 1)} ${units[i]}`;
}
