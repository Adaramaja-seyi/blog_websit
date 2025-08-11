<template>
  <div class="comments-section mt-4">
    <h4>Comments ({{ comments.length }})</h4>
    <div v-if="comments.length === 0" class="text-muted">No comments yet.</div>
    <ul class="list-group mb-3">
      <li
        v-for="comment in comments"
        :key="comment.id"
        class="list-group-item d-flex justify-content-between align-items-start"
      >
        <div>
          <strong>{{ comment.user.name }}</strong>
          <div class="small text-muted">
            {{ formatDate(comment.created_at) }}
          </div>
          <div>{{ comment.content }}</div>
        </div>
        <div v-if="isAuthor || comment.user_id === userId">
          <button
            class="btn btn-sm btn-danger ms-2"
            @click="deleteComment(comment.id)"
          >
            Delete
          </button>
          <button
            v-if="!comment.approved"
            class="btn btn-sm btn-success ms-2"
            @click="approveComment(comment.id)"
          >
            Approve
          </button>
        </div>
      </li>
    </ul>
    <form @submit.prevent="addComment">
      <div class="input-group mb-3">
        <textarea
          v-model="newComment"
          class="form-control"
          placeholder="Add a comment..."
          required
          rows="3"
          style="resize: none"
        ></textarea>
        <button
          class="btn btn-primary"
          type="submit"
          :disabled="!newComment.trim()"
          title="Send comment"
        >
          Send
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "CommentSection",
  props: {
    postId: { type: [String, Number], required: true },
    isAuthor: { type: Boolean, default: false },
    userId: { type: [String, Number], default: null },
  },
  data() {
    return {
      comments: [],
      newComment: "",
    };
  },
  created() {
    this.fetchComments();
  },
  methods: {
    async fetchComments() {
      const token = localStorage.getItem("token");
      const res = await axios.get(
        `http://localhost:8000/api/posts/${this.postId}/comments`,
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.comments = res.data.data || res.data;
    },
    async addComment() {
      const token = localStorage.getItem("token");
      await axios.post(
        "http://localhost:8000/api/comments",
        {
          post_id: this.postId,
          content: this.newComment,
        },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.newComment = "";
      this.fetchComments();
    },
    async deleteComment(id) {
      const token = localStorage.getItem("token");
      await axios.delete(`http://localhost:8000/api/comments/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      this.fetchComments();
    },
    async approveComment(id) {
      const token = localStorage.getItem("token");
      await axios.post(
        `http://localhost:8000/api/comments/${id}/approve`,
        {},
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.fetchComments();
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
  },
};
</script>

<style scoped>
.comments-section {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
  padding: 1.5rem;
}
</style>
