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

    let themeModalId;;

    window.openThemeModal = function(modalId){
        document.getElementById(modalId).classList.remove('hidden');
        themeModalId = modalId;
    }

    document.getElementById('closeThemeModal').addEventListener('click', ()=>{
        document.getElementById(themeModalId).classList.add('hidden');
    });

    authButton.addEventListener('click', ()=>{

        const toggle = this.getElementById('toggle');

        fetch('/admin/settings-tfauth', {
            method:'GET',
            headers:{'Content-Type': "application/json",
              
            }
        })
        .then(res => res.json())
        .then(data =>{ 
            const authState = data.auth_state[0].tf_auth_state;

            console.log(authState);

            if(authState === 'off'){
                this.classList.remove('bg-gray-200');
                this.classList.add('bg-blue-500');
                toggle.classList.remove('translate-x-1');
                toggle.classList.add('translate-x-6');
        
            }else{
                this.classList.remove('bg-blue-500');
                this.classList.add('bg-gray-200');
                toggle.classList.remove('translate-x-6');
                toggle.classList.add('translate-x-1');
            }
        });


    });


});
