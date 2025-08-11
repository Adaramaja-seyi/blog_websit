<template>
  <aside class="sidebar">
    <div class="sidebar-brand">
      <h1>Blog<span>website</span></h1>
    </div>



    <nav class="sidebar-nav">
      <ul>
        <li>
          <router-link to="/feed" class="nav-link" active-class="active" exact>
            <i class="bi bi-house-door"></i>
            <span>Home</span>
          </router-link>
        </li>
        <li>
          <router-link to="/dashboard" class="nav-link" active-class="active">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
          </router-link>
        </li>
        <li>
          <router-link
            to="/dashboard/posts"
            class="nav-link"
            active-class="active"
          >
            <i class="bi bi-file-earmark-text"></i>
            <span>Posts</span>
          </router-link>
        </li>
        <li>
          <router-link
            to="/dashboard/comments"
            class="nav-link"
            active-class="active"
          >
            <i class="bi bi-chat-left-text"></i>
            <span>Comments</span>
          </router-link>
        </li>
        <li>
          <router-link
            to="/dashboard/profile"
            class="nav-link"
            active-class="active"
          >
            <i class="bi bi-person"></i>
            <span>Profile</span>
          </router-link>
        </li>
      </ul>

          <!-- User Profile Section -->
    <div class="user-profile-section" @click="openProfileModal">
      <div class="user-avatar">
        <img 
          :src="user?.avatar || user?.profile_picture || defaultAvatar" 
          :alt="user?.name || 'User'"
          class="avatar-img"
        />
      </div>
      <div class="user-info">
        <h4 class="user-name">{{ user?.name || 'User' }}</h4>
        <p class="user-email">{{ user?.email || '' }}</p>
      </div>
    </div>

      <div class="sidebar-footer">
        <button class="logout-btn" @click="$emit('logout')">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </button>
      </div>
    </nav>

    <!-- Profile Update Modal -->
    <div v-if="showProfileModal" class="profile-modal-overlay" @click.self="closeProfileModal">
      <div class="profile-modal">
        <div class="modal-header">
          <h3>Update Profile</h3>
          <button class="close-btn" @click="closeProfileModal">&times;</button>
        </div>
        
        <form @submit.prevent="updateProfile" enctype="multipart/form-data">
          <div class="form-group">
            <label>Profile Picture</label>
            <div class="profile-picture-section">
              <img
                :src="previewImage || (user?.avatar || user?.profile_picture || defaultAvatar)"
                alt="Profile"
                class="preview-image"
              />
              <input
                type="file"
                @change="onFileChange"
                accept="image/*"
                class="file-input"
              />
            </div>
          </div>

          <div class="form-group">
            <label>Name</label>
            <input 
              v-model="profileForm.name" 
              type="text" 
              class="form-control"
              required
            />
          </div>

          <div class="form-group">
            <label>Email</label>
            <input 
              v-model="profileForm.email" 
              type="email" 
              class="form-control"
              disabled
              readonly
            />
          </div>

          <div class="form-group">
            <label>Bio</label>
            <textarea 
              v-model="profileForm.bio" 
              class="form-control"
              rows="3"
              placeholder="Tell us about yourself..."
            ></textarea>
          </div>

          <div class="form-group">
            <label>Gender</label>
            <select v-model="profileForm.gender" class="form-control">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" @click="closeProfileModal">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? 'Updating...' : 'Update Profile' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </aside>
</template>

<script>
import api from '../../services/api';

