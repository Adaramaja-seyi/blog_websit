import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api',
    withCredentials: true
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
        return apiClient.post('/create_users', data);
    },
    login(data) {
        return apiClient.post('/login', data);
    },
    logout() {
        return apiClient.post('/logout');
    },

    getUserData() {
        return apiClient.get('/user_data');
    },

    updateProfile(data, config = {}) {
        return apiClient.post('/update_profile', data, config);
    },

    getPosts() {
        return apiClient.get('/posts');
    },
    createPost(data) {
        const postData = {
            title: data.title,
            content: data.content,
            is_published: Boolean(data.is_published)
        };
        return apiClient.post('/posts', postData);
    },
    getPost(id) {
        return apiClient.get(`/posts/${id}`);
    },
    updatePost(id, data) {
        const postData = {
            title: data.title,
            content: data.content,
            is_published: Boolean(data.is_published)
        };
        return apiClient.put(`/posts/${id}`, postData);
    },
    deletePost(id) {
        return apiClient.delete(`/posts/${id}`);
    },
    // New methods for Dashboard.vue
    getDashboardStats(params) {
        return apiClient.get('/dashboard/stats', { params });
    },
    getRecentPosts() {
        return apiClient.get('/posts/recent');
    },
    getRecentComments() {
        return apiClient.get('/comments/recent');
    },
};
