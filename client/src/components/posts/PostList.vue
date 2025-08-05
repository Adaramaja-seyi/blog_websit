<template>
  <div class="post-list">
    <div v-if="posts.length === 0" class="text-center py-5">
      <h4>No posts found</h4>
      <p class="text-muted">Be the first to create a post!</p>
      <router-link v-if="isAuthenticated" to="/dashboard/posts/create" class="btn btn-primary">
        Create Post
      </router-link>
    </div>

    <div v-else>
      <PostItem v-for="post in posts" 
                :key="post.id" 
                :post="post"
                @edit="$emit('edit', post)" />
    </div>
  </div>
</template>

<script>
import PostItem from './PostItem.vue'
import { mapGetters } from 'vuex'

export default {
  components: { PostItem },
  props: {
    posts: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  computed: {
    ...mapGetters('auth', ['isAuthenticated'])
  }
}
</script>

<style scoped>
.post-list {
  margin-bottom: 3rem;
}
</style>