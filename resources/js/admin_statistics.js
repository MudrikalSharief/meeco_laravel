const selectRev = document.getElementById('select-rev');
const dailyChart = document.getElementById('dailyChart');
const monthlyChart = document.getElementById('monthlyChart');

let daily_labels, daily_revenue_data, daily_revenue_data_att, daily_revenue, daily_total_amount;
let monthly_labels, monthly_revenue_data, monthly_revenue_data_att, monthly_revenue, monthly_total_amount;

daily_labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
daily_revenue_data = document.querySelector('.daily-rev');
daily_revenue_data_att = daily_revenue_data.getAttribute('data-daily-rev');

daily_revenue = JSON.parse(daily_revenue_data_att);

daily_total_amount = daily_revenue.map(item => parseFloat(item.total_amount));


monthly_labels = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
monthly_revenue_data = document.querySelector('.monthly-rev');
monthly_revenue_data_att = monthly_revenue_data.getAttribute('data-monthly-rev');

monthly_revenue = JSON.parse(monthly_revenue_data_att);

monthly_total_amount = monthly_revenue.map(item => parseFloat(item.total_amount));

selectRev.value = 'opt-daily';

selectRev.addEventListener('change', (e)=>{
  if (selectRev.value === 'opt-daily'){
    dailyChart.style.display = 'block';
  }else{
    dailyChart.style.display = 'none';
  }
  if (selectRev.value === 'opt-monthly'){
    monthlyChart.style.display = 'block';
  }else{
    monthlyChart.style.display = 'none';
  }
});

if(daily_labels && daily_total_amount){
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


if(monthly_labels && monthly_total_amount){
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

//Online Users

const selectRev = document.getElementById('select-rev');

daily_labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
daily_revenue_data = document.querySelector('.daily-rev');
daily_revenue_data_att = daily_revenue_data.getAttribute('data-daily-rev');

daily_revenue = JSON.parse(daily_revenue_data_att);

daily_total_amount = daily_revenue.map(item => parseFloat(item.total_amount));


monthly_labels = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
monthly_revenue_data = document.querySelector('.monthly-rev');
monthly_revenue_data_att = monthly_revenue_data.getAttribute('data-monthly-rev');

monthly_revenue = JSON.parse(monthly_revenue_data_att);

monthly_total_amount = monthly_revenue.map(item => parseFloat(item.total_amount));

selectRev.value = 'opt-daily';

selectRev.addEventListener('change', (e)=>{
  if (selectRev.value === 'opt-daily'){
    dailyChart.style.display = 'block';
  }else{
    dailyChart.style.display = 'none';
  }
  if (selectRev.value === 'opt-monthly'){
    monthlyChart.style.display = 'block';
  }else{
    monthlyChart.style.display = 'none';
  }
});

if(daily_labels && daily_total_amount){
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


if(monthly_labels && monthly_total_amount){
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









