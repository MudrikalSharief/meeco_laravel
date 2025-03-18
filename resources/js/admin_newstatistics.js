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
      createWeeklyChart(weeksOfMonth);
    });

    yearlyButton.addEventListener('click', ()=>{
      weeklyChart.style.display = 'none';
      yearlyChart.style.display = 'block';
      filterWeeks.style.display = 'none';
      filterMonths.style.display = 'block';
      // filterYears.style.display = 'block';
      yearButtons.innerHTML = '';
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

    const years = ['2025', '2024', '2023', '2022', '2021'];
    
    function createYearlyChart(){
      for (let i = 0; i < 5; i++) {
        let year = 2025 - i; // Generate years from 2025 down to 2021
        let button = document.createElement("button");

        button.id = `year-button-${year}`;
        button.className = `
          px-4 py-2 bg-blue-500 text-white font-semibold text-sm 
          first:rounded-l-lg last:rounded-r-lg border border-gray-300 
          hover:bg-blue-600 transition duration-300 transform hover:scale-105
        `; 
        button.dataset.year = year; 
        button.innerText = year; // Display year

        yearButtons.appendChild(button);

        button.addEventListener("click", function() {
            let selectedYear = this.dataset.year;
            fetchYearlyData(selectedYear);
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
      if (yearlyChartInstance) {
        yearlyChartInstance.destroy();
        yearlyChartInstance = null;
      }

      // Always create new chart
      var ctx = yearlyRevenue.getContext('2d');
      yearlyChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: months,
          datasets: [{
            data: yearly_revenue,
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