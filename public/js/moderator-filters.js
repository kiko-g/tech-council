/**
 * Users
 */
const userSearchInput = document.getElementById("user-search-input");
let userQuery = "";

userSearchInput.addEventListener("keypress", sendUserSearchRequest);

function sendUserSearchRequest(event) {
    if (event.key !== 'Enter') return;
    userQuery = userSearchInput.value;
    
    sendAjaxRequest(
        "get", 
        "api/search/user", 
        {
            query_string: userQuery,
            rpp: 6,
            page: 1
        },
        userSearchHandler
    );
}

function userSearchHandler() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-user-results");
        searchResults.innerHTML = this.responseText;

        updateUserHandlers();
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
            query_string: userQuery,
            page: currentPage,
            rpp: resultsPerPage
        },
        updateUserPagination
    )
}

function updateUserPagination() {
    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("search-user-results");
        console.log(searchResults);
        window.scrollTo(0, 0);
        searchResults.innerHTML = this.responseText;

        updateUserHandlers();
    }
}


/**
 * Tags
 */
 const tagSearchInput = document.getElementById("tag-search-input");
 let tagQuery = "";
 
 tagSearchInput.addEventListener("keypress", sendTagSearchRequest);
 
 function sendTagSearchRequest(event) {
     if (event.key !== 'Enter') return;
     tagQuery = tagSearchInput.value;
     
     sendAjaxRequest(
         "get", 
         "api/search/tag", 
         {
             query_string: tagQuery,
             rpp: 10,
             page: 1,
             type: "alphabetical",
             is_view: 2
         },
         tagSearchHandler
     );
 }
 
 function tagSearchHandler() {
     if (this.status == 200 || this.status == 201) {
         const searchResults = document.getElementById("search-tag-results");
         searchResults.innerHTML = this.responseText;
 
         updateTagHandlers();
     }
 }

 // Tag pagination
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
             query_string: tagQuery,
             page: currentPage,
             rpp: resultsPerPage,
             type: "alphabetical",
             is_view: 2
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