function openThemesModal(themesModal){
        
    themesModal.classList.remove('hidden');
}

function setTheme(themesSelect){

    if(themesSelect.value === 'dark'){
        localStorage.setItem('darkModeVal', 'true'); 
    }else{
        localStorage.setItem('darkModeVal', 'false'); 
    }

}

function closeThemesModal(themesModal){

    themesModal.classList.add('hidden');

}

window.darkMode = function(){
    document.documentElement.classList.add('dark');
}

export { openThemesModal, setTheme, closeThemesModal };