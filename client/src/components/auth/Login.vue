<template>
  <div class="auth-wrapper">
    <!-- Animated Background -->
    <div class="background-container">
      <div class="gradient-orb orb-1"></div>
      <div class="gradient-orb orb-2"></div>
      <div class="gradient-orb orb-3"></div>
      <div class="grid-overlay"></div>
    </div>

    <!-- Main Content -->
    <div class="auth-content">
      <!-- Left Side - Branding -->
      <div class="branding-section">
        <div class="brand-container">
          <div class="brand-icon">
            <div class="icon-inner">
              <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M16 2L26 8V24L16 30L6 24V8L16 2Z" fill="url(#brandGradient)" stroke="white" stroke-width="1"/>
                <circle cx="16" cy="16" r="4" fill="white"/>
                <defs>
                  <linearGradient id="brandGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#8B5CF6"/>
                    <stop offset="100%" style="stop-color:#3B82F6"/>
                  </linearGradient>
                </defs>
              </svg>
            </div>
          </div>
          <h1 class="brand-title">SecurePortal</h1>
          <p class="brand-subtitle">Experience the future of authentication with our advanced security platform.</p>
          
          <div class="feature-list">
            <div class="feature-item">
              <div class="feature-icon">✨</div>
              <span>Advanced Encryption</span>
            </div>
            <div class="feature-item">
              <div class="feature-icon">🚀</div>
              <span>Lightning Fast</span>
            </div>
            <div class="feature-item">
              <div class="feature-icon">🔒</div>
              <span>Bank-Level Security</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side - Login Form -->
      <div class="login-section">
        <div class="glass-card">
          <div class="card-header">
            <h2 class="login-title">Welcome Back</h2>
            <p class="login-subtitle">Access your secure dashboard</p>
          </div>

          <form @submit.prevent="handleLogin" class="login-form">
            <!-- Email Input -->
            <div class="input-wrapper">
              <div class="input-group" :class="{ 'focused': emailFocused, 'filled': form.email }">
                <label class="floating-label">Email Address</label>
                <input
                  type="email"
                  v-model="form.email"
                  @focus="emailFocused = true"
                  @blur="emailFocused = false"
                  class="modern-input"
                  required
                />
                <div class="input-decoration">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" fill="none"/>
                    <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2" fill="none"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Password Input -->
            <div class="input-wrapper">
              <div class="input-group" :class="{ 'focused': passwordFocused, 'filled': form.password }">
                <label class="floating-label">Password</label>
                <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="form.password"
                  @focus="passwordFocused = true"
                  @blur="passwordFocused = false"
                  class="modern-input"
                  required
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="password-toggle"
                >
                  <svg v-if="!showPassword" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M1 12S5 4 12 4S23 12 23 12S19 20 12 20S1 12 1 12Z" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none"/>
                  </svg>
                  <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20C5 20 1 12 1 12A16.16 16.16 0 0 1 6.06 6.06" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4C19 4 23 12 23 12A16.16 16.16 0 0 1 19.07 16.94" stroke="currentColor" stroke-width="2" fill="none"/>
                    <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Form Options -->
            <div class="form-options">
              <label class="checkbox-container">
                <input type="checkbox" v-model="rememberMe">
                <span class="checkmark">
                  <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
                <span class="checkbox-label">Remember me</span>
              </label>
              
              <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              class="submit-button"
              :class="{ 'loading': loading }"
              :disabled="loading"
            >
              <span class="button-content">
                <span v-if="!loading" class="button-text">Sign In</span>
                <div v-else class="loading-state">
                  <div class="loading-spinner"></div>
                  <span class="button-text">Authenticating...</span>
                </div>
              </span>
              <div class="button-ripple"></div>
            </button>

            <!-- Social Login -->
            <div class="social-section">
              <div class="divider">
                <span>or continue with</span>
              </div>
              
              <div class="social-buttons">
                <button type="button" class="social-btn google-btn">
                  <svg width="18" height="18" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                  </svg>
                  Google
                </button>
                
                <button type="button" class="social-btn github-btn">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                  </svg>
                  GitHub
                </button>
              </div>
            </div>
          </form>

          <!-- Footer -->
          <div class="card-footer">
            <p class="signup-prompt">
              Don't have an account? 
              <router-link to="/register" class="signup-link">Create one</router-link>
            </p>
          </div>
        </div>
      </div>
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
      emailFocused: false,
      passwordFocused: false,
      showPassword: false,
      rememberMe: false,
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
        // Redirect to landing feed
        setTimeout(() => {
          this.$router.push({name: 'landing-feed'});
        }, 2000);
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
/* Reset and Base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.auth-wrapper {
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif;
  position: relative;
  overflow: hidden;
}

/* Animated Background */
.background-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  z-index: 0;
}

.gradient-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  animation: float 8s ease-in-out infinite;
  opacity: 0.7;
}

