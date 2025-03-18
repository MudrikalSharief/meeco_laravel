import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    const burger = document.getElementById('burger');
    const sidebar = document.getElementById('sidebar');
    const upper_nav=document.getElementById('upper_nav');
    const navTexts = document.querySelectorAll('.nav_text');
    const name = document.querySelector('.name');
    const topicsContainer = document.getElementById('topicsContainer');

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



    //This is for the add Subject
    const topics_container = document.getElementById('topics-container');
    const addSubjectButton = document.getElementById('addSubjectButton');
    const addSubjectModal = document.getElementById('addSubjectModal');
    const cancelButton = document.getElementById('cancelButton');
    const saveButton = document.getElementById('saveButton');
    const newSubjectName = document.getElementById('newSubjectName');
    const subjectsContainer = document.getElementById('subjectsContainer');
    const noSubjectsMessage = document.getElementById('noSubjectsMessage');
    const noTopicsMessage = document.getElementById('noTopicsMessage');
    const NewSubjectError = document.getElementById('NewSubjectError');

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
                        subjectButton.href = `/subjects/${data.subject.subject_id}`;
                            subjectButton.innerHTML = `<button class="w-full text-start py-2 px-3 my-2 shadow-md bg-white rounded-md flex justify-between items-center hover:bg-blue-50 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300"> ${data.subject.name}
                                                         <span class="delete-subject text-red-500 h-full" data-subject-id="${data.subject.subject_id}"> <img class="w-full h-full max-h-6 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete"></span>
                                                        </button>`;
                            subjectsContainer.appendChild(subjectButton);

                            noSubjectsMessage.classList.add('hidden');
                            addSubjectModal.classList.add('hidden');
                            newSubjectName.value = '';
                        
                    } else {
                        if(NewSubjectError){
                        NewSubjectError.classList.remove('hidden');
                        }
                    }
                })
                .catch(error => console.error('Error adding2 subject:', error));
            }
        });
    }
    
   
         
  
    // This is for the add Topic
    const addTopicButton = document.getElementById('addTopicButton');
    const addTopicModal = document.getElementById('addTopicModal');
    const cancelTopicButton = document.getElementById('cancelTopicButton');
    const saveTopicButton = document.getElementById('saveTopicButton');
    const newTopicName = document.getElementById('newTopicName');
    const subjectId = document.querySelector('.p-3');
        
    if(subjectId){
        subjectId.dataset.subjectId;
    }

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


    const extractTextModal = document.getElementById('extractTextModal');
    const closeExtractTextModal = document.getElementById('closeExtractTextModal');
    const cancelExtractTextModal = document.getElementById('cancelExtractTextModal');
    const subjectDropdown = document.getElementById('subjectDropdown');
   

    if (closeExtractTextModal) {
        closeExtractTextModal.addEventListener('click', () => {
            extractTextModal.classList.add('hidden');
        });
    }

    if (cancelExtractTextModal) {
        cancelExtractTextModal.addEventListener('click', () => {
            extractTextModal.classList.add('hidden');
        });
    }


    const topicDropdown = document.getElementById('topicDropdown');
    const subjectReminder = document.getElementById('subjectReminder');
    const confirmExtractText = document.getElementById('confirmExtractText');


    if (confirmExtractText) {
        confirmExtractText.addEventListener('click', () => {
            if (!subjectDropdown.value) {
                subjectReminder.classList.remove('hidden');
            } else {
                // Proceed with the extraction process
                subjectReminder.classList.add('hidden');
                extractTextModal.classList.add('hidden');
                // Add your extraction logic here
            }
        });
    }

    const cancelSubjectButton = document.getElementById('cancelSubjectButton');
    const saveSubjectButton = document.getElementById('saveSubjectButton');
    const subjectConfirmModal = document.getElementById('subjectConfirmModal');
    const closeSubjectConfirm = document.getElementById('closeSubjectConfirm');

    if (addSubjectButton) {
        addSubjectButton.addEventListener('click', () => {
            addSubjectModal.classList.remove('hidden');
        });
    }

    if (cancelSubjectButton) {
        cancelSubjectButton.addEventListener('click', () => {
            addSubjectModal.classList.add('hidden');
            newSubjectName.value = '';
        });
    }


    if (closeSubjectConfirm) {
        closeSubjectConfirm.addEventListener('click', () => {
            subjectConfirmModal.classList.add('hidden');
        });
    }



    if (confirmExtractText) {
        confirmExtractText.addEventListener('click', () => {
            if (!subjectDropdown.value) {
                subjectReminder.classList.remove('hidden');
            } else {
                // Proceed with the extraction process
                subjectReminder.classList.add('hidden');
                extractTextModal.classList.add('hidden');
                // Add your extraction logic here
            }
        });
    }

    if (addSubjectButton) {
        addSubjectButton.addEventListener('click', () => {
            addSubjectModal.classList.remove('hidden');
        });
    }

    if (cancelSubjectButton) {
        cancelSubjectButton.addEventListener('click', () => {
            addSubjectModal.classList.add('hidden');
            newSubjectName.value = '';
        });
    }


    if (closeSubjectConfirm) {
        closeSubjectConfirm.addEventListener('click', () => {
            subjectConfirmModal.classList.add('hidden');
        });
    }

    const deleteTopicConfirmModal = document.getElementById('deleteTopicConfirmModal');
    const cancelTopicDelete = document.getElementById('cancelTopicDelete');
    const confirmTopicDelete = document.getElementById('confirmTopicDelete');
    
    let topicIdToDelete = null;
    let topicElementToDelete = null;

    if (cancelTopicDelete) {
        cancelTopicDelete.addEventListener('click', function () {
            deleteTopicConfirmModal.classList.add('hidden');
            topicIdToDelete = null;
            topicElementToDelete = null;
        });
    }

        if (confirmTopicDelete) {
            confirmTopicDelete.addEventListener('click', function () {
                if (topicIdToDelete && topicElementToDelete) {
                    fetch('/topics/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id: topicIdToDelete })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            topicElementToDelete.remove();
                            if (topics_container.children.length === 0) {
                                noTopicsMessage.classList.remove('hidden');
                            }
                        } else {
                            alert('Failed to delete topic.');
                        }
                    })
                    .catch(error => console.error('Error deleting topic:', error))
                    .finally(() => {
                        deleteTopicConfirmModal.classList.add('hidden');
                        topicIdToDelete = null;
                        topicElementToDelete = null;
                    });
                }
            });
        }

        if(extractTextModal){
            fetch('/topics')
            .then(response => response.json())
            .then(data => {
                if (data.topics && data.topics.length > 0 ) {
                    data.topics.forEach((topic, index) => {
                        const topicButton = document.createElement('button');
                        topicButton.className = 'subject_topics w-full text-start py-2 px-3 my-2 shadow-md rounded-md flex justify-between items-center hover:bg-blue-50 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300';
                        topicButton.id = topic.topic_id;
                        topicButton.innerHTML =` 
                                                        <span>${topic.name}</span>
                                                        <span class="delete-topic text-red-500 h-full" data-topic-id="${topic.topic_id}"><img class="w-full h-full max-h-6 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete"></span>
                                                    `;
                        if(topics_container){
                            topics_container.appendChild(topicButton);
                        }
                    });

                    document.querySelectorAll('.delete-topic').forEach(button => {
                        button.addEventListener('click', function (event) {
                            event.preventDefault(); // Prevent navigation
                            topicIdToDelete = this.getAttribute('data-topic-id');
                            topicElementToDelete = this.closest('a');
                            deleteTopicConfirmModal.classList.remove('hidden');
                        });
                    });
                } else {
                    if(noTopicsMessage){
                        noTopicsMessage.classList.remove('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching topics:', error));
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

// ...existing code...

const openCamera = document.getElementById('openCamera');
const closeCamera = document.getElementById('closeCamera');
const cameraModal = document.getElementById('cameraModal');
const cameraFeed = document.getElementById('cameraFeed');
const captureImage = document.getElementById('captureImage');
const captureConfirmModal = document.getElementById('captureConfirmModal');
const captureConfirmMessage = document.getElementById('captureConfirmMessage');
const closeCaptureConfirm = document.getElementById('closeCaptureConfirm');

if (openCamera) {
    openCamera.addEventListener('click', () => {
        cameraModal.classList.remove('hidden');
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    cameraFeed.srcObject = stream;
                    cameraFeed.play();
                })
                .catch(error => console.error('Error accessing camera:', error));
        }
    });
}

if (closeCamera) {
    closeCamera.addEventListener('click', () => {
        cameraModal.classList.add('hidden');
        if (cameraFeed.srcObject) {
            const stream = cameraFeed.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
            cameraFeed.srcObject = null;
        }
    });
}


// ...existing code...

    
    const extractTextModal = document.getElementById('extractTextModal');
    
    const cancelExtractTextModal = document.getElementById('cancelExtractTextModal');
    const subjectDropdown = document.getElementById('subjectDropdown');
    const topicsContainer = document.getElementById('topicsDropdownContainer');
    const topicDropdown = document.getElementById('topicDropdown');
    const subjectReminder = document.getElementById('subjectReminder');
    const confirmExtractText = document.getElementById('confirmExtractText');
    const noTopicsMessage = document.getElementById('noTopicsMessage');
    const addSubjectButton = document.getElementById('addSubjectButton');
    const addSubjectModal = document.getElementById('addSubjectModal');
    const cancelSubjectButton = document.getElementById('cancelSubjectButton');
    const saveSubjectButton = document.getElementById('saveSubjectButton');
    const newSubjectName = document.getElementById('newSubjectName');
    const subjectConfirmModal = document.getElementById('subjectConfirmModal');
    const closeSubjectConfirm = document.getElementById('closeSubjectConfirm');
    const addTopicButton = document.getElementById('addTopicButton');
    const addTopicModal = document.getElementById('addTopicModal');
    const cancelTopicButton = document.getElementById('cancelTopicButton');
    const saveTopicButton = document.getElementById('saveTopicButton');
    const newTopicName = document.getElementById('newTopicName');
    const topicConfirmModal = document.getElementById('topicConfirmModal');
    const closeTopicConfirm = document.getElementById('closeTopicConfirm');
    const topicExistsModal = document.getElementById('topicExistsModal');
    const closeTopicExists = document.getElementById('closeTopicExists');
    const topicsDropdownContainer = document.getElementById('topicsDropdownContainer');
    

    let subjectDropdownListenerAdded = false;
    let  selectedSubjectId = null;
    function handleSubjectDropdownChange() {
         selectedSubjectId = subjectDropdown.value;
        if (!selectedSubjectId) {
            subjectReminder.classList.remove('hidden');
            topicsContainer.classList.add('hidden');
            noTopicsMessage.classList.add('hidden');
            addTopicButton.classList.add('hidden');
        } else {
            subjectReminder.classList.add('hidden');
            addTopicButton.classList.remove('hidden');
            fetch(`/subject/topics/${selectedSubjectId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.topics && data.topics.length > 0) {
                        topicDropdown.innerHTML = '';
                        data.topics.forEach(topic => {
                            const option = document.createElement('option');
                            option.value = topic.topic_id;
                            option.textContent = topic.name;
                            topicDropdown.appendChild(option);
                        });
                        topicsDropdownContainer.classList.remove('hidden');
                        noTopicsMessage.classList.add('hidden');
                    } else {
                        topicsDropdownContainer.classList.add('hidden');
                        noTopicsMessage.classList.remove('hidden');
                    }
                })
                .catch(error => console.error('Error fetching topics:', error));
        }
    }

    

    if (saveSubjectButton) {
        saveSubjectButton.addEventListener('click', () => {
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
                        const option = document.createElement('option');
                        option.value = data.subject.subject_id;
                        option.textContent = data.subject.name;
                        subjectDropdown.appendChild(option);
                        subjectDropdown.value = data.subject.subject_id; // Select the newly added subject
                        subjectDropdown.dispatchEvent(new Event('change')); // Simulate a change event
                        handleSubjectDropdownChange();
                        addSubjectModal.classList.add('hidden');
                        newSubjectName.value = '';
                        subjectConfirmModal.classList.remove('hidden');
                    } else {
                        alert('Error adding subject: ' + data.message);
                    }
                })
                .catch(error => console.error('Error adding subject:', error));
            }
        });
    }


  
   
    
   
        
            if (addSubjectButton) {
                addSubjectButton.addEventListener('click', () => {
                    addSubjectModal.classList.remove('hidden');
                });
            }
        
            if (cancelSubjectButton) {
                cancelSubjectButton.addEventListener('click', () => {
                    addSubjectModal.classList.add('hidden');
                    newSubjectName.value = '';
                });
            }
        
            if (closeSubjectConfirm) {
                closeSubjectConfirm.addEventListener('click', () => {
                    subjectConfirmModal.classList.add('hidden');
                });
            }
        
            if (addTopicButton) {
                addTopicButton.addEventListener('click', () => {
                    addTopicModal.classList.remove('hidden');
                });
            }
        
            if (cancelTopicButton) {
                cancelTopicButton.addEventListener('click', () => {
                    addTopicModal.classList.add('hidden');
                    newTopicName.value = '';
                });
            }
            const topics_container = document.getElementById('topics-container');
            if (saveTopicButton) {
                saveTopicButton.addEventListener('click', () => {
                    const topicName = newTopicName.value.trim();
                    const selectedSubjectId = subjectDropdown ? subjectDropdown.value : document.querySelector('.subject_id_in_topics').dataset.subjectId;
        
                    if (topicName && selectedSubjectId) {
                        fetch('/topics/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ name: topicName, subject_id: selectedSubjectId })
                        })
                        .then(response => response.json().catch(() => {
                            return response.text().then(text => { throw new Error(text); });
                        }))
                        .then(data => {
                            if (data.success) {
                                const option = document.createElement('option');
                                if (topicDropdown) {
                                  
                                    if (topicsContainer) {
                                      
                                        topicDropdown.dispatchEvent(new Event('change')); // Simulate a change event    
                                        handleSubjectDropdownChange();
                                        option.value = data.topic.topic_id;
                                        option.textContent = data.topic.name;
                                        topicDropdown.appendChild(option);
                                        topicDropdown.value = data.topic.id; // Select the newly added topic
                                    }
                                   
                                    noTopicsMessage.classList.add('hidden');
                                    addTopicModal.classList.add('hidden');
                                    newTopicName.value = '';
                                    topicConfirmModal.classList.remove('hidden');
                                }else{
                                    const topicButton = document.createElement('a');
                                    topicButton.href = `/reviewer/${data.topic.topic_id}`;
                                    topicButton.value = data.topic.topic_id;
                                    topicButton.textContent = data.topic.name;
                                    topicButton.innerHTML = `<button class="w-full text-start py-2 px-3 my-2 shadow-md rounded-md flex justify-between items-center hover:bg-blue-50 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300">${topicName}
                                                                            <span class="delete-topic text-red-500 h-full" data-topic-id="${data.topic.topic_id}"><img class="w-full h-full max-h-6 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete"></span>
                                                                            </button>`;
                                    topics_container.appendChild(topicButton);
                                    noTopicsMessage.classList.add('hidden');
                                    addTopicModal.classList.add('hidden');
                                    topicConfirmModal.classList.remove('hidden');
                                    newTopicName.value = '';
                                }
                                 
                               
                            } else {
                                if (data.message.includes('unique')) {
                                    topicExistsModal.classList.remove('hidden');
                                } else {
                                    alert('Error adding topic: ' + data.message);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error adding topic:', error);
                            alert('Error adding topic: ' + error.message);
                        });
                    }
        
                });
            }
        
            if (closeTopicConfirm) {
                closeTopicConfirm.addEventListener('click', () => {
                    topicConfirmModal.classList.add('hidden');
                });
            }
        
            if (closeTopicExists) {
                closeTopicExists.addEventListener('click', () => {
                    topicExistsModal.classList.add('hidden');
                });
            }
    
        
        
            const deleteTopicConfirmModal = document.getElementById('deleteTopicConfirmModal');

            const subjectId = document.querySelector('.subject_id_in_topics');
            if(subjectId){
                subjectId.dataset.subjectId;
            }
        
            if (addTopicButton) {
                addTopicButton.addEventListener('click', function () {
                    addTopicModal.classList.remove('hidden');
                });
            }
        
            if (cancelTopicButton) {
                cancelTopicButton.addEventListener('click', function () {
                    addTopicModal.classList.add('hidden');
                    newTopicName.value = '';
                });
            }

            

    if (closeTopicConfirm) {
        closeTopicConfirm.addEventListener('click', () => {
            topicConfirmModal.classList.add('hidden');
        });
    }

    if (closeTopicExists) {
        closeTopicExists.addEventListener('click', () => {
            topicExistsModal.classList.add('hidden');
        });
    }

    const saveTopicButtonCapture = document.getElementById('saveTopicButtonCapture');
    if (saveTopicButtonCapture) {
        saveTopicButtonCapture.addEventListener('click', () => {
            const topicName = newTopicName.value.trim();
            const selectedSubjectId = subjectDropdown ? subjectDropdown.value : document.querySelector('.subject_id_in_topics').dataset.subjectId;

            if (topicName && selectedSubjectId) {
                fetch('/topics/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ name: topicName, subject_id: selectedSubjectId })
                })
                .then(response => response.json().catch(() => {
                    return response.text().then(text => { throw new Error(text); });
                }))
                .then(data => {
                    if (data.success) {
                        const option = document.createElement('option');
                        if (topicDropdown) {
                          
                            if (topicsContainer) {
                              
                                topicDropdown.dispatchEvent(new Event('change')); // Simulate a change event    
                                handleSubjectDropdownChange();
                                option.value = data.topic.topic_id;
                                option.textContent = data.topic.name;
                                topicDropdown.appendChild(option);
                                topicDropdown.value = data.topic.id; // Select the newly added topic
                            }
                           
                            noTopicsMessage.classList.add('hidden');
                            addTopicModal.classList.add('hidden');
                            newTopicName.value = '';
                            topicConfirmModal.classList.remove('hidden');
                        }else{
                            const topicButton = document.createElement('a');
                            topicButton.value = data.topic.topic_id;
                            topicButton.textContent = data.topic.name;
                            topicButton.innerHTML = `<button class="max-w-2xl w-full border text-start py-2 px-3 my-2 shadow-md rounded-md">${topicName}</button>`;
                            topics_container.appendChild(topicButton);
                            noTopicsMessage.classList.add('hidden');
                            addTopicModal.classList.add('hidden');
                            topicConfirmModal.classList.remove('hidden');
                            newTopicName.value = '';
                        }
                         
                       
                    } else {
                        if (data.message.includes('unique')) {
                            topicExistsModal.classList.remove('hidden');
                        } else {
                            alert('Error adding topic: ' + data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error adding topic:', error);
                    alert('Error adding topic: ' + error.message);
                });
            }

        });
    }    
  
    
    // ...existing code...

