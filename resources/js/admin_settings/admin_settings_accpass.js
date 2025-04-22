function allowEditAPSettings(APforms, APtable){

    console.log(APforms, APtable);


    const paragraphs = APtable.querySelectorAll('p');
    paragraphs.forEach(p => {

        p.classList.add('hidden');

    })


    APforms.forEach(forms => {

        forms.classList.remove('hidden');

    });
    
    
};

function renderPasswordConfigurations(){

    const passwordConfigurations = APtable.querySelectorAll('.password_configurations');

    fetch('/admin/pass-configurations',{
        method:'GET',
    })
    .then(res => res.json())
    .then(data =>{ console.log(data.configurations)

    });

}

export { allowEditAPSettings, renderPasswordConfigurations };