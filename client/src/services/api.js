import axios from 'axios';

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_BASE_URL || 'http://localhost:8000',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
    },
});

// Add token interceptor
apiClient.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default {
    createUsers(data) {
        return apiClient.post('/api/create_users', data);
    },
    login(data) {
        return apiClient.post('/api/login', data);
    },
    logout() {
        return apiClient.post('/api/logout');
    },
    getPosts() {
        return apiClient.get('/api/posts');
    },
    createPost(data) {
        return apiClient.post('/api/posts', data);
    },
    getPost(id) {
        return apiClient.get(`/api/posts/${id}`);
    },
    updatePost(id, data) {
        return apiClient.put(`/api/posts/${id}`, data);
    },
    deletePost(id) {
        return apiClient.delete(`/api/posts/${id}`);
    }
};
