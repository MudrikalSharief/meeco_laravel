import { render2FAuthToggleState, toggle2FAuthState } from './admin_settings_tfauth.js';
import { openThemesModal, setTheme, closeThemesModal } from './admin_settings_theme.js';

render2FAuthToggleState();

const tfauthButton = document.getElementById('authBtn');
const themesButton = document.getElementById('themesButton');
const themesModal = document.getElementById('themesModal');
const themesSelect = document.getElementById('themesSelect');
const themesModalExit = themesModal.querySelector('#themesModalExit');

document.addEventListener('DOMContentLoaded', function(){

    tfauthButton.addEventListener('click',  toggle2FAuthState);
    
    themesButton.addEventListener('click', ()=> openThemesModal(themesModal));

    themesSelect.addEventListener('change', ()=> setTheme(themesSelect));
    
    themesModalExit.addEventListener('click', ()=> closeThemesModal(themesModal));

});
