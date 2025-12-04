/**
 * ============================================
 * API HELPER - SIPALINGKOPI
 * ============================================
 * 
 * Helper functions untuk REST API calls
 * Menggunakan fetch API dengan token authentication
 */

// Get API token from localStorage or meta tag
function getApiToken() {
    return localStorage.getItem('api_token') || '';
}

// Set API token
function setApiToken(token) {
    localStorage.setItem('api_token', token);
}

// Remove API token
function removeApiToken() {
    localStorage.removeItem('api_token');
}

// Get CSRF token from meta tag
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

// Base API URL
const API_BASE = '/api';

/**
 * Generic API request function
 */
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE}${endpoint}`;
    
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
    };

    const token = getApiToken();
    if (token) {
        defaultHeaders['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...options.headers,
        },
    };

    try {
        const response = await fetch(url, config);
        const data = await response.json();

        if (!response.ok) {
            throw { status: response.status, data };
        }

        return data;
    } catch (error) {
        if (error.status === 401) {
            // Unauthorized - redirect to login
            removeApiToken();
            window.location.href = '/login';
        }
        throw error;
    }
}

/**
 * API Methods
 */
const api = {
    // GET request
    get: (endpoint) => apiRequest(endpoint, { method: 'GET' }),

    // POST request
    post: (endpoint, data) => apiRequest(endpoint, {
        method: 'POST',
        body: JSON.stringify(data),
    }),

    // PUT request
    put: (endpoint, data) => apiRequest(endpoint, {
        method: 'PUT',
        body: JSON.stringify(data),
    }),

    // DELETE request
    delete: (endpoint) => apiRequest(endpoint, { method: 'DELETE' }),
};

// ============================================
// AUTH API
// ============================================
const authApi = {
    login: async (email, password) => {
        const response = await api.post('/auth/login', { email, password });
        if (response.data?.token) {
            setApiToken(response.data.token);
        }
        return response;
    },

    logout: async () => {
        const response = await api.post('/auth/logout');
        removeApiToken();
        return response;
    },

    me: () => api.get('/auth/me'),
};

// ============================================
// MENU API
// ============================================
const menuApi = {
    getAll: (params = '') => api.get(`/menus${params ? '?' + params : ''}`),
    getAvailable: () => api.get('/menus/available'),
    getOne: (id) => api.get(`/menus/${id}`),
    create: (data) => api.post('/menus', data),
    update: (id, data) => api.put(`/menus/${id}`, data),
    delete: (id) => api.delete(`/menus/${id}`),
};

// ============================================
// CATEGORY API
// ============================================
const categoryApi = {
    getAll: () => api.get('/categories'),
    getOne: (id) => api.get(`/categories/${id}`),
    create: (data) => api.post('/categories', data),
    update: (id, data) => api.put(`/categories/${id}`, data),
    delete: (id) => api.delete(`/categories/${id}`),
};

// ============================================
// USER API
// ============================================
const userApi = {
    getAll: (params = '') => api.get(`/users${params ? '?' + params : ''}`),
    getOne: (id) => api.get(`/users/${id}`),
    create: (data) => api.post('/users', data),
    update: (id, data) => api.put(`/users/${id}`, data),
    delete: (id) => api.delete(`/users/${id}`),
};

// ============================================
// ORDER API
// ============================================
const orderApi = {
    getAll: (params = '') => api.get(`/orders${params ? '?' + params : ''}`),
    getKitchen: () => api.get('/orders/kitchen'),
    getHistory: () => api.get('/orders/history'),
    getOne: (id) => api.get(`/orders/${id}`),
    create: (data) => api.post('/orders', data),
    updateStatus: (id, status) => api.put(`/orders/${id}/status`, { status }),
};

// ============================================
// STOCK API
// ============================================
const stockApi = {
    getAll: (params = '') => api.get(`/stock${params ? '?' + params : ''}`),
    toggleAvailability: (menuId) => api.post(`/stock/${menuId}/toggle`),
    updateStock: (menuId, stock) => api.post(`/stock/${menuId}/update`, { stock }),
};

// ============================================
// DASHBOARD API
// ============================================
const dashboardApi = {
    getData: (params = '') => api.get(`/dashboard${params ? '?' + params : ''}`),
};

// ============================================
// REPORT API
// ============================================
const reportApi = {
    getData: (params = '') => api.get(`/reports${params ? '?' + params : ''}`),
};

// Export for use in other files
window.api = api;
window.authApi = authApi;
window.menuApi = menuApi;
window.categoryApi = categoryApi;
window.userApi = userApi;
window.orderApi = orderApi;
window.stockApi = stockApi;
window.dashboardApi = dashboardApi;
window.reportApi = reportApi;
window.getApiToken = getApiToken;
window.setApiToken = setApiToken;
window.removeApiToken = removeApiToken;
