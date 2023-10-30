<template>
    <div class="bg-slate-50 rounded-t-lg shadow-lg p-4">
      <p class="text-md font-semibold text-black my-3">Total Payment Based on Receiver Program</p>
        <div class="border-t"></div>
    </div>
    <div>
      <canvas class="bg-slate-50 rounded-b-lg shadow-lg p-4" ref="columnChart" width="400" height="400"></canvas>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import Chart from 'chart.js/auto';
  
  export default {
    setup() {
      const columnChart = ref(null);
  
      onMounted(() => {
        const ctx = columnChart.value.getContext('2d');
        new Chart(ctx, {
          type: 'bar', // Column chart
          data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
              {
                label: 'Allocations',
                data: [10, 20, 15, 25, 30, 18, 12, 28, 22, 19, 14, 27],
                backgroundColor: '#6c5ffc',
                borderColor: '#6c5ffc',
                borderWidth: 1,
              },
              {
                label: 'Programs',
                data: [5, 15, 8, 18, 22, 12, 10, 24, 16, 10, 8, 20],
                backgroundColor: '#05c3fb',
                borderColor: '#05c3fb',
                borderWidth: 1,
              },
              {
                label: 'Receivers',
                data: [15, 25, 18, 30, 35, 25, 20, 36, 28, 24, 20, 32],
                backgroundColor: 'rgba(48,205,115,255)',
                borderColor: 'rgba(48,205,115,255)',
                borderWidth: 1,
              },
              {
                label: 'Payments',
                data: [8, 12, 10, 20, 28, 15, 18, 26, 20, 16, 12, 22],
                backgroundColor: 'rgba(241,197,18,255)',
                borderColor: 'rgba(241,197,18,255)',
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
          },
        });
      });
  
      return {
        columnChart,
      };
    },
  };
  </script>
  