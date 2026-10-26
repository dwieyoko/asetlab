import { ref, watch } from 'vue';

const DEFAULT_PRODUCTS = [
  {
    id: 'PROD-001',
    sku: 'DS-CYBERPULSE-25',
    title: 'Fintech & Crypto SaaS Dashboard UI Kit',
    category: 'UI Kit',
    price: 349000,
    downloads: 412,
    rating: 4.9,
    status: 'Live',
    version: 'v2.4.0',
    description: 'Design system Figma lengkap dengan 240+ komponen terautentikasi token.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg9T4DTRaBkFvrg4pFnhR2FgqglJ4EpxLOjsVpsi1Fq54VWbRkdS-DNQBw27OBFPqcGKygWzveoIyWU-pyyvPRgVroCHVK_EzXkPn-9Thj7ZksV_eQTkNivC5Y6_65ZhjVQBlquaRBZoJGHxXw4YCx98VAmpdZlpJcpAd9-E9-mobxP1EEp0meRavidw2lJICGSZYNFZE7_fQMC8hfRgkDnWTf6cOy0t0wWxQt0ebR7xqGMgdGOAGopg',
    tags: ['Figma', 'Tailwind', 'SaaS', 'Dark Mode']
  },
  {
    id: 'PROD-002',
    sku: 'TMPL-NEXTJS-LANDING',
    title: 'SaaS Landing Page Next.js & Tailwind',
    category: 'Source Code',
    price: 299000,
    downloads: 328,
    rating: 4.8,
    status: 'Live',
    version: 'v1.8.2',
    description: 'Template modern Next.js 14 App Router dengan optimasi SEO 100/100.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDEjS290740Qc3P7-bU9o1pLp02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4',
    tags: ['Next.js', 'React', 'Tailwind']
  },
  {
    id: 'PROD-003',
    sku: 'DS-TOKEN-LIB-ASET',
    title: 'Asetlab Design System & Token Library',
    category: 'UI Kit',
    price: 499000,
    downloads: 189,
    rating: 5.0,
    status: 'Live',
    version: 'v3.0.0',
    description: 'Master token library untuk Flutter, React, dan Tailwind CSS.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD28K8p02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4e7R8s9',
    tags: ['Design System', 'Tokens', 'Figma']
  },
  {
    id: 'PROD-004',
    sku: 'APP-ECOMMERCE-STARTER',
    title: 'E-Commerce Mobile App UI Starter',
    category: 'UI Kit',
    price: 279000,
    downloads: 541,
    rating: 4.7,
    status: 'Live',
    version: 'v1.2.0',
    description: 'Desain mobile app e-commerce komprehensif dengan 60+ layar.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHCeZo0BiPaq-zAbEFfp48AYriqBbCnJu2PTpOd4Kxnk9PtH3tvMmfdPqpsJVs2HDnsX0uFUiCY7YPnAuBO6wamOZfu_hQkCAZtadSIgVM-JhjdI3DiSkJpNvTI25BdCVWDfPWBWVdUumuQypcEbzeZDUFoUZh_D61XjJJKUtFMWreDUuxX3PKpmHpaNAlnl_ofhEjLxP0RpfN4b-u29NuK0DwYyPHNtPnatNlV5MmVJlceiYzwjmfQ',
    tags: ['Mobile', 'iOS', 'Android']
  },
  {
    id: 'PROD-005',
    sku: 'NOTION-STARTUP-OS',
    title: 'Notion Startup Operating System 2025',
    category: 'Template & OS',
    price: 189000,
    downloads: 875,
    rating: 4.9,
    status: 'Live',
    version: 'v2.1.0',
    description: 'Sistem operasional Notion untuk startup, product roadmap, dan sprint.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A',
    tags: ['Notion', 'Productivity', 'Startup']
  },
  {
    id: 'PROD-006',
    sku: '3D-CYBER-ICON-PACK',
    title: '3D Cybernetic Luminescent Icon Pack',
    category: '3D & Grafis',
    price: 249000,
    downloads: 160,
    rating: 4.8,
    status: 'Draft',
    version: 'v1.0.0',
    description: 'Koleksi 80 icon 3D format PNG transparan & Blender source file.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5PTQIeTVaAT6R-XDADGuRAXzRdY5qLwkE-w4zgfmQhUNvA24iCkA7Aq7jxZUBu9d0QM54WTYmfgOQEeSAoEFx3fUtLK7voMIsF5ZwGua8la5IsqJKtcUe5bWrUe6pRJ7U1zUGVpfeaLXw0hCKvqpguYqqbcY5S9VRGyyB9yJZmoerCmYi_18vjd0whHp2PHDKO6O4CSDRHft0YciAY441DQx1Gc411N_fXlFml-s2HmR-coZUxCUc8Q',
    tags: ['3D', 'Icons', 'Blender']
  }
];

