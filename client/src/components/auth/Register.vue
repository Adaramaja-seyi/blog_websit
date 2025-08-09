<template>
  <div class="auth-container">
    <div class="auth-card">
      <!-- Loading overlay -->
      <div v-if="loading" class="loading-overlay">
        <div class="spinner-container">
          <div class="loading-spinner"></div>
          <p class="loading-text">Creating your account...</p>
        </div>
      </div>

      <!-- Header Section -->
      <div class="auth-header">
        <div class="logo-container">
          <router-link to="/" class="logo-link">
            <div class="logo">
              <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                <circle
                  cx="20"
                  cy="20"
                  r="16"
                  fill="#2563EB"
                  stroke="#E0E7FF"
                  stroke-width="8"
                />
                <path
                  d="M15 20L18 23L25 16"
                  stroke="white"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
          </router-link>
        </div>
        <h1 class="welcome-title">Create Account</h1>
        <p class="welcome-subtitle">Fill in your details to get started</p>
      </div>

      <!-- Form Section -->
      <form @submit.prevent="handleRegister" class="auth-form">
        <!-- Name and Email Row -->
        <div class="form-row">
          <div class="input-group half-width">
            <label class="input-label">Full Name</label>
            <div class="input-container">
              <input
                type="text"
                class="form-input"
                v-model="form.name"
                placeholder="Enter your full name"
                :disabled="loading"
                required
                autofocus
              />
              <div class="input-focus-border"></div>
            </div>
          </div>

          <div class="input-group half-width">
            <label class="input-label">Email</label>
            <div class="input-container">
              <input
                type="email"
                class="form-input"
                v-model="form.email"
                placeholder="Enter your email"
                :disabled="loading"
                required
              />
              <div class="input-focus-border"></div>
            </div>
          </div>
        </div>

        <!-- Password Row -->
        <div class="form-row">
          <div class="input-group half-width">
            <label class="input-label">Password</label>
            <div class="input-container">
              <input
                type="password"
                class="form-input"
                v-model="form.password"
                placeholder="Create password"
                :disabled="loading"
                required
                minlength="8"
              />
              <div class="input-focus-border"></div>
            </div>
            <div class="input-hint">Min. 8 characters</div>
          </div>

          <div class="input-group half-width">
            <label class="input-label">Confirm Password</label>
            <div class="input-container">
              <input
                type="password"
                class="form-input"
                v-model="form.password_confirmation"
                placeholder="Confirm password"
                :disabled="loading"
                required
              />
              <div class="input-focus-border"></div>
            </div>
          </div>
        </div>

        <!-- Terms Agreement -->
        <div class="terms-group">
          <label class="terms-checkbox">
            <input
              type="checkbox"
              class="checkbox-input"
              v-model="form.terms"
              :disabled="loading"
              required
            />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">
              I agree to the
              <a
                href="#"
                @click.prevent="!loading && showTerms()"
                class="terms-link"
              >
                Terms and Conditions
              </a>
            </span>
          </label>
        </div>

        <!-- Error Display -->
        <div v-if="errors.length" class="error-container">
          <div class="error-icon">⚠</div>
          <div class="error-list">
            <div
              v-for="(error, index) in errors"
              :key="index"
              class="error-item"
            >
              {{ error }}
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="register-button" :disabled="loading">
          <span v-if="!loading" class="button-text">Create Account</span>
          <div v-else class="loading-container">
            <div class="loading-spinner small"></div>
            <span class="button-text">Creating Account...</span>
          </div>
        </button>
      </form>

      <!-- Footer -->
      <div class="auth-footer">
        <p class="login-text">
          Already have an account?
          <router-link
            to="/login"
            class="login-link"
            :class="{ 'disabled-link': loading }"
          >
            Sign in
          </router-link>
        </p>
      </div>
    </div>

    <!-- Background Decoration -->
    <div class="bg-decoration">
      <div class="circle circle-1"></div>
      <div class="circle circle-2"></div>
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
        terms: false,
      },
      loading: false,
      errors: {},
    };
  },
  methods: {
    async handleRegister() {
      this.loading = true;
      this.errors = {};
      try {
        const response = await api.createUsers(this.form);
        this.$toast.success(
          response.data.message || "Success! Redirecting to login..."
        );
        setTimeout(() => {
          this.$router.push("/login");
        }, 2000);
      } catch (error) {
        if (error.response && error.response.data) {
          this.errors = error.response.data.errors || {};
          this.$toast.error(
            error.response.data.message ||
              "Registration failed. Please try again."
          );
        } else {
          this.$toast.error("An unexpected error occurred.");
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
/* Reset and Base Styles */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.auth-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem 1rem;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  position: relative;
  overflow: hidden;
}

/* Background Decoration */
.bg-decoration {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 0;
}

.circle {
  position: absolute;
  border-radius: 50%;
  background: linear-gradient(
    135deg,
    rgba(37, 99, 235, 0.08),
    rgba(147, 197, 253, 0.08)
  );
  animation: float 8s ease-in-out infinite;
}

.circle-1 {
  width: 250px;
  height: 250px;
  top: -125px;
  right: -125px;
  animation-delay: 0s;
}

.circle-2 {
  width: 180px;
  height: 180px;
  bottom: -90px;
  left: -90px;
  animation-delay: -3s;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-15px) rotate(180deg);
  }
}

/* Main Card */
.auth-card {
  background: white;
  width: 100%;
  max-width: 520px;
  padding: 2.5rem 2rem;
  border-radius: 20px;
  box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
  position: relative;
  z-index: 1;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Loading Overlay */
.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.95);
  z-index: 10;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 20px;
  backdrop-filter: blur(5px);
}

