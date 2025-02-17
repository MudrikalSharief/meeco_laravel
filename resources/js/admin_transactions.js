document.addEventListener("DOMContentLoaded", ()=>{
    const statusFilter = document.getElementById('subscription-filter');
    let filterValue;

    statusFilter.addEventListener('change', (e)=>{
        if (statusFilter.value === 'Subscribed'){
            filterValue = statusFilter.value;
        }
        else if (statusFilter.value === 'Admin Granted'){
            filterValue = statusFilter.value;
        }

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