const DEFAULT_ORDERS = [
  {
    id: 'ORD-2025-089',
    client: 'PT FinPay Nusantara',
    clientEmail: 'corporate@finpay.id',
    clientAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A',
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
    clientAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5PTQIeTVaAT6R-XDADGuRAXzRdY5qLwkE-w4zgfmQhUNvA24iCkA7Aq7jxZUBu9d0QM54WTYmfgOQEeSAoEFx3fUtLK7voMIsF5ZwGua8la5IsqJKtcUe5bWrUe6pRJ7U1zUGVpfeaLXw0hCKvqpguYqqbcY5S9VRGyyB9yJZmoerCmYi_18vjd0whHp2PHDKO6O4CSDRHft0YciAY441DQx1Gc411N_fXlFml-s2HmR-coZUxCUc8Q',
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
    clientAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCLl94JZ4kGRP67QKvRvoSNxeX1RqtMC9aajgU-lA4ehu8jbKWEBnSmai24xVujV-aJ_voffRYeoXRH8TaUJ0DgUFn7nXCh7rw_xD5RsVa8AWHal2ElGVpZCztupCXtJ9ilugvJmcX7_c6AVizYNYCG4z17mcAYN2zTqZaUXLALBSbV4mPlJGS_bwt4KsAamomqzPRc5cEGBEUoR0Ss2In8OKcn8TtQQ5Xsb5I5X_Tvp-49qDI4WZVa6A',
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
    clientAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDtOZwVf0Ni8WXP6p6oad4fdo2TcWklxZViT4_eggWtrCCo_cfWe-3Wv2wdpU1PsEeMlINBwnesZijA6ONbLPXhdA9ic-3T4RXiuXoFapaeOarKBwTV4W7tjrlwWBGvpM8VG2ruHOwLe3kKULjNCPCEq35-034XQRk0wq3RhYQSJHfEe6ierA3QqPZqxvuOvWQba1qcSz1SgjtHu-NqgfqvREGNu6OFSL_t5fL8QwyZhCzInyhmslMljw',
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
    clientAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHCeZo0BiPaq-zAbEFfp48AYriqBbCnJu2PTpOd4Kxnk9PtH3tvMmfdPqpsJVs2HDnsX0uFUiCY7YPnAuBO6wamOZfu_hQkCAZtadSIgVM-JhjdI3DiSkJpNvTI25BdCVWDfPWBWVdUumuQypcEbzeZDUFoUZh_D61XjJJKUtFMWreDUuxX3PKpmHpaNAlnl_ofhEjLxP0RpfN4b-u29NuK0DwYyPHNtPnatNlV5MmVJlceiYzwjmfQ',
    project: 'Social Media Growth & Creative Automation',
    scope: 'Creative Direction & Ads Strategy',
    assignee: 'Siti Andini',
    budget: 18500000,
    deadline: '2026-10-30',
    progress: 60,
    status: 'Review'
  }
];

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

// Reactive store with localStorage persistence
export const products = ref(loadFromStorage('asetlab_products', DEFAULT_PRODUCTS));
export const orders = ref(loadFromStorage('asetlab_orders', DEFAULT_ORDERS));

// Auto sync to localStorage
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

// CRUD Operations: Produk Digital
export function addProduct(newProduct) {
  const id = `PROD-${String(products.value.length + 1).padStart(3, '0')}`;
  const product = {
    id,
    sku: newProduct.sku || `SKU-${Date.now().toString().slice(-6)}`,
    title: newProduct.title,
    category: newProduct.category || 'UI Kit',
    price: Number(newProduct.price) || 0,
    downloads: 0,
    rating: 5.0,
    status: newProduct.status || 'Live',
    version: newProduct.version || 'v1.0.0',
    description: newProduct.description || '',
    image: newProduct.image || 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg9T4DTRaBkFvrg4pFnhR2FgqglJ4EpxLOjsVpsi1Fq54VWbRkdS-DNQBw27OBFPqcGKygWzveoIyWU-pyyvPRgVroCHVK_EzXkPn-9Thj7ZksV_eQTkNivC5Y6_65ZhjVQBlquaRBZoJGHxXw4YCx98VAmpdZlpJcpAd9-E9-mobxP1EEp0meRavidw2lJICGSZYNFZE7_fQMC8hfRgkDnWTf6cOy0t0wWxQt0ebR7xqGMgdGOAGopg',
    tags: newProduct.tags || [newProduct.category]
  };
  products.value.unshift(product);
  return product;
}

export function updateProduct(id, updatedFields) {
  const index = products.value.findIndex(p => p.id === id);
  if (index !== -1) {
    products.value[index] = { ...products.value[index], ...updatedFields };
    return products.value[index];
  }
  return null;
}

export function deleteProduct(id) {
  const index = products.value.findIndex(p => p.id === id);
  if (index !== -1) {
    const deleted = products.value.splice(index, 1)[0];
    return deleted;
  }
  return null;
}

// CRUD Operations: Projek / Pesanan
export function addOrder(newOrder) {
  const id = `ORD-2026-${String(orders.value.length + 95).padStart(3, '0')}`;
  const order = {
    id,
    client: newOrder.client,
    clientEmail: newOrder.clientEmail || 'client@company.id',
    clientAvatar: newOrder.clientAvatar || 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A',
    project: newOrder.project,
    scope: newOrder.scope || 'Custom Web Development',
    assignee: newOrder.assignee || 'Tim Arsitek AsetLab',
    budget: Number(newOrder.budget) || 0,
    deadline: newOrder.deadline || new Date().toISOString().slice(0, 10),
    progress: Number(newOrder.progress) || 10,
    status: newOrder.status || 'Menunggu Konfirmasi'
  };
  orders.value.unshift(order);
  return order;
}

export function updateOrder(id, updatedFields) {
  const index = orders.value.findIndex(o => o.id === id);
  if (index !== -1) {
    orders.value[index] = { ...orders.value[index], ...updatedFields };
    return orders.value[index];
  }
  return null;
}

export function deleteOrder(id) {
  const index = orders.value.findIndex(o => o.id === id);
  if (index !== -1) {
    const deleted = orders.value.splice(index, 1)[0];
    return deleted;
  }
  return null;
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
    status: 'Berhasil'
  },
  {
    id: 'TRX-94818',
    refInvoice: 'INV/20261021/ASET/9040',
    date: '21 Okt 2026, 16:05 WIB',
    client: 'LogistiKu Global',
    category: 'DP 30% Perancangan Sistem',
    method: 'Corporate Escrow Stripe',
    amount: 19500000,
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
    status: 'Berhasil'
  }
]);

export function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(number);
}
