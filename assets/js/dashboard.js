document.addEventListener('DOMContentLoaded', function() {
    // Registrations per day chart
    const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsChart = new Chart(registrationsCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Registrations',
                data: [38, 19, 32, 58, 33, 38, 38],
                backgroundColor: 'rgba(66, 139, 202, 0.6)',
                borderColor: 'rgba(66, 139, 202, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 60,
                    ticks: {
                        stepSize: 10
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

    // Average royalty chart
    const royaltyCtx = document.getElementById('royaltyChart').getContext('2d');
    const royaltyChart = new Chart(royaltyCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Average Royalty',
                data: [60, 70, 35, 70, 45, 115, 65],
                backgroundColor: 'rgba(130, 106, 249, 0.2)',
                borderColor: 'rgba(130, 106, 249, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 140,
                    ticks: {
                        stepSize: 20
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            if (context[0].dataIndex == 2) {
                                return '22 February';
                            }
                            return context[0].label;
                        },
                        label: function(context) {
                            if (context.dataIndex == 2) {
                                return '$70.68';
                            }
                            return '$' + context.raw;
                        }
                    }
                }
            }
        }
    });

    // Handle tab buttons
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.querySelector('.active').classList.remove('active');
            this.classList.add('active');
        });
    });

    // Add click event for menu items
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            document.querySelector('.menu-item.active').classList.remove('active');
            this.classList.add('active');
        });
    });

    // Add click event for logout button
    const logoutBtn = document.querySelector('.logout');
    logoutBtn.addEventListener('click', function() {
        console.log('Logout clicked');
        // Add logout functionality here
    });
});