<template>
  <div class="landing-layout">
    <Sidebar @logout="handleLogout" />
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<script>
import Sidebar from './shared/Sidebar.vue';

export default {
  name: 'LandingLayout',
  components: {
    Sidebar
  },
  methods: {
    handleLogout() {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped>
.landing-layout {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  margin-left: 240px;
  background: #f6f8fa;
  min-height: 100vh;
}

@media (max-width: 992px) {
  .landing-layout {
    flex-direction: column;
  }
  
  .main-content {
    margin-left: 0;
    margin-top: 60px;
  }
}
</style>
