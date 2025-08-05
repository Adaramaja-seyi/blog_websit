<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <router-link class="navbar-brand fw-bold" to="/">
        <i class="bi bi-journal-text me-2"></i>BlogPlatform
      </router-link>

      <button class="navbar-toggler" type="button" @click="toggleMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" :class="{ show: isMenuOpen }">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/" exact-active-class="active">
              <i class="bi bi-house-door me-1"></i>Home
            </router-link>
          </li>
          <li v-if="isAuthenticated" class="nav-item">
            <router-link class="nav-link" to="/dashboard" active-class="active">
              <i class="bi bi-speedometer2 me-1"></i>Dashboard
            </router-link>
          </li>
          <li v-if="isAuthenticated" class="nav-item">
            <router-link class="nav-link" to="/posts" active-class="active">
              <i class="bi bi-file-text me-1"></i>Posts
            </router-link>
          </li>
        </ul>

        <div class="d-flex align-items-center">
          <!-- Guest Actions -->
          <template v-if="!isAuthenticated">
            <router-link to="/login" class="btn btn-outline-light me-2">
              <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </router-link>
            <router-link to="/register" class="btn btn-light">
              <i class="bi bi-person-plus me-1"></i>Register
            </router-link>
          </template>

          <!-- Authenticated User Actions -->
          <template v-else>
            <div class="dropdown">
              <button
                class="btn btn-light dropdown-toggle d-flex align-items-center"
                type="button"
                id="userDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <i class="bi bi-person-circle me-2"></i>
                <span>{{ userName || "User" }}</span>
              </button>
              <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="userDropdown"
              >
                <li>
                  <router-link class="dropdown-item" to="/profile">
                    <i class="bi bi-person me-2"></i>Profile
                  </router-link>
                </li>
                <li>
                  <router-link class="dropdown-item" to="/posts/create">
                    <i class="bi bi-plus-circle me-2"></i>Create Post
                  </router-link>
                </li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <button class="dropdown-item text-danger" @click="logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                  </button>
                </li>
              </ul>
            </div>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import api from "@/services/api";

export default {
  name: "Navbar",
  data() {
    return {
      isMenuOpen: false,
      loading: false,
    };
  },

  computed: {
    isAuthenticated() {
      return !!localStorage.getItem("token");
    },
    userName() {
      const user = localStorage.getItem("user");
      if (user) {
        try {
          const userData = JSON.parse(user);
          return userData.name || userData.email || "User";
        } catch {
          return "User";
        }
      }
      return null;
    },
  },

  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    },

    async logout() {
      this.loading = true;
      try {
        await api.logout();
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        this.$toast.success("Logged out successfully");
        this.$router.push("/login");
      } catch (error) {
        this.$toast.error(error.response?.data?.message || "Logout failed");
      } finally {
        this.loading = false;
        this.isMenuOpen = false;
      }
    },
  },
};
</script>

<style scoped>
.navbar {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.bg-primary {
  background-color: #4e73df !important;
}

.navbar-brand {
  font-weight: 700;
  font-size: 1.25rem;
}

.nav-link {
  font-weight: 500;
  transition: all 0.3s ease;
}

.nav-link:hover {
  transform: translateY(-1px);
}

.nav-link.active {
  font-weight: 700;
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 0.25rem;
}

.btn {
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-1px);
}

.dropdown-toggle {
  border: none;
  background: transparent;
}

.dropdown-toggle:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.dropdown-item {
  font-weight: 500;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
}

@media (max-width: 991.98px) {
  .navbar-nav {
    margin-bottom: 1rem;
  }

  .btn {
    width: 100%;
    margin-bottom: 0.5rem;
  }
}
</style>
