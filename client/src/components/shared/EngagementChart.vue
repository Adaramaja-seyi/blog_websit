<template>
  <div class="engagement-chart">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script>
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

export default {
  name: 'EngagementChart',
  props: {
    data: {
      type: Object,
      required: true,
      validator: value => {
        return (
          Array.isArray(value.labels) &&
          Array.isArray(value.likes) &&
          Array.isArray(value.comments)
        );
      }
    },
    aspectRatio: {
      type: Number,
      default: 2
    }
  },
  data() {
    return {
      chart: null
    };
  },
  mounted() {
    this.renderChart();
  },
  watch: {
    data: {
      deep: true,
      handler() {
        if (this.chart) {
          this.chart.data.labels = this.data.labels;
          this.chart.data.datasets[0].data = this.data.likes;
          this.chart.data.datasets[1].data = this.data.comments;
          this.chart.update();
        } else {
          this.renderChart();
        }
      }
    }
  },
  beforeUnmount() {
    if (this.chart) {
      this.chart.destroy();
    }
  },
  methods: {
    renderChart() {
      const ctx = this.$refs.chartCanvas.getContext('2d');
      
      this.chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: this.data.labels,
          datasets: [
            {
              label: 'Likes',
              data: this.data.likes,
              borderColor: '#4361ee',
              backgroundColor: 'rgba(67, 97, 238, 0.1)',
              tension: 0.3,
              borderWidth: 2,
              fill: true
            },
            {
              label: 'Comments',
              data: this.data.comments,
              borderColor: '#4cc9f0',
              backgroundColor: 'rgba(76, 201, 240, 0.1)',
              tension: 0.3,
              borderWidth: 2,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          aspectRatio: this.aspectRatio,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                usePointStyle: true,
                padding: 20
              }
            },
            tooltip: {
              mode: 'index',
              intersect: false
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              }
            },
            y: {
              beginAtZero: true,
              grid: {
                drawBorder: false
              }
            }
          }
        }
      });
    }
  }
};
</script>

<style scoped>
.engagement-chart {
  position: relative;
  width: 100%;
  min-height: 300px;
}
</style>