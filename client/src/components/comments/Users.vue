<!-- src/components/Users.vue -->
<template>
  <div class="users-container">
    <h2>Users Management</h2>
    <p>Manage your users here.</p>
  </div>
</template>

<script>
export default {
  name: 'Users',
};
</script>

<style scoped>
.users-container {
  padding: 1.5rem;
  background-color: #f8f9fc;
}

h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #4e73df;
  margin-bottom: 1rem;
}

p {
  color: #858796;
}
</style>