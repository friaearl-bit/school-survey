const cfg = window.serverConfig || {};
// console.log(cfg.apiUrl, cfg.someValue, cfg.categoryLabels, cfg.categoryScores);


// ===================================================================
// ADMIN DASHBOARD - CHART.JS CONFIGURATIONS
// ===================================================================

// Theme Colors
const colors = {
  navy: '#1c2b53',
  navySoft: '#3b4b72',
  navyLight: '#5a6b8e',
  success: '#059669',
  warning: '#d97706',
  danger: '#dc2626',
  info: '#0284c7',
  light: '#f8fafc',
  glass: 'rgba(255, 255, 255, 0.85)',
  border: 'rgba(28, 43, 83, 0.1)'
};

// Chart.js Global Defaults
Chart.defaults.font.family = "'Poppins', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#4a5568';
Chart.defaults.plugins.legend.display = true;
Chart.defaults.plugins.legend.position = 'bottom';
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.padding = 20;

// ===================================================================
// 1. RADAR CHART: Average Score by Category
// ===================================================================
const radarCtx = document.getElementById('radarChart').getContext('2d');
const radarChart = new Chart(radarCtx, {
  type: 'radar',
  data: {
    // labels: ['Teaching Skills', 'Classroom Mgmt.', 'Student Engagement', 'Assignments', 'Professionalism'],
    labels: cfg.categoryLabels,
    datasets: [{
      label: 'Average Score (1-5)',
      // data: [4.2, 3.8, 4.5, 4.0, 4.7],
      data: cfg.categoryScores,
      backgroundColor: 'rgba(28, 43, 83, 0.2)',
      borderColor: colors.navy,
      borderWidth: 2,
      pointBackgroundColor: colors.navy,
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointRadius: 4,
      pointHoverRadius: 6
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      r: {
        beginAtZero: true,
        max: 5,
        ticks: {
          stepSize: 1,
          color: '#4a5568'
        },
        grid: {
          color: 'rgba(28, 43, 83, 0.1)'
        },
        pointLabels: {
          color: '#4a5568',
          font: {
            size: 11,
            weight: '500'
          }
        }
      }
    },
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: colors.navy,
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8,
        displayColors: false
      }
    }
  }
});

// ===================================================================
// 2. DOUGHNUT CHART: Responses by Section
// ===================================================================
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
const doughnutChart = new Chart(doughnutCtx, {
  type: 'doughnut',
  data: {
    // labels: ['STEM A-1', 'STEM B-1', 'STEM C-1', 'HUMSS A-1', 'HUMSS B-1'],
    labels: cfg.sectionNames,
    datasets: [{
      // data: [320, 280, 350, 180, 117],
      data: cfg.sectionResponses,
      backgroundColor: [
        'rgba(28, 43, 83, 0.8)',
        'rgba(59, 75, 114, 0.8)',
        'rgba(90, 107, 142, 0.8)',
        'rgba(122, 138, 170, 0.8)',
        'rgba(154, 169, 198, 0.8)'
      ],
      borderWidth: 0,
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '65%',
    plugins: {
      legend: {
        position: 'right'
      },
      tooltip: {
        backgroundColor: colors.navy,
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8,
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.raw || 0;
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const percentage = Math.round((value / total) * 100);
            return `${label}: ${value} (${percentage}%)`;
          }
        }
      }
    }
  }
});

// ===================================================================
// 3. LINE CHART: Responses Over Time
// ===================================================================
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineChart = new Chart(lineCtx, {
  type: 'line',
  data: {
    // labels: ['Jan 1', 'Jan 8', 'Jan 15', 'Jan 22', 'Jan 29', 'Feb 5', 'Feb 12'],
    labels: cfg.responseDates,
    datasets: [{
      label: 'Responses',
      // data: [120, 150, 200, 180, 250, 220, 300],
      data: cfg.responseCounts,
      borderColor: colors.navy,
      backgroundColor: 'rgba(28, 43, 83, 0.1)',
      fill: true,
      tension: 0.4,
      borderWidth: 3,
      pointBackgroundColor: colors.navy,
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointRadius: 5,
      pointHoverRadius: 7
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        grid: {
          display: false
        },
        ticks: {
          color: '#4a5568'
        }
      },
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(28, 43, 83, 0.1)'
        },
        ticks: {
          color: '#4a5568'
        }
      }
    },
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: colors.navy,
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8
      }
    }
  }
});

// ===================================================================
// 4. PIE CHART: Anonymous vs. Named Responses
// ===================================================================
const pieCtx = document.getElementById('pieChart').getContext('2d');
const pieChart = new Chart(pieCtx, {
  type: 'pie',
  data: {
    labels: ['Named Responses', 'Anonymous Responses'],
    datasets: [{
      // data: [890, 357],
      data: [cfg.totalNamed, cfg.totalAnonymous],
      backgroundColor: [
        'rgba(28, 43, 83, 0.8)',
        'rgba(59, 75, 114, 0.8)'
      ],
      borderWidth: 0,
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom'
      },
      tooltip: {
        backgroundColor: colors.navy,
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8,
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.raw || 0;
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const percentage = Math.round((value / total) * 100);
            return `${label}: ${value} (${percentage}%)`;
          }
        }
      }
    }
  }
});

