
async function render2FAuthToggleState(){

    fetch('/admin/2f-auth-state', {
        method: 'GET'
    })
    .then(res => res.json())
    .then(data => toggle2FAuthStateBtn(data)
    );

}

function toggle2FAuthState(){
    fetch('/admin/2f-auth-state', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({})
    })

    render2FAuthToggleState();
}

function toggle2FAuthStateBtn(data){

    const authBtn = document.getElementById('authBtn');
    const toggle = authBtn.querySelector('#toggle');

    if(data.auth_state.tf_auth_state === 'on'){
        authBtn.classList.remove('bg-gray-200');
        authBtn.classList.add('bg-blue-500');
        toggle.classList.remove('translate-x-1');
        toggle.classList.add('translate-x-6');
    }else{
        toggle.classList.remove('bg-blue-500');
        authBtn.classList.add('bg-gray-200');
        toggle.classList.remove('translate-x-6');
        toggle.classList.add('translate-x-1');
    }

}

export { render2FAuthToggleState, toggle2FAuthState, toggle2FAuthStateBtn };