<!-- src/components/comments/Media.vue -->
<template>
  <div class="media-container">
    <h2>Media Management</h2>
    <p>Manage your media files here.</p>
  </div>
</template>

<script>
export default {
  name: 'Media',
};
</script>

<style scoped>
.media-container {
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