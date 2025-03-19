window.darkMode = function(){
    document.documentElement.classList.add('dark');
}

const themeSelect = document.getElementById('themeSelect');
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
});
