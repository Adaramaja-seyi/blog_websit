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
          <h1 class="brand-title">Join SecurePortal</h1>
          <p class="brand-subtitle">Create your account and join thousands of users who trust our platform for secure, reliable access to their digital world.</p>
          
          <div class="feature-list">
            <div class="feature-item">
              <div class="feature-icon">⚡</div>
              <span>Instant Account Setup</span>
            </div>
            <div class="feature-item">
              <div class="feature-icon">🛡️</div>
              <span>Enterprise Security</span>
            </div>
            <div class="feature-item">
              <div class="feature-icon">🌟</div>
              <span>Premium Features</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side - Register Form -->
      <div class="register-section">
        <div class="glass-card">
          <div class="card-header">
            <h2 class="register-title">Create Account</h2>
            <p class="register-subtitle">Join our platform to get started</p>
          </div>

          <form @submit.prevent="handleRegister" class="register-form">
            <!-- Full Name Input -->
            <div class="input-wrapper">
              <div class="input-group" :class="{ 'focused': nameFocused, 'filled': form.name }">
                <label class="floating-label">Full Name</label>
                <input
                  type="text"
                  v-model="form.name"
                  @focus="nameFocused = true"
                  @blur="nameFocused = false"
                  class="modern-input"
                  :disabled="loading"
                  required
                />
                <div class="input-decoration">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
              </div>
            </div>

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
                  :disabled="loading"
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
                  :disabled="loading"
                  required
                  minlength="8"
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
              <div class="password-hint">Use at least 8 characters</div>
            </div>

            <!-- Confirm Password Input -->
            <div class="input-wrapper">
              <div class="input-group" :class="{ 'focused': confirmPasswordFocused, 'filled': form.password_confirmation }">
                <label class="floating-label">Confirm Password</label>
                <input
                  :type="showConfirmPassword ? 'text' : 'password'"
                  v-model="form.password_confirmation"
                  @focus="confirmPasswordFocused = true"
                  @blur="confirmPasswordFocused = false"
                  class="modern-input"
                  :disabled="loading"
                  required
                />
                <button
                  type="button"
                  @click="showConfirmPassword = !showConfirmPassword"
                  class="password-toggle"
                >
                  <svg v-if="!showConfirmPassword" width="20" height="20" viewBox="0 0 24 24" fill="none">
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

            <!-- Terms and Conditions -->
            <div class="terms-section">
              <label class="checkbox-container">
                <input 
                  type="checkbox" 
                  v-model="form.terms"
                  :disabled="loading"
                  required
                />
                <span class="checkmark">
                  <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
                <span class="checkbox-label">
                  I agree to the 
                  <a href="#" @click.prevent="showTerms" class="terms-link">Terms of Service</a> 
                  and 
                  <a href="#" @click.prevent="showPrivacy" class="terms-link">Privacy Policy</a>
                </span>
              </label>
            </div>

            <!-- Error Messages -->
            <div v-if="errors.length" class="error-container">
              <div class="error-icon">⚠️</div>
              <div class="error-list">
                <div v-for="(error, index) in errors" :key="index" class="error-item">
                  {{ error }}
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              class="submit-button"
              :class="{ 'loading': loading }"
              :disabled="loading"
            >
              <span class="button-content">
                <span v-if="!loading" class="button-text">Create Account</span>
                <div v-else class="loading-state">
                  <div class="loading-spinner"></div>
                  <span class="button-text">Creating Account...</span>
                </div>
              </span>
              <div class="button-ripple"></div>
            </button>

            <!-- Social Register -->
            <div class="social-section">
              <div class="divider">
                <span>or sign up with</span>
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
            <p class="signin-prompt">
              Already have an account? 
              <router-link to="/login" class="signin-link">Sign in</router-link>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="loading-overlay">
      <div class="overlay-spinner"></div>
    </div>
  </div>
</template>

<script>
import api from '../../services/api';

export default {
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
      errors: [],
      nameFocused: false,
      emailFocused: false,
      passwordFocused: false,
      confirmPasswordFocused: false,
      showPassword: false,
      showConfirmPassword: false
    };
  },
  methods: {
    async handleRegister() {
      this.loading = true;
      this.errors = [];
      try {
        const response = await api.createUsers(this.form);
        this.$toast.success("Account created successfully!");
        setTimeout(() => {
          this.$router.push("/login");
        }, 1500);
      } catch (error) {
        if (error.response?.data?.errors) {
          for (const [key, value] of Object.entries(error.response.data.errors)) {
            this.errors.push(...value);
          }
        } else {
          this.errors = [error.response?.data?.message || "Registration failed"];
        }
      } finally {
        this.loading = false;
      }
    },
    showTerms() {
      // Show terms modal
    },
    showPrivacy() {
      // Show privacy modal
    }
  }
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

