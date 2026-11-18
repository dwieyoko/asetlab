/**
 * AsetLab Platform - API Client Service (DomaiNesia PHP REST API)
 * Mendukung JWT Bearer Authentication otomatis & FormData upload.
 */

const API_BASE = import.meta.env.VITE_API_BASE_URL || '/api';
const TOKEN_STORAGE_KEY = 'asetlab_auth_token';

export function getStoredToken() {
  try {
    return localStorage.getItem(TOKEN_STORAGE_KEY) || '';
  } catch {
    return '';
  }
}

export async function request(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`;
  const isFormData = typeof FormData !== 'undefined' && options.body instanceof FormData;

  const headers = {
    ...(isFormData ? {} : { 'Content-Type': 'application/json' }),
    ...(options.headers || {}),
  };

  const token = getStoredToken();
  if (token && !headers.Authorization) {
    headers.Authorization = `Bearer ${token}`;
  }

  const config = {
    ...options,
    headers,
  };

  const response = await fetch(url, config);
  let data = null;
  const contentType = response.headers.get('content-type') || '';

  if (contentType.includes('application/json')) {
    data = await response.json();
  } else {
    const text = await response.text();
    try {
      data = JSON.parse(text);
    } catch {
      data = { status: 'error', message: text || `HTTP error ${response.status}` };
    }
  }

  if (!response.ok) {
    const err = new Error(data?.message || `HTTP error! status: ${response.status}`);
    err.status = response.status;
    err.data = data;

    if (response.status === 401 && typeof window !== 'undefined') {
      window.dispatchEvent(new CustomEvent('asetlab:unauthorized'));
    }
    throw err;
  }

  return data;
}

// ----------------------------------------------------
// Autentikasi API
// ----------------------------------------------------
export const AuthApi = {
  async login(email, password, remember = false) {
    return request('/auth.php?action=login', {
      method: 'POST',
      body: JSON.stringify({ email, password, remember }),
    });
  },
  async register({ name, email, whatsapp, password }) {
    return request('/auth.php?action=register', {
      method: 'POST',
      body: JSON.stringify({ name, email, whatsapp, password }),
    });
  },
  async me() {
    return request('/auth.php?action=me');
  },
  async changePassword(currentPassword, newPassword) {
    return request('/auth.php?action=change-password', {
      method: 'POST',
      body: JSON.stringify({
        current_password: currentPassword,
        new_password: newPassword,
      }),
    });
  },
};

// ----------------------------------------------------
// Produk Digital API
// ----------------------------------------------------
export const ProductsApi = {
  async getAll(params = {}) {
    const qs = new URLSearchParams(params).toString();
    return request(`/products.php${qs ? `?${qs}` : ''}`);
  },
  async getById(id) {
    return request(`/products.php?id=${encodeURIComponent(id)}`);
  },
  async getBySlug(slug) {
    return request(`/products.php?slug=${encodeURIComponent(slug)}`);
  },
  async create(product) {
    return request('/products.php', {
      method: 'POST',
      body: JSON.stringify(product),
    });
  },
  async update(id, product) {
    return request(`/products.php?id=${encodeURIComponent(id)}`, {
      method: 'PUT',
      body: JSON.stringify(product),
    });
  },
  async delete(id) {
    return request(`/products.php?id=${encodeURIComponent(id)}`, {
      method: 'DELETE',
    });
  },
};

// ----------------------------------------------------
// Pesanan & Manajemen Proyek Jasa API (Admin)
// ----------------------------------------------------
export const OrdersApi = {
  async getAll() {
    return request('/orders.php');
  },
  async getById(id) {
    return request(`/orders.php?id=${encodeURIComponent(id)}`);
  },
  async create(order) {
    return request('/orders.php', {
      method: 'POST',
      body: JSON.stringify(order),
    });
  },
  async update(id, order) {
    return request(`/orders.php?id=${encodeURIComponent(id)}`, {
      method: 'PUT',
      body: JSON.stringify(order),
    });
  },
  async delete(id) {
    return request(`/orders.php?id=${encodeURIComponent(id)}`, {
      method: 'DELETE',
    });
  },
};

// ----------------------------------------------------
// Transaksi Keuangan API (Admin)
// ----------------------------------------------------
export const TransactionsApi = {
  async getAll() {
    return request('/transactions.php');
  },
};

// ----------------------------------------------------
// Upload Cover Gambar & File Produk Privat API (Admin)
// ----------------------------------------------------
export const UploadApi = {
  async uploadImage(file) {
    const fd = new FormData();
    fd.append('type', 'image');
    fd.append('file', file);
    return request('/upload.php', {
      method: 'POST',
      body: fd,
    });
  },
  async uploadProductFile(file, productId = '') {
    const fd = new FormData();
    fd.append('type', 'product_file');
    fd.append('file', file);
    if (productId) {
      fd.append('product_id', productId);
    }
    return request('/upload.php', {
      method: 'POST',
      body: fd,
    });
  },
};

// ----------------------------------------------------
// Kupon Promo API
// ----------------------------------------------------
export const CouponsApi = {
  async validate(code, subtotal) {
    const qs = new URLSearchParams({
      code: String(code || '').trim(),
      subtotal: String(subtotal || 0),
    }).toString();
    return request(`/coupons.php?${qs}`);
  },
};

// ----------------------------------------------------
// Digital Checkout & Invoice API (Guest & Member)
// ----------------------------------------------------
export const CheckoutApi = {
  async create(payload) {
    return request('/checkout.php', {
      method: 'POST',
      body: JSON.stringify(payload),
    });
  },
  async getInvoice(invoiceNumber, accessToken = '') {
    const params = { invoice: invoiceNumber };
    if (accessToken) {
      params.token = accessToken;
    }
    const qs = new URLSearchParams(params).toString();
    return request(`/checkout.php?${qs}`);
  },
};

// ----------------------------------------------------
// Payment Settlement & Webhook Simulator / Admin Confirm
// ----------------------------------------------------
export const PaymentApi = {
  async simulate(invoice, token, outcome = 'paid') {
    return request('/webhook_payment.php?gateway=simulator', {
      method: 'POST',
      body: JSON.stringify({ invoice, token, outcome }),
    });
  },
  async adminConfirm(invoice, outcome = 'paid', paymentMethod = 'Konfirmasi Manual Admin') {
    return request('/webhook_payment.php?action=admin_confirm', {
      method: 'POST',
      body: JSON.stringify({ invoice, outcome, payment_method: paymentMethod }),
    });
  },
};

// ----------------------------------------------------
// Customer Account & Library API (Authenticated)
// ----------------------------------------------------
export const AccountApi = {
  async getOverview() {
    return request('/account.php');
  },
};

// ----------------------------------------------------
// Dashboard Overview & Consultations API
// ----------------------------------------------------
export const DashboardApi = {
  async getSummary() {
    return request('/dashboard.php');
  },
};

export const ConsultationsApi = {
  async submit(payload) {
    return request('/consultations.php', {
      method: 'POST',
      body: JSON.stringify(payload),
    });
  },
  async getAll() {
    return request('/consultations.php');
  },
};

export function buildSecureDownloadUrl(licenseKey, accessToken = '') {
  const params = new URLSearchParams({ license: licenseKey });
  if (accessToken) {
    params.set('token', accessToken);
  } else {
    const jwt = getStoredToken();
    if (jwt) {
      params.set('jwt', jwt);
    }
  }
  return `${API_BASE}/download.php?${params.toString()}`;
}
