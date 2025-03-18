var subsTable = $('#subsTable');

subsTable.DataTable({
    paging: true,
    info: false,
    ajax:{
        url:'/admin/search-transactions',
        type:'GET', 
        data: function(d){
            return {
            draw: d.draw,
            start: d.start,
            length: d.length,
            search: d.search.value,  // Send search term to the backend
            order: d.order[0] ? {
                column: d.order[0].column,
                dir: d.order[0].dir
            } : {}
            }
        }
    },
    columns: [
        { data: 'name' },
        { data: 'promo_name'},
        { data: 'reference_no' },
        { data: 'start_date' },
        { data: 'end_date' },
        { data: 'amount' },
    ]
}
);


document.addEventListener("DOMContentLoaded", ()=>{
    const statusFilter = document.getElementById('subscription-filter');
    const sortBy = document.getElementById('sort-by');
    const searchTransactions = document.getElementById('search-transactions');

    let filterValue, sortValue;

    const selectedStatusFilter = localStorage.getItem('selectedStatusFilter');
    if (selectedStatusFilter) {
        statusFilter.value = selectedStatusFilter;
    }

    const selectedSortBy = localStorage.getItem('selectedSortBy');
    if (selectedSortBy) {
        sortBy.value = selectedSortBy;
    }

    
   searchTransactions.addEventListener('input', (e)=>{

    fetch('/admin/search-transactions',{
        method: 'GET',
        headers:
        {
        'Content-Type':'application/json',
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data)
    })
    .catch(error => console.error(error))
   });

    sortBy.addEventListener('change', (e)=>{
        if(sortBy.value === 'Users' || sortBy.value === 'Promo Type' || sortBy.value === 'Date' || sortBy.value === 'Amount' ||  sortBy.value === 'Sort By'){
            sortValue = sortBy.value;
        }

        localStorage.setItem('selectedSortBy', sortValue);

        fetch('/admin/sort-transaction',{
            method:'POST',
            headers:{
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({sortValue: sortValue})
        })
        .then(res => res.json)
        .catch(error => console.error("Error:", error));

        window.location.reload()
    })

    statusFilter.addEventListener('change', (e)=>{
        if (statusFilter.value === 'Subscribed' || statusFilter.value === 'Admin Granted' || statusFilter.value === 'All'){
            filterValue = statusFilter.value;
        }

        localStorage.setItem('selectedStatusFilter', filterValue);

        fetch('/admin/filter-transaction',{
            method:'POST',
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: filterValue })
        })
        .then(res => res.json)
        .catch(error => console.error("Error:", error));

        window.location.reload();
    })
    


})