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
    const errorMessage = document.createElement('p');
    const p4 = document.querySelector('p4');
    errorMessage.textContent = 'Please select an image.';
    errorMessage.className = 'text-red-500 mt-2 hidden';

    if(p4){
        p4.appendChild(errorMessage);
    }

    if (fileInput && uploadButton) {
        // Disable the upload button initially
        uploadButton.disabled = true;

        // Enable the upload button only if a file is selected
        fileInput.addEventListener('change', () => {
            uploadButton.disabled = fileInput.files.length === 0;
            errorMessage.classList.add('hidden');
        });

        uploadButton.addEventListener('click', (event) => {
            if (fileInput.files.length === 0) {
                event.preventDefault();
                errorMessage.classList.remove('hidden');
            }
        });
    }

    if (fileInput && uploadButton) {
        // Disable the upload button initially
        uploadButton.disabled = true;

        // Enable the upload button only if a file is selected
        fileInput.addEventListener('change', () => {
            uploadButton.disabled = fileInput.files.length === 0;
        });
    }

    // Modal logic for displaying images
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    window.openModal = function(imageSrc) {
        modalImage.src = imageSrc;
        imageModal.classList.remove('hidden');
    };

    window.closeModal = function(event) {
        if (event.target === imageModal) {
            imageModal.classList.add('hidden');
        }
    };

    const imageContainer = document.getElementById('imageContainer');
    const zoomModal = document.getElementById('zoomModal');
    const zoomedImage = document.getElementById('zoomedImage');


    if (imageContainer) {
        imageContainer.addEventListener('click', (event) => {
            if (event.target.tagName === 'IMG') {
                zoomedImage.src = event.target.src;
                zoomModal.classList.remove('hidden');
            }
        });
    }


    if (zoomModal) {
        zoomModal.addEventListener('click', (event) => {
            if (event.target === zoomModal) {
                zoomModal.classList.add('hidden');
            }
        });
    }

    //This is for the add Subject
    const addSubjectButton = document.getElementById('addSubjectButton');
    const addSubjectModal = document.getElementById('addSubjectModal');
    const cancelButton = document.getElementById('cancelButton');
    const saveButton = document.getElementById('saveButton');
    const newSubjectName = document.getElementById('newSubjectName');
    const subjectsContainer = document.getElementById('subjectsContainer');
    const noSubjectsMessage = document.getElementById('noSubjectsMessage');

    if(addSubjectButton){
        addSubjectButton.addEventListener('click', function () {
            addSubjectModal.classList.remove('hidden');
        });

    }

    if(cancelButton){

        cancelButton.addEventListener('click', function () {
            addSubjectModal.classList.add('hidden');
            newSubjectName.value = '';
        });
    }

    if(saveButton){

        saveButton.addEventListener('click', function () {
            const subjectName = newSubjectName.value.trim();
            if (subjectName) {
                fetch('/subjects/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ name: subjectName })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const subjectButton = document.createElement('a');
                        subjectButton.href = `/subjects/${subjectName}`;
                        subjectButton.innerHTML = `<button class="w-full border text-start py-2 px-3 my-2 shadow-md rounded-md"> ${subjectName}</button>`;
                        subjectsContainer.appendChild(subjectButton);
                        noSubjectsMessage.classList.add('hidden');
                        addSubjectModal.classList.add('hidden');
                        newSubjectName.value = '';
                    } else {
                        alert('Error adding subject: ' + data.message);
                    }
                })
                .catch(error => console.error('Error adding subject:', error));
            }
        });
    }

    fetch('/subjects')
        .then(response => response.json())
        .then(data => {
            if (data.subjects && data.subjects.length > 0) {
                data.subjects.forEach((subject, index) => {
                    const subjectButton = document.createElement('a');
                    subjectButton.href = `/subjects/${subject.name}`;
                    subjectButton.innerHTML = `<button class="w-full border text-start py-2 px-3 my-2 shadow-md rounded-md"> ${subject.name}</button>`;
                    if(subjectsContainer){
                        subjectsContainer.appendChild(subjectButton);
                    }
                });
            } else {
                noSubjectsMessage.classList.remove('hidden');
            }
        })
        .catch(error => console.error('Error fetching subjects:', error));
  
    // This is for the add Topic
    const addTopicButton = document.getElementById('addTopicButton');
    const addTopicModal = document.getElementById('addTopicModal');
    const cancelTopicButton = document.getElementById('cancelTopicButton');
    const saveTopicButton = document.getElementById('saveTopicButton');
    const newTopicName = document.getElementById('newTopicName');
    const subjectId = document.querySelector('.p-3').dataset.subjectId;

    if(addTopicButton){
        addTopicButton.addEventListener('click', function () {
            addTopicModal.classList.remove('hidden');
        });
    }

    if(cancelTopicButton){
        cancelTopicButton.addEventListener('click', function () {
            addTopicModal.classList.add('hidden');
            newTopicName.value = '';
        });
    }

    if(saveTopicButton){
        saveTopicButton.addEventListener('click', function () {
            const topicName = newTopicName.value.trim();
            if (topicName) {
                fetch('/topics/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ name: topicName, subject_id: subjectId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const topicButton = document.createElement('a');
                        topicButton.href = `/topics/${topicName}`;
                        topicButton.innerHTML = `<button class="w-full border text-start py-2 px-3 my-2 shadow-md rounded-md"> ${topicName}</button>`;
                        const topicsContainer = document.querySelector('.topics-container');
                        if(topicsContainer){
                            topicsContainer.appendChild(topicButton);
                        } else {
                            // If no topics are present, replace the "No Topics to Show" message
                            const noTopicsMessage = document.querySelector('.text-gray-500');
                            if(noTopicsMessage){
                                noTopicsMessage.remove();
                            }
                            const newContainer = document.createElement('div');
                            newContainer.className = 'topics-container';
                            newContainer.appendChild(topicButton);
                            document.querySelector('.p-3').appendChild(newContainer);
                        }
                        addTopicModal.classList.add('hidden');
                        newTopicName.value = '';
                    } else {
                        alert('Error adding topic: ' + data.message);
                    }
                })
                .catch(error => console.error('Error adding topic:', error));
            }
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
                            imageContainer.innerHTML = '';
                            data.images.forEach((url, index) => {
                                const imgWrapper = document.createElement('div');
                                imgWrapper.className = 'm-2';

                                const img = document.createElement('img');
                                img.src = url;
                                img.alt = 'Uploaded Image';
                                img.className = 'w-28 h-32 object-cover border border-gray-300 rounded';

                                const name = document.createElement('p');
                                name.textContent = `Image ${index + 1}`;
                                name.className = 'text-center';

                                imgWrapper.appendChild(img);
                                imgWrapper.appendChild(name);
                                imageContainer.appendChild(imgWrapper);
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error))
                    .finally(() => {
                        uploaded.disabled = false;
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

//this is for the Showing the image in capture
// Fetch and display previously uploaded images
document.addEventListener('DOMContentLoaded', function () {
    const imageContainer = document.getElementById('imageContainer');
    const imageNamesContainer = document.getElementById('imageNamesContainer');
    const extractTextButton = document.getElementById('extractTextButton');

    function toggleExtractButton() {
        if(imageContainer){

                if (imageContainer.children.length > 0) {
                    extractTextButton.classList.remove('hidden');
                } else {
                    extractTextButton.classList.add('hidden');
                }
        }
    }

    // Initial check
    toggleExtractButton();

    if(imageContainer){
        fetch('/capture/images')
        .then(response => response.json())
        .then(data => {
            if (data.images && data.images.length > 0) {
                imageContainer.innerHTML = '';
                data.images.forEach((url, index) => {
                    const imgWrapper = document.createElement('div');
                    imgWrapper.className = 'm-2';

                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Uploaded Image';
                    img.className = 'w-28 h-32 object-cover border border-gray-300 rounded cursor-pointer';

                    const name = document.createElement('p');
                    name.textContent = `Image ${index + 1}`;
                    name.className = 'text-center';

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(name);
                    imageContainer.appendChild(imgWrapper);
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

    // // Example event listener for image upload (you need to implement the actual logic)
    // document.getElementById('uploadButton').addEventListener('click', function () {
    //     // Simulate image upload
    //     setTimeout(function () {
    //         const img = document.createElement('img');
    //         img.src = 'path/to/image.jpg'; // Replace with actual image path
    //         imageContainer.appendChild(img);
    //         toggleExtractButton();
    //     }, 1000);
    // });

    // Example event listener for image removal (you need to implement the actual logic)
    // imageContainer.addEventListener('click', function (event) {
    //     if (event.target.tagName === 'IMG') {
    //         imageContainer.removeChild(event.target);
    //         toggleExtractButton();
    //     }
    // });

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