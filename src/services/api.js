/**
 * AsetLab Platform - API Client Service for DomaiNesia Hosting
 * Endpoint terhubung ke backend PHP MySQL di /api/
 */

const API_BASE = import.meta.env.VITE_API_BASE_URL || '/api';

async function request(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`;
  const config = {
    headers: {
      'Content-Type': 'application/json',
      ...(options.headers || {})
    },
    ...options
  };

  try {
    const response = await fetch(url, config);
    const data = await response.json();
    if (!response.ok) {
      throw new Error(data.message || `HTTP error! status: ${response.status}`);
    }
    return data;
  } catch (error) {
    console.warn(`[API] Gagal request ke ${url}, fallback ke data lokal:`, error.message);
    throw error;
  }
}

// ----------------------------------------------------
// Produk Digital API
// ----------------------------------------------------
export const ProductsApi = {
  async getAll() {
    return request('/products.php');
  },
  async getById(id) {
    return request(`/products.php?id=${encodeURIComponent(id)}`);
  },
  async create(product) {
    return request('/products.php', {
      method: 'POST',
      body: JSON.stringify(product)
    });
  },
  async update(id, product) {
    return request(`/products.php?id=${encodeURIComponent(id)}`, {
      method: 'PUT',
      body: JSON.stringify(product)
    });
  },
  async delete(id) {
    return request(`/products.php?id=${encodeURIComponent(id)}`, {
      method: 'DELETE'
    });
  }
};

// ----------------------------------------------------
// Pesanan & Manajemen Proyek API
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
      body: JSON.stringify(order)
    });
  },
  async update(id, order) {
    return request(`/orders.php?id=${encodeURIComponent(id)}`, {
      method: 'PUT',
      body: JSON.stringify(order)
    });
  },
  async delete(id) {
    return request(`/orders.php?id=${encodeURIComponent(id)}`, {
      method: 'DELETE'
    });
  }
};

// ----------------------------------------------------
// Transaksi Keuangan API
// ----------------------------------------------------
export const TransactionsApi = {
  async getAll() {
    return request('/transactions.php');
  }
};

// ----------------------------------------------------
// Autentikasi API
// ----------------------------------------------------
export const AuthApi = {
  async login(email, password) {
    return request('/auth.php?action=login', {
      method: 'POST',
      body: JSON.stringify({ email, password })
    });
  },
  async register(name, email, password) {
    return request('/auth.php?action=register', {
      method: 'POST',
      body: JSON.stringify({ name, email, password })
    });
  }
};
