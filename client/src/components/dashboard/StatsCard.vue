<template>
  <div class="card border-left-primary shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
            {{ title }}
          </div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
            {{ value }}
          </div>
        </div>
        <div class="col-auto">
          <i class="fas" :class="iconClass" style="font-size: 2rem; color: #dddfeb;"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    },
    value: {
      type: [String, Number],
      required: true
    },
    icon: {
      type: String,
      required: true
    }
  },
  computed: {
    iconClass() {
      return `fa-${this.icon}`
    }
  }
}
</script>

<style scoped>
.card {
  border-radius: 0.35rem;
  transition: transform 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
}

.text-gray-800 {
  color: #5a5c69 !important;
}

.border-left-primary {
  border-left: 0.25rem solid #4e73df !important;
}
</style>