<template>
  <div class="auth-container">
    <div class="auth-card">
      <!-- Loading overlay -->
      <div v-if="loading" class="loading-overlay">
        <div class="spinner"></div>
      </div>

      <!-- Header -->
      <div class="header">
        <h1>Create Account</h1>
        <p>Join our platform to get started</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleRegister" class="form">
        <div class="form-group">
          <label>Full Name</label>
          <input
            type="text"
            v-model="form.name"
            placeholder="Enter your full name"
            :disabled="loading"
            required
          />
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input
            type="email"
            v-model="form.email"
            placeholder="your@email.com"
            :disabled="loading"
            required
          />
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="password-input">
            <input
              :type="showPassword ? 'text' : 'password'"
              v-model="form.password"
              placeholder="Create a password"
              :disabled="loading"
              required
              minlength="8"
            />
            <button type="button" class="toggle-password" @click="showPassword = !showPassword">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path v-if="showPassword" d="M12 6C15.79 6 19.17 8.13 20.82 11.5C19.17 14.87 15.79 17 12 17C8.21 17 4.83 14.87 3.18 11.5C4.83 8.13 8.21 6 12 6ZM12 4C7 4 2.73 7.11 1 11.5C2.73 15.89 7 19 12 19C17 19 21.27 15.89 23 11.5C21.27 7.11 17 4 12 4ZM12 9C13.38 9 14.5 10.12 14.5 11.5C14.5 12.88 13.38 14 12 14C10.62 14 9.5 12.88 9.5 11.5C9.5 10.12 10.62 9 12 9ZM12 7C9.52 7 7.5 9.02 7.5 11.5C7.5 13.98 9.52 16 12 16C14.48 16 16.5 13.98 16.5 11.5C16.5 9.02 14.48 7 12 7Z" fill="#888"/>
                <path v-else d="M12 6C15.79 6 19.17 8.13 20.82 11.5C19.17 14.87 15.79 17 12 17C8.21 17 4.83 14.87 3.18 11.5C4.83 8.13 8.21 6 12 6ZM12 4C7 4 2.73 7.11 1 11.5C2.73 15.89 7 19 12 19C17 19 21.27 15.89 23 11.5C21.27 7.11 17 4 12 4ZM12 9C13.38 9 14.5 10.12 14.5 11.5C14.5 12.88 13.38 14 12 14C10.62 14 9.5 12.88 9.5 11.5C9.5 10.12 10.62 9 12 9Z" fill="#888"/>
              </svg>
            </button>
          </div>
          <div class="hint">Use at least 8 characters</div>
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <div class="password-input">
            <input
              :type="showConfirmPassword ? 'text' : 'password'"
              v-model="form.password_confirmation"
              placeholder="Confirm your password"
              :disabled="loading"
              required
            />
            <button type="button" class="toggle-password" @click="showConfirmPassword = !showConfirmPassword">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path v-if="showConfirmPassword" d="M12 6C15.79 6 19.17 8.13 20.82 11.5C19.17 14.87 15.79 17 12 17C8.21 17 4.83 14.87 3.18 11.5C4.83 8.13 8.21 6 12 6ZM12 4C7 4 2.73 7.11 1 11.5C2.73 15.89 7 19 12 19C17 19 21.27 15.89 23 11.5C21.27 7.11 17 4 12 4ZM12 9C13.38 9 14.5 10.12 14.5 11.5C14.5 12.88 13.38 14 12 14C10.62 14 9.5 12.88 9.5 11.5C9.5 10.12 10.62 9 12 9ZM12 7C9.52 7 7.5 9.02 7.5 11.5C7.5 13.98 9.52 16 12 16C14.48 16 16.5 13.98 16.5 11.5C16.5 9.02 14.48 7 12 7Z" fill="#888"/>
                <path v-else d="M12 6C15.79 6 19.17 8.13 20.82 11.5C19.17 14.87 15.79 17 12 17C8.21 17 4.83 14.87 3.18 11.5C4.83 8.13 8.21 6 12 6ZM12 4C7 4 2.73 7.11 1 11.5C2.73 15.89 7 19 12 19C17 19 21.27 15.89 23 11.5C21.27 7.11 17 4 12 4ZM12 9C13.38 9 14.5 10.12 14.5 11.5C14.5 12.88 13.38 14 12 14C10.62 14 9.5 12.88 9.5 11.5C9.5 10.12 10.62 9 12 9Z" fill="#888"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="terms">
          <label class="checkbox-container">
            <input 
              type="checkbox" 
              v-model="form.terms"
              :disabled="loading"
              required
            />
            <span class="checkmark"></span>
            <span class="terms-text">
              I agree to the <a href="#" @click.prevent="showTerms">Terms</a> and <a href="#" @click.prevent="showPrivacy">Privacy Policy</a>
            </span>
          </label>
        </div>

        <div v-if="errors.length" class="error-message">
          <div v-for="(error, index) in errors" :key="index">
            {{ error }}
          </div>
        </div>

        <button type="submit" class="submit-btn" :disabled="loading">
          <span v-if="!loading">Create Account</span>
          <span v-else>Processing...</span>
        </button>
      </form>

      <div class="footer">
        Already have an account? <router-link to="/login">Sign in</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../services/api'; // Adjust path if needed 
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
.auth-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background-color: #f8fafc;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.auth-card {
  width: 100%;
  max-width: 440px;
  background: white;
  border-radius: 12px;
  padding: 40px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
}

