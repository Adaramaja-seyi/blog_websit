<template>
  <div class="activity-chart">
    <div class="chart-header d-flex justify-content-between align-items-center mb-4">
      <h5 class="mb-0">{{ title }}</h5>
      <div class="chart-legend d-flex">
        <div class="legend-item me-3" v-for="item in legendItems" :key="item.label">
          <span class="legend-color" :style="{ backgroundColor: item.color }"></span>
          <span class="legend-label">{{ item.label }}</span>
        </div>
      </div>
    </div>
    
    <div class="chart-container">
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import Chart from 'chart.js/auto'

export default {
  props: {
    title: {
      type: String,
      default: 'Monthly Activity'
    },
    data: {
      type: Object,
      required: true,
      validator: value => {
        return (
          Array.isArray(value.labels) &&
          Array.isArray(value.posts) &&
          Array.isArray(value.comments) &&
          Array.isArray(value.likes)
        )
      }
    }
  },
  setup(props) {
    const chartCanvas = ref(null)
    let chartInstance = null
    
    const legendItems = [
      { label: 'Posts', color: '#4e73df' },
      { label: 'Comments', color: '#1cc88a' },
      { label: 'Likes', color: '#e74a3b' }
    ]
    
    const initChart = () => {
      if (chartInstance) {
        chartInstance.destroy()
      }
      
      if (chartCanvas.value) {
        const ctx = chartCanvas.value.getContext('2d')
        
        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: props.data.labels,
            datasets: [
              {
                label: 'Posts',
                backgroundColor: '#4e73df',
                borderColor: '#4e73df',
                borderRadius: 4,
                borderWidth: 1,
                data: props.data.posts
              },
              {
                label: 'Comments',
                backgroundColor: '#1cc88a',
                borderColor: '#1cc88a',
                borderRadius: 4,
                borderWidth: 1,
                data: props.data.comments
              },
              {
                label: 'Likes',
                backgroundColor: '#e74a3b',
                borderColor: '#e74a3b',
                borderRadius: 4,
                borderWidth: 1,
                data: props.data.likes
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#fff',
                titleColor: '#5a5c69',
                bodyColor: '#858796',
                borderColor: '#dddfeb',
                borderWidth: 1,
                padding: 15,
                callbacks: {
                  label: function(context) {
                    return `${context.dataset.label}: ${context.raw}`
                  }
                }
              }
            },
            scales: {
              x: {
                stacked: false,
                grid: {
                  display: false,
                  drawBorder: false
                },
                ticks: {
                  color: '#858796'
                }
              },
              y: {
                stacked: false,
                grid: {
                  color: 'rgba(0, 0, 0, 0.05)',
                  drawBorder: false
                },
                ticks: {
                  color: '#858796',
                  beginAtZero: true,
                  precision: 0
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index'
            }
          }
        })
      }
    }
    
    onMounted(() => {
      initChart()
    })
    
    watch(() => props.data, () => {
      initChart()
    }, { deep: true })
    
    return {
      chartCanvas,
      legendItems
    }
  }
}
</script>

<style scoped>
.activity-chart {
  background-color: #fff;
  border-radius: 0.35rem;
  padding: 1.25rem;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.chart-header {
  padding: 0 0.5rem;
}

.chart-container {
  position: relative;
  height: 300px;
  min-height: 300px;
  flex-grow: 1;
}

.legend-item {
  display: flex;
  align-items: center;
  font-size: 0.8rem;
  color: #5a5c69;
}

.legend-color {
  display: inline-block;
  width: 12px;
  height: 12px;
  border-radius: 3px;
  margin-right: 6px;
}

.legend-label {
  font-size: 0.8rem;
  color: #5a5c69;
}
</style>