.spinner-container {
  text-align: center;
}

.loading-text {
  margin-top: 1rem;
  color: #2563eb;
  font-weight: 600;
  font-size: 0.95rem;
}

/* Header Section */
.auth-header {
  text-align: center;
  margin-bottom: 2rem;
}

.logo-container {
  margin-bottom: 1rem;
}

.logo-link {
  display: inline-block;
  text-decoration: none;
  transition: transform 0.3s ease;
}

.logo-link:hover {
  transform: scale(1.05);
}

.logo-link:focus {
  outline: 2px solid #2563eb;
  outline-offset: 4px;
  border-radius: 20px;
}

.logo {
  display: inline-block;
  padding: 0.6rem;
  background: rgba(37, 99, 235, 0.05);
  border-radius: 16px;
  transition: all 0.3s ease;
  cursor: pointer;
}

.logo:hover {
  background: rgba(37, 99, 235, 0.1);
  transform: translateY(-1px);
}

.welcome-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.4rem;
  letter-spacing: -0.025em;
}

.welcome-subtitle {
  color: #64748b;
  font-size: 0.95rem;
  font-weight: 400;
}

/* Form Styles */
.auth-form {
  margin-bottom: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.25rem;
}

.input-group {
  position: relative;
}

.input-group.half-width {
  width: 100%;
}

.input-label {
  display: block;
  font-size: 0.8rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.4rem;
}

.input-container {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 0.8rem 1rem;
  font-size: 0.9rem;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  background: #f8fafc;
  transition: all 0.3s ease;
  color: #1e293b;
}

.form-input:focus {
  outline: none;
  background: white;
  border-color: #2563eb;
  transform: translateY(-1px);
  box-shadow: 0 8px 20px -5px rgba(37, 99, 235, 0.1);
}

.form-input:disabled {
  background: #f1f5f9;
  cursor: not-allowed;
  opacity: 0.7;
}

.form-input::placeholder {
  color: #94a3b8;
  font-size: 0.85rem;
}

.input-focus-border {
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #2563eb, #3b82f6);
  transform: translateX(-50%);
  transition: width 0.3s ease;
  border-radius: 1px;
}

.form-input:focus + .input-focus-border {
  width: 100%;
}

.input-hint {
  font-size: 0.75rem;
  color: #64748b;
  margin-top: 0.3rem;
}

/* Terms Section */
.terms-group {
  margin-bottom: 1.5rem;
}

.terms-checkbox {
  display: flex;
  align-items: flex-start;
  cursor: pointer;
  user-select: none;
  gap: 0.75rem;
}

.checkbox-input {
  display: none;
}

.checkbox-custom {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 5px;
  position: relative;
  transition: all 0.3s ease;
  background: white;
  flex-shrink: 0;
  margin-top: 2px;
}

.checkbox-input:checked + .checkbox-custom {
  background: #2563eb;
  border-color: #2563eb;
  transform: scale(1.05);
}

.checkbox-input:checked + .checkbox-custom::after {
  content: "";
  position: absolute;
  left: 5px;
  top: 1px;
  width: 4px;
  height: 8px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.checkbox-text {
  font-size: 0.85rem;
  color: #64748b;
  font-weight: 500;
  line-height: 1.4;
}

.terms-link {
  color: #2563eb;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}

.terms-link:hover {
  color: #1d4ed8;
  text-decoration: underline;
}

/* Error Display */
.error-container {
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  display: flex;
  gap: 0.75rem;
}

.error-icon {
  color: #ef4444;
  font-size: 1.1rem;
  flex-shrink: 0;
}

.error-list {
  flex: 1;
}

.error-item {
  color: #dc2626;
  font-size: 0.85rem;
  margin-bottom: 0.25rem;
  font-weight: 500;
}

.error-item:last-child {
  margin-bottom: 0;
}

/* Register Button */
.register-button {
  width: 100%;
  padding: 0.9rem 1.5rem;
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.register-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 15px 30px -8px rgba(37, 99, 235, 0.4);
}

.register-button:active {
  transform: translateY(0);
}

.register-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.button-text {
  position: relative;
  z-index: 2;
}

.loading-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.6rem;
}

.loading-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.loading-spinner.small {
  width: 16px;
  height: 16px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/* Footer Section */
.auth-footer {
  text-align: center;
}

.login-text {
  color: #64748b;
  font-size: 0.85rem;
  margin: 0;
}

.login-link {
  color: #2563eb;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}

.login-link:hover {
  color: #1d4ed8;
  text-decoration: underline;
}

.disabled-link {
  pointer-events: none;
  opacity: 0.6;
  text-decoration: none !important;
}

/* Responsive Design */
@media (max-width: 640px) {
  .auth-container {
    padding: 1rem;
  }

  .auth-card {
    padding: 2rem 1.5rem;
    border-radius: 16px;
    max-width: 100%;
  }

  .welcome-title {
    font-size: 1.5rem;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
    margin-bottom: 1rem;
  }

  .input-group {
    margin-bottom: 1rem;
  }

  .circle-1,
  .circle-2 {
    display: none;
  }
}

@media (max-width: 480px) {
  .auth-card {
    padding: 1.5rem 1rem;
  }

  .welcome-title {
    font-size: 1.4rem;
  }

  .form-input {
    padding: 0.7rem 0.9rem;
  }
}

/* Focus states for accessibility */
.form-input:focus,
.checkbox-custom:focus-within,
.terms-link:focus,
.register-button:focus,
.login-link:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
}
</style>
