<template>
  <div class="post-manager">
    <!-- Header Section -->
    <div class="header">
      <h2>Post Management</h2>
      <button @click="openCreateModal" class="create-btn">
        <i class="fas fa-plus"></i> Create Post
      </button>
    </div>

    <!-- Posts Table -->
    <div class="card posts-table">
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="post in posts" :key="post.id">
              <td>
                <router-link :to="`/posts/${post.id}`" class="post-title">
                  {{ post.title }}
                </router-link>
              </td>
              <td>
                <span :class="`status-badge ${post.status}`">
                  {{ post.status }}
                </span>
              </td>
              <td>{{ formatDate(post.created_at) }}</td>
              <td class="actions">
                <button @click="editPost(post)" class="icon-btn edit">
                  <i class="fas fa-edit"></i>
                </button>
                <button @click="confirmDelete(post.id)" class="icon-btn delete">
                  <i class="fas fa-trash"></i>
                </button>
                <router-link 
                  :to="`/posts/${post.id}`" 
                  class="icon-btn view"
                >
                  <i class="fas fa-eye"></i>
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination">
        <button 
          @click="prevPage" 
          :disabled="currentPage === 1"
          class="page-btn"
        >
          Previous
        </button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button 
          @click="nextPage" 
          :disabled="currentPage === totalPages"
          class="page-btn"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Create/Edit Post Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ isEditing ? 'Edit Post' : 'Create New Post' }}</h3>
          <button @click="closeModal" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitPost">
            <div class="form-group">
              <label>Title</label>
              <input 
                v-model="currentPost.title" 
                type="text" 
                required
                placeholder="Enter post title"
              >
            </div>
            
            <div class="form-group">
              <label>Content</label>
              <textarea 
                v-model="currentPost.content" 
                rows="8"
                required
                placeholder="Write your post content here..."
              ></textarea>
            </div>
            
            <div class="form-group">
              <label>Publish Status</label>
              <select v-model="currentPost.is_published" required>
                <option :value="false">Draft</option>
                <option :value="true">Published</option>
              </select>
            </div>
            
            <div class="form-actions">
              <button 
                type="button" 
                @click="closeModal" 
                class="cancel-btn"
              >
                Cancel
              </button>
              <button 
                type="submit" 
                class="submit-btn"
                :disabled="isSubmitting"
              >
                {{ isSubmitting ? 'Processing...' : 'Save Post' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content confirm-modal">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this post? This action cannot be undone.</p>
        <div class="modal-actions">
          <button @click="showDeleteModal = false" class="cancel-btn">
            Cancel
          </button>
          <button @click="deletePost" class="delete-btn">
            Delete Post
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { format } from 'date-fns';

export default {
  name: 'PostManager',
  data() {
    return {
      posts: [],
      currentPage: 1,
      totalPages: 1,
      showModal: false,
      showDeleteModal: false,
      isEditing: false,
      isSubmitting: false,
      postToDelete: null,
      currentPost: {
        id: null,
        title: '',
        content: '',
        is_published: false
      }
    }
  },
  created() {
    this.fetchPosts();
  },
  methods: {
    async fetchPosts() {
      try {
        const response = await this.$api.get(`/posts?page=${this.currentPage}`);
        this.posts = response.data.posts;
        this.totalPages = response.data.total_pages;
      } catch (error) {
        console.error('Error fetching posts:', error);
        this.$toast.error('Failed to load posts');
      }
    },
    openCreateModal() {
      this.isEditing = false;
      this.currentPost = {
        id: null,
        title: '',
        content: '',
        status: 'draft'
      };
      this.showModal = true;
    },
    editPost(post) {
      this.isEditing = true;
      this.currentPost = { ...post };
      this.showModal = true;
    },
    async submitPost() {
      this.isSubmitting = true;
      try {
        let response;
        if (this.isEditing) {
          response = await this.$api.updatePost(this.currentPost.id, this.currentPost);
        } else {
          response = await this.$api.createPost(this.currentPost);
        }
        
        // Check if response exists and has the expected structure
        if (response && response.data) {
          const responseData = response.data;
          
          // Check for success flag from the API
          if (responseData.success === true) {
            this.$toast.success(this.isEditing ? 'Post updated successfully' : 'Post created successfully');
            console.log('Post saved:', responseData.data || responseData);
            this.showModal = false;
            this.fetchPosts();
          } else {
            // Handle API-level failure
            const errorMessage = responseData.message || 'Failed to save post';
            this.$toast.error(errorMessage);
          }
        } else {
          this.$toast.error('Invalid response from server');
        }
      } catch (error) {
        console.error('Error saving post:', error);
        
        // Handle HTTP/network errors
        if (error.response && error.response.data) {
          const errorData = error.response.data;
          const errorMessage = errorData.message || 'Failed to save post';
          
          // Handle validation errors
          if (errorData.errors) {
            const firstError = Object.values(errorData.errors)[0][0];
            this.$toast.error(firstError);
          } else {
            this.$toast.error(errorMessage);
          }
        } else if (error.request) {
          // Network error
          this.$toast.error('Network error. Please check your connection.');
        } else {
          this.$toast.error('Failed to save post');
        }
      } finally {
        this.isSubmitting = false;
      }
    },
    confirmDelete(postId) {
      this.postToDelete = postId;
      this.showDeleteModal = true;
    },
    async deletePost() {
      try {
        await this.$api.deletePost(this.postToDelete);
        this.$toast.success('Post deleted successfully');
        this.showDeleteModal = false;
        this.fetchPosts();
      } catch (error) {
        console.error('Error deleting post:', error);
        this.$toast.error('Failed to delete post');
      }
    },
    closeModal() {
      this.showModal = false;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchPosts();
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchPosts();
      }
    },
    formatDate(date) {
      return format(new Date(date), 'MMM dd, yyyy');
    }
  }
}
</script>

<style scoped>
/* Main Container */
.post-manager {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

/* Header */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.header h2 {
  font-size: 1.75rem;
  color: #2c3e50;
  margin: 0;
}

.create-btn {
  background-color: #4e73df;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.create-btn:hover {
  background-color: #3a5bc7;
}

/* Table Styles */
.posts-table {
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #eaeaea;
}

th {
  background-color: #f8f9fa;
  font-weight: 600;
  color: #495057;
}

.post-title {
  color: #4e73df;
  text-decoration: none;
  font-weight: 500;
}

.post-title:hover {
  text-decoration: underline;
}

.status-badge {
  display: inline-block;
  padding: 0.35rem 0.75rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.status-badge.draft {
  background-color: #f6c23e;
  color: #fff;
}

.status-badge.published {
  background-color: #1cc88a;
  color: #fff;
}

.status-badge.archived {
  background-color: #e74a3b;
  color: #fff;
}

/* Action Buttons */
.actions {
  display: flex;
  gap: 0.5rem;
}

.icon-btn {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: none;
  background: transparent;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}

.icon-btn:hover {
  background-color: #f0f0f0;
}

.icon-btn.edit {
  color: #4e73df;
}

.icon-btn.delete {
  color: #e74a3b;
}

.icon-btn.view {
  color: #1cc88a;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1.5rem;
  gap: 1rem;
}

.page-btn {
  padding: 0.5rem 1rem;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
  background: #f8f9fa;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  animation: modalFadeIn 0.3s;
}

.confirm-modal {
  padding: 2rem;
  text-align: center;
}

.modal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6c757d;
}

.modal-body {
  padding: 1.5rem;
}

/* Form Styles */
.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #495057;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group textarea {
  resize: vertical;
  min-height: 150px;
}

.form-group select {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 1rem;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 2rem;
}

.cancel-btn {
  padding: 0.75rem 1.5rem;
  background: #f8f9fa;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.cancel-btn:hover {
  background: #e9ecef;
}

.submit-btn {
  padding: 0.75rem 1.5rem;
  background: #4e73df;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.submit-btn:hover:not(:disabled) {
  background: #3a5bc7;
}

.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.delete-btn {
  padding: 0.75rem 1.5rem;
  background: #e74a3b;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.delete-btn:hover {
  background: #c82333;
}

.modal-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 2rem;
}

/* Animations */
@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .create-btn {
    width: 100%;
    justify-content: center;
  }
  
  table {
    display: block;
    overflow-x: auto;
  }
}
</style>