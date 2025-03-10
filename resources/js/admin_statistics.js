//REVENUE
const selectRev = document.getElementById('select-rev');
const dailyChart = document.getElementById('dailyChart');
const monthlyChart = document.getElementById('monthlyChart');
const yearlyChart = document.getElementById('yearlyChart');

const selectOl = document.getElementById('select-ol');
const dailyOlChart = document.getElementById('dailyOlChart');
const monthlyOlChart = document.getElementById('monthlyOlChart');
const yearlyOlChart = document.getElementById('yearlyOlChart');

const dailyOlCount = document.getElementById('daily-ol-count');
const monthlyOlCount = document.getElementById('monthly-ol-count');
const yearlyOlCount = document.getElementById('yearly-ol-count');

const dailyRevCount = document.getElementById('daily-rev-count');
const monthlyRevCount = document.getElementById('monthly-rev-count');
const yearlyRevCount = document.getElementById('yearly-rev-count');

let daily_labels;
let monthly_labels;

daily_labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
monthly_labels = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],

fetch('/admin/get-statistics',{
  method: 'GET',
  headers:{'Content-Type': "application/json",
  "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
  }
})
.then(response => response.json())
.then(data => {
  const daily_total_amount = data.daily_rev[0].total_amount;
  const monthly_total_amount = data.monthly_rev[0].total_amount;
  const yearly_total_amount = data.yearly_rev[0].total_amount;

  dailyRevCount.innerText = daily_total_amount;
  monthlyRevCount.innerText = monthly_total_amount;
  yearlyRevCount.innerText = yearly_total_amount;

  const daily_total_users = data.daily_ol[0].total_users;
  const monthly_total_users = data.monthly_ol[0].total_users;
  const yearly_total_users = data.yearly_ol[0].total_users;

  dailyOlCount.innerText = daily_total_users;
  monthlyOlCount.innerText = monthly_total_users;
  yearlyOlCount.innerText = yearly_total_users;

  const yearly_labels = data.recent_5_years_str;

  updateDailyChart(daily_total_amount);
  updateMonthlyChart(monthly_total_amount);
  updateYearlyChart(yearly_total_amount, yearly_labels);

  updateDailyOlChart([daily_total_users]);
  updateMonthlyOlChart([monthly_total_users]);
  updateYearlyOlChart([yearly_total_users], yearly_labels);
})
.catch(error => console.error(error));


selectRev.value = 'opt-daily';
selectOl.value = 'opt-daily-ol';

selectRev.addEventListener('change', (e)=>{
  if (selectRev.value === 'opt-daily'){
    dailyChart.style.display = 'block';
    dailyRevCount.display = 'block';
  }else{
    dailyChart.style.display = 'none';
    dailyRevCount.display = 'none';
  }
  if (selectRev.value === 'opt-monthly'){
    monthlyChart.style.display = 'block';
     monthlyRevCount.display = 'block';
  }else{
    monthlyChart.style.display = 'none';
    monthlyRevCount.display = 'none';
  }
  if (selectRev.value === 'opt-yearly'){
    yearlyChart.style.display = 'block';
    yearlyRevCount.display = 'block';
  }else{
    yearlyChart.style.display = 'none';
    yearlyRevCount.display = 'none';
  }
});


selectOl.addEventListener('change', (e)=>{
  if (selectOl.value === 'opt-daily-ol'){
    dailyOlChart.style.display = 'block';
    dailyOlCount.display = 'block';
  }else{
    dailyOlChart.style.display = 'none';
    dailyOlCount.display = 'none';
  }
  if (selectOl.value === 'opt-monthly-ol'){
    monthlyOlChart.style.display = 'block';
    monthlyOlCount.display = 'block';
  }else{
    monthlyOlChart.style.display = 'none';
    monthlyOlCount.display = 'none';
  }
  if (selectOl.value === 'opt-yearly-ol'){
    yearlyOlChart.style.display = 'block';
    yearlyOlCount.display = 'block';
  }else{
    yearlyOlChart.style.display = 'none';
    yearlyOlCount.display = 'none';
  }
});

async function updateDailyChart(daily_total_amount){
  var ctx = dailyChart.getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line', // Choose your chart type here (line, bar, pie, etc.)
          data: {
            labels: daily_labels,
            datasets: [{
              data: daily_total_amount,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(0, 0, 139, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins:{
              legend:{
                display:false
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

async function updateMonthlyChart(monthly_total_amount){
  var ctx = monthlyChart.getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line', // Choose your chart type here (line, bar, pie, etc.)
          data: {
            labels: monthly_labels,
            datasets: [{
              data: monthly_total_amount,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(0, 0, 139, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins:{
              legend:{
                display:false
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


async function updateYearlyChart(yearly_total_amount, yearly_labels){
  var ctx = yearlyChart.getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line', // Choose your chart type here (line, bar, pie, etc.)
          data: {
            labels: yearly_labels,
            datasets: [{
              data: yearly_total_amount,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(0, 0, 139, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins:{
              legend:{
                display:false
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


async function updateDailyOlChart(daily_total_users){
  var ctx = dailyOlChart.getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line', // Choose your chart type here (line, bar, pie, etc.)
          data: {
            labels: daily_labels,
            datasets: [{
              data: daily_total_users,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(0, 0, 139, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins:{
              legend:{
                display:false
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

async function updateMonthlyOlChart(monthly_total_users){
  var ctx = monthlyOlChart.getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line', // Choose your chart type here (line, bar, pie, etc.)
          data: {
            labels: monthly_labels,
            datasets: [{
              data: monthly_total_users,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(0, 0, 139, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins:{
              legend:{
                display:false
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


async function updateYearlyOlChart(yearly_total_users, yearly_labels){
  var ctx = yearlyOlChart.getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line', // Choose your chart type here (line, bar, pie, etc.)
          data: {
            labels: yearly_labels,
            datasets: [{
              data: yearly_total_users,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(0, 0, 139, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins:{
              legend:{
                display:false
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





