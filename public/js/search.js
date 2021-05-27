function addEventListeners() {
    const tagSearch = document.getElementById("ask-search-tag");
    try { tagSearch.addEventListener("keyup", searchAskTags); } catch(ignore) {}
}

function searchAskTags(event) {
    console.log(event.target.value);

    sendAjaxRequest(
        "get", 
        "/api/search/tag", 
        {
            query_string: event.target.value,
            rpp: 10,
            page: 1,
            type: 'best',
        }, 
        function () {
            if (this.status == 200 || this.status == 201) {
                console.log(this.responseText);
        }
    });
}

addEventListeners();