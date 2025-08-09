<template>
  <div v-if="showModal" class="modal-backdrop">
    <div class="modal">
      <div class="modal-header">
        <h3>Edit Profile</h3>
        <button @click="closeModal" class="close-btn">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="submitProfileUpdate">
          <div class="avatar-upload">
            <div class="avatar-preview">
              <img
                v-if="previewAvatar || form.avatar"
                :src="previewAvatar || form.avatar"
                alt="Avatar Preview"
              />
              <i v-else class="fas fa-user"></i>
            </div>
            <input
              type="file"
              id="avatar-upload"
              accept="image/*"
              @change="handleAvatarChange"
              class="file-input"
            />
            <label for="avatar-upload" class="upload-btn">
              <i class="fas fa-camera"></i> Change Photo
            </label>
          </div>

          <div class="form-group">
            <label>Full Name</label>
            <input
              type="text"
              v-model="form.name"
              required
              class="form-control"
            />
          </div>

          <div class="form-group">
            <label>Email</label>
            <input
              type="email"
              v-model="form.email"
              required
              class="form-control"
            />
          </div>

          <div class="form-group">
            <label>Bio</label>
            <textarea
              v-model="form.bio"
              rows="3"
              class="form-control"
              placeholder="Tell us about yourself..."
            ></textarea>
          </div>

          <div class="form-group">
            <label>Gender</label>
            <select v-model="form.gender" class="form-control">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="form-actions">
            <button type="button" @click="closeModal" class="btn btn-secondary">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="!loading">Update Profile</span>
              <span v-else>Updating...</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import api from "@/services/api"; // Make sure your api.js supports post requests

export default {
  name: "ProfileUpdateModal",
  props: {
    showModal: {
      type: Boolean,
      default: false,
    },
    userData: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      loading: false,
      form: {
        name: "",
        email: "",
        avatar: "",
        bio: "",
        gender: "",
      },
      avatarFile: null, // New: hold the raw file object
      previewAvatar: null,
      originalAvatar: null,
    };
  },
  watch: {
    userData: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.form = {
            name: newVal.name || "",
            email: newVal.email || "",
            avatar: newVal.avatar || "",
            bio: newVal.bio || "",
            gender: newVal.gender || "",
          };
          this.originalAvatar = newVal.avatar || "";
        }
      },
    },
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },

    handleAvatarChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.avatarFile = file;

        const reader = new FileReader();
        reader.onload = (e) => {
          this.previewAvatar = e.target.result; // For preview
        };
        reader.readAsDataURL(file);
      }
    },

    async submitProfileUpdate() {
      this.loading = true;

      try {
        const formData = new FormData();
        formData.append("name", this.form.name);
        formData.append("email", this.form.email);
        if (this.form.bio) {
          formData.append("bio", this.form.bio);
        }
        if (this.form.gender) {
          formData.append("gender", this.form.gender);
        }
        // Debug: log avatarFile type and value
        console.log(
          "avatarFile:",
          this.avatarFile,
          "isFile:",
          this.avatarFile instanceof File
        );
        // Only append avatar if it's a File object
        if (this.avatarFile && this.avatarFile instanceof File) {
          formData.append("avatar", this.avatarFile);
        }

        // Laravel expects PUT, so spoof it
        formData.append("_method", "PUT");

        // Debug: log FormData entries
        for (let pair of formData.entries()) {
          console.log(pair[0] + ", " + pair[1]);
        }

        const response = await api.updateProfile(formData, {
          withCredentials: true,
        });

        const updatedUser = response.data.user || {
          ...this.userData,
          ...this.form,
        };

        // Update localStorage and emit update
        localStorage.setItem("user", JSON.stringify(updatedUser));
        this.$emit("profile-updated", updatedUser);
        this.$toast?.success("Profile updated successfully!");
        this.avatarFile = null; // Clear after upload
        this.closeModal();
      } catch (error) {
        console.error("Profile update error:", error);
        if (error.response) {
          console.log("Backend error response:", error.response.data);
        }
        const errorMessage =
          error.response?.data?.message || "Failed to update profile";
        this.$toast?.error(errorMessage);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1100;
}

.modal {
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.modal-header {
  padding: 1rem;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  color: #333;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  color: #666;
}

.modal-body {
  padding: 1.5rem;
}

.avatar-upload {
  text-align: center;
  margin-bottom: 1.5rem;
}

.avatar-preview {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: #f5f5f5;
  margin: 0 auto 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.avatar-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-preview i {
  font-size: 2.5rem;
  color: #999;
}

.file-input {
  display: none;
}

.upload-btn {
  display: inline-block;
  padding: 0.5rem 1rem;
  background: #4e73df;
  color: white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
}

.upload-btn:hover {
  background: #3a5bc7;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #555;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.5rem;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
}

.btn-primary {
  background: #4e73df;
  color: white;
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn:disabled {
  background: #cccccc;
  cursor: not-allowed;
}
</style>
