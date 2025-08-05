<template>
  <div class="auth-container">
    <div class="auth-card">
      <!-- Loading overlay -->
      <div v-if="loading" class="loading-overlay">
        <div class="spinner-container">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Creating your account...</p>
        </div>
      </div>

      <h2 class="text-center mb-4">Create Your Account</h2>

      <form @submit.prevent="handleRegister">
        <!-- Name Field -->
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            v-model="form.name"
            :disabled="loading"
            required
            autofocus
          />
          <div class="loading-placeholder" v-if="loading">
            <div class="placeholder-bar"></div>
          </div>
        </div>

        <!-- Email Field -->
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input
            type="email"
            class="form-control"
            id="email"
            v-model="form.email"
            :disabled="loading"
            required
          />
          <div class="loading-placeholder" v-if="loading">
            <div class="placeholder-bar"></div>
          </div>
        </div>

        <!-- Password Field -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            v-model="form.password"
            :disabled="loading"
            required
            minlength="8"
          />
          <div class="form-text">Minimum 8 characters</div>
          <div class="loading-placeholder" v-if="loading">
            <div class="placeholder-bar"></div>
          </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
          <label for="password_confirmation" class="form-label"
            >Confirm Password</label
          >
          <input
            type="password"
            class="form-control"
            id="password_confirmation"
            v-model="form.password_confirmation"
            :disabled="loading"
            required
          />
          <div class="loading-placeholder" v-if="loading">
            <div class="placeholder-bar"></div>
          </div>
        </div>

        <!-- Terms Checkbox -->
        <div class="mb-3 form-check">
          <input
            type="checkbox"
            class="form-check-input"
            id="terms"
            v-model="form.terms"
            :disabled="loading"
            required
          />
          <label class="form-check-label" for="terms">
            I agree to the
            <a href="#" @click.prevent="!loading && showTerms()"
              >Terms and Conditions</a
            >
          </label>
          <div class="loading-placeholder" v-if="loading">
            <div class="placeholder-bar short"></div>
          </div>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="btn btn-primary w-100"
          :disabled="loading"
          :class="{ 'btn-loading': loading }"
        >
          <template v-if="!loading"> Register </template>
          <template v-else>
            <span class="spinner-border spinner-border-sm me-2"></span>
            Processing...
          </template>
        </button>

        <!-- Error Display -->
        <div v-if="errors.length" class="alert alert-danger mt-3">
          <ul class="mb-0">
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
          </ul>
        </div>
      </form>

      <p class="mt-3 text-center">
        Already have an account?
        <router-link to="/login" :class="{ 'disabled-link': loading }"
          >Login</router-link
        >
      </p>
    </div>
  </div>
</template>

<script>
import TermsContent from "@/components/auth/TermsContent.vue";
import api from "@/services/api";
import { Modal } from "bootstrap";

export default {
  components: { TermsContent },
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      loading: false,
      errors: [],
    };
  },
  methods: {
    async handleRegister() {
      this.errors = [];

      // Basic validation
      if (this.form.password !== this.form.password_confirmation) {
        this.errors.push("Passwords do not match");
        return;
      }

      if (!this.form.terms) {
        this.errors.push("You must accept the terms and conditions");
        return;
      }

      this.loading = true;
      try {
        const response = await api.createUsers(this.form);
        // Handle both 200 and 201 as success
        if (response.status === 201 || response.status === 200) {
          this.$toast.success(
            "Registration successful! Redirecting to login..."
          );
          setTimeout(() => {
            this.$router.push("/login");
          }, 3000);
        } else {
          this.errors.push("Registration failed");
        }
      } catch (error) {
        if (error.response?.data?.errors) {
          // Handle Laravel validation errors
          for (const field in error.response.data.errors) {
            this.errors.push(...error.response.data.errors[field]);
          }
        } else {
          this.errors.push(
            error.response?.data?.message || "Registration failed"
          );
        }
      } finally {
        this.loading = false;
      }
    },
    showTerms() {
      const modal = new Modal(document.getElementById("termsModal"));
      modal.show();
    },
  },
};
</script>

<style scoped>
.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f8f9fa;
  background-image: linear-gradient(180deg, #e9ecef 10%, #f8f9fa 100%);
  position: relative;
}

.auth-card {
  background: white;
  padding: 2.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 450px;
  position: relative;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 10;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 0.5rem;
}

.spinner-container {
  text-align: center;
}

.spinner-container p {
  margin-top: 1rem;
  color: #4e73df;
  font-weight: 500;
}

.loading-placeholder {
  margin-top: 0.5rem;
}

.placeholder-bar {
  height: 10px;
  background-color: #e9ecef;
  border-radius: 4px;
  animation: pulse 1.5s infinite ease-in-out;
}

.placeholder-bar.short {
  width: 50%;
}

@keyframes pulse {
  0% {
    opacity: 0.6;
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 0.6;
  }
}

.btn-loading {
  position: relative;
  pointer-events: none;
}

.disabled-link {
  pointer-events: none;
  opacity: 0.6;
  text-decoration: none;
}

.form-check-label a {
  text-decoration: none;
  color: var(--bs-primary);
}

.form-check-label a:hover {
  text-decoration: underline;
}

.btn-primary {
  background-color: #4e73df;
  border-color: #4e73df;
  padding: 0.5rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background-color: #3a5bc7;
  border-color: #3a5bc7;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-primary:active {
  transform: translateY(0);
  box-shadow: none;
}

/* Disabled state styling */
.form-control:disabled,
.form-check-input:disabled {
  background-color: #f8f9fa;
  cursor: not-allowed;
}

/* Modal disabled state */
.btn-close:disabled {
  opacity: 0.5;
  pointer-events: none;
}
</style>