.header {
  text-align: center;
  margin-bottom: 32px;
}

.header h1 {
  font-size: 24px;
  font-weight: 600;
  color: #1a1a1a;
  margin-bottom: 8px;
}

.header p {
  color: #666;
  font-size: 15px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 14px;
  color: #444;
  margin-bottom: 8px;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.2s;
  background-color: #fcfcfc;
}

.form-group input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  background-color: white;
}

.password-input {
  position: relative;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  color: #888;
}

.hint {
  font-size: 12px;
  color: #777;
  margin-top: 6px;
}

.terms {
  margin: 24px 0;
}

.checkbox-container {
  display: flex;
  align-items: center;
  cursor: pointer;
  user-select: none;
  font-size: 14px;
}

.checkbox-container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.checkmark {
  position: relative;
  height: 18px;
  width: 18px;
  min-width: 18px;
  background-color: white;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  margin-right: 10px;
}

.checkbox-container:hover input ~ .checkmark {
  border-color: #6366f1;
}

.checkbox-container input:checked ~ .checkmark {
  background-color: #6366f1;
  border-color: #6366f1;
}

.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

.checkbox-container input:checked ~ .checkmark:after {
  display: block;
}

.checkbox-container .checkmark:after {
  left: 6px;
  top: 2px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.terms-text {
  color: #555;
}

.terms-text a {
  color: #6366f1;
  text-decoration: none;
  font-weight: 500;
}

.terms-text a:hover {
  text-decoration: underline;
}

.error-message {
  background: #fee2e2;
  color: #dc2626;
  padding: 12px;
  border-radius: 8px;
  font-size: 14px;
  margin-bottom: 16px;
}

.error-message div {
  margin: 4px 0;
}

.submit-btn {
  width: 100%;
  padding: 14px;
  background-color: #6366f1;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  margin-top: 8px;
}

.submit-btn:hover {
  background-color: #4f46e5;
}

.submit-btn:disabled {
  background-color: #a5b4fc;
  cursor: not-allowed;
}

.footer {
  text-align: center;
  margin-top: 24px;
  font-size: 14px;
  color: #666;
}

.footer a {
  color: #6366f1;
  text-decoration: none;
  font-weight: 500;
}

.footer a:hover {
  text-decoration: underline;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  z-index: 10;
}

.spinner {
  width: 24px;
  height: 24px;
  border: 3px solid rgba(99, 102, 241, 0.2);
  border-top-color: #6366f1;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@media (max-width: 480px) {
  .auth-card {
    padding: 32px 24px;
  }
  
  .header h1 {
    font-size: 22px;
  }
}
</style>