// ===================================================================
// 5. HORIZONTAL BAR CHART: Top 5 Highest-Rated Questions
// ===================================================================
const horizontalBarCtx = document.getElementById('horizontalBarChart').getContext('2d');
const horizontalBarChart = new Chart(horizontalBarCtx, {
  type: 'bar',
  data: {
    // labels: [
    //   'Explains lessons clearly',
    //   'Treats students equally',
    //   'Encourages participation',
    //   'Maintains classroom order',
    //   'Uses visual aids effectively'
    // ],
    labels: cfg.questionTexts,
    datasets: [{
      label: 'Average Score',
      // data: [4.8, 4.7, 4.6, 4.5, 4.4],
      data: cfg.questionScores,
      backgroundColor: 'rgba(28, 43, 83, 0.8)',
      borderRadius: 6,
      barThickness: 20
    }]
  },
  options: {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        beginAtZero: true,
        max: 5,
        grid: {
          color: 'rgba(28, 43, 83, 0.1)'
        },
        ticks: {
          color: '#4a5568',
          stepSize: 1
        }
      },
      y: {
        grid: {
          display: false
        },
        ticks: {
          color: '#4a5568',
          font: {
            size: 11
          }
        }
      }
    },
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: colors.navy,
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8
      }
    }
  }
});

// ===================================================================
// 6. BAR CHART: Score Distribution
// ===================================================================
const barCtx = document.getElementById('barChart').getContext('2d');
const barChart = new Chart(barCtx, {
  type: 'bar',
  data: {
    // labels: ['1 (Poor)', '2 (Fair)', '3 (Average)', '4 (Good)', '5 (Excellent)'],
    labels: cfg.answerLabels,
    datasets: [{
      label: 'Count',
      // data: [12, 45, 120, 280, 440],
      data: cfg.answerCounts,
      backgroundColor: [
        'rgba(220, 38, 38, 0.8)',
        'rgba(239, 68, 68, 0.8)',
        'rgba(253, 164, 17, 0.8)',
        'rgba(34, 197, 94, 0.8)',
        'rgba(16, 185, 129, 0.8)'
      ],
      borderRadius: 6,
      barThickness: 30
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        grid: {
          display: false
        },
        ticks: {
          color: '#4a5568'
        }
      },
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(28, 43, 83, 0.1)'
        },
        ticks: {
          color: '#4a5568'
        }
      }
    },
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: colors.navy,
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8
      }
    }
  }
});

// ===================================================================
// 7. GROUPED BAR CHART: Instructor Performance by Category
// ===================================================================
const groupedBarCtx = document.getElementById('groupedBarChart').getContext('2d');
const groupedBarChart = new Chart(groupedBarCtx, {
  type: 'bar',
  data: {
    labels: cfg.categoryLabels,
    datasets: [
      {
        label: 'Alexandria Reyes',
        data: cfg.instructorScores['Alexandria Reyes'],
        backgroundColor: 'rgba(15, 23, 42, 0.8)',
        borderRadius: 6,
        barThickness: 20
      },
      {
        label: 'Bianca Montevend',
        data: cfg.instructorScores['Bianca Montevend'],
        backgroundColor: 'rgba(28, 43, 83, 0.8)',
        borderRadius: 6,
        barThickness: 20
      },
      {
        label: 'Maverick Evander',
        data: cfg.instructorScores['Maverick Evander'],
        backgroundColor: 'rgba(40, 60, 100, 0.8)',
        borderRadius: 6,
        barThickness: 20
      },
      {
        label: 'Nabia Gatchalian',
        data: cfg.instructorScores['Nabia Gatchalian'],
        backgroundColor: 'rgba(59, 75, 114, 0.8)',
        borderRadius: 6,
        barThickness: 20
      },
      {
        label: 'Ronan Slade Veniel',
        data: cfg.instructorScores['Ronan Slade Veniel'],
        backgroundColor: 'rgba(74, 95, 138, 0.8)',
        borderRadius: 6,
        barThickness: 20
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        grid: { display: false },
        ticks: { color: '#4a5568' }
      },
      y: {
        beginAtZero: true,
        max: 5,
        grid: { color: 'rgba(28, 43, 83, 0.1)' },
        ticks: { color: '#4a5568', stepSize: 1 }
      }
    },
    plugins: {
      legend: {
        position: 'top',
        align: 'end'
      },
      tooltip: {
        backgroundColor: 'rgba(15, 23, 42, 0.95)',
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8
      }
    }
  }
});