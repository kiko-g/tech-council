let questionActiveRadio = null;
let questionPreviousRadio = null;
let questionRadioFilters = document.querySelectorAll('[id^=filterRadio]');

const urlParams = new URLSearchParams(window.location.search);
const queryString = urlParams.get('q');
console.log(queryString);

questionRadioFilters.forEach(element => {
    if (element.checked) quetionActiveRadio = element.id.split('_')[1];
    element.addEventListener('click', event => {
        questionActiveRadio = element.id.split('_')[1];
        if(questionActiveRadio !== questionPreviousRadio) {
            sendQuestionFilterRequest(questionActiveRadio);
            switch (questionActiveRadio) {
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
        }
        questionPreviousRadio = questionActiveRadio;
    })
});

function sendQuestionFilterRequest(filterType) {
    const urlParams = new URLSearchParams(window.location.search);
    const queryString = urlParams.get('q');

    console.log(queryString);

    sendAjaxRequest(
        "get", 
        "api/search/question", 
        {
            query_string: queryString,
            type: filterType,
            rpp: 6,
            page: 1
        },
        questionFilterHandler
    );
}

function questionFilterHandler() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-question-results");
        searchResults.innerHTML = this.responseText;
    }
}