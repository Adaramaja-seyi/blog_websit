<template>
  
  <div class="home-container">
    <h1>Welcome to BlogPlatform</h1>
    <p class="lead">Join our community of writers and readers</p>
    <div class="auth-buttons">
      <router-link to="/register" class="btn btn-primary me-3"
        >Register</router-link
      >
      <router-link to="/login" class="btn btn-secondary">Login</router-link>
    </div>
  </div>
</template>

<script>

export default {
  name: "Home",
  components: {
  
    
  },
  
};
</script>

<style scoped>
.home-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 80vh;
  text-align: center;
}

.auth-buttons {
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  font-size: 1.2rem;
}
</style>
