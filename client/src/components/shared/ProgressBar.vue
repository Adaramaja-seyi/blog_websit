<template>
  <div class="progress-container">
    <div class="progress-bar" :style="{ width: progressPercentage + '%' }"></div>
    <span class="progress-text" v-if="showText">{{ progressPercentage }}%</span>
  </div>
</template>

<script>
export default {
  name: 'ProgressBar',
  props: {
    value: {
      type: Number,
      required: true,
      validator: value => value >= 0
    },
    max: {
      type: Number,
      default: 100
    },
    showText: {
      type: Boolean,
      default: true
    },
    color: {
      type: String,
      default: '#4361ee' // Default to primary color
    }
  },
  computed: {
    progressPercentage() {
      const percentage = (this.value / this.max) * 100;
      return Math.min(100, Math.max(0, Math.round(percentage)));
    }
  }
};
</script>

<style scoped>
.progress-container {
  height: 8px;
  width: 100%;
  background-color: #e9ecef;
  border-radius: 4px;
  position: relative;
  margin-top: 0.5rem;
}

.progress-bar {
  height: 100%;
  border-radius: 4px;
  background-color: v-bind(color);
  transition: width 0.3s ease;
}

.progress-text {
  position: absolute;
  right: 0;
  top: -20px;
  font-size: 0.75rem;
  color: #6c757d;
}
</style>