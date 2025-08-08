<template>
  <div>
    <!-- Mobile Toggle Button -->
    <button class="sidebar-toggle" @click="toggleSidebar">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" :class="{ 'sidebar-open': isSidebarOpen }">
      <div class="sidebar-brand">
        <router-link to="/dashboard">
          <i class="fas fa-blog"></i>
          <span>BlogAdmin</span>
        </router-link>
        <button class="sidebar-close" @click="toggleSidebar">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <hr class="sidebar-divider" />

      <div class="sidebar-nav">
        <ul class="nav-items">
          <li class="nav-item">
            <router-link
              to="/dashboard"
              class="nav-link"
              active-class="active"
              @click.native="closeSidebarOnMobile"
            >
              <i class="fas fa-fw fa-tachometer-alt"></i>
              <span>Dashboard</span>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link
              to="/dashboard/posts"
              class="nav-link"
              active-class="active"
              @click.native="closeSidebarOnMobile"
            >
              <i class="fas fa-fw fa-file-alt"></i>
              <span>Posts</span>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link
              to="/dashboard/comments"
              class="nav-link"
              active-class="active"
              @click.native="closeSidebarOnMobile"
            >
              <i class="fas fa-fw fa-comments"></i>
              <span>Comments</span>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link
              to="/dashboard/users"
              class="nav-link"
              active-class="active"
              @click.native="closeSidebarOnMobile"
            >
              <i class="fas fa-fw fa-users"></i>
              <span>Users</span>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link
              to="/dashboard/media"
              class="nav-link"
              active-class="active"
              @click.native="closeSidebarOnMobile"
            >
              <i class="fas fa-fw fa-images"></i>
              <span>Media Library</span>
            </router-link>
          </li>
        </ul>
      </div>

      <div class="sidebar-footer">
        <div class="user-panel" @click="openEditModal">
          <div class="avatar">
            <img v-if="user.avatar" :src="user.avatar" alt="User Avatar" />
            <i v-else class="fas fa-user"></i>
          </div>
          <div class="user-info">
            <span class="username">{{ user.name || "User" }}</span>
            <span class="role">{{ user.role || "Member" }}</span>
          </div>
        </div>
        <button @click="handleLogout" class="logout-btn">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span>
        </button>
      </div>
    </div>

    <!-- Overlay -->
    <div
      class="sidebar-overlay"
      :class="{ active: isSidebarOpen }"
      @click="toggleSidebar"
    ></div>

    <!-- Profile Update Modal -->
    <ProfileUpdateModal
      :show-modal="showEditModal"
      :user-data="user"
      @close="closeEditModal"
      @profile-updated="handleProfileUpdated"
    />
  </div>
</template>

<script>
import ProfileUpdateModal from "./ProfileUpdateModal.vue";
import PostForm from "../posts/PostForm.vue";

export default {
  name: "Sidebar",
  components: {
    ProfileUpdateModal,
    PostForm,
  },
  props: {
    // Receive user data from parent component
    userData: {
      type: Object,
      default: () => ({
        name: "",
        email: "",
        avatar: "",
        bio: "",
        gender: "",
        role: "",
      }),
    },
  },
  data() {
    return {
      isSidebarOpen: false,
      isMobile: false,
      showEditModal: false,
      isUpdating: false,
      user: {
        name: "",
        email: "",
        avatar: "",
        bio: "",
        gender: "",
        role: "",
      },
    };
  },
  watch: {
    // Update local user data when prop changes
    userData: {
      immediate: true,
      handler(newVal) {
        this.user = { ...newVal };
      },
    },
  },
  created() {
    this.checkScreenSize();
  },
  methods: {
    getUserData() {
      // Fetch user data from API
      this.$api
        .getUserData()
        .then((response) => {
          this.user = response.data;
          this.editForm = { ...this.user };
        })
        .catch((error) => {
          console.error("Error fetching user data:", error);
        });
    },
    openEditModal() {
      this.showEditModal = true;
    },

    closeEditModal() {
      this.showEditModal = false;
    },

    handleAvatarUpload(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          this.editForm.avatar = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    },

    async updateProfile() {
      this.isUpdating = true;

      try {
        // Emit event to parent with updated data
        this.$emit("profile-update", this.editForm);

        // Update local user data
        this.user = { ...this.editForm };

        // Close modal
        this.closeEditModal();

        // Show success message
        this.$toast.success("Profile updated successfully!");
      } catch (error) {
        console.error("Error updating profile:", error);
        this.$toast.error("Failed to update profile");
      } finally {
        this.isUpdating = false;
      }
    },

    handleProfileUpdated(updatedData) {
      // Update local user data
      this.user = { ...this.user, ...updatedData };

      // Emit to parent component
      this.$emit("profile-update", updatedData);

      // Show success message
      this.$toast?.success("Profile updated successfully!");
    },

    handleLogout() {
      // Emit logout event to parent
      this.$emit("logout");
    },

    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    },

    closeSidebarOnMobile() {
      if (this.isMobile) {
        this.isSidebarOpen = false;
      }
    },

    checkScreenSize() {
      this.isMobile = window.innerWidth < 992;
      if (!this.isMobile) {
        this.isSidebarOpen = false;
      }
    },
  },
  mounted() {
    window.addEventListener("resize", this.checkScreenSize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.checkScreenSize);
  },
};
</script>

