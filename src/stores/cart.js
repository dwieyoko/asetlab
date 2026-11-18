import { ref, computed, watch } from 'vue';

const CART_STORAGE_KEY = 'asetlab_cart_items';
const RECENT_INVOICES_KEY = 'asetlab_recent_invoices';

function loadCart() {
  try {
    const saved = localStorage.getItem(CART_STORAGE_KEY);
    const parsed = saved ? JSON.parse(saved) : [];
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
}

export const cartItems = ref(loadCart());
export const isCartDrawerOpen = ref(false);

if (typeof window !== 'undefined') {
  watch(
    cartItems,
    (val) => {
      try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(val));
      } catch (e) {
        console.error('Gagal menyimpan keranjang:', e);
      }
    },
    { deep: true }
  );
}

export const cartCount = computed(() => cartItems.value.length);
export const cartSubtotal = computed(() =>
  cartItems.value.reduce((sum, item) => sum + Number(item.price || 0), 0)
);

export function hasInCart(productId) {
  return cartItems.value.some((item) => item.id === productId);
}

export function addToCart(product, { openDrawer = true } = {}) {
  if (!product || !product.id) return false;
  if (!hasInCart(product.id)) {
    cartItems.value.push({
      id: product.id,
      slug: product.slug || product.id,
      sku: product.sku || '',
      title: product.title,
      category: product.category || 'UI Kit',
      version: product.version || 'v1.0.0',
      license_type: product.license_type || 'Commercial Single Project',
      price: Number(product.price || 0),
      original_price: product.original_price ? Number(product.original_price) : null,
      image: product.image || '',
    });
  }
  if (openDrawer) {
    isCartDrawerOpen.value = true;
  }
  return true;
}

export function removeFromCart(productId) {
  cartItems.value = cartItems.value.filter((item) => item.id !== productId);
}

export function clearCart() {
  cartItems.value = [];
}

export function openCartDrawer() {
  isCartDrawerOpen.value = true;
}

export function closeCartDrawer() {
  isCartDrawerOpen.value = false;
}

export function saveRecentInvoice(order) {
  if (!order?.invoice_number) return;
  try {
    const raw = localStorage.getItem(RECENT_INVOICES_KEY);
    const list = raw ? JSON.parse(raw) : [];
    const filtered = list.filter((o) => o.invoice_number !== order.invoice_number);
    filtered.unshift({
      invoice_number: order.invoice_number,
      access_token: order.access_token || '',
      total_amount: order.total_amount,
      status: order.status,
      created_at: order.created_at || new Date().toISOString(),
    });
    localStorage.setItem(RECENT_INVOICES_KEY, JSON.stringify(filtered.slice(0, 15)));
  } catch (e) {
    console.error('Gagal menyimpan riwayat invoice:', e);
  }
}

export function getRecentInvoices() {
  try {
    const raw = localStorage.getItem(RECENT_INVOICES_KEY);
    return raw ? JSON.parse(raw) : [];
  } catch {
    return [];
  }
}

export function useCart() {
  return {
    items: cartItems,
    isOpen: isCartDrawerOpen,
    count: cartCount,
    subtotal: cartSubtotal,
    hasInCart,
    addToCart,
    removeFromCart,
    clearCart,
    openCartDrawer,
    closeCartDrawer,
  };
}
