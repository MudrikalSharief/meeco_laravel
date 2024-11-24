import './bootstrap';

// This code is for the background color of the navigation when clicked
// document.addEventListener('DOMContentLoaded', () => {
//     const menuItems = document.querySelectorAll('.menu-item');

//     if(menuItems){
//         menuItems.forEach((item) => {
//             item.addEventListener('click', function () {
//                 // Remove the background from all items
//                 menuItems.forEach(item => {item.classList.remove('bg-blue-100')});
//                 // Add the background to the clicked item
//                 this.classList.add('bg-blue-100');
//             }); 
//         });
//     }
// });

// code is for the collapse and expand of the burger


document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger');
    const sidebar = document.getElementById('sidebar');
    const upper_nav=document.getElementById('upper_nav');
    const navTexts = document.querySelectorAll('.nav_text');
    const name = document.querySelector('.name');


        // Check if the burger element exists before adding the event listener
    if (burger) {
        burger.addEventListener('click', () => {
            // Toggle the sidebar width between collapsed and expanded
            if (sidebar.classList.contains('w-14')) {
                sidebar.classList.remove('w-14');
                sidebar.classList.add('w-52');
                upper_nav.classList.remove('pl-16');
                upper_nav.classList.add('pl-56');
                // Show the text labels
                navTexts.forEach(text => text.classList.remove('hidden'));
                name.classList.add('hidden');
            } else {
                sidebar.classList.remove('w-52');
                sidebar.classList.add('w-14');
                upper_nav.classList.remove('pl-56');
                upper_nav.classList.add('pl-16');

                // Hide the text labels
                navTexts.forEach(text => text.classList.add('hidden'));
                name.classList.remove('hidden');
            }
        });
    }
});
    