<template>
  <div class="stat-card" :style="`--card-color: ${color}`">
    <div class="card-icon">
      <i :class="`fas fa-${icon}`"></i>
    </div>
    <div class="card-content">
      <h6 class="card-title">{{ title }}</h6>
      <p class="card-value">{{ value }}</p>
    </div>
    <div class="card-trend" :class="trend">
      <i :class="`fas fa-arrow-${trend}`"></i>
      <span>{{ change }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatCard',
  props: {
    icon: String,
    title: String,
    value: [Number, String],
    color: {
      type: String,
      default: '#4e73df'
    },
    trend: {
      type: String,
      validator: function(value) {
        return ['up', 'down'].includes(value)
      }
    },
    change: String
  }
}
</script>

<style scoped>
.stat-card {
  background: white;
  border-radius: 0.35rem;
  padding: 1.25rem;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
}

.card-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: rgba(var(--card-color-rgb), 0.1);
  color: var(--card-color);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  flex-shrink: 0;
}

.card-icon i {
  font-size: 1.25rem;
}

.card-content {
  flex: 1;
}

.card-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #858796;
  margin: 0 0 0.25rem 0;
  text-transform: uppercase;
}

.card-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #5a5c69;
  margin: 0;
}

.card-trend {
  display: flex;
  align-items: center;
  font-size: 0.875rem;
  margin-left: 1rem;
}

.card-trend.up {
  color: #1cc88a;
}

.card-trend.down {
  color: #e74a3b;
}

.card-trend i {
  margin-right: 0.25rem;
}

/* Convert color to RGB for the opacity effect */
.stat-card {
  --card-color-rgb: calc(var(--card-color-r) * 255), calc(var(--card-color-g) * 255), calc(var(--card-color-b) * 255);
}
</style>