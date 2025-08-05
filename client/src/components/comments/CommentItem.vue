<template>
  <div class="comment-item mb-3">
    <div class="d-flex">
      <img :src="comment.user.avatar || '/images/default-avatar.png'" 
           class="rounded-circle me-3"
           width="40"
           height="40"
           alt="User avatar">
      
      <div class="flex-grow-1">
        <div class="comment-header d-flex justify-content-between mb-1">
          <h6 class="mb-0 fw-bold">{{ comment.user.name }}</h6>
          <small class="text-muted">{{ formatDate(comment.created_at) }}</small>
        </div>
        
        <div class="comment-body mb-2">
          <p class="mb-0">{{ comment.content }}</p>
        </div>
        
        <div class="comment-footer">
          <button v-if="canDelete" 
                  @click="deleteComment"
                  class="btn btn-sm btn-outline-danger">
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    comment: {
      type: Object,
      required: true
    },
    currentUserId: {
      type: Number,
      default: null
    }
  },
  computed: {
    canDelete() {
      return this.currentUserId && 
            (this.currentUserId === this.comment.user.id || this.currentUserId === this.comment.post.user_id)
    }
  },
  methods: {
    formatDate(dateString) {
      return new Date(dateString).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },
    async deleteComment() {
      if (confirm('Are you sure you want to delete this comment?')) {
        try {
          await axios.delete(`/api/comments/${this.comment.id}`)
          this.$emit('comment-deleted')
          this.$toast.success('Comment deleted successfully')
        } catch (error) {
          this.$toast.error(error.response?.data?.message || 'Failed to delete comment')
        }
      }
    }
  }
}
</script>

<style scoped>
.comment-item {
  padding: 1rem;
  background-color: #fff;
  border-radius: 8px;
  border: 1px solid #eee;
  transition: all 0.3s;
}

.comment-item:hover {
  border-color: #ddd;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.comment-header {
  font-size: 0.9rem;
}

.comment-body {
  font-size: 0.95rem;
  line-height: 1.5;
}
</style>