function allowEditAPSettings(APforms, APtable){

    const paragraphs = APtable.querySelectorAll('p');
    paragraphs.forEach(p => {

        p.classList.add('hidden');

    })


    APforms.forEach(forms => {

        forms.classList.remove('hidden');

    });
    
    
};

function renderPasswordConfigurations(){

    fetch('/admin/pass-configurations',{
        method:'GET',
    })
    .then(res => res.json())
    .then(data => assignPasswordConfigurations(data));

}

function assignPasswordConfigurations(data){

    const passwordConfigurations = APtable.querySelectorAll('.password_configurations');

    const configArray = Object.values(data.configurations[0]);

    let i = 0;

    passwordConfigurations.forEach(configurations =>{

        configurations.textContent = configArray[i];
    
        i++;

    })


}

export { allowEditAPSettings, renderPasswordConfigurations };