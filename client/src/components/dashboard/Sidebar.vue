
<template>
  <div class="sidebar">
    <div class="sidebar-header">
      <h3>Dashboard</h3>
    </div>
    
    <ul class="sidebar-nav">
      <li>
        <router-link to="/dashboard" class="nav-link" active-class="active">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Overview</span>
        </router-link>
      </li>
      <li>
        <router-link to="/dashboard/posts" class="nav-link" active-class="active">
          <i class="fas fa-fw fa-file-alt"></i>
          <span>My Posts</span>
        </router-link>
      </li>
      <li>
        <router-link to="/dashboard/comments" class="nav-link" active-class="active">
          <i class="fas fa-fw fa-comments"></i>
          <span>My Comments</span>
        </router-link>
      </li>
      <li>
        <router-link to="/profile" class="nav-link" active-class="active">
          <i class="fas fa-fw fa-user"></i>
          <span>Profile</span>
        </router-link>
      </li>
      <li>
        <router-link to="/settings" class="nav-link" active-class="active">
          <i class="fas fa-fw fa-cog"></i>
          <span>Settings</span>
        </router-link>
      </li>
    </ul>
    
    <div class="sidebar-footer">
      <button @click="logout" class="btn btn-link">
        <i class="fas fa-fw fa-sign-out-alt"></i>
        <span>Logout</span>
      </button>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  methods: {
    ...mapActions('auth', ['logout'])
  }
}
</script>

<style scoped>
.sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  z-index: 1000;
}

.sidebar-header {
  padding: 1.5rem 1rem;
  border-bottom: 1px solid #eee;
}

.sidebar-header h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 600;
}

.sidebar-nav {
  flex: 1;
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar-nav li {
  margin: 0;
}

.sidebar-nav .nav-link {
  display: flex;
  align-items: center;
  padding: 1rem;
  color: #333;
  text-decoration: none;
  transition: all 0.3s;
}

.sidebar-nav .nav-link:hover {
  background-color: #f8f9fa;
  color: #4e73df;
}

.sidebar-nav .nav-link.active {
  background-color: #f8f9fa;
  color: #4e73df;
  border-left: 3px solid #4e73df;
}

.sidebar-nav .nav-link i {
  margin-right: 0.5rem;
  width: 20px;
  text-align: center;
}

.sidebar-footer {
  padding: 1rem;
  border-top: 1px solid #eee;
}

.sidebar-footer .btn {
  width: 100%;
  text-align: left;
  color: #333;
}

.sidebar-footer .btn:hover {
  color: #dc3545;
}
</style>