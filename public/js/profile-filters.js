/**
 * Filters
 */
let questionActiveRadio = null;
let questionPreviousRadio = null;
let questionRadioFilters = document.querySelectorAll('[id^=filterRadio]');
const urlParts = window.location.href.split("/");
const userId = urlParts.pop() || urlParts.pop();
const filterData = {};
let apiUrl = "/api/search/question";
 
questionRadioFilters.forEach(element => {
    if (element.checked) questionActiveRadio = element.id.split('_')[1];
    element.addEventListener('click', event => {
        questionActiveRadio = element.id.split('_')[1];
        if(questionActiveRadio !== questionPreviousRadio) {
            sendQuestionFilterRequest(questionActiveRadio);
        }
        questionPreviousRadio = questionActiveRadio;
    })
});

function sendQuestionFilterRequest(filterType) {
    const filterData = {
        queryString: '',
        type: 'best',
        rpp: 6,
        page: 1
    }
    switch(filterType) {
        case 'saved':
            apiUrl = "/api/search/question";
            filterData.saved = userId;
            break;

        case 'myquestions':
            apiUrl = "/api/search/question";
            filterData.author = userId;
            break;

        case 'myanswers':
            apiUrl = "/api/search/answer";
            filterData.author = userId; //TODO: change
            break;
    }

    sendAjaxRequest(
        "get", 
        apiUrl, 
        filterData,
        questionFilterHandler
    );
}

function questionFilterHandler() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-question-results");
        searchResults.innerHTML = this.responseText;

        updateQuestionHandlers();
    }
}

/**
 * Pagination
 */
let questionPrevious = document.getElementById("search-question-previous");
let questionNext = document.getElementById("search-question-next");
let questionCurrent = document.getElementById("search-question-current");
questionPrevious.addEventListener("click", event => paginateQuestion(event, "prev"));
questionNext.addEventListener("click", event => paginateQuestion(event, "next"));

function updateQuestionHandlers() {
    questionPrevious = document.getElementById("search-question-previous");
    questionNext = document.getElementById("search-question-next");
    questionCurrent = document.getElementById("search-question-current");
    questionPrevious.addEventListener("click", event => paginateQuestion(event, "prev"));
    questionNext.addEventListener("click", event => paginateQuestion(event, "next"));
}

function paginateQuestion(event, type) {
    event.preventDefault();
    currentPage = parseInt(questionCurrent.getAttribute("page"));
    maxPage = parseInt(questionCurrent.getAttribute("pages"));
    resultsPerPage = parseInt(questionCurrent.getAttribute("rpp"));
    if(type === "prev") {
        currentPage--;
    } else {
        currentPage++;
    }

    if(currentPage == 0 || currentPage > maxPage) return;

    filterData.rpp = resultsPerPage;
    filterData.page = currentPage;

    sendAjaxRequest(
        "get",
        apiUrl,
        filterData,
        updateQuestionPagination
    )
}

function updateQuestionPagination() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-question-results");
        window.scrollTo(0, 0);
        searchResults.innerHTML = this.responseText;

        updateQuestionHandlers();
    }
}