export default {
  name: "Sidebar",
  data() {
    return {
      user: null,
      showProfileModal: false,
      profileForm: {
        name: '',
        email: '',
        bio: '',
        gender: '',
        profile_picture: null
      },
      previewImage: '',
      loading: false,
      defaultAvatar: "https://ui-avatars.com/api/?name=User&background=4361ee&color=fff"
    };
  },
  created() {
    this.fetchUserData();
  },
  methods: {
    async fetchUserData() {
      try {
        const response = await api.getUserData();
        this.user = response.data.user;
        this.initializeProfileForm();
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    },
    
    initializeProfileForm() {
      if (this.user) {
        this.profileForm = {
          name: this.user.name || '',
          email: this.user.email || '',
          bio: this.user.bio || '',
          gender: this.user.gender || '',
          profile_picture: null
        };
      }
    },
    
    openProfileModal() {
      this.showProfileModal = true;
      this.initializeProfileForm();
    },
    
    closeProfileModal() {
      this.showProfileModal = false;
      this.profileForm.profile_picture = null;
      this.previewImage = '';
    },
    
    onFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.profileForm.profile_picture = file;
        this.previewImage = URL.createObjectURL(file);
      }
    },
    
    async updateProfile() {
      this.loading = true;
      
      try {
        const formData = new FormData();
        formData.append('name', this.profileForm.name);
        formData.append('bio', this.profileForm.bio);
        formData.append('gender', this.profileForm.gender);
        
        if (this.profileForm.profile_picture) {
          formData.append('avatar', this.profileForm.profile_picture);
        }

        const response = await api.updateProfile(formData);
        
        // Update user data immediately
        this.user = response.data.user;
        
        // Emit event to update user data globally
        this.$emit('user-updated', this.user);
        
        // Close modal
        this.closeProfileModal();
        
        // Show success message
        this.$emit('show-notification', {
          type: 'success',
          message: 'Profile updated successfully!'
        });
        
      } catch (error) {
        console.error('Error updating profile:', error);
        this.$emit('show-notification', {
          type: 'error',
          message: 'Failed to update profile. Please try again.'
        });
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.sidebar {
  width: 240px;
  height: 100vh;
  background: white;
  border-right: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  position: fixed;
  z-index: 100;
}

.sidebar-brand {
  padding: 1.5rem 1.5rem 1rem;
  border-bottom: 1px solid #e5e7eb;
}

.sidebar-brand h1 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #212529;
}

.sidebar-brand h1 span {
  font-weight: 400;
  color: #6c757d;
}

.sidebar-nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 1rem 0;
  overflow-y: auto;
}

.sidebar-nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  color: #495057;
  text-decoration: none;
  transition: all 0.3s ease;
  gap: 0.75rem;
}

.nav-link i {
  font-size: 1.1rem;
  width: 24px;
  text-align: center;
}

.nav-link:hover {
  background-color: #f8f9fa;
  color: #4361ee;
}

.nav-link.active {
  background-color: #f0f2ff;
  color: #4361ee;
  border-left: 3px solid #4361ee;
}

/* User Profile Section */
.user-profile-section {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.user-profile-section:hover {
  background-color: #f8f9fa;
}

.user-avatar {
  text-align: center;
  margin-bottom: 1rem;
}

.avatar-img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

.user-info {
  text-align: center;
}

.user-name {
  font-size: 1rem;
  font-weight: 600;
  color: #212529;
  margin: 0 0 0.25rem 0;
}

.user-email {
  font-size: 0.875rem;
  color: #6c757d;
  margin: 0;
}

/* User Profile Section */
.user-profile-section {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.user-profile-section:hover {
  background-color: #f8f9fa;
}

.user-avatar {
  text-align: center;
  margin-bottom: 1rem;
}

.avatar-img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

.user-info {
  text-align: center;
}

.user-name {
  font-size: 1rem;
  font-weight: 600;
  color: #212529;
  margin: 0 0 0.25rem 0;
}

.user-email {
  font-size: 0.875rem;
  color: #6c757d;
  margin: 0;
}

.sidebar-footer {
  margin-top: auto;
  padding: 1rem 0 0;
  border-top: 1px solid #e5e7eb;
}

.logout-btn {
  display: flex;
  align-items: center;
  width: 100%;
  padding: 0.75rem 1.5rem;
  background: none;
  border: none;
  color: #495057;
  cursor: pointer;
  transition: all 0.3s ease;
  gap: 0.75rem;
}

.logout-btn:hover {
  background-color: #f8f9fa;
  color: #dc3545;
}

.logout-btn i {
  font-size: 1.1rem;
  width: 24px;
  text-align: center;
}

/* Profile Modal Styles */
.profile-modal-overlay {
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

.profile-modal {
  background: white;
  border-radius: 8px;
  padding: 2rem;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.modal-header h3 {
  margin: 0;
  color: #212529;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6c757d;
}

.close-btn:hover {
  color: #212529;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #212529;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
}

.form-control:focus {
  outline: none;
  border-color: #4361ee;
  box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.form-control:disabled {
  background-color: #e9ecef;
  opacity: 0.6;
}

.profile-picture-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.preview-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

.file-input {
  margin-top: 0.5rem;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 2rem;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.btn-primary {
  background-color: #4361ee;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #3a56d4;
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

/* Responsive */
@media (max-width: 992px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
    border-right: none;
    border-bottom: 1px solid #e5e7eb;
  }

  .sidebar-nav {
    flex-direction: row;
    overflow-x: auto;
    padding: 0;
  }

  .sidebar-nav ul {
    display: flex;
    flex: 1;
  }

  .nav-link {
    padding: 1rem;
    flex-direction: column;
    gap: 0.5rem;
    font-size: 0.75rem;
  }

  .nav-link i {
    font-size: 1rem;
  }

  .nav-link.active {
    border-left: none;
    border-bottom: 3px solid #4361ee;
  }

  .sidebar-footer {
    display: none;
  }
}
</style>