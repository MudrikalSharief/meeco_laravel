<x-admin_layout>
    <main class="pl-5">
        {{-- //============================== --}}
    
    <div class="z-0">
        <div class="relative flex size-full min-h-screen flex-col bg-[#f8fafb] justify-between group/design-root overflow-x-hidden"
        style='font-family: Inter, "Noto Sans", sans-serif;'
        >
        <div>
            <div class="flex flex-col gap-2 bg-[#f8fafb] p-4 pb-2">
            <div class="flex items-center h-12 justify-end">
                <div class="flex w-12 items-center justify-end">
                <button
                    class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-12 bg-transparent text-[#0e161b] gap-2 text-base font-bold leading-normal tracking-[0.015em] min-w-0 p-0"
                >
                    <div class="text-[#0e161b]" data-icon="MagnifyingGlass" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                        <path
                        d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"
                        ></path>
                    </svg>
                    </div>
                </button>
                </div>
            </div>
            <p class="text-[#0e161b] tracking-light text-[28px] font-bold leading-tight">Dashboard</p>
            </div>
            <div class="flex flex-wrap gap-4 p-4">
            <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 bg-[#e8eef3] hover:shadow-md transition-shadow duration-300">
                <p class="text-[#0e161b] text-base font-medium leading-normal">Reviewer Created</p>
                <p class="text-[#0e161b] tracking-light text-2xl font-bold leading-tight">{{ app('App\Http\Controllers\SubscriptionController')->getTotalCounts()['totalReviewerCreated'] }}</p>
            </div>
            <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 bg-[#e8eef3] hover:shadow-md transition-shadow duration-300">
                <p class="text-[#0e161b] text-base font-medium leading-normal">Quiz Generated</p>
                <p class="text-[#0e161b] tracking-light text-2xl font-bold leading-tight">{{ app('App\Http\Controllers\SubscriptionController')->getTotalCounts()['totalQuizCreated'] }}</p>
            </div>
            </div>
            <div class="flex flex-wrap gap-4 px-4 py-6">
            <div class="flex min-w-72 flex-1 flex-col gap-2 p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                <p class="text-[#0e161b] text-base font-medium leading-normal">Monthly Revenue</p>
                @php
                    $monthlyStats = app('App\Http\Controllers\SubscriptionController')->getDashboardMonthlyStats(6);
                @endphp
                <p class="text-[#0e161b] tracking-light text-[32px] font-bold leading-tight truncate">₱{{ number_format($monthlyStats['total_revenue'], 2) }}</p>
                <div class="flex gap-1">
                <p class="text-[#507a95] text-base font-normal leading-normal">{{ $monthlyStats['period'] }}</p>
                <p class="text-{{ $monthlyStats['growth_percentage'] >= 0 ? '[#078838]' : '[#e53935]' }} text-base font-medium leading-normal">
                    {{ $monthlyStats['growth_percentage'] >= 0 ? '+' : '' }}{{ $monthlyStats['growth_percentage'] }}%
                </p>
                <span class="text-gray-500 text-sm ml-1">(vs {{ $monthlyStats['previous_period'] }})</span>
                </div>
                <div class="flex min-h-[320px] flex-1 flex-col gap-8 py-4 relative"> <!-- Increased from 240px to 320px -->
                    <div class="absolute top-0 right-0 p-2 text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <span class="inline-block w-3 h-3 bg-[#507a95] rounded-full"></span>
                            <span>Income in PHP</span>
                        </div>
                    </div>
                    <canvas id="dashboardRevenueChart" class="w-full h-[280px]"></canvas> <!-- Increased from 210px to 280px -->
                    <div class="flex justify-around mt-5"> <!-- Added margin-top for better spacing -->
                        @foreach($monthlyStats['labels'] as $month)
                            <p class="text-[#507a95] text-[14px] font-bold leading-normal tracking-[0.015em]">{{ $month }}</p> <!-- Increased font size from 13px to 14px -->
                        @endforeach
                    </div>
                </div>
            </div>
            </div>
            <div class="flex flex-wrap gap-4 px-4 py-6">
            
            </div>
        </div>
    
        </div>
    </div>
        {{-- //===================================== --}}
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart configuration
            const config = {
                padding: { top: 40, right: 30, bottom: 20, left: 60 }, // Increased left padding for axis labels
                gridColor: '#eee',
                axisColor: '#ddd',
                lineColor: '#507a95',
                fillColor: 'rgba(80, 122, 149, 0.2)',
                pointColor: '#507a95',
                pointRadius: 7, // Increased from 6 to 7
                pointBorderWidth: 2.5, // Increased from 2 to 2.5
                pointBorderColor: 'white',
                animationDuration: 1000,
                hoverEffect: true
            };

            // Get chart data from PHP
            const chartData = {
                labels: @json($monthlyStats['labels']),
                values: @json($monthlyStats['values'])
            };

            // Render the chart
            renderDashboardChart(chartData.labels, chartData.values);
            
            // Function to render the dashboard revenue chart
            function renderDashboardChart(labels, values) {
                // Get the canvas element
                const canvas = document.getElementById('dashboardRevenueChart');
                if (!canvas) return; // Exit if canvas doesn't exist
                
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
                
                // Calculate appropriate y-axis max value based on data
                const maxValue = Math.max(...values, 1); // Ensure at least 1
                let yMax = Math.ceil(maxValue * 1.2); // Add 20% headroom
                
                // Round to a nice number
                if (yMax <= 100) {
                    yMax = Math.ceil(yMax / 10) * 10;
                } else if (yMax <= 1000) {
                    yMax = Math.ceil(yMax / 200) * 200;
                } else {
                    yMax = Math.ceil(yMax / 1000) * 1000;
                }
                
                // Track the currently hovered point
                let activePointIndex = -1;
                
                // Mouse move event for hover effect
                if (config.hoverEffect) {
                    canvas.addEventListener('mousemove', function(event) {
                        const rect = canvas.getBoundingClientRect();
                        const x = (event.clientX - rect.left) * dpr;
                        const y = (event.clientY - rect.top) * dpr;
                        
                        // Find if we're hovering over a point
                        let foundIndex = -1;
                        const points = calculatePoints(chartWidth, chartHeight, values, yMax, 1);
                        
                        for (let i = 0; i < points.length; i++) {
                            const point = points[i];
                            const distance = Math.sqrt(Math.pow(x - point.x * dpr, 2) + Math.pow(y - point.y * dpr, 2));
                            if (distance <= config.pointRadius * 2 * dpr) {
                                foundIndex = i;
                                break;
                            }
                        }
                        
                        if (foundIndex !== activePointIndex) {
                            activePointIndex = foundIndex;
                            drawChart(1, activePointIndex);
                        }
                    });
                    
                    // Reset hover state when mouse leaves the canvas
                    canvas.addEventListener('mouseleave', function() {
                        activePointIndex = -1;
                        drawChart(1, activePointIndex);
                    });
                }
                
                // Calculate points for given progress
                function calculatePoints(chartWidth, chartHeight, values, yMax, progress) {
                    const points = [];
                    const xStep = chartWidth / (values.length - 1);
                    
                    for (let i = 0; i < values.length; i++) {
                        const x = config.padding.left + i * xStep;
                        const y = config.padding.top + chartHeight - (values[i] / yMax) * chartHeight * progress;
                        points.push({ x, y, value: values[i] });
                    }
                    
                    return points;
                }
                
                // Draw function
                function drawChart(progress = 1, highlightIndex = -1) {
                    // Clear canvas
                    ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
                    
                    // Draw background gradient
                    const bgGradient = ctx.createLinearGradient(0, 0, 0, rect.height);
                    bgGradient.addColorStop(0, 'rgba(248, 250, 251, 0.5)');
                    bgGradient.addColorStop(1, 'rgba(248, 250, 251, 0)');
                    ctx.fillStyle = bgGradient;
                    ctx.fillRect(0, 0, rect.width, rect.height);
                    
                    // Draw grid lines
                    ctx.beginPath();
                    ctx.strokeStyle = config.gridColor;
                    ctx.lineWidth = 1;
                    
                    // Horizontal grid lines (5 divisions for more detail)
                    const yStep = yMax / 5;
                    for (let i = 0; i <= 5; i++) {
                        const y = config.padding.top + chartHeight - (i * yStep / yMax) * chartHeight;
                        ctx.moveTo(config.padding.left, y);
                        ctx.lineTo(config.padding.left + chartWidth, y);
                    }
                    
                    // Calculate spacing for x-axis (months)
                    const xStep = chartWidth / (labels.length - 1);
                    
                    // Vertical grid lines (months)
                    for (let i = 0; i < labels.length; i++) {
                        const x = config.padding.left + i * xStep;
                        // Draw fainter vertical grid lines
                        ctx.globalAlpha = 0.5;
                        ctx.moveTo(x, config.padding.top);
                        ctx.lineTo(x, config.padding.top + chartHeight);
                        ctx.globalAlpha = 1;
                    }
                    
                    ctx.stroke();
                    
                    // Calculate points and prepare for drawing
                    const points = calculatePoints(chartWidth, chartHeight, values, yMax, progress);
                    
                    // Draw filled area under the line
                    ctx.beginPath();
                    ctx.moveTo(points[0].x, points[0].y);
                    
                    for (let i = 1; i < points.length; i++) {
                        ctx.lineTo(points[i].x, points[i].y);
                    }
                    
                    // Continue path to create a closed shape for filling
                    ctx.lineTo(points[points.length - 1].x, config.padding.top + chartHeight);
                    ctx.lineTo(points[0].x, config.padding.top + chartHeight);
                    ctx.closePath();
                    
                    // Create gradient fill
                    const gradient = ctx.createLinearGradient(0, config.padding.top, 0, config.padding.top + chartHeight);
                    gradient.addColorStop(0, 'rgba(80, 122, 149, 0.4)');
                    gradient.addColorStop(1, 'rgba(80, 122, 149, 0)');
                    
                    // Fill the area with gradient
                    ctx.fillStyle = gradient;
                    ctx.fill();
                    
                    // Draw the line path with thicker stroke
                    ctx.beginPath();
                    ctx.moveTo(points[0].x, points[0].y);
                    
                    for (let i = 1; i < points.length; i++) {
                        ctx.lineTo(points[i].x, points[i].y);
                    }
                    
                    ctx.lineWidth = 3.5; // Increased line width from 3 to 3.5
                    ctx.strokeStyle = config.lineColor;
                    ctx.stroke();
                    
                    // Draw axis
                    ctx.beginPath();
                    ctx.strokeStyle = '#ccc';
                    ctx.lineWidth = 1.5;
                    
                    // Y-axis
                    ctx.moveTo(config.padding.left, config.padding.top);
                    ctx.lineTo(config.padding.left, config.padding.top + chartHeight);
                    
                    // X-axis
                    ctx.moveTo(config.padding.left, config.padding.top + chartHeight);
                    ctx.lineTo(config.padding.left + chartWidth, config.padding.top + chartHeight);
                    ctx.stroke();
                    
                    // Draw points
                    for (let i = 0; i < points.length; i++) {
                        const point = points[i];
                        const isHighlighted = i === highlightIndex;
                        
                        // Draw outer glow for highlighted point
                        if (isHighlighted) {
                            ctx.beginPath();
                            ctx.fillStyle = 'rgba(80, 122, 149, 0.3)';
                            ctx.arc(point.x, point.y, config.pointRadius * 2.2, 0, Math.PI * 2);
                            ctx.fill();
                        }
                        
                        // Draw point
                        ctx.beginPath();
                        ctx.fillStyle = isHighlighted ? '#3498db' : config.pointColor;
                        ctx.arc(point.x, point.y, isHighlighted ? config.pointRadius * 1.2 : config.pointRadius, 0, Math.PI * 2);
                        ctx.fill();
                        
                        // Draw point border
                        ctx.beginPath();
                        ctx.strokeStyle = config.pointBorderColor;
                        ctx.lineWidth = config.pointBorderWidth;
                        ctx.arc(point.x, point.y, isHighlighted ? config.pointRadius * 1.2 : config.pointRadius, 0, Math.PI * 2);
                        ctx.stroke();
                        
                        // Show value for highlighted point or for all points if they have value
                        if ((isHighlighted || values[i] > 0) && values[i] > 0) {
                            ctx.fillStyle = '#333';
                            ctx.font = isHighlighted ? 'bold 13px Arial' : 'bold 12px Arial'; // Larger font for highlighted point
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            
                            // Draw tooltip background
                            const valueText = `₱${point.value.toLocaleString()}`;
                            const textWidth = ctx.measureText(valueText).width;
                            const tooltipPadding = 6;
                            const tooltipHeight = 24;
                            
                            if (isHighlighted) {
                                ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
                                ctx.beginPath();
                                ctx.roundRect(
                                    point.x - textWidth/2 - tooltipPadding, 
                                    point.y - tooltipHeight - 10, 
                                    textWidth + tooltipPadding * 2,
                                    tooltipHeight,
                                    4
                                );
                                ctx.fill();
                                
                                // Add small triangle pointer
                                ctx.beginPath();
                                ctx.moveTo(point.x, point.y - 10);
                                ctx.lineTo(point.x - 5, point.y - 16);
                                ctx.lineTo(point.x + 5, point.y - 16);
                                ctx.closePath();
                                ctx.fill();
                                
                                ctx.fillStyle = '#333';
                                ctx.fillText(valueText, point.x, point.y - 18);
                            } else if (values[i] > 0) {
                                // Just show a small value label above non-highlighted points
                                ctx.fillStyle = 'rgba(255, 255, 255, 0.75)';
                                ctx.fillText(`₱${point.value.toLocaleString()}`, point.x, point.y - 10);
                            }
                        }
                    }
                    
                    // Draw y-axis values with larger font
                    ctx.fillStyle = '#666';
                    ctx.font = '12px Arial'; // Increased from 11px to 12px
                    ctx.textAlign = 'right';
                    ctx.textBaseline = 'middle';
                    
                    for (let i = 0; i <= 5; i++) { // Change to 5 divisions to match grid lines
                        const value = i * yStep;
                        const y = config.padding.top + chartHeight - (i * yStep / yMax) * chartHeight;
                        
                        // Add a slight background to make the text more readable
                        const valueText = `₱${value.toLocaleString()}`;
                        const textWidth = ctx.measureText(valueText).width;
                        
                        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                        ctx.fillRect(config.padding.left - textWidth - 8, y - 8, textWidth + 6, 16);
                        
                        ctx.fillStyle = '#666';
                        ctx.fillText(valueText, config.padding.left - 5, y);
                    }
                }
                
                // Animation
                let startTime = null;
                
                function animate(timestamp) {
                    if (!startTime) startTime = timestamp;
                    const elapsed = timestamp - startTime;
                    const progress = Math.min(elapsed / config.animationDuration, 1);
                    
                    // Use easeOutQuart easing function for a more dynamic animation
                    const easedProgress = 1 - Math.pow(1 - progress, 4);
                    
                    drawChart(easedProgress);
                    
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    }
                }
                
                // Start animation
                requestAnimationFrame(animate);
            }
            
            // Handle window resizing
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    renderDashboardChart(chartData.labels, chartData.values);
                }, 250);  // Debounce resize events
            });
        });
    </script>
</x-admin_layout>