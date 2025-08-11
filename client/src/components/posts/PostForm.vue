<template>
  <div class="post-form-container">
    <div class="post-form-card">
      <h2 class="form-title">{{ isEdit ? "Edit Post" : "Create New Post" }}</h2>
      <form @submit.prevent="handleSubmit" class="post-form">
        <div class="form-group">
          <label class="form-label">Title</label>
          <input
            v-model="form.title"
            class="form-input"
            placeholder="Enter post title"
            required
          />
          <span class="input-focus-border"></span>
        </div>

        <div class="form-group">
          <label class="form-label">Content</label>
          <textarea
            v-model="form.content"
            class="form-textarea"
            rows="6"
            placeholder="Write your post content here..."
            required
          ></textarea>
          <span class="input-focus-border"></span>
        </div>

        <div class="form-group">
          <label class="form-label">Status</label>
          <div class="status-toggle">
            <button
              type="button"
              class="toggle-option"
              :class="{ active: !form.is_published }"
              @click="form.is_published = false"
            >
              Draft
            </button>
            <button
              type="button"
              class="toggle-option"
              :class="{ active: form.is_published }"
              @click="form.is_published = true"
            >
              Published
            </button>
          </div>
        </div>

        <div class="form-actions">
          <button class="submit-btn" type="submit">
            {{ isEdit ? "Update Post" : "Publish Post" }}
            <span class="btn-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                viewBox="0 0 16 16"
              >
                <path
                  d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11z"
                />
              </svg>
            </span>
          </button>
          <router-link class="cancel-btn" :to="{ name: 'posts' }">
            Cancel
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import api from "../../services/api";
export default {
  name: "PostForm",
  data() {
    return {
      form: {
        title: "",
        content: "",
        is_published: false,
      },
      isEdit: false,
    };
  },
  created() {
    if (this.$route.params.id) {
      this.isEdit = true;
      this.fetchPost();
    }
  },
  methods: {
    async fetchPost() {
      try {
        const res = await api.getPost(this.$route.params.id);
        const postData = res.data.data || res.data;
        this.form = {
          title: postData.title,
          content: postData.content,
          is_published: postData.is_published,
        };
      } catch (e) {
        this.$toast && this.$toast.error("Failed to load post");
      }
    },
    async handleSubmit() {
      try {
        if (this.isEdit) {
          await api.updatePost(this.$route.params.id, this.form);
          this.$toast && this.$toast.success("Post updated successfully!");
        } else {
          await api.createPost(this.form);
          this.$toast && this.$toast.success("Post created successfully!");
        }
        this.$emit("post-updated");
        // Force reload to update dashboard and post list
        if (this.$route.name === "post-create") {
          this.$router.push({ name: "dashboard" });
        } else {
          this.$router.push({ name: "posts" });
        }
        setTimeout(() => {
          window.location.reload();
        }, 300);
      } catch (e) {
        this.$toast && this.$toast.error("Failed to save post");
      }
    },
  },
};
</script>

<style scoped>
.post-form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100%;
  padding: 2rem;
  background-color: #f8fafc;
}

.post-form-card {
  width: 100%;
  max-width: 700px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  padding: 2.5rem;
  transition: all 0.3s ease;
}

.form-title {
  color: #1e293b;
  font-size: 1.75rem;
  font-weight: 600;
  margin-bottom: 2rem;
  text-align: center;
}

.post-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #64748b;
}

.form-input,
.form-textarea {
  padding: 0.75rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s ease;
  background-color: #f8fafc;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #6366f1;
  background-color: white;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input::placeholder,
.form-textarea::placeholder {
  color: #94a3b8;
}

.form-textarea {
  resize: vertical;
  min-height: 150px;
}

.input-focus-border {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background-color: #6366f1;
  transition: width 0.3s ease;
}

.form-input:focus ~ .input-focus-border,
.form-textarea:focus ~ .input-focus-border {
  width: 100%;
}

.status-toggle {
  display: flex;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
  background-color: #f8fafc;
}

.toggle-option {
  flex: 1;
  padding: 0.75rem;
  border: none;
  background: transparent;
  cursor: pointer;
  font-weight: 500;
  color: #64748b;
  transition: all 0.2s ease;
}

.toggle-option.active {
  background-color: #6366f1;
  color: white;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

.submit-btn {
  padding: 0.75rem 1.5rem;
  background-color: #6366f1;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s ease;
}

.submit-btn:hover {
  background-color: #4f46e5;
  transform: translateY(-1px);
}

.cancel-btn {
  padding: 0.75rem 1.5rem;
  background-color: white;
  color: #64748b;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  text-align: center;
  transition: all 0.2s ease;
}

.cancel-btn:hover {
  background-color: #f1f5f9;
  border-color: #cbd5e1;
}

.btn-icon {
  display: flex;
  align-items: center;
}

@media (max-width: 768px) {
  .post-form-card {
    padding: 1.5rem;
  }

  .form-actions {
    flex-direction: column;
  }

  .submit-btn,
  .cancel-btn {
    width: 100%;
  }
}
</style>