<style scoped>
/* (Keep all your existing styles exactly the same) */
/* Base Sidebar Styles */
.sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
  color: white;
  display: flex;
  flex-direction: column;
  z-index: 1000;
  transition: transform 0.3s ease;
  transform: translateX(-100%);
}

.sidebar.sidebar-open {
  transform: translateX(0);
}

.sidebar-brand {
  padding: 1.5rem 1rem;
  text-align: center;
  position: relative;
}

.sidebar-brand a {
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  font-size: 1.2rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05rem;
}

.sidebar-brand i {
  font-size: 1.5rem;
  margin-right: 0.5rem;
}

.sidebar-close {
  position: absolute;
  right: 10px;
  top: 10px;
  background: transparent;
  border: none;
  color: rgba(255, 255, 255, 0.8);
  font-size: 1.25rem;
  cursor: pointer;
  display: none;
}

.sidebar-divider {
  border-top: 1px solid rgba(255, 255, 255, 0.15);
  margin: 0 1rem 1rem;
}

.sidebar-nav {
  flex: 1;
  overflow-y: auto;
}

.nav-items {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-item {
  margin-bottom: 0.25rem;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 1rem;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.3s;
}

.nav-link i {
  width: 20px;
  margin-right: 0.5rem;
  text-align: center;
  font-size: 0.875rem;
}

.nav-link:hover {
  color: white;
  background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
  color: white;
  background-color: rgba(255, 255, 255, 0.2);
  border-left: 4px solid #f8f9fc;
}

.sidebar-footer {
  padding: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.15);
}

.user-panel {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
  cursor: pointer;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.2);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.75rem;
  overflow: hidden;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.user-info {
  display: flex;
  flex-direction: column;
}

.username {
  font-weight: 600;
  font-size: 0.875rem;
}

.role {
  font-size: 0.75rem;
  opacity: 0.8;
}

.logout-btn {
  display: flex;
  align-items: center;
  width: 100%;
  padding: 0.75rem 1rem;
  background: transparent;
  border: none;
  color: rgba(255, 255, 255, 0.8);
  cursor: pointer;
  border-radius: 0.35rem;
  transition: all 0.3s;
}

.logout-btn i {
  margin-right: 0.5rem;
  width: 20px;
  text-align: center;
}

.logout-btn:hover {
  color: white;
  background-color: rgba(255, 255, 255, 0.1);
}

/* Toggle Button */
.sidebar-toggle {
  position: fixed;
  left: 10px;
  top: 10px;
  background: #4e73df;
  border: none;
  color: white;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  z-index: 999;
  cursor: pointer;
  display: none;
}

/* Overlay */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 998;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.sidebar-overlay.active {
  opacity: 1;
  visibility: visible;
}

/* Modal Styles */
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

/* Avatar Upload */
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

/* Form Styles */
.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #555;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group textarea {
  min-height: 100px;
  resize: vertical;
}

/* Form Actions */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.5rem;
}

.cancel-btn {
  padding: 0.75rem 1.5rem;
  background: #f5f5f5;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.update-btn {
  padding: 0.75rem 1.5rem;
  background: #4e73df;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.update-btn:disabled {
  background: #cccccc;
  cursor: not-allowed;
}

/* Responsive Styles */
@media (max-width: 991px) {
  .sidebar-toggle {
    display: block;
  }

  .sidebar-close {
    display: block;
  }

  .sidebar-brand {
    justify-content: space-between;
    padding-right: 2.5rem;
  }

  .sidebar.sidebar-open {
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
  }
}

/* Desktop Styles (sidebar always visible) */
@media (min-width: 992px) {
  .sidebar {
    transform: translateX(0);
  }
}
</style>
