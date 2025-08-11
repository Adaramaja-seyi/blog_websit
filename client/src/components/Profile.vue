<template>
  <div class="profile-page">
    <h2>User Profile</h2>
    <div v-if="user">
      <form @submit.prevent="updateProfile" enctype="multipart/form-data">
        <div class="mb-3 text-center">
          <img
            :src="user.avatar || user.profile_picture || defaultAvatar"
            alt="Profile Picture"
            class="profile-img mb-2"
          />
          <input
            type="file"
            @change="onFileChange"
            accept="image/*"
            class="form-control"
          />
        </div>
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input v-model="form.name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input v-model="form.email" class="form-control" disabled />
        </div>
        <div class="mb-3">
          <label class="form-label">Bio</label>
          <textarea v-model="form.bio" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Gender</label>
          <select v-model="form.gender" class="form-control">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
        <button class="btn btn-primary" type="submit" :disabled="loading">
          {{ loading ? "Updating..." : "Update Profile" }}
        </button>
      </form>

      <!-- Success/Error Messages -->
      <div
        v-if="message"
        :class="[
          'alert',
          messageType === 'success' ? 'alert-success' : 'alert-danger',
          'mt-3',
        ]"
      >
        {{ message }}
      </div>

      <hr />
      <div class="mb-3">
        <strong>Posts:</strong>
        {{ user.posts_count || (user.posts ? user.posts.length : 0) }}
      </div>
      <div class="mb-3">
        <strong>Likes:</strong> {{ user.likes_count || 0 }}
      </div>
      <div class="mb-3">
        <strong>Comments:</strong> {{ user.comments_count || 0 }}
      </div>
      <div v-if="user.posts && user.posts.length">
        <h4 class="mt-4">My Posts</h4>
        <ul class="list-group">
          <li v-for="post in user.posts" :key="post.id" class="list-group-item">
            <strong>{{ post.title }}</strong>
            <span class="text-muted small float-end">{{
              formatDate(post.created_at)
            }}</span>
          </li>
        </ul>
      </div>
    </div>
    <div v-else>
      <p>Loading...</p>
    </div>
  </div>
</template>

<script>
import api from "../services/api";
export default {
  name: "Profile",
  data() {
    return {
      user: null,
      form: {
        name: "",
        email: "",
        bio: "",
        gender: "",
        avatar: null,
      },
      defaultAvatar: "https://ui-avatars.com/api/?name=User",
      loading: false,
      message: "",
      messageType: "success",
    };
  },
  created() {
    this.fetchProfile();
  },
  methods: {
    async fetchProfile() {
      try {
        const res = await api.getUserData();
        this.user = res.data;
        this.form.name = res.data.name;
        this.form.email = res.data.email;
        this.form.bio = res.data.bio || "";
        this.form.gender = res.data.gender || "";
      } catch (e) {
        this.user = null;
        this.showMessage("Failed to load profile", "error");
      }
    },
    onFileChange(e) {
      const file = e.target.files[0];
      if (file) {
        this.form.avatar = file;
      }
    },
    async updateProfile() {
      console.log("updateProfile called");
      this.loading = true;
      this.message = "";

      const formData = new FormData();
      formData.append("name", this.form.name);
      formData.append("bio", this.form.bio || "");
      formData.append("gender", this.form.gender || "");

      // Handle file upload - use 'avatar' field name to match backend
      if (this.form.avatar) {
        formData.append("avatar", this.form.avatar);
      }

      // Debug: log FormData entries before sending
      for (let pair of formData.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }

      try {
        const response = await api.updateProfile(formData);

        // Update local user data and form fields immediately
        this.user = { ...this.user, ...response.data.user };
        this.form.name = response.data.user.name;
        this.form.bio = response.data.user.bio || "";
        this.form.gender = response.data.user.gender || "";
        if (response.data.user.avatar) {
          this.user.avatar = response.data.user.avatar;
        }

        // Show success message
        this.showMessage("Profile updated successfully!", "success");

        // Clear file input
        this.form.avatar = null;
      } catch (error) {
        console.error("Profile update error:", error);

        // Handle validation errors
        if (error.response?.data?.errors) {
          const errors = Object.values(error.response.data.errors).flat();
          this.showMessage(errors.join(", "), "error");
        } else if (error.response?.data?.message) {
          this.showMessage(error.response.data.message, "error");
        } else {
          this.showMessage(
            "Failed to update profile. Please try again.",
            "error"
          );
        }
      } finally {
        this.loading = false;
      }
    },

    showMessage(text, type) {
      this.message = text;
      this.messageType = type;

      // Auto-hide message after 5 seconds
      setTimeout(() => {
        this.message = "";
      }, 5000);
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
  },
};
</script>

<style scoped>
.profile-page {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
  padding: 2rem;
  max-width: 600px;
  margin: 2rem auto;
}
.profile-img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 50%;
  border: 2px solid #e5e7eb;
}
.alert {
  padding: 0.75rem 1rem;
  border-radius: 0.375rem;
}
.alert-success {
  background-color: #d1edff;
  border-color: #b8daff;
  color: #004085;
}
.alert-danger {
  background-color: #f8d7da;
  border-color: #f5c6cb;
  color: #721c24;
}
</style>
