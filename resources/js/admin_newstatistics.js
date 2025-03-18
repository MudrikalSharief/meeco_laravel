document.addEventListener('DOMContentLoaded', function() {
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();

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

    const selectMonth = document.getElementById('month');
    const selectYear = document.getElementById('year');

    selectMonth.addEventListener('change', (e)=>{
      const selectedMonth = selectMonth.value;
      const selectedYear = selectYear.value;
      
      weeksOfMonth = getWeeksInMonth(selectedMonth, selectedYear);
      createWeeklyChart(weeksOfMonth);

    });

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
        px-3 py-2 bg-blue-500 text-white font-semibold text-sm rounded-lg 
        hover:bg-blue-600 transition duration-300 transform hover:scale-105
        `; 
        button.dataset.week = JSON.stringify(weeks[i][0].toLocaleDateString("en-CA")); 
        button.innerText = `${firstDate} - ${lastDate}`;
        weekButtons.appendChild(button);

        button.addEventListener("click", function() {
            let weekData = this.dataset.week;
            fetchWeeklyData(weekData);
        });
    }
  }

        function fetchWeeklyData(weekData){

            let daily_chart_labels;
            let daily_revenue;

            fetch('/admin/get-statistics',{
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
                WeeklyRevenueChart(daily_chart_labels, daily_revenue);
            })
            .catch(error => console.error(error.message));
        }

        function fetchMonthlyData(weekData){
          fetch('/admin/get-statistics',{
              method: 'POST',
              headers:{'Content-Type': "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
              },
              body: weekData
          })
          .then(response => response.json())
          .then(data =>{
              const daily_chart_labels = data.week_labels;
              const daily_revenue = data.daily_rev_arr;
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

        const weeklyRevenue = document.getElementById('weeklyRevenue');

        
        let weeklyChartInstance = null;

    async function WeeklyRevenueChart(daily_chart_labels, daily_revenue) {
      // Destroy existing chart if it exists
      if (weeklyChartInstance) {
        weeklyChartInstance.destroy();
        weeklyChartInstance = null;
      }

      // Always create new chart
      var ctx = weeklyRevenue.getContext('2d');
      weeklyChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: daily_chart_labels,
          datasets: [{
            data: daily_revenue,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(0, 0, 139, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
});