import './bootstrap';

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

    const fileInput = document.getElementById('imageInput');
    const uploadButton = document.getElementById('uploadButton');

    if (fileInput && uploadButton) {
        // Disable the upload button initially
        uploadButton.disabled = true;

        // Enable the upload button only if a file is selected
        fileInput.addEventListener('change', () => {
            uploadButton.disabled = fileInput.files.length === 0;
        });
    }
});

//This is for upload image modal logic
const openModal = document.getElementById('openModal');
const closeModal = document.getElementById('closeModal');
const uploadModal = document.getElementById('uploadModal');
const cancelUpload = document.getElementById('cancelUpload');

if(openModal){
    openModal.addEventListener('click', () => uploadModal.classList.remove('hidden'));
}
if(closeModal){
    closeModal.addEventListener('click', () => uploadModal.classList.add('hidden'));
}
if(cancelUpload){
    cancelUpload.addEventListener('click', () => uploadModal.classList.add('hidden'));
}

//This is for the upload image from modal
const uploaded = document.getElementById('uploadButton');

if(uploaded){
    uploaded.addEventListener('click', function () {
        // Disable the upload button to prevent spamming
        uploaded.disabled = true;

        let form = document.getElementById('uploadForm');
        const fileInput = document.getElementById('imageInput'); 
    
        let formData = new FormData(form);
    
        fetch('/capture/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Images uploaded successfully!');
                    // Handle success (e.g., refresh image list or close modal)
                    document.querySelector('#uploadModal').classList.add('hidden')
                    fileInput.value = ''; // Reset the file input value
                    
                    fetch('/capture/images')
                    .then(response => response.json())
                    .then(data => {
                        if (data.images && data.images.length > 0) {
                            // Clear the container first (optional, in case of reloads)
                            imageContainer.innerHTML = '';
    
                            data.images.forEach(url => {
                                // Create an image element for each URL
                                const img = document.createElement('img');
                                img.src = url;
                                img.alt = 'Uploaded Image';
                                img.className = ' w-28 h-32 object-cover m-2 border border-gray-300 rounded';
                                imageContainer.appendChild(img); // Append to the container
                            });
                        }
                        
                        });
                        
                } else {
                    alert('Failed to upload images.');
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                // Re-enable the upload button after the request is complete
                uploaded.disabled = false;
            });
    });
}

//this is for the Showing the image
// Fetch and display previously uploaded images
document.addEventListener('DOMContentLoaded', function () {
    const imageContainer = document.getElementById('imageContainer');
    const extractTextButton = document.getElementById('extractTextButton');

    function toggleExtractButton() {
        if (imageContainer.children.length > 0) {
            extractTextButton.classList.remove('hidden');
        } else {
            extractTextButton.classList.add('hidden');
        }
    }

    // Initial check
    toggleExtractButton();

    if(imageContainer){
        fetch('/capture/images')
        .then(response => response.json())
        .then(data => {
            if (data.images && data.images.length > 0) {
                // Clear the container first (optional, in case of reloads)
                imageContainer.innerHTML = '';
                console.log(data.images);
                data.images.forEach(url => {
                    // Create an image element for each URL
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Uploaded Image';
                    img.className = ' w-28 h-32 object-cover m-2 border border-gray-300 rounded';
                    imageContainer.appendChild(img); // Append to the container
                });
                toggleExtractButton();
            } else {
                // Show a message if no images are available
                const message = document.createElement('p');
                message.textContent = 'No images uploaded yet.';
                message.className = 'text-gray-500 mt-2';
                imageContainer.appendChild(message);
                
            }
        })
        .catch(error => console.error('Error fetching uploaded images:', error));
    }

    // Example event listener for image upload (you need to implement the actual logic)
    document.getElementById('uploadButton').addEventListener('click', function () {
        // Simulate image upload
        setTimeout(function () {
            const img = document.createElement('img');
            img.src = 'path/to/image.jpg'; // Replace with actual image path
            imageContainer.appendChild(img);
            toggleExtractButton();
        }, 1000);
    });

    // Example event listener for image removal (you need to implement the actual logic)
    imageContainer.addEventListener('click', function (event) {
        if (event.target.tagName === 'IMG') {
            imageContainer.removeChild(event.target);
            toggleExtractButton();
        }
    });
});

//THis is for FAQ in WEBSITE
document.addEventListener('DOMContentLoaded', function () {
    const accordion_header = document.querySelectorAll('.accordion-header');
    if(accordion_header){
        accordion_header.forEach(button => {
            button.addEventListener('click', () => {
                const accordionItem = button.parentElement;
                const isActive = accordionItem.classList.contains('active');
                
                // Close all accordion items
                document.querySelectorAll('.accordion-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // If the clicked item wasn't active, open it
                if (!isActive) {
                    accordionItem.classList.add('active');
                }
            });
        });
    }
});