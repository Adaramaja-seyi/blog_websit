<template>
  <div v-if="post">
    <h2>{{ post.title }}</h2>
    <p><strong>Status:</strong> {{ post.is_published ? 'Published' : 'Draft' }}</p>
    <p><strong>Created:</strong> {{ formatDate(post.created_at) }}</p>
    <div class="mb-3">
      <strong>Content:</strong>
      <div class="border p-3">{{ post.content }}</div>
    </div>
    <router-link class="btn btn-secondary" :to="{ name: 'posts' }"
      >Back to Posts</router-link
    >
  </div>
  <div v-else>
    <p>Loading...</p>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "PostDetail",
  data() {
    return {
      post: null,
    };
  },
  created() {
    this.fetchPost();
  },
  methods: {
    async fetchPost() {
      const token = localStorage.getItem("token");
      const res = await axios.get(
        `http://localhost:8000/api/posts/${this.$route.params.id}`,
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.post = res.data.data || res.data;
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
  },
};
</script>
