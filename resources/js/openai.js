document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    const form = document.querySelector('form');
    const messageInput = document.querySelector('#message');
    const submitButton = form.querySelector('button');
    const messagesContainer = document.querySelector('.messages');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        console.log('Form submitted');

        // Disable Form
        messageInput.disabled = true;
        submitButton.disabled = true;
        console.log('Form disabled');

        fetch('/openai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                content: messageInput.value
            })
        })
        .then(response => {
            console.log('Response received');
            return response.json();
        })
        .then(res => {
            console.log('Response parsed', res);

            // Populate sending message
            const userMessage = document.createElement('div');
            userMessage.classList.add('right', 'message');
            userMessage.innerHTML = `<p>${messageInput.value}</p>`;
            messagesContainer.appendChild(userMessage);
            console.log('User message added to chat');

            // Populate response message
            const aiMessage = document.createElement('div');
            aiMessage.classList.add('left', 'message');
            aiMessage.innerHTML = `<p>${res.choices[0].message.content}</p>`;
            messagesContainer.appendChild(aiMessage);
            console.log('AI message added to chat');

            // Clean Form
            messageInput.value = '';
            window.scrollTo(0, document.body.scrollHeight);
            console.log('Form cleaned and scrolled to bottom');
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Re-enable Form
            messageInput.disabled = false;
            submitButton.disabled = false;
            console.log('Form re-enabled');
        });
    });
});