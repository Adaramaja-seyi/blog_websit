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
    
   getUserData() {
        return apiClient.get('/api/user_data');
    },
    updateProfile(data) {
        return apiClient.put('/api/update_profile', data);
    },
    
    getPosts() {
        return apiClient.get('/api/posts');
    },
    createPost(data) {
        const postData = {
            title: data.title,
            content: data.content,
            is_published: Boolean(data.is_published)
        };
        return apiClient.post('/api/posts', postData);
    },
    getPost(id) {
        return apiClient.get(`/api/posts/${id}`);
    },
    updatePost(id, data) {
        const postData = {
            title: data.title,
            content: data.content,
            is_published: Boolean(data.is_published)
        };
        return apiClient.put(`/api/posts/${id}`, postData);
    },
    deletePost(id) {
        return apiClient.delete(`/api/posts/${id}`);
    },
    // New methods for Dashboard.vue
   getDashboardStats(params) {
        return apiClient.get('/api/dashboard/stats', { params });
    },
    getRecentPosts() {
        return apiClient.get('/api/posts/recent');
    },
    getRecentComments() {
        return apiClient.get('/api/comments/recent');
    },
};
