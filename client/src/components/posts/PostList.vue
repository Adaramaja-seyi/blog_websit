<template>
  <div class="post-list-container">
    <div class="post-list-header">
      <h2 class="page-title">Manage Posts</h2>
      <router-link :to="{ name: 'post-create' }" class="create-post-btn">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          viewBox="0 0 16 16"
          class="btn-icon"
        >
          <path
            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
          />
        </svg>
        Create New Post
      </router-link>
    </div>

    <div class="post-list-table">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="post in posts" :key="post.id">
              <td data-label="Title" class="post-title">
                <router-link
                  :to="{ name: 'post-detail', params: { id: post.id } }"
                >
                  {{ post.title }}
                </router-link>
              </td>
              <td data-label="Status">
                <span
                  class="status-badge"
                  :class="post.is_published ? 'published' : 'draft'"
                >
                  {{ post.is_published ? "Published" : "Draft" }}
                </span>
              </td>
              <td data-label="Created" class="created-date">
                {{ formatDate(post.created_at) }}
              </td>
              <td data-label="Actions" class="actions">
                <div class="action-buttons">
                  <router-link
                    :to="{ name: 'post-detail', params: { id: post.id } }"
                    class="action-btn view-btn"
                    title="View"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"
                      />
                      <path
                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"
                      />
                    </svg>
                  </router-link>
                  <router-link
                    :to="{ name: 'post-edit', params: { id: post.id } }"
                    class="action-btn edit-btn"
                    title="Edit"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"
                      />
                    </svg>
                  </router-link>
                  <button
                    class="action-btn delete-btn"
                    @click="deletePost(post.id)"
                    title="Delete"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                      />
                      <path
                        fill-rule="evenodd"
                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                      />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="posts.length === 0">
              <td colspan="4" class="no-posts">
                No posts found. Create your first post!
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <PostForm v-if="showPostForm" @post-updated="handlePostUpdated" />
  </div>
</template>

<script>
import api from "../../services/api";
import PostForm from "./PostForm.vue";

export default {
  name: "PostList",
  components: {
    PostForm,
  },
  data() {
    return {
      posts: [],
      user: {},
      showPostForm: false,
    };
  },
  async created() {
    await this.fetchUser();
    // Only fetch posts after user is loaded
    if (this.user && this.user.id) {
      await this.fetchPosts();
    }
  },
  methods: {
    async fetchUser() {
      try {
        const res = await api.getUserData();
        this.user = res.data;
      } catch (e) {
        this.user = JSON.parse(localStorage.getItem("user")) || {};
      }
    },
    async fetchPosts() {
      try {
        if (this.user && this.user.id) {
          console.log("Fetching posts for user:", this.user.id);
          const res = await api.getPosts({ user_id: this.user.id });
          console.log("API response:", res);
          const userPosts = res.data.data || res.data || [];
          console.log("Posts extracted:", userPosts);
          this.posts = userPosts;
        } else {
          this.posts = [];
        }
      } catch (e) {
        console.error("Error fetching posts:", e);
        this.posts = [];
      }
    },
    async deletePost(id) {
      if (!confirm("Are you sure you want to delete this post?")) return;
      try {
        await api.deletePost(id);
        this.$toast && this.$toast.success("Post deleted successfully");
        this.fetchPosts();
      } catch (e) {
        this.$toast && this.$toast.error("Failed to delete post");
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    },
    handlePostUpdated() {
      this.fetchPosts();
    },
  },
};
</script>

<style scoped>
.post-list-container {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.post-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.page-title {
  color: #1e293b;
  font-size: 1.75rem;
  font-weight: 600;
  margin: 0;
}

.create-post-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background-color: #6366f1;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s ease;
}

.create-post-btn:hover {
  background-color: #4f46e5;
  transform: translateY(-1px);
}

.post-list-table {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table thead {
  background-color: #f8fafc;
}

.table th {
  padding: 1rem 1.5rem;
  text-align: left;
  font-weight: 600;
  color: #64748b;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.table td {
  padding: 1rem 1.5rem;
  border-top: 1px solid #f1f5f9;
  color: #334155;
}

.post-title a {
  color: #1e293b;
  font-weight: 500;
  text-decoration: none;
  transition: color 0.2s ease;
}

.post-title a:hover {
  color: #6366f1;
}

.status-badge {
  display: inline-block;
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-badge.published {
  background-color: #ecfdf5;
  color: #059669;
}

.status-badge.draft {
  background-color: #eff6ff;
  color: #2563eb;
}

.created-date {
  color: #64748b;
  font-size: 0.875rem;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: none;
  background: transparent;
  cursor: pointer;
  transition: all 0.2s ease;
}

.action-btn svg {
  width: 16px;
  height: 16px;
}

.view-btn {
  color: #6366f1;
}

.view-btn:hover {
  background-color: #e0e7ff;
}

.edit-btn {
  color: #f59e0b;
}

.edit-btn:hover {
  background-color: #fef3c7;
}

.delete-btn {
  color: #ef4444;
}

.delete-btn:hover {
  background-color: #fee2e2;
}

.no-posts {
  text-align: center;
  color: #64748b;
  padding: 2rem;
}

/* Responsive styles */
@media (max-width: 768px) {
  .table {
    display: block;
  }

  .table thead {
    display: none;
  }

  .table tr {
    display: block;
    margin-bottom: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .table td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-top: none;
    border-bottom: 1px solid #f1f5f9;
  }

  .table td::before {
    content: attr(data-label);
    font-weight: 600;
    color: #64748b;
    margin-right: 1rem;
  }

  .table td:last-child {
    border-bottom: none;
  }

  .action-buttons {
    justify-content: flex-end;
  }
}

@media (max-width: 480px) {
  .post-list-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .create-post-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
