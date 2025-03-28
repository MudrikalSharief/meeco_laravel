const darkModeVal = localStorage.getItem('darkModeVal'); // Get stored value

console.log("Stored darkModeVal:", darkModeVal, typeof darkModeVal); // Debugging

if (darkModeVal === 'true') {  // Check if it's explicitly "true" as a string
    console.log("Dark mode is ON");
    darkMode(); // Call your function
} else {
    console.log("Dark mode is OFF");
}
document.addEventListener('DOMContentLoaded', function() {

    const buttons = document.querySelectorAll('button');
    const weeklyButton = document.getElementById('btn-weekly');
    const yearlyButton = document.getElementById('btn-yearly');
    const yearButtons = document.getElementById("yearButtons");

    buttons.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            buttons.forEach(b => b.classList.remove("bg-blue-500", "text-white"));
            buttons.forEach(b => b.classList.add("bg-gray-200", "text-gray-700"));

            btn.classList.add("bg-blue-500", "text-white");
            btn.classList.remove("bg-gray-200", "text-gray-700");
        });
    });

    const weeklyChart = document.getElementById('weeklyChart');
    const yearlyChart = document.getElementById('yearlyChart');
    const filterWeeks = document.getElementById('filterWeeks');

    const filterMonths = document.getElementById('filterMonths');
    // const filterYears = document.getElementById('filterYears');

    weeklyButton.addEventListener('click', ()=>{
      weeklyChart.style.display = 'block';
      yearlyChart.style.display = 'none';
      filterWeeks.style.display = 'block';
      filterMonths.style.display = 'none';
      // filterYears.style.display = 'none';
      document.getElementById("monthFrom").value = "";
      document.getElementById("monthTo").value = "";
      createWeeklyChart(weeksOfMonth);
    });

    yearlyButton.addEventListener('click', ()=>{
      weeklyChart.style.display = 'none';
      yearlyChart.style.display = 'block';
      filterWeeks.style.display = 'none';
      filterMonths.style.display = 'block';
      // filterYears.style.display = 'block';
      yearButtons.innerHTML = '';
      document.getElementById("dateFrom").value = "";
      document.getElementById("dateTo").value = "";
      createYearlyChart();
      fetchYearlyData(2025);
    });

  //GET STATISTICS
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();
    const weeklyRevenue = document.getElementById('weeklyRevenue');
    const yearlyRevenue = document.getElementById('yearlyRevenue');
    let weeklyChartInstance = null;
    let yearlyChartInstance = null;

    function getWeeksInMonth(month, year) {
        const weeks = [];
        let currentWeek = [];
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let day = 1; day <= daysInMonth; day++) {
            let date = new Date(year, month, day);
            if(day % 7 === 0){
                currentWeek.push(date);
                weeks.push(currentWeek);
                currentWeek = [];
            }else{
                currentWeek.push(date);
            }
        }

        if (currentWeek.length) weeks.push(currentWeek); // Add the last week
        return weeks;
    }

    let weeksOfMonth = getWeeksInMonth(currentMonth, currentYear);
    createWeeklyChart(weeksOfMonth);

    function createWeeklyChart(weeksOfMonth){
    let weeks = [];

    let weekButtons = document.getElementById("weekButtons");
    weekButtons.innerHTML = "";

    outerLoop:
    for(let i = 0; i < weeksOfMonth.length; i++){
        weeks[i] = weeksOfMonth[i];

        for(let j = 0; j < weeks[i].length; j++){
          const today = new Date().getDate();
          const week = weeks[i][j];
          const dateWeek = new Date(week).getDate();
          
          if(today === dateWeek){
            const weekData = JSON.stringify(weeks[i][0].toLocaleDateString("en-CA"));
            fetchWeeklyData(weekData);
            break outerLoop;
          }
        }
      }

    for(let i = 0; i < weeksOfMonth.length; i++){
        weeks[i] = weeksOfMonth[i];
    
        let button = document.createElement("button");

        let firstDate = weeks[i][0].getDate();
        let lastDate = weeks[i][weeks[i].length - 1].getDate(); 

        button.id = `week-button-${i+1}`;
        button.className = `
        px-3 py-2 text-sm font-semibold border border-gray-300
        bg-white text-gray-800 rounded-none first:rounded-l-lg last:rounded-r-lg
        hover:bg-gray-200 transition duration-50
    `;
        button.dataset.week = JSON.stringify(weeks[i][0].toLocaleDateString("en-CA")); 
        button.innerText = `${firstDate} - ${lastDate}`;
        weekButtons.appendChild(button);

        button.addEventListener("click", function() {
            let weekData = this.dataset.week;
            fetchWeeklyData(weekData);

            document.getElementById("dateFrom").value = "";
            document.getElementById("dateTo").value = "";

            document.querySelectorAll("#weekButtons button").forEach(btn => {
                btn.classList.remove("bg-blue-500", "text-white");
                btn.classList.add("bg-white", "text-gray-800");
            });
    
            // Apply active styles to the clicked button
            this.classList.remove("bg-white", "text-gray-800");
            this.classList.add("bg-blue-500", "text-white");
        });
    }
  }

        function fetchWeeklyData(weekData){

            let daily_chart_labels;
            let daily_revenue;

            const totalRevenue = document.getElementById('totalRevenue');
            const avgRevenue = document.getElementById('avgRevenue');
            const totalSubs = document.getElementById('totalSubs');

            fetch('/admin/get-weekly-statistics',{
                method: 'POST',
                headers:{'Content-Type': "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: weekData
            })
            .then(response =>  response.json())
            .then(data =>{
                daily_chart_labels = data.week_labels;
                daily_revenue = data.daily_rev_arr;

                totalRevenue.textContent = 'PHP ' + data.total_amount;
                avgRevenue.textContent = 'PHP ' + data.average_rev;
                totalSubs.textContent = data.total_subs;

                WeeklyRevenueChart(daily_chart_labels, daily_revenue);
            })
            .catch(error => console.error(error.message));
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

        async function WeeklyRevenueChart(daily_chart_labels, daily_revenue) {
            const canvas = document.getElementById('weeklyRevenue');
            const ctx = canvas.getContext('2d');
            
            // Set canvas dimensions for better resolution
            const dpr = window.devicePixelRatio || 1;
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            ctx.scale(dpr, dpr);
            
            // Calculate chart dimensions
            const chartWidth = rect.width - 200;
            const chartHeight = rect.height - 225;
            const chartXOffset = 100; // Centering horizontally
            
            // Calculate dynamic y-axis maximum
            const maxValue = Math.max(...daily_revenue, 1);
            let roundedMax = Math.ceil(maxValue / 100) * 100;
            
            // Draw function
            function drawChart(progress = 1) {
                ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
                
                ctx.beginPath();
                ctx.strokeStyle = '#ddd';
                ctx.lineWidth = 1;
                
                // Y-axis grid lines
                for (let i = 0; i <= 5; i++) {
                    let y = chartHeight - (i * (chartHeight / 5));
                    ctx.moveTo(chartXOffset, y);
                    ctx.lineTo(chartWidth + chartXOffset, y);
                
                    ctx.fillStyle = '#666';
                    ctx.font = '12px Arial';
                    ctx.fillText(`PHP ${(roundedMax / 5) * i}`, chartXOffset - 40, y + 4);
                }
                ctx.stroke();
                
                // X-axis labels (Diagonal Rotation)
                ctx.fillStyle = '#666';
                ctx.font = '12px Arial';
                ctx.textAlign = "center";
                ctx.textBaseline = "top";
                daily_chart_labels.forEach((label, i) => {
                    let x = chartXOffset + (i * (chartWidth / (daily_chart_labels.length - 1)));
                    // Position labels 30px below chart base (leaves space for "Date" at 40px)
                    let yPosition = chartHeight;
                    ctx.save();
                    ctx.translate(x, yPosition);
                    ctx.rotate(-Math.PI / 4); // Rotate labels diagonally
                    ctx.fillText(label, 0, 0);
                    ctx.restore();
                });
                
                // Draw axes
                ctx.beginPath();
                ctx.strokeStyle = '#000';
                ctx.moveTo(chartXOffset, 0);
                ctx.lineTo(chartXOffset, chartHeight);
                ctx.lineTo(chartWidth + chartXOffset, chartHeight);
                ctx.stroke();
                
                // X-axis label "Date"
                ctx.fillStyle = '#000';
                ctx.font = '14px Arial';
                ctx.textAlign = "center";
                
                // Y-axis label "Amount"
                ctx.save();
                ctx.translate(chartXOffset - 50, chartHeight / 2);
                ctx.rotate(-Math.PI / 2);
                ctx.restore();
                
                // Draw line chart
               // Draw filled area under the line chart
                ctx.beginPath();
                ctx.moveTo(chartXOffset, chartHeight); // Start from x-axis baseline

                daily_revenue.forEach((value, i) => {
                    let x = chartXOffset + (i * (chartWidth / (daily_chart_labels.length - 1)));
                    let y = chartHeight - ((value / roundedMax) * chartHeight * progress);
                    ctx.lineTo(x, y);
                });

                ctx.lineTo(chartXOffset + chartWidth, chartHeight); // Extend to x-axis
                ctx.closePath();

                ctx.fillStyle = 'rgba(173, 216, 230, 0.5)'; // Light blue with transparency
                ctx.fill();

                // Draw the line chart on top
                ctx.beginPath();
                ctx.strokeStyle = 'blue';
                ctx.lineWidth = 2;
                daily_revenue.forEach((value, i) => {
                    let x = chartXOffset + (i * (chartWidth / (daily_chart_labels.length - 1)));
                    let y = chartHeight - ((value / roundedMax) * chartHeight * progress);
                    if (i === 0) {
                        ctx.moveTo(x, y);
                    } else {
                        ctx.lineTo(x, y);
                    }
                });
                ctx.stroke();

                
                // Draw points
                daily_revenue.forEach((value, i) => {
                    let x = chartXOffset + (i * (chartWidth / (daily_chart_labels.length - 1)));
                    let y = chartHeight - ((value / roundedMax) * chartHeight * progress);
                    
                    ctx.beginPath();
                    ctx.arc(x, y, 4, 0, Math.PI * 2);
                    ctx.fillStyle = 'blue';
                    ctx.fill();
                });
            }
            
            // Animation
            let startTime = null;
            function animate(timestamp) {
                if (!startTime) startTime = timestamp;
                const elapsed = timestamp - startTime;
                const progress = Math.min(elapsed / 1000, 1);
                drawChart(progress);
                if (progress < 1) requestAnimationFrame(animate);
            }
            requestAnimationFrame(animate);
        }
        
        

    const years = ['2025', '2024', '2023', '2022', '2021'];
    
    function createYearlyChart(){
      for (let i = 0; i < 5; i++) {
        let year = 2025 - i; // Generate years from 2025 down to 2021
        let button = document.createElement("button");

        button.id = `year-button-${year}`;
        button.className = `
        px-4 py-2 text-sm font-semibold border border-gray-300 
        bg-white text-gray-800 rounded-none first:rounded-l-lg last:rounded-r-lg
        hover:bg-gray-200 transition duration-50
      `;
        button.dataset.year = year; 
        button.innerText = year; // Display year

        yearButtons.appendChild(button);

        button.addEventListener("click", function() {
            let selectedYear = this.dataset.year;
            fetchYearlyData(selectedYear);

            document.getElementById("monthFrom").value = "";
            document.getElementById("monthTo").value = "";

            document.querySelectorAll("#yearButtons button").forEach(btn => {
                btn.classList.remove("bg-blue-500", "text-white");
                btn.classList.add("bg-white", "text-gray-800");
            });

            // Apply active styles to the clicked button
            this.classList.remove("bg-white", "text-gray-800");
            this.classList.add("bg-blue-500", "text-white");
        });
      }
    }

    function fetchYearlyData(yearData){

      let yearly_revenue;

      const totalRevenue = document.getElementById('totalRevenue');
      const avgRevenue = document.getElementById('avgRevenue');
      const totalSubs = document.getElementById('totalSubs');

      fetch('/admin/get-yearly-statistics',{
          method: 'POST',
          headers:{'Content-Type': "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: yearData
      })
      .then(response =>  response.json())
      .then(data =>{
          yearly_revenue = data.yearly_rev;

          totalRevenue.textContent = 'PHP ' + data.total_amount;
          avgRevenue.textContent = 'PHP ' + data.average_rev;
          totalSubs.textContent = data.total_subs;

          YearlyRevenueChart(months, yearly_revenue);
      })
      .catch(error => console.error(error.message));
    }

    const months = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];

    async function YearlyRevenueChart(months, yearly_revenue) {
        const canvas = document.getElementById('yearlyRevenue'); // Changed canvas ID
        const ctx = canvas.getContext('2d');
        
        // Same resolution setup
        const dpr = window.devicePixelRatio || 1;
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        ctx.scale(dpr, dpr);
        
        // Same chart dimensions calculations
        const chartWidth = rect.width - 200;
        const chartHeight = rect.height - 150;
        const chartXOffset = 100;
        
        // Same max value calculation
        const maxValue = Math.max(...yearly_revenue, 1); // Changed parameter
        let roundedMax = Math.ceil(maxValue / 100) * 100;
        
        // Identical draw function structure
        function drawChart(progress = 1) {
            ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
            
            // Same grid line implementation
            ctx.beginPath();
            ctx.strokeStyle = '#ddd';
            ctx.lineWidth = 1;
            
            // Unchanged Y-axis grid
            for (let i = 0; i <= 5; i++) {
                let y = chartHeight - (i * (chartHeight / 5));
                ctx.moveTo(chartXOffset, y);
                ctx.lineTo(chartWidth + chartXOffset, y);
                
                ctx.fillStyle = '#666';
                ctx.font = '12px Arial';
                ctx.fillText(`PHP ${(roundedMax / 5) * i}`, chartXOffset - 40, y + 4);
            }
            ctx.stroke();
            
            // Same X-axis label positioning
            ctx.fillStyle = '#666';
            ctx.font = '12px Arial';
            ctx.textAlign = "center";
            ctx.textBaseline = "top";
            months.forEach((label, i) => { // Changed parameter
                let x = chartXOffset + (i * (chartWidth / (months.length - 1)));
                let yPosition = chartHeight;
                ctx.save();
                ctx.translate(x, yPosition);
                ctx.rotate(-Math.PI / 4);
                ctx.fillText(label, 0, 0);
                ctx.restore();
            });
            
            // Identical axis drawing
            ctx.beginPath();
            ctx.strokeStyle = '#000';
            ctx.moveTo(chartXOffset, 0);
            ctx.lineTo(chartXOffset, chartHeight);
            ctx.lineTo(chartWidth + chartXOffset, chartHeight);
            ctx.stroke();
            
            // Same "Date" label positioning
            ctx.fillStyle = '#000';
            ctx.font = '14px Arial';
            ctx.textAlign = "center";
            
            // Same Y-axis label rotation
            ctx.save();
            ctx.translate(chartXOffset - 50, chartHeight / 2);
            ctx.rotate(-Math.PI / 2);
            ctx.restore();
            
            // Identical line chart animation
            // Draw filled area under the line chart
ctx.beginPath();
ctx.moveTo(chartXOffset, chartHeight); // Start from x-axis baseline

yearly_revenue.forEach((value, i) => {
    let x = chartXOffset + (i * (chartWidth / (months.length - 1)));
    let y = chartHeight - ((value / roundedMax) * chartHeight * progress);
    ctx.lineTo(x, y);
});

ctx.lineTo(chartXOffset + chartWidth, chartHeight); // Extend to x-axis
ctx.closePath();

ctx.fillStyle = 'rgba(173, 216, 230, 0.5)'; // Light blue with transparency
ctx.fill();

// Draw the line chart on top
ctx.beginPath();
ctx.strokeStyle = 'blue';
ctx.lineWidth = 2;
yearly_revenue.forEach((value, i) => { 
    let x = chartXOffset + (i * (chartWidth / (months.length - 1)));
    let y = chartHeight - ((value / roundedMax) * chartHeight * progress);
    if (i === 0) {
        ctx.moveTo(x, y);
    } else {
        ctx.lineTo(x, y);
    }
});
ctx.stroke();

            
            // Same point drawing logic
            yearly_revenue.forEach((value, i) => { // Changed parameter
                let x = chartXOffset + (i * (chartWidth / (months.length - 1)));
                let y = chartHeight - ((value / roundedMax) * chartHeight * progress);
                
                ctx.beginPath();
                ctx.arc(x, y, 4, 0, Math.PI * 2);
                ctx.fillStyle = 'blue';
                ctx.fill();
            });
        }
        
        // Same animation system
        let startTime = null;
        function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            const elapsed = timestamp - startTime;
            const progress = Math.min(elapsed / 1000, 1);
            drawChart(progress);
            if (progress < 1) requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);
    }
    

  //FILTER STATISTICS

  const dateFrom = document.getElementById('dateFrom');
  const dateTo = document.getElementById('dateTo');

  const monthFrom = document.getElementById('monthFrom');
  const monthTo = document.getElementById('monthTo');

  let dateFromVal = null, dateToVal = null, dateFromValstr = null, dateToValstr = null;
  let monthFromVal = null, monthToVal = null, monthFromValstr = null, monthToValstr = null;


  dateFrom.addEventListener('change', ()=>{
    dateFromVal = new Date(dateFrom.value);
    dateFromValstr = JSON.stringify(dateFromVal.toLocaleDateString("en-CA"));
    checkAndFetchDateData();
  })
  dateTo.addEventListener('change', ()=>{
    dateToVal = new Date(dateTo.value);
    dateToValstr = JSON.stringify(dateToVal.toLocaleDateString("en-CA"));
    checkAndFetchDateData();
  })

  monthFrom.addEventListener('change', ()=>{
    monthFromVal = new Date(monthFrom.value);
    monthFromValstr = JSON.stringify(monthFromVal.toLocaleDateString("en-CA"));
    checkAndFetchMonthData();
  })
  monthTo.addEventListener('change', ()=>{
    monthToVal = new Date(monthTo.value);
    monthToValstr = JSON.stringify(monthToVal.toLocaleDateString("en-CA"));
    checkAndFetchMonthData();
  })

  function checkAndFetchDateData() {
    if (dateFromValstr && dateToValstr) {
        fetchFilteredWeeklyData(dateFromValstr, dateToValstr);
        console.log({ dateFrom: dateFromVal, dateTo: dateToVal });
    }
  }

  function checkAndFetchMonthData() {
    if (monthFromValstr && monthToValstr) {
        fetchFilteredMonthlyData(monthFromValstr, monthToValstr);
        console.log({ monthFrom: monthFromVal, monthTo: monthToVal });
    }
  }

  function fetchFilteredMonthlyData(monthFromVal, monthToVal){
    const totalRevenue = document.getElementById('totalRevenue');
    const avgRevenue = document.getElementById('avgRevenue');
    const totalSubs = document.getElementById('totalSubs');

    fetch('/admin/filter-yearly-stats', {
      method:'POST',
      headers:{'Content-Type': "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
      body: JSON.stringify([monthFromVal, monthToVal])
    })
    .then(response=>response.json())
    .then(data=>{
      const month_labels = data.month_labels;
      const filter_rev = data.filter_rev_arr;

      totalRevenue.textContent = 'PHP ' + data.total_amount;
      avgRevenue.textContent = 'PHP ' + data.average_rev;
      totalSubs.textContent = data.total_subs;

      YearlyRevenueChart(month_labels, filter_rev);
    })
    .catch(error => console.error(error.message));
  }

  function fetchFilteredWeeklyData(dateFromVal, dateToVal){
    const totalRevenue = document.getElementById('totalRevenue');
    const avgRevenue = document.getElementById('avgRevenue');
    const totalSubs = document.getElementById('totalSubs');

    fetch('/admin/filter-weekly-stats', {
      method:'POST',
      headers:{'Content-Type': "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
      body: JSON.stringify([dateFromVal, dateToVal])
    })
    .then(response=>response.json())
    .then(data=>{
      const date_labels = data.date_labels;
      const filter_rev = data.filter_rev_arr;

      totalRevenue.textContent = 'PHP ' + data.total_amount;
      avgRevenue.textContent = 'PHP ' + data.average_rev;
      totalSubs.textContent = data.total_subs;

      WeeklyRevenueChart(date_labels, filter_rev);
    })
    .catch(error => console.error(error.message));
  }
});