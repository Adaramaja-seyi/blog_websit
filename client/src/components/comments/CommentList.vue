<template>
  <div class="comment-list">
    <div v-if="loading" class="text-center py-3">
      <div class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <template v-else>
      <CommentItem v-for="comment in comments" 
                  :key="comment.id"
                  :comment="comment"
                  :current-user-id="currentUserId"
                  @comment-deleted="$emit('comment-deleted')" />
      
      <div v-if="comments.length === 0" class="text-center py-4 text-muted">
        No comments yet. Be the first to comment!
      </div>
    </template>
  </div>
</template>

<script>
import CommentItem from './CommentItem.vue'

export default {
  components: { CommentItem },
  props: {
    comments: {
      type: Array,
      required: true,
      default: () => []
    },
    currentUserId: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      loading: false
    }
  }
}
</script>

<style scoped>
.comment-list {
  margin-top: 2rem;
}
</style>