<template>
  <div>
    <h2>All Comments</h2>
    <ul class="list-group">
      <li v-for="comment in comments" :key="comment.id" class="list-group-item">
        <strong>{{ comment.user?.name || "User" }}</strong
        >:
        {{ comment.content }}
        <span class="text-muted small float-end">{{
          formatDate(comment.created_at)
        }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "CommentList",
  data() {
    return {
      comments: [],
    };
  },
  created() {
    this.fetchComments();
  },
  methods: {
    async fetchComments() {
      const token = localStorage.getItem("token");
      const res = await axios.get("http://localhost:8000/api/comments", {
        headers: { Authorization: `Bearer ${token}` },
      });
      this.comments = res.data.data || res.data;
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
  },
};
</script>
