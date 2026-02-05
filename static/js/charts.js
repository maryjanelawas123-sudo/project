/**
 * Chart.js Integration for MCC Lost & Found
 */

class DashboardCharts {
    constructor() {
        this.charts = {};
        this.colors = {
            primary: '#3498db',
            success: '#27ae60',
            warning: '#f39c12',
            danger: '#e74c3c',
            info: '#17a2b8',
            secondary: '#95a5a6'
        };
    }
    
    /**
     * Initialize all charts
     */
    init() {
        this.initStatusChart();
        this.initCategoryChart();
        this.initTimelineChart();
        this.initClaimsChart();
    }
    
    /**
     * Status Distribution Pie Chart
     */
    initStatusChart() {
        const ctx = document.getElementById('statusChart');
        if (!ctx) return;
        
        fetch(`${APP_URL}/admin/chart-data`)
            .then(response => response.json())
            .then(data => {
                this.charts.status = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(data.status_counts),
                        datasets: [{
                            data: Object.values(data.status_counts),
                            backgroundColor: [
                                this.colors.warning,  // Lost
                                this.colors.info,     // Found
                                this.colors.success,  // Claimed
                                this.colors.secondary, // Pending
                                this.colors.danger    // Rejected
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
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
            });
    }
    
    /**
     * Category Distribution Bar Chart
     */
    initCategoryChart() {
        const ctx = document.getElementById('categoryChart');
        if (!ctx) return;
        
        fetch(`${APP_URL}/admin/chart-data`)
            .then(response => response.json())
            .then(data => {
                this.charts.category = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.category_counts.map(item => item.category),
                        datasets: [{
                            label: 'Number of Items',
                            data: data.category_counts.map(item => item.count),
                            backgroundColor: this.colors.primary,
                            borderColor: '#2980b9',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
    }
    
    /**
     * Timeline Line Chart
     */
    initTimelineChart() {
        const ctx = document.getElementById('timelineChart');
        if (!ctx) return;
        
        fetch(`${APP_URL}/admin/chart-data`)
            .then(response => response.json())
            .then(data => {
                const timeline = data.timeline.daily;
                
                this.charts.timeline = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: timeline.labels,
                        datasets: [{
                            label: 'Items Reported',
                            data: timeline.values,
                            borderColor: this.colors.primary,
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
    }
    
    /**
     * Claims Status Chart
     */
    initClaimsChart() {
        const ctx = document.getElementById('claimsChart');
        if (!ctx) return;
        
        // This would require additional API endpoint for claims data
        // For now, using dummy data
        const claimsData = {
            labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{
                data: [5, 12, 2],
                backgroundColor: [
                    this.colors.warning,
                    this.colors.success,
                    this.colors.danger
                ]
            }]
        };
        
        this.charts.claims = new Chart(ctx, {
            type: 'doughnut',
            data: claimsData,
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    /**
     * Update all charts
     */
    update() {
        Object.values(this.charts).forEach(chart => {
            if (chart && typeof chart.update === 'function') {
                chart.update();
            }
        });
    }
    
    /**
     * Destroy all charts
     */
    destroy() {
        Object.values(this.charts).forEach(chart => {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        });
        this.charts = {};
    }
    
    /**
     * Export chart as image
     */
    exportChart(chartName, filename = 'chart.png') {
        const chart = this.charts[chartName];
        if (!chart) return;
        
        const link = document.createElement('a');
        link.download = filename;
        link.href = chart.toBase64Image();
        link.click();
    }
}

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        const dashboardCharts = new DashboardCharts();
        dashboardCharts.init();
        
        // Export to global scope
        window.dashboardCharts = dashboardCharts;
    }
});