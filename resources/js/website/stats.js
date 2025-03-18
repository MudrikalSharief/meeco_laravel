// Function to render the monthly chart
function WeeklyRevenueChart(labels, values) {
    const canvas = document.getElementById('weeklyRevenue');d
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
    
    // Calculate dynamic y-axis maximum
    const maxValue = Math.max(...values, 1); // Ensure at least 1
    
    // Dynamic calculation to get a nice rounded value
    let roundedMax;
    if (maxValue <= 1000) {
        roundedMax = Math.ceil(maxValue / 200) * 200; // Round up to nearest 200 for small values
    } else if (maxValue <= 10000) {
        roundedMax = Math.ceil(maxValue / 2000) * 2000; // Round up to nearest 2000
    } else if (maxValue <= 100000) {
        roundedMax = Math.ceil(maxValue / 20000) * 20000; // Round up to nearest 20000
    } else {
        roundedMax = Math.ceil(maxValue / 50000) * 50000; // Round up to nearest 50000
    }
    
    monthlyConfig.yMax = Math.max(roundedMax, 100); // Ensure at least 100
    
    // Draw function
    function drawChart(progress = 1) {
        // Clear canvas
        ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
        
        // Draw grid lines
        ctx.beginPath();
        ctx.strokeStyle = monthlyConfig.gridColor;
        ctx.lineWidth = 1;
        
        // Calculate evenly spaced price points (6 divisions)
        const priceStep = monthlyConfig.yMax / 6;
        const pricePoints = [];
        for (let i = 0; i <= 6; i++) {
            pricePoints.push(Math.round(i * priceStep));
        }
        
        // Horizontal grid lines with dynamic price points
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