import { ref, computed } from 'vue';
import { AuthApi } from '@/services/api';

const TOKEN_KEY = 'asetlab_auth_token';
const USER_KEY = 'asetlab_auth_user';

function loadUser() {
  try {
    const raw = localStorage.getItem(USER_KEY);
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

function loadToken() {
  try {
    return localStorage.getItem(TOKEN_KEY) || '';
  } catch {
    return '';
  }
}

export const authToken = ref(loadToken());
export const authUser = ref(loadUser());
export const authChecked = ref(false);

export const isAuthenticated = computed(() => Boolean(authToken.value && authUser.value));
export const isAdmin = computed(() => isAuthenticated.value && authUser.value?.role === 'admin');
export const isClient = computed(() => isAuthenticated.value && authUser.value?.role === 'client');

export function setSession(token, user) {
  authToken.value = token || '';
  authUser.value = user || null;
  try {
    if (token) {
      localStorage.setItem(TOKEN_KEY, token);
    } else {
      localStorage.removeItem(TOKEN_KEY);
    }
    if (user) {
      localStorage.setItem(USER_KEY, JSON.stringify(user));
    } else {
      localStorage.removeItem(USER_KEY);
    }
  } catch (e) {
    console.error('Gagal menyimpan sesi lokal:', e);
  }
}

export function clearSession() {
  setSession('', null);
}

export async function login(email, password, remember = false) {
  const res = await AuthApi.login(email, password, remember);
  if (res?.status === 'success' && res?.token && res?.user) {
    setSession(res.token, res.user);
    authChecked.value = true;
    return res;
  }
  throw new Error(res?.message || 'Login gagal');
}

export async function register({ name, email, whatsapp, password }) {
  const res = await AuthApi.register({ name, email, whatsapp, password });
  if (res?.status === 'success' && res?.token && res?.user) {
    setSession(res.token, res.user);
    authChecked.value = true;
    return res;
  }
  throw new Error(res?.message || 'Registrasi gagal');
}

export async function verifySession() {
  if (!authToken.value) {
    authChecked.value = true;
    return null;
  }
  try {
    const res = await AuthApi.me();
    if (res?.status === 'success' && res?.user) {
      setSession(authToken.value, res.user);
      authChecked.value = true;
      return res.user;
    }
    clearSession();
    authChecked.value = true;
    return null;
  } catch (err) {
    // Jika 401/403 hapus sesi; jika backend offline di dev lokal pertahankan sesi sementara
    if (err?.status === 401 || err?.status === 403) {
      clearSession();
    }
    authChecked.value = true;
    return authUser.value;
  }
}

export function logout() {
  clearSession();
}

// Dengarkan event 401 global dari api.js
if (typeof window !== 'undefined') {
  window.addEventListener('asetlab:unauthorized', () => {
    clearSession();
  });
}

export function useAuth() {
  return {
    token: authToken,
    user: authUser,
    isAuthenticated,
    isAdmin,
    isClient,
    login,
    register,
    logout,
    verifySession,
  };
}
