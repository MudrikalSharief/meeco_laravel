window.darkMode = function(){
    document.documentElement.classList.add('dark');
}

const themeSelect = document.getElementById('themeSelect');
const authButton = document.getElementById('authBtn');

themeSelect.addEventListener('change', ()=>{
    if(themeSelect.value === 'dark'){
        localStorage.setItem('darkModeVal', 'true'); 
    }else{
        localStorage.setItem('darkModeVal', 'false'); 
    }
});

document.addEventListener('DOMContentLoaded', function(){

    get2FactorAuthState();
    toggle2FAuthState();

    let themeModalId;;

    window.openThemeModal = function(modalId){
        document.getElementById(modalId).classList.remove('hidden');
        themeModalId = modalId;
    }

    document.getElementById('closeThemeModal').addEventListener('click', ()=>{
        document.getElementById(themeModalId).classList.add('hidden');
    });

});

async function get2FactorAuthState(){

    fetch('/admin/2f-auth-state', {
        method: 'GET'
    })
    .then(res => res.json())
    .then(data =>{
        toggle2FAuthStateBtn(data);
    });

}

function toggle2FAuthState() {
    const authButton = document.getElementById('authBtn');
    authButton.addEventListener('click', ()=> {
   
        fetch('/admin/2f-auth-state', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data =>{

            console.log(data);
            get2FactorAuthState();
        });
  
    });
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