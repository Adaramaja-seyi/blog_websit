<template>
  <div class="card mb-4 shadow-sm">
    <img v-if="post.featured_image" :src="post.featured_image" class="card-img-top" alt="Featured image">
    <div class="card-body">
      <h5 class="card-title">{{ post.title }}</h5>
      <p class="card-text text-muted">
        <small>
          Posted by {{ post.user.name }} on {{ formatDate(post.created_at) }}
        </small>
      </p>
      <p class="card-text">{{ truncateContent(post.content) }}</p>
      
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <router-link :to="`/posts/${post.id}`" class="btn btn-sm btn-outline-primary">
            Read More
          </router-link>
          <button v-if="isAuthor" @click="editPost" class="btn btn-sm btn-outline-secondary ms-2">
            Edit
          </button>
        </div>
        
        <div class="text-muted">
          <i class="far fa-comment me-1"></i> {{ post.comments_count }}
          <i class="far fa-heart ms-3 me-1"></i> {{ post.likes_count }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  computed: {
    ...mapGetters('auth', ['currentUser']),
    isAuthor() {
      return this.currentUser && this.currentUser.id === this.post.user.id
    }
  },
  methods: {
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' }
      return new Date(dateString).toLocaleDateString(undefined, options)
    },
    truncateContent(content) {
      return content.length > 150 ? content.substring(0, 150) + '...' : content
    },
    editPost() {
      this.$emit('edit', this.post)
    }
  }
}
</script>

<style scoped>
.card {
  transition: transform 0.2s ease;
}

.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.card-img-top {
  height: 200px;
  object-fit: cover;
}
</style>