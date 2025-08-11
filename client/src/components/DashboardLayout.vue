<template>
  <div class="container-fluid dashboard-container">
    <div class="row flex-nowrap">
      <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0">
        <Sidebar @logout="handleLogout" />
      </div>
      <div class="col py-3">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from './shared/Sidebar.vue';
export default {
  components: { Sidebar },
  methods: {
    handleLogout() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    }
  }
};
</script>