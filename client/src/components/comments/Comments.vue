<template>
  <div class="comment-form mb-4">
    <form @submit.prevent="submitComment">
      <div class="form-group">
        <textarea v-model="content" 
                  class="form-control" 
                  rows="3" 
                  placeholder="Write your comment..."
                  required></textarea>
      </div>
      <div class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
          Post Comment
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    postId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      content: '',
      loading: false
    }
  },
  methods: {
    async submitComment() {
      this.loading = true
      try {
        await axios.post(`/api/posts/${this.postId}/comments`, {
          content: this.content
        })
        this.content = ''
        this.$emit('comment-added')
        this.$toast.success('Comment added successfully')
      } catch (error) {
        this.$toast.error(error.response?.data?.message || 'Failed to add comment')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.comment-form {
  background-color: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
}

textarea.form-control {
  border-radius: 8px;
  resize: none;
  border: 1px solid #ddd;
}

textarea.form-control:focus {
  border-color: #4e73df;
  box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
}
</style>