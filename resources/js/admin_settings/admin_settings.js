import { render2FAuthToggleState, toggle2FAuthState } from './admin_settings_tfauth.js';
import { openThemesModal, setTheme, closeThemesModal } from './admin_settings_theme.js';
import { allowEditAPSettings } from './admin_settings_accpass.js';

render2FAuthToggleState();

const tfauthBtn = document.getElementById('authBtn');

const APtable = document.getElementById('APtable');
const editAPBtn = document.getElementById('editAccountPasswordsBtn');
const APforms = document.querySelectorAll('.account-passwords')

const themesBtn = document.getElementById('themesButton');
const themesModal = document.getElementById('themesModal');
const themesSelect = document.getElementById('themesSelect');
const themesModalExit = themesModal.querySelector('#themesModalExit');

document.addEventListener('DOMContentLoaded', function(){

    tfauthBtn.addEventListener('click',  toggle2FAuthState);

    editAPBtn.addEventListener('click', ()=> allowEditAPSettings(APforms, APtable));
    
    themesBtn.addEventListener('click', ()=> openThemesModal(themesModal));

    themesSelect.addEventListener('change', ()=> setTheme(themesSelect));
    
    themesModalExit.addEventListener('click', ()=> closeThemesModal(themesModal));

});
