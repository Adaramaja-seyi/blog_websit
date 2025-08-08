<template>
  <div class="post-list">
    <div v-if="loading" class="text-center py-5">
      <div class="spinner"></div>
      <p>Loading posts...</p>
    </div>
    <div v-else-if="error" class="text-center py-5">
      <i class="fas fa-exclamation-triangle"></i>
      <p class="text-danger">{{ error }}</p>
      <button @click="fetchPosts" class="btn btn-primary">Retry</button>
    </div>
    <div v-else-if="postsToDisplay.length === 0" class="text-center py-5">
      <h4>No posts found</h4>
      <p class="text-muted">Be the first to create a post!</p>
      <router-link v-if="isAuthenticated" to="/dashboard/posts/create" class="btn btn-primary">
        Create Post
      </router-link>
    </div>
    <div v-else>
      <PostItem
        v-for="post in postsToDisplay"
        :key="post.id"
        :post="post"
        @edit="$emit('edit', post)"
      />
    </div>
  </div>
</template>

<script>
import PostItem from './PostItem.vue';
import api from '@/services/api';

export default {
  name: 'PostList',
  components: { PostItem },
  props: {
    posts: {
      type: Array,
      required: false, // Made optional
      default: () => [],
    },
  },
  data() {
    return {
      localPosts: [],
      loading: false,
      error: null,
    };
  },
  computed: {
    isAuthenticated() {
      return !!localStorage.getItem('token'); // Match router's auth check
    },
    postsToDisplay() {
      return this.posts.length ? this.posts : this.localPosts; // Use prop if provided, else localPosts
    },
  },
  async created() {
    if (!this.posts.length) {
      await this.fetchPosts(); // Fetch posts if prop not provided
    }
  },
  methods: {
    async fetchPosts() {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.getRecentPosts(); // Falls back to /api/posts
        this.localPosts = response.data.posts || [];
      } catch (error) {
        console.error('Error fetching posts:', error);
        this.error = 'Failed to load posts. Please try again.';
        this.localPosts = [];
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.post-list {
  margin-bottom: 3rem;
}

.spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #4e73df;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.text-danger {
  color: #e74a3b;
}

.btn-primary {
  background-color: #4e73df;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  text-decoration: none;
}

.btn-primary:hover {
  background-color: #2e59d9;
}

.text-center {
  text-align: center;
}

.text-muted {
  color: #858796;
}

h4 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #4e73df;
}
</style>