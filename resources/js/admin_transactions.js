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

    
    // searchTransactions.addEventListener('input', (e) => {
    //     const searchValue = e.target.value.trim(); // Get the current search value

    //     // Perform the search operation
    //     fetch('/admin/search-transactions', {
    //         method: 'POST',
    //         headers: {
    //             "Content-Type": "application/json",
    //             "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    //         },
    //         body: JSON.stringify({ searchValue: searchValue })
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         // Handle the search results (e.g., update the DOM with the results)
    //         console.log("Search Results:", data);
    //     })
    //     .catch(error => {
    //         console.error("Error:", error);
    //     });
    // });

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