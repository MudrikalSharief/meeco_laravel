document.addEventListener('DOMContentLoaded', function() {
    // Set default date range for daily chart (last 7 days)
    const today = new Date();
    const lastWeek = new Date();
    lastWeek.setDate(today.getDate() - 6);
    
    // Get references to date inputs
    const dateFromInput = document.getElementById('dateFrom');
    const dateToInput = document.getElementById('dateTo');
    
    // Set initial values
    dateFromInput.valueAsDate = lastWeek;
    
    // Function to update "To" date based on "From" date
    function updateToDate() {
        const fromDate = new Date(dateFromInput.value);
        const toDate = new Date(fromDate);
        toDate.setDate(fromDate.getDate() + 6); // 7 days total (including start date)
        dateToInput.valueAsDate = toDate;
    }
    
    // Set initial "To" date
    updateToDate();
    
    // Update "To" date whenever "From" date changes
    dateFromInput.addEventListener('change', updateToDate);
    
    // Set up year selector for monthly chart
    const currentYear = new Date().getFullYear();
    const yearSelect = document.getElementById('yearSelect');
    
    // Add last 5 years to selection
    for (let year = currentYear; year >= currentYear - 4; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }
    
    // Chart configurations
    const config = {
        padding: { top: 40, right: 40, bottom: 60, left: 110 },
        gridColor: '#eee',
        axisColor: '#ddd',
        lineColor: '#39a9ff',
        pointColor: '#39a9ff',
        pointRadius: 5,
        pointBorderWidth: 2,
        pointBorderColor: 'white',
        animationDuration: 1000,
        yMax: 3000 // Default value, will be adjusted based on data
    };
    
    // Monthly chart configuration
    const monthlyConfig = {
        padding: { top: 40, right: 40, bottom: 60, left: 110 },
        gridColor: '#eee',
        axisColor: '#ddd',
        lineColor: '#4CAF50',
        fillColor: 'rgba(76, 175, 80, 0.1)',
        pointColor: '#4CAF50',
        pointRadius: 5,
        pointBorderWidth: 2,
        pointBorderColor: 'white',
        animationDuration: 1000,
        yMax: 60000 // Fixed max value for y-axis
    };
    
    // Function to load subscription data using the dedicated API endpoint
    async function fetchSubscriptionData(fromDate, toDate) {
        try {
            // Format dates for URL parameters
            const from = fromDate ? fromDate.toISOString().split('T')[0] : '';
            const to = toDate ? toDate.toISOString().split('T')[0] : '';
            
            // Use the dedicated API endpoint instead of scraping HTML
            const response = await fetch(`/admin/subscription-stats?from_date=${from}&to_date=${to}`);
            
            if (!response.ok) {
                throw new Error('Failed to fetch subscription data');
            }
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            return data;
        } catch (error) {
            console.error('Error fetching subscription data:', error);
            
            // Return fallback data
            return {
                dailyData: {
                    labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    values: [0, 0, 0, 0, 0, 0, 0]
                },
                summary: {
                    totalRevenue: 0,
                    totalSubscriptions: 0,
                    avgRevenue: 0
                }
            };
        }
    }
    
    // Function to fetch monthly subscription data
    async function fetchMonthlyData(year) {
        try {
            const response = await fetch(`/admin/subscription-stats/monthly?year=${year}`);
            
            if (!response.ok) {
                throw new Error('Failed to fetch monthly subscription data');
            }
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            return data;
        } catch (error) {
            console.error('Error fetching monthly subscription data:', error);
            
            // Return fallback data
            return {
                monthlyData: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    values: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                },
                summary: {
                    annualRevenue: 0,
                    annualSubscriptions: 0,
                    avgMonthlyRevenue: 0
                }
            };
        }
    }
    
    // Update summary statistics
    function updateSummaryStats(summary) {
        document.getElementById('totalRevenue').textContent = `PHP ${summary.totalRevenue.toLocaleString()}`;
        document.getElementById('totalSubscriptions').textContent = summary.totalSubscriptions.toLocaleString();
        document.getElementById('avgRevenue').textContent = `PHP ${summary.avgRevenue.toFixed(2)}`;
    }
    
    // Function to update monthly summary statistics
    function updateMonthlySummary(summary) {
        document.getElementById('annualRevenue').textContent = `PHP ${summary.annualRevenue.toLocaleString()}`;
        document.getElementById('annualSubscriptions').textContent = summary.annualSubscriptions.toLocaleString();
        document.getElementById('avgMonthlyRevenue').textContent = `PHP ${summary.avgMonthlyRevenue.toLocaleString(undefined, { maximumFractionDigits: 2 })}`;
    }
    
    // Function to render the daily chart
    function renderChart(labels, values) {
        // Get the canvas element
        const canvas = document.getElementById('incomeChart');
        const ctx = canvas.getContext('2d');
        
        // Set canvas dimensions for better resolution
        const dpr = window.devicePixelRatio || 1;
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        ctx.scale(dpr, dpr);
        
        // Calculate chart dimensions
        const chartWidth = rect.width - config.padding.left - config.padding.right;
        const chartHeight = rect.height - config.padding.top - config.padding.bottom;
        
        // Calculate appropriate y-axis max value
        const maxValue = Math.max(...values, 1); // Ensure at least 1 to avoid division by zero
        config.yMax = Math.max(3000, Math.ceil(maxValue * 1.2 / 500) * 500); // At least 3000, rounded up to nearest 500
        
        // Draw function
        function drawChart(progress = 1) {
            // Clear canvas
            ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
            
            // Draw grid lines
            ctx.beginPath();
            ctx.strokeStyle = config.gridColor;
            ctx.lineWidth = 1;
            
            // Horizontal grid lines with specific price points
            const pricePoints = [0, 500, 1000, 1500, 2000, 2500, 3000];
            for (let i = 0; i < pricePoints.length; i++) {
                const value = pricePoints[i];
                const y = config.padding.top + chartHeight - (value / config.yMax) * chartHeight;
                
                if (value <= config.yMax) {
                    ctx.moveTo(config.padding.left, y);
                    ctx.lineTo(config.padding.left + chartWidth, y);
                    
                    // Y-axis labels
                    ctx.fillStyle = '#666';
                    ctx.font = '12px Arial';
                    ctx.textAlign = 'right';
                    ctx.fillText(`PHP ${value.toLocaleString()}`, config.padding.left - 15, y + 4);
                }
            }
            
            // Show message if no data is available
            if (values.length === 0 || labels.length === 0) {
                ctx.fillStyle = '#666';
                ctx.font = '14px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No subscription data available for the selected period', 
                            config.padding.left + chartWidth/2, 
                            config.padding.top + chartHeight/2);
                return;
            }
            
            // Calculate spacing for x-axis labels
            const xStep = chartWidth / Math.max(labels.length - 1, 1);
            
            // Vertical grid lines and x-axis labels
            for (let i = 0; i < labels.length; i++) {
                const x = config.padding.left + i * xStep;
                ctx.moveTo(x, config.padding.top);
                ctx.lineTo(x, config.padding.top + chartHeight);
            }
            
            ctx.stroke();
            
            // X-axis labels
            ctx.fillStyle = '#666';
            ctx.font = '12px Arial';
            ctx.textAlign = 'center';
            
            for (let i = 0; i < labels.length; i++) {
                const x = config.padding.left + i * xStep;
                ctx.save();
                ctx.translate(x, config.padding.top + chartHeight + 10);
                ctx.rotate(Math.PI / 6); // Rotate labels to prevent overlap
                ctx.fillText(labels[i], 0, 0);
                ctx.restore();
            }
            
            // Draw axes
            ctx.beginPath();
            ctx.strokeStyle = config.axisColor;
            ctx.lineWidth = 1;
            
            // Y-axis
            ctx.moveTo(config.padding.left, config.padding.top);
            ctx.lineTo(config.padding.left, config.padding.top + chartHeight);
            
            // X-axis
            ctx.moveTo(config.padding.left, config.padding.top + chartHeight);
            ctx.lineTo(config.padding.left + chartWidth, config.padding.top + chartHeight);
            
            ctx.stroke();
            
            // Draw axis titles
            ctx.fillStyle = '#666';
            ctx.font = '12px Arial';
            
            // Y-axis title
            ctx.save();
            ctx.translate(25, config.padding.top + chartHeight / 2);
            ctx.rotate(-Math.PI / 2);
            ctx.textAlign = 'center';
            ctx.fillText('Income (PHP)', 0, 0);
            ctx.restore();
            
            // X-axis title
            ctx.textAlign = 'center';
            ctx.fillText('Subscription Start Date', config.padding.left + chartWidth / 2, config.padding.top + chartHeight + 45);
            
            // Draw line
            ctx.beginPath();
            ctx.strokeStyle = config.lineColor;
            ctx.lineWidth = 2;
            
            // Calculate points and draw line
            const points = [];
            
            for (let i = 0; i < values.length; i++) {
                const x = config.padding.left + i * xStep;
                const y = config.padding.top + chartHeight - (values[i] / config.yMax) * chartHeight * progress;
                points.push({ x, y, value: values[i] });
                
                if (i === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            }
            
            ctx.stroke();
            
            // Draw points and value labels
            for (const point of points) {
                // Circle for data point
                ctx.beginPath();
                ctx.fillStyle = config.pointColor;
                ctx.arc(point.x, point.y, config.pointRadius, 0, Math.PI * 2);
                ctx.fill();
                
                ctx.beginPath();
                ctx.strokeStyle = config.pointBorderColor;
                ctx.lineWidth = config.pointBorderWidth;
                ctx.arc(point.x, point.y, config.pointRadius, 0, Math.PI * 2);
                ctx.stroke();
                
                // Value label above point
                if (point.value > 0) {
                    ctx.fillStyle = '#333';
                    ctx.font = 'bold 12px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText(`PHP ${point.value.toLocaleString()}`, point.x, point.y - 12);
                }
            }
        }
        
        // Animation
        let startTime = null;
        
        function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            const elapsed = timestamp - startTime;
            const progress = Math.min(elapsed / config.animationDuration, 1);
            
            drawChart(progress);
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }
        
        // Start animation
        requestAnimationFrame(animate);
    }
    
    // Function to render the monthly chart
    function renderMonthlyChart(labels, values) {
        const canvas = document.getElementById('monthlyIncomeChart');
        const ctx = canvas.getContext('2d');
        
        // Set canvas dimensions for better resolution
        const dpr = window.devicePixelRatio || 1;
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        ctx.scale(dpr, dpr);
        
        // Calculate chart dimensions
        const chartWidth = rect.width - monthlyConfig.padding.left - monthlyConfig.padding.right;
        const chartHeight = rect.height - monthlyConfig.padding.top - monthlyConfig.padding.bottom;
        
        // Draw function
        function drawChart(progress = 1) {
            // Clear canvas
            ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
            
            // Draw grid lines
            ctx.beginPath();
            ctx.strokeStyle = monthlyConfig.gridColor;
            ctx.lineWidth = 1;
            
            // Horizontal grid lines with specific price points (0-60000 in 10000 increments)
            const pricePoints = [0, 10000, 20000, 30000, 40000, 50000, 60000];
            
            for (let i = 0; i < pricePoints.length; i++) {
                const value = pricePoints[i];
                const y = monthlyConfig.padding.top + chartHeight - (value / monthlyConfig.yMax) * chartHeight;
                
                ctx.moveTo(monthlyConfig.padding.left, y);
                ctx.lineTo(monthlyConfig.padding.left + chartWidth, y);
                
                // Y-axis labels
                ctx.fillStyle = '#666';
                ctx.font = '12px Arial';
                ctx.textAlign = 'right';
                ctx.fillText(`PHP ${value.toLocaleString()}`, monthlyConfig.padding.left - 15, y + 4);
            }
            
            // Calculate spacing for x-axis (months)
            const xStep = chartWidth / (labels.length - 1);
            
            // Vertical grid lines for months
            for (let i = 0; i < labels.length; i++) {
                const x = monthlyConfig.padding.left + i * xStep;
                ctx.moveTo(x, monthlyConfig.padding.top);
                ctx.lineTo(x, monthlyConfig.padding.top + chartHeight);
            }
            
            ctx.stroke();
            
            // X-axis labels (months)
            ctx.fillStyle = '#666';
            ctx.font = '12px Arial';
            ctx.textAlign = 'center';
            
            for (let i = 0; i < labels.length; i++) {
                const x = monthlyConfig.padding.left + i * xStep;
                ctx.fillText(labels[i], x, monthlyConfig.padding.top + chartHeight + 20);
            }
            
            // Draw axes
            ctx.beginPath();
            ctx.strokeStyle = monthlyConfig.axisColor;
            ctx.lineWidth = 1;
            
            // Y-axis
            ctx.moveTo(monthlyConfig.padding.left, monthlyConfig.padding.top);
            ctx.lineTo(monthlyConfig.padding.left, monthlyConfig.padding.top + chartHeight);
            
            // X-axis
            ctx.moveTo(monthlyConfig.padding.left, monthlyConfig.padding.top + chartHeight);
            ctx.lineTo(monthlyConfig.padding.left + chartWidth, monthlyConfig.padding.top + chartHeight);
            
            ctx.stroke();
            
            // Draw axis titles
            ctx.fillStyle = '#666';
            ctx.font = '12px Arial';
            
            // Y-axis title
            ctx.save();
            ctx.translate(25, monthlyConfig.padding.top + chartHeight / 2);
            ctx.rotate(-Math.PI / 2);
            ctx.textAlign = 'center';
            ctx.fillText('Income (PHP)', 0, 0);
            ctx.restore();
            
            // X-axis title
            ctx.textAlign = 'center';
            ctx.fillText('Month', monthlyConfig.padding.left + chartWidth / 2, monthlyConfig.padding.top + chartHeight + 45);
            
            // Draw line and area
            ctx.beginPath();
            ctx.strokeStyle = monthlyConfig.lineColor;
            ctx.lineWidth = 2;
            
            // Calculate points and draw line
            const points = [];
            
            for (let i = 0; i < values.length; i++) {
                const x = monthlyConfig.padding.left + i * xStep;
                const y = monthlyConfig.padding.top + chartHeight - (values[i] / monthlyConfig.yMax) * chartHeight * progress;
                points.push({ x, y, value: values[i] });
                
                if (i === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            }
            
            // Save path for stroke
            const linePath = ctx.getLineDash();
            ctx.stroke();
            
            // Fill area under line
            ctx.lineTo(monthlyConfig.padding.left + (values.length - 1) * xStep, monthlyConfig.padding.top + chartHeight);
            ctx.lineTo(monthlyConfig.padding.left, monthlyConfig.padding.top + chartHeight);
            ctx.closePath();
            ctx.fillStyle = monthlyConfig.fillColor;
            ctx.fill();
            
            // Draw data points
            for (const point of points) {
                // Circle for data point
                ctx.beginPath();
                ctx.fillStyle = monthlyConfig.pointColor;
                ctx.arc(point.x, point.y, monthlyConfig.pointRadius, 0, Math.PI * 2);
                ctx.fill();
                
                ctx.beginPath();
                ctx.strokeStyle = monthlyConfig.pointBorderColor;
                ctx.lineWidth = monthlyConfig.pointBorderWidth;
                ctx.arc(point.x, point.y, monthlyConfig.pointRadius, 0, Math.PI * 2);
                ctx.stroke();
                
                // Value label above point
                if (point.value > 0) {
                    ctx.fillStyle = '#333';
                    ctx.font = 'bold 12px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText(`PHP ${point.value.toLocaleString()}`, point.x, point.y - 12);
                }
            }
        }
        
        // Animation
        let startTime = null;
        
        function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            const elapsed = timestamp - startTime;
            const progress = Math.min(elapsed / monthlyConfig.animationDuration, 1);
            
            drawChart(progress);
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }
        
        // Start animation
        requestAnimationFrame(animate);
    }
    
    // Initial data load
    const fromDate = dateFromInput.valueAsDate;
    const toDate = dateToInput.valueAsDate;
    
    fetchSubscriptionData(fromDate, toDate).then(data => {
        renderChart(data.dailyData.labels, data.dailyData.values);
        updateSummaryStats(data.summary);
    });
    
    // Load monthly chart data initially (current year)
    fetchMonthlyData(currentYear).then(data => {
        renderMonthlyChart(data.monthlyData.labels, data.monthlyData.values);
        updateMonthlySummary(data.summary);
    });
    
    // Date filter event listener - Update logic for fixed 7-day range
    document.getElementById('applyDateFilter').addEventListener('click', function() {
        const fromDate = dateFromInput.valueAsDate;
        const toDate = dateToInput.valueAsDate;
        
        console.log('Date filter applied:', { fromDate, toDate }); // Log dates for debugging
        
        if (!fromDate) {
            alert('Please select a start date');
            return;
        }
        
        fetchSubscriptionData(fromDate, toDate).then(data => {
            renderChart(data.dailyData.labels, data.dailyData.values);
            updateSummaryStats(data.summary);
        });
    });
    
    // Year filter for monthly chart
    document.getElementById('applyYearFilter').addEventListener('click', function() {
        const selectedYear = document.getElementById('yearSelect').value;
        
        if (!selectedYear) {
            alert('Please select a year');
            return;
        }
        
        fetchMonthlyData(selectedYear).then(data => {
            renderMonthlyChart(data.monthlyData.labels, data.monthlyData.values);
            updateMonthlySummary(data.summary);
        });
    });
    
    // Redraw on window resize
    window.addEventListener('resize', function() {
        // Redraw daily chart
        const fromDate = document.getElementById('dateFrom').valueAsDate;
        const toDate = document.getElementById('dateTo').valueAsDate;
        
        fetchSubscriptionData(fromDate, toDate).then(data => {
            renderChart(data.dailyData.labels, data.dailyData.values);
        });
        
        // Redraw monthly chart
        const selectedYear = document.getElementById('yearSelect').value;
        fetchMonthlyData(selectedYear).then(data => {
            renderMonthlyChart(data.monthlyData.labels, data.monthlyData.values);
        });
    });
});