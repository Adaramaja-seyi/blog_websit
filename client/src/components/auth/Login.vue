<template>
  <div class="auth-container">
    <div class="auth-card">
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
        <h1 class="welcome-title">Welcome Back</h1>
        <p class="welcome-subtitle">Sign in to continue to your account</p>
      </div>

      <!-- Form Section -->
      <form @submit.prevent="handleLogin" class="auth-form">
        <div class="input-group">
          <label class="input-label">Email</label>
          <div class="input-container">
            <input
              type="email"
              class="form-input"
              v-model="form.email"
              placeholder="Enter your email address"
              required
            />
            <div class="input-focus-border"></div>
          </div>
        </div>

        <div class="input-group">
          <label class="input-label">Password</label>
          <div class="input-container">
            <input
              type="password"
              class="form-input"
              v-model="form.password"
              placeholder="Enter your password"
              required
            />
            <div class="input-focus-border"></div>
          </div>
        </div>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" class="checkbox-input" />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">Remember me</span>
          </label>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <button type="submit" class="login-button" :disabled="loading">
          <span v-if="!loading" class="button-text">Sign In</span>
          <div v-else class="loading-container">
            <div class="loading-spinner"></div>
            <span class="button-text">Signing In...</span>
          </div>
        </button>
      </form>

      <!-- Footer -->
      <div class="auth-footer">
        <div class="divider">
          <span class="divider-text">or</span>
        </div>

        <button type="button" class="social-button">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path
              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
              fill="#4285F4"
            />
            <path
              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
              fill="#34A853"
            />
            <path
              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
              fill="#FBBC05"
            />
            <path
              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
              fill="#EA4335"
            />
          </svg>
          Continue with Google
        </button>

        <p class="signup-text">
          Don't have an account?
          <router-link to="/register" class="signup-link">Sign up</router-link>
        </p>
      </div>
    </div>

    <!-- Background Decoration -->
    <div class="bg-decoration">
      <div class="circle circle-1"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
    </div>
  </div>
</template>

<script>
import api from "@/services/api.js";

export default {
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      loading: false,
    };
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      try {
        const response = await api.login(this.form);
        // Save token to local storage
        localStorage.setItem("token", response.data.token);
        this.$toast.success(response.data.message || "Login successful!");
        // Redirect to dashboard
        this.$router.push("/dashboard");
      } catch (error) {
        this.$toast.error(error.response?.data?.message || "Login failed");
      } finally {
        this.loading = false;
      }
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
  padding: 2rem 1rem;
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
    rgba(37, 99, 235, 0.1),
    rgba(147, 197, 253, 0.1)
  );
  animation: float 6s ease-in-out infinite;
}

.circle-1 {
  width: 300px;
  height: 300px;
  top: -150px;
  right: -150px;
  animation-delay: 0s;
}

.circle-2 {
  width: 200px;
  height: 200px;
  bottom: -100px;
  left: -100px;
  animation-delay: -2s;
}

.circle-3 {
  width: 150px;
  height: 150px;
  top: 20%;
  left: 10%;
  animation-delay: -4s;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}

/* Main Card */
.auth-card {
  background: white;
  width: 100%;
  max-width: 420px;
  padding: 3rem 2.5rem;
  border-radius: 24px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
  position: relative;
  z-index: 1;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Header Section */
.auth-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.logo-container {
  margin-bottom: 1.5rem;
}

.logo {
  display: inline-block;
  padding: 0.75rem;
  background: rgba(37, 99, 235, 0.05);
  border-radius: 20px;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

.welcome-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.5rem;
  letter-spacing: -0.025em;
}

.welcome-subtitle {
  color: #64748b;
  font-size: 1rem;
  font-weight: 400;
}

/* Form Styles */
.auth-form {
  margin-bottom: 2rem;
}

.input-group {
  margin-bottom: 1.5rem;
}

.input-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
}

.input-container {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 1rem 1.25rem;
  font-size: 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 16px;
  background: #f8fafc;
  transition: all 0.3s ease;
  color: #1e293b;
}

.form-input:focus {
  outline: none;
  background: white;
  border-color: #2563eb;
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.1);
}

.form-input::placeholder {
  color: #94a3b8;
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

/* Form Options */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.remember-me {
  display: flex;
  align-items: center;
  cursor: pointer;
  user-select: none;
}

.checkbox-input {
  display: none;
}

.checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #d1d5db;
  border-radius: 6px;
  margin-right: 0.75rem;
  position: relative;
  transition: all 0.3s ease;
  background: white;
}

.checkbox-input:checked + .checkbox-custom {
  background: #2563eb;
  border-color: #2563eb;
  transform: scale(1.05);
}

.checkbox-input:checked + .checkbox-custom::after {
  content: "";
  position: absolute;
  left: 6px;
  top: 2px;
  width: 4px;
  height: 8px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.checkbox-text {
  font-size: 0.875rem;
  color: #64748b;
  font-weight: 500;
}

.forgot-password {
  color: #2563eb;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.2s ease;
}

.forgot-password:hover {
  color: #1d4ed8;
  text-decoration: underline;
}

/* Login Button */
.login-button {
  width: 100%;
  padding: 1rem 1.5rem;
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
  color: white;
  border: none;
  border-radius: 16px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.login-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 20px 40px -12px rgba(37, 99, 235, 0.4);
}

.login-button:active {
  transform: translateY(0);
}

.login-button:disabled {
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
  gap: 0.75rem;
}

.loading-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
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

.divider {
  position: relative;
  margin: 2rem 0;
}

.divider::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: #e2e8f0;
}

.divider-text {
  background: white;
  color: #94a3b8;
  padding: 0 1rem;
  font-size: 0.875rem;
  position: relative;
  z-index: 1;
}

.social-button {
  width: 100%;
  padding: 1rem 1.5rem;
  background: white;
  border: 2px solid #e2e8f0;
  border-radius: 16px;
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.social-button:hover {
  background: #f8fafc;
  border-color: #cbd5e1;
  transform: translateY(-1px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.signup-text {
  color: #64748b;
  font-size: 0.875rem;
  margin: 0;
}

.signup-link {
  color: #2563eb;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}

.signup-link:hover {
  color: #1d4ed8;
  text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 480px) {
  .auth-container {
    padding: 1rem;
  }

  .auth-card {
    padding: 2rem 1.5rem;
    border-radius: 20px;
  }

  .welcome-title {
    font-size: 1.75rem;
  }

  .form-options {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .circle-1,
  .circle-2,
  .circle-3 {
    display: none;
  }
}

/* Focus states for accessibility */
.form-input:focus,
.checkbox-custom:focus-within,
.forgot-password:focus,
.login-button:focus,
.social-button:focus,
.signup-link:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
}
</style>
