<template>
  <div class="chart-container">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import Chart from 'chart.js/auto'

export default {
  name: 'BarChart',
  props: {
    chartData: Object
  },
  setup(props) {
    const chartCanvas = ref(null)
    let chartInstance = null

    const renderChart = () => {
      if (chartInstance) {
        chartInstance.destroy()
      }

      if (chartCanvas.value) {
        const ctx = chartCanvas.value.getContext('2d')
        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: props.chartData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'top',
                labels: {
                  boxWidth: 12,
                  padding: 20,
                  usePointStyle: true
                }
              },
              tooltip: {
                backgroundColor: '#fff',
                titleColor: '#5a5c69',
                bodyColor: '#5a5c69',
                borderColor: '#e3e6f0',
                borderWidth: 1,
                padding: 15,
                usePointStyle: true,
                callbacks: {
                  label: function(context) {
                    return `${context.dataset.label}: ${context.raw}`
                  }
                }
              }
            },
            scales: {
              x: {
                grid: {
                  display: false,
                  drawBorder: false
                },
                ticks: {
                  color: '#858796'
                }
              },
              y: {
                grid: {
                  color: '#e3e6f0',
                  drawBorder: false
                },
                ticks: {
                  color: '#858796',
                  padding: 10
                }
              }
            }
          }
        })
      }
    }

    onMounted(renderChart)
    watch(() => props.chartData, renderChart)

    return {
      chartCanvas
    }
  }
}
</script>

<style scoped>
.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
}
</style>