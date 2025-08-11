<template>
  <div class="post-detail-container">
    <div v-if="post" class="post-detail-card">
      <div class="post-header">
        <div class="post-meta">
          <span class="post-status" :class="post.is_published ? 'published' : 'draft'">
            {{ post.is_published ? 'Published' : 'Draft' }}
          </span>
          <span class="post-date">{{ formatDate(post.created_at) }}</span>
        </div>
        <h1 class="post-title">{{ post.title }}</h1>
      </div>

      <div class="post-content">
        <div class="content-box">
          <div class="content-text" v-html="formatContent(post.content)"></div>
        </div>
      </div>

      <div class="post-actions">
        <router-link :to="{ name: 'posts' }" class="back-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="btn-icon">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
          </svg>
          Back to Posts
        </router-link>
        <router-link 
          v-if="post"
          :to="{ name: 'post-edit', params: { id: post.id } }"
          class="edit-btn"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="btn-icon">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
          </svg>
          Edit Post
        </router-link>
      </div>
    </div>

    <div v-else class="loading-state">
      <div class="loading-spinner"></div>
      <p>Loading post...</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "PostDetail",
  data() {
    return {
      post: null,
    };
  },
  created() {
    this.fetchPost();
  },
  methods: {
    async fetchPost() {
      try {
        const token = localStorage.getItem("token");
        const res = await axios.get(
          `http://localhost:8000/api/posts/${this.$route.params.id}`,
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );
        this.post = res.data.data || res.data;
      } catch (error) {
        console.error("Error fetching post:", error);
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    formatContent(content) {
      // Simple formatting - replace newlines with <br> tags
      return content.replace(/\n/g, '<br>');
    }
  },
};
</script>

<style scoped>
.post-detail-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.post-detail-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  padding: 2.5rem;
}

.post-header {
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #f1f5f9;
}

.post-meta {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 0.75rem;
  font-size: 0.875rem;
}

.post-status {
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  font-weight: 500;
  font-size: 0.75rem;
}

.post-status.published {
  background-color: #ecfdf5;
  color: #059669;
}

.post-status.draft {
  background-color: #eff6ff;
  color: #2563eb;
}

.post-date {
  color: #64748b;
}

.post-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
  line-height: 1.3;
}

.post-content {
  margin: 2rem 0;
}

.content-box {
  background-color: #f8fafc;
  border-radius: 8px;
  padding: 2rem;
  border: 1px solid #e2e8f0;
}

.content-text {
  line-height: 1.7;
  color: #334155;
  font-size: 1.1rem;
}

.content-text >>> br {
  content: "";
  display: block;
  margin: 1rem 0;
}

.post-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid #f1f5f9;
}

.back-btn, .edit-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s ease;
}

.back-btn {
  background-color: white;
  color: #64748b;
  border: 1px solid #e2e8f0;
}

.back-btn:hover {
  background-color: #f1f5f9;
  border-color: #cbd5e1;
}

.edit-btn {
  background-color: #6366f1;
  color: white;
  border: none;
}

.edit-btn:hover {
  background-color: #4f46e5;
  transform: translateY(-1px);
}

.btn-icon {
  display: flex;
  align-items: center;
}

.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 300px;
  gap: 1rem;
  color: #64748b;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #e2e8f0;
  border-top: 4px solid #6366f1;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive styles */
@media (max-width: 768px) {
  .post-detail-card {
    padding: 1.5rem;
  }
  
  .post-title {
    font-size: 1.75rem;
  }
  
  .content-box {
    padding: 1.5rem;
  }
  
  .post-actions {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }
  
  .back-btn, .edit-btn {
    justify-content: center;
    width: 100%;
  }
}

@media (max-width: 480px) {
  .post-title {
    font-size: 1.5rem;
  }
  
  .post-meta {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>