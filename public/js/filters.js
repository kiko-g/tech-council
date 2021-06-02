let activeRadio = null;
let radioFilters = document.querySelectorAll('[id^=filterRadio]')

radioFilters.forEach(element => {
    if (element.checked) activeRadio = element.id.split('_')[1];
    element.addEventListener('click', event => {
        activeRadio = element.id.split('_')[1];
        switch (activeRadio) {
            case 'best':
                console.log('Paginate questions related to followed tags first');
                break;

            case 'new':
                console.log('Paginate questions ordered by date in descending order');
                break;

            case 'trending':
                console.log('Paginate by trending criteria');
                break;

            case 'interactions':
                console.log('Paginate order by $question->countInteractions()');
                break;

            default:
                break;
        }
    })
});