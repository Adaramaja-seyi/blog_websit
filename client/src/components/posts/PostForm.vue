<template>
  <div class="post-form-container">
    <div class="card">
      <div class="card-header">
        <h3 class="mb-0">{{ isEdit ? 'Edit Post' : 'Create New Post' }}</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input 
              type="text" 
              class="form-control" 
              id="title" 
              v-model="form.title"
              required
            >
          </div>
          
          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea 
              class="form-control" 
              id="content" 
              rows="10"
              v-model="form.content"
              required
            ></textarea>
          </div>
          
          <div class="mb-3">
            <label for="featured_image" class="form-label">Featured Image</label>
            <input 
              type="file" 
              class="form-control" 
              id="featured_image" 
              @change="handleImageUpload"
            >
            <div v-if="form.featured_image_url" class="mt-2">
              <img :src="form.featured_image_url" alt="Featured" class="img-thumbnail" style="max-height: 200px;">
            </div>
          </div>
          
          <div class="mb-3 form-check">
            <input 
              type="checkbox" 
              class="form-check-input" 
              id="is_published"
              v-model="form.is_published"
            >
            <label class="form-check-label" for="is_published">Publish immediately</label>
          </div>
          
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-2" @click="cancel">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm"></span>
              {{ isEdit ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    post: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      form: {
        title: '',
        content: '',
        is_published: false,
        featured_image: null,
        featured_image_url: null
      },
      loading: false
    };
  },
  computed: {
    isEdit() {
      return this.post !== null;
    }
  },
  created() {
    if (this.isEdit) {
      this.form = {
        title: this.post.title,
        content: this.post.content,
        is_published: this.post.is_published,
        featured_image: null,
        featured_image_url: this.post.featured_image
      };
    }
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.form.featured_image = file;
        
        // Create preview URL
        const reader = new FileReader();
        reader.onload = (e) => {
          this.form.featured_image_url = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    async handleSubmit() {
      this.loading = true;
      try {
        const formData = new FormData();
        formData.append('title', this.form.title);
        formData.append('content', this.form.content);
        formData.append('is_published', this.form.is_published);
        if (this.form.featured_image) {
          formData.append('featured_image', this.form.featured_image);
        }
        
        if (this.isEdit) {
          await this.$http.put(`/posts/${this.post.id}`, formData);
          this.$toast.success('Post updated successfully');
        } else {
          await this.$http.post('/posts', formData);
          this.$toast.success('Post created successfully');
        }
        
        this.$router.push('/dashboard');
      } catch (error) {
        this.$toast.error(error.response?.data?.message || 'An error occurred');
      } finally {
        this.loading = false;
      }
    },
    cancel() {
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>
.post-form-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}
</style>