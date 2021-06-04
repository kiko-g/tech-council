const urlParams = new URLSearchParams(window.location.search);
const queryString = urlParams.get('q') || '';
const urlParts = window.location.href.split("/");
const tagId = urlParts.pop() || urlParts.pop();

/**
 * Question Filters
 */
let questionActiveRadio = null;
let questionPreviousRadio = null;
let questionRadioFilters = document.querySelectorAll('[id^=filterRadio]');

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
    sendAjaxRequest(
        "get", 
        "api/search/question", 
        {
            query_string: queryString,
            type: filterType,
            rpp: 6,
            page: 1,
            tag: tagId
        },
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
 * Question search pagination
 */
 let questionPrevious = document.getElementById("search-question-previous");
 let questionNext = document.getElementById("search-question-next");
 let questionCurrent = document.getElementById("search-question-current");
 
 try {
     questionPrevious.addEventListener("click", event => paginateQuestion(event, "prev"));
     questionNext.addEventListener("click", event => paginateQuestion(event, "next"));
 } catch(ignore) {}
 
 function updateQuestionHandlers() {
     questionPrevious = document.getElementById("search-question-previous");
     questionNext = document.getElementById("search-question-next");
     questionCurrent = document.getElementById("search-question-current");
 
     try {
         questionPrevious.addEventListener("click", event => paginateQuestion(event, "prev"));
         questionNext.addEventListener("click", event => paginateQuestion(event, "next"));
     } catch(ignore) {}
 }
 
 function paginateQuestion(event, type) {
     event.preventDefault();
     currentPage = parseInt(questionCurrent.getAttribute("data-page"));
     maxPage = parseInt(questionCurrent.getAttribute("data-pages"));
     resultsPerPage = parseInt(questionCurrent.getAttribute("data-rpp"));
     if(type === "prev") {
         currentPage--;
     } else {
         currentPage++;
     }
 
     if(currentPage == 0 || currentPage > maxPage) return;
 
     sendAjaxRequest(
         "get",
         "/api/search/question",
         {
            query_string: queryString,
            page: currentPage,
            rpp: resultsPerPage,
            type: questionActiveRadio,
            tag: tagId
         },
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