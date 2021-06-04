const urlParams = new URLSearchParams(window.location.search);
const queryString = urlParams.get('q') || '';

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
    console.log(`OLA: ${queryString}`);

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

        updateQuestionHandlers();
    }
}

/**
 * Tag filters
 */
let tagActiveRadio = null;
let tagPreviousRadio = null;
let tagRadioFilters = document.querySelectorAll('[id^=tagFilterRadio]');
 
tagRadioFilters.forEach(element => {
     if (element.checked) tagActiveRadio = element.id.split('_')[1];
     element.addEventListener('click', event => {
         tagActiveRadio = element.id.split('_')[1];
         if(tagActiveRadio !== tagPreviousRadio) {
             sendTagFilterRequest(tagActiveRadio);
         }
         tagPreviousRadio = tagActiveRadio;
     })
 });

 function sendTagFilterRequest(filterType) {

    console.log(queryString);

    sendAjaxRequest(
        "get", 
        "/api/search/tag", 
        {
            query_string: queryString,
            type: filterType,
            rpp: 6,
            page: 1,
            is_view: 1
        },
        tagFilterHandler
    );
}

function tagFilterHandler() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-tag-results");
        searchResults.innerHTML = this.responseText;

        updateTagHandlers();
    }
}

/**
 * Question search pagination
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

    sendAjaxRequest(
        "get",
        "/api/search/question",
        {
            query_string: queryString,
            page: currentPage,
            rpp: resultsPerPage,
            type: questionActiveRadio
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

/**
 * Tag search pagination
 */
let tagPrevious = document.getElementById("search-tag-previous");
let tagNext = document.getElementById("search-tag-next");
let tagCurrent = document.getElementById("search-tag-current");

tagPrevious.addEventListener("click", event => paginateTag(event, "prev"));
tagNext.addEventListener("click", event => paginateTag(event, "next"));

function updateTagHandlers() {
    console.log("HELLO");
    tagPrevious = document.getElementById("search-tag-previous");
    tagNext = document.getElementById("search-tag-next");
    tagCurrent = document.getElementById("search-tag-current");
    tagPrevious.addEventListener("click", event => paginateTag(event, "prev"));
    tagNext.addEventListener("click", event => paginateTag(event, "next"));
}

function paginateTag(event, type) {
    event.preventDefault();
    currentPage = parseInt(tagCurrent.getAttribute("page"));
    maxPage = parseInt(tagCurrent.getAttribute("pages"));
    resultsPerPage = parseInt(tagCurrent.getAttribute("rpp"));
    if(type === "prev") {
        currentPage--;
    } else {
        currentPage++;
    }

    if(currentPage == 0 || currentPage > maxPage) return;

    sendAjaxRequest(
        "get",
        "/api/search/tag",
        {
            query_string: queryString,
            page: currentPage,
            rpp: resultsPerPage,
            type: tagActiveRadio,
            is_view: 1
        },
        updateTagPagination
    )
}

function updateTagPagination() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-tag-results");
        window.scrollTo(0, 0);
        searchResults.innerHTML = this.responseText;

        updateTagHandlers();
    }
}

// User pagination
const userPrevious = document.getElementById("search-user-previous");
const userNext = document.getElementById("search-user-next");
const userCurrent = document.getElementById("search-user-current");
