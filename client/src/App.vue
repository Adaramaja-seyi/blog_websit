<template>
  <div>
    <!-- Global Navigation -->
    <Navbar />

    <main>
      <router-view >
      </router-view>
    </main>

   
  </div>
</template>

<script>
import Navbar from '../src/components/shared/Navbar.vue'


export default {
  components: {
    Navbar,
    
  },
  
  
}
</script>
