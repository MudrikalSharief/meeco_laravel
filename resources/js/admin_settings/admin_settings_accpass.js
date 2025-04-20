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

export { allowEditAPSettings };