<template>
  <div>
    <h2>All Posts</h2>
    <router-link class="btn btn-primary mb-3" :to="{ name: 'post-create' }"
      >Create New Post</router-link
    >
    <table class="table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="post in posts" :key="post.id">
          <td>{{ post.title }}</td>
          <td>{{ post.is_published ? 'Published' : 'Draft' }}</td>
          <td>{{ formatDate(post.created_at) }}</td>
          <td>
            <router-link
              :to="{ name: 'post-detail', params: { id: post.id } }"
              class="btn btn-sm btn-info"
              >View</router-link
            >
            <router-link
              :to="{ name: 'post-edit', params: { id: post.id } }"
              class="btn btn-sm btn-warning mx-1"
              >Edit</router-link
            >
            <button class="btn btn-sm btn-danger" @click="deletePost(post.id)">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import api from "../../services/api";
export default {
  name: "PostList",
  data() {
    return {
      posts: [],
    };
  },
  created() {
    this.fetchPosts();
  },
  methods: {
    async fetchPosts() {
      try {
        const res = await api.getPosts();
        this.posts = res.data.data || res.data || [];
      } catch (e) {
        this.posts = [];
      }
    },
    async deletePost(id) {
      if (!confirm("Are you sure you want to delete this post?")) return;
      try {
        await api.deletePost(id);
        this.fetchPosts();
      } catch (e) {
        alert("Failed to delete post");
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
  },
};
</script>