.orb-1 {
  width: 400px;
  height: 400px;
  background: linear-gradient(45deg, #8B5CF6, #3B82F6);
  top: -200px;
  right: -200px;
  animation-delay: 0s;
}

.orb-2 {
  width: 300px;
  height: 300px;
  background: linear-gradient(45deg, #06D6A0, #118AB2);
  bottom: -150px;
  left: -150px;
  animation-delay: -3s;
}

.orb-3 {
  width: 250px;
  height: 250px;
  background: linear-gradient(45deg, #FFD23F, #FF6B6B);
  top: 50%;
  left: 30%;
  animation-delay: -6s;
}

.grid-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: 
    linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  background-size: 50px 50px;
  opacity: 0.3;
}

@keyframes float {
  0%, 100% { 
    transform: translateY(0px) rotate(0deg) scale(1); 
  }
  33% { 
    transform: translateY(-30px) rotate(120deg) scale(1.1); 
  }
  66% { 
    transform: translateY(15px) rotate(240deg) scale(0.9); 
  }
}

/* Main Content Layout */
.auth-content {
  position: relative;
  z-index: 1;
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 100vh;
}

/* Branding Section */
.branding-section {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4rem;
  color: white;
}

.brand-container {
  max-width: 500px;
}

.brand-icon {
  margin-bottom: 2rem;
}

.icon-inner {
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

.brand-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #ffffff, #e0e7ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.brand-subtitle {
  font-size: 1.25rem;
  line-height: 1.6;
  opacity: 0.9;
  margin-bottom: 3rem;
}

.feature-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.feature-item:hover {
  transform: translateX(10px);
  background: rgba(255, 255, 255, 0.15);
}

.feature-icon {
  font-size: 1.5rem;
}

/* Login Section */
.login-section {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(20px);
}

.glass-card {
  width: 100%;
  max-width: 450px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 24px;
  padding: 3rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 
    0 8px 32px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.5);
}

.card-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.login-title {
  font-size: 2.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.login-subtitle {
  color: #6b7280;
  font-size: 1rem;
}

/* Modern Input Styles */
.input-wrapper {
  margin-bottom: 1.5rem;
}

.input-group {
  position: relative;
  border-radius: 16px;
  border: 2px solid #e5e7eb;
  background: #fafafa;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

.input-group.focused {
  border-color: #8b5cf6;
  background: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(139, 92, 246, 0.15);
}

.input-group.filled .floating-label {
  transform: translateY(-12px) scale(0.75);
  color: #8b5cf6;
}

.floating-label {
  position: absolute;
  left: 1rem;
  top: 1.25rem;
  color: #9ca3af;
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  pointer-events: none;
  z-index: 2;
}

.modern-input {
  width: 100%;
  padding: 1.5rem 3rem 1rem 1rem;
  border: none;
  background: transparent;
  font-size: 1rem;
  color: #1f2937;
  outline: none;
  z-index: 1;
  position: relative;
}

.input-decoration {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  transition: color 0.3s ease;
}

.input-group.focused .input-decoration {
  color: #8b5cf6;
}

.password-toggle {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: 4px;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.password-toggle:hover {
  color: #8b5cf6;
  background: rgba(139, 92, 246, 0.1);
}

/* Form Options */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.checkbox-container {
  display: flex;
  align-items: center;
  cursor: pointer;
  user-select: none;
}

.checkbox-container input[type="checkbox"] {
  display: none;
}

.checkmark {
  width: 20px;
  height: 20px;
  border: 2px solid #d1d5db;
  border-radius: 6px;
  margin-right: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  background: white;
}

.checkbox-container input:checked + .checkmark {
  background: linear-gradient(135deg, #8b5cf6, #3b82f6);
  border-color: #8b5cf6;
  transform: scale(1.05);
}

.checkbox-container input:checked + .checkmark svg {
  opacity: 1;
  transform: scale(1);
}

.checkmark svg {
  opacity: 0;
  transform: scale(0.5);
  transition: all 0.3s ease;
}

.checkbox-label {
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}

.forgot-link {
  color: #8b5cf6;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.3s ease;
  position: relative;
}

.forgot-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 2px;
  bottom: -2px;
  left: 0;
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
  transition: width 0.3s ease;
}

.forgot-link:hover::after {
  width: 100%;
}

/* Submit Button */
.submit-button {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
  color: white;
  border: none;
  border-radius: 16px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  margin-bottom: 2rem;
}

.submit-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 20px 40px rgba(139, 92, 246, 0.4);
}

.submit-button:active {
  transform: translateY(0);
}

.submit-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.button-content {
  position: relative;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.loading-state {
  display: flex;
  align-items: center;
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
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.button-ripple {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
  transform: scale(0);
  opacity: 0;
  pointer-events: none;
}

.submit-button:active .button-ripple {
  animation: ripple 0.6s ease-out;
}

@keyframes ripple {
  to {
    transform: scale(2);
    opacity: 1;
  }
}

/* Social Section */
.social-section {
  margin-bottom: 2rem;
}

.divider {
  position: relative;
  text-align: center;
  margin: 2rem 0;
}

.divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: #e5e7eb;
}

.divider span {
  background: rgba(255, 255, 255, 0.95);
  color: #9ca3af;
  padding: 0 1rem;
  font-size: 0.875rem;
  position: relative;
}

.social-buttons {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.social-btn {
  padding: 0.875rem 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  background: white;
  color: #374151;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.social-btn:hover {
  transform: translateY(-2px);
  border-color: #d1d5db;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.github-btn:hover {
  background: #24292e;
  color: white;
  border-color: #24292e;
}

/* Card Footer */
.card-footer {
  text-align: center;
}

.signup-prompt {
  color: #6b7280;
  font-size: 0.875rem;
}

.signup-link {
  color: #8b5cf6;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.signup-link:hover {
  color: #7c3aed;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .auth-content {
    grid-template-columns: 1fr;
  }
  
  .branding-section {
    display: none;
  }
  
  .login-section {
    background: rgba(255, 255, 255, 0.1);
  }
}

@media (max-width: 480px) {
  .login-section {
    padding: 1rem;
  }
  
  .glass-card {
    padding: 2rem 1.5rem;
    border-radius: 20px;
  }
  
  .login-title {
    font-size: 1.875rem;
  }
  
  .form-options {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .social-buttons {
    grid-template-columns: 1fr;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Focus states */
.modern-input:focus,
.submit-button:focus,
.social-btn:focus,
.forgot-link:focus,
.signup-link:focus {
  outline: 2px solid #8b5cf6;
  outline-offset: 2px;
}
</style>