/* Register Section */
.register-section {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(20px);
  overflow-y: auto;
}

.glass-card {
  width: 100%;
  max-width: 500px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 24px;
  padding: 3rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 
    0 8px 32px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.5);
  margin: 2rem 0;
}

.card-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.register-title {
  font-size: 2.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.register-subtitle {
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

.password-hint {
  font-size: 0.875rem;
  color: #6b7280;
  margin-top: 0.5rem;
  padding-left: 1rem;
}

/* Terms Section */
.terms-section {
  margin: 2rem 0;
}

.checkbox-container {
  display: flex;
  align-items: flex-start;
  cursor: pointer;
  user-select: none;
  gap: 0.75rem;
}

.checkbox-container input[type="checkbox"] {
  display: none;
}

.checkmark {
  width: 20px;
  height: 20px;
  min-width: 20px;
  border: 2px solid #d1d5db;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  background: white;
  margin-top: 2px;
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
  line-height: 1.5;
}

.terms-link {
  color: #8b5cf6;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.terms-link:hover {
  color: #7c3aed;
  text-decoration: underline;
}

/* Error Container */
.error-container {
  display: flex;
  gap: 0.75rem;
  padding: 1rem;
  background: linear-gradient(135deg, #fee2e2, #fecaca);
  border-radius: 12px;
  border: 1px solid #f87171;
  margin-bottom: 1.5rem;
}

.error-icon {
  font-size: 1.25rem;
  flex-shrink: 0;
}

.error-list {
  flex: 1;
}

.error-item {
  color: #dc2626;
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.error-item:last-child {
  margin-bottom: 0;
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
  color: #949dad;
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

.signin-prompt {
  color: #6b7280;
  font-size: 0.875rem;
}

.signin-link {
  color: #8b5cf6;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.signin-link:hover {
  color: #7c3aed;
}

/* Loading Overlay */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  backdrop-filter: blur(4px);
}

.overlay-spinner {
  width: 60px;
  height: 60px;
  border: 4px solid rgba(139, 92, 246, 0.2);
  border-top: 4px solid #8b5cf6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .auth-content {
    grid-template-columns: 1fr;
  }
  
  .branding-section {
    display: none;
  }
  
  .register-section {
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
  }
}

@media (max-width: 768px) {
  .glass-card {
    padding: 2rem 1.5rem;
    margin: 1rem 0;
  }
  
  .register-title {
    font-size: 1.875rem;
  }
  
  .social-buttons {
    grid-template-columns: 1fr;
  }
  
  .checkbox-container {
    align-items: flex-start;
  }
}

@media (max-width: 480px) {
  .register-section {
    padding: 0.5rem;
  }
  
  .glass-card {
    padding: 1.5rem 1rem;
    border-radius: 20px;
  }
  
  .register-title {
    font-size: 1.75rem;
  }
  
  .brand-title {
    font-size: 2.5rem;
  }
  
  .input-wrapper {
    margin-bottom: 1.25rem;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
  
  .gradient-orb {
    animation: none;
  }
  
  .icon-inner {
    animation: none;
  }
}

/* Focus states */
.modern-input:focus,
.submit-button:focus,
.social-btn:focus,
.terms-link:focus,
.signin-link:focus,
.checkbox-container:focus-within {
  outline: 2px solid #8b5cf6;
  outline-offset: 2px;
}

/* High contrast mode */
@media (prefers-contrast: high) {
  .glass-card {
    background: white;
    border: 2px solid #000;
  }
  
  .input-group {
    border-color: #000;
  }
  
  .submit-button {
    background: #000;
    color: white;
  }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .glass-card {
    background: rgba(31, 41, 55, 0.95);
    color: #f9fafb;
  }
  
  .register-title {
    color: #f9fafb;
  }
  
  .register-subtitle {
    color: #d1d5db;
  }
  
  .floating-label {
    color: #d1d5db;
  }
  
  .modern-input {
    color: #f9fafb;
  }
  
  .input-group {
    background: rgba(55, 65, 81, 0.5);
    border-color: #4b5563;
  }
  
  .password-hint {
    color: #d1d5db;
  }
  
  .checkbox-label {
    color: #d1d5db;
  }
  
  .signin-prompt {
    color: #d1d5db;
  }
  
  .social-btn {
    background: rgba(55, 65, 81, 0.8);
    color: #f9fafb;
    border-color: #4b5563;
  }
}

/* Print styles */
@media print {
  .auth-wrapper {
    background: white;
  }
  
  .background-container,
  .gradient-orb,
  .grid-overlay,
  .branding-section {
    display: none;
  }
  
  .auth-content {
    grid-template-columns: 1fr;
  }
  
  .glass-card {
    background: white;
    box-shadow: none;
    border: 1px solid #000;
  }
}
</style>