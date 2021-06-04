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

    console.log(filterType);

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

try {
    tagPrevious.addEventListener("click", event => paginateTag(event, "prev"));
    tagNext.addEventListener("click", event => paginateTag(event, "next"));
} catch(ignore) {}


function updateTagHandlers() {
    tagPrevious = document.getElementById("search-tag-previous");
    tagNext = document.getElementById("search-tag-next");
    tagCurrent = document.getElementById("search-tag-current");

    try {
        tagPrevious.addEventListener("click", event => paginateTag(event, "prev"));
        tagNext.addEventListener("click", event => paginateTag(event, "next"));
    } catch(ignore) {}
}

function paginateTag(event, type) {
    event.preventDefault();
    currentPage = parseInt(tagCurrent.getAttribute("data-page"));
    maxPage = parseInt(tagCurrent.getAttribute("data-pages"));
    resultsPerPage = parseInt(tagCurrent.getAttribute("data-rpp"));

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
let userPrevious = document.getElementById("search-user-previous");
let userNext = document.getElementById("search-user-next");
let userCurrent = document.getElementById("search-user-current");

try {
    userPrevious.addEventListener("click", event => paginateUser(event, "prev"));
    userNext.addEventListener("click", event => paginateUser(event, "next"));
} catch(ignore) {}


function updateUserHandlers() {
    userPrevious = document.getElementById("search-user-previous");
    userNext = document.getElementById("search-user-next");
    userCurrent = document.getElementById("search-user-current");

    try {
        userPrevious.addEventListener("click", event => paginateUser(event, "prev"));
        userNext.addEventListener("click", event => paginateUser(event, "next"));
    } catch(ignore) {}
}

function paginateUser(event, type) {
    event.preventDefault();
    currentPage = parseInt(userCurrent.getAttribute("data-page"));
    maxPage = parseInt(userCurrent.getAttribute("data-pages"));
    resultsPerPage = parseInt(userCurrent.getAttribute("data-rpp"));

    if(type === "prev") {
        currentPage--;
    } else {
        currentPage++;
    }

    if(currentPage == 0 || currentPage > maxPage) return;

    sendAjaxRequest(
        "get",
        "/api/search/user",
        {
            query_string: queryString,
            page: currentPage,
            rpp: resultsPerPage
        },
        updateUserPagination
    )
}

function updateUserPagination() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-user-results");
        window.scrollTo(0, 0);
        searchResults.innerHTML = this.responseText;

        updateUserHandlers();
    }
}
