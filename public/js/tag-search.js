const askQuestionSelectedTags = new Set();

function addEventListeners() {
    const tagSearch = document.getElementById("ask-search-tag");
    try { tagSearch.addEventListener("keyup", searchAskTags); } catch(ignore) {}
    const tags = document.getElementById("ask-selected-tags").children;
    for (const tag of tags) tag.addEventListener("click", removeTagSearch);
}

function searchAskTags(event) {
    sendAjaxRequest(
        "get", 
        "/api/search/tag", 
        {
            bundled: 0,
            query_string: event.target.value,
            rpp: 10,
            page: 1,
            type: 'best',
        }, 
        tagSearchHandler
    );
}

function tagSearchHandler() {
    let response = JSON.parse(this.responseText);

    if (this.status == 200 || this.status == 201) {
        const searchResults = document.getElementById("ask-tag-search-results");
        searchResults.innerHTML = "";

        response
            .map((searchResult) => createTagSearchResult(searchResult))
            .map((tag) => {
                if(!askQuestionSelectedTags.has(tag.id)) {
                    searchResults.appendChild(tag);
                    tag.onclick = selectTagSearch;
                }
            });
    }
}

function createTagSearchResult(searchResult) {
    const tagSearchResult = document.createElement("div");
    tagSearchResult.id = "tag-" + searchResult.name;
    tagSearchResult.classList.add(
        "search-tag",
        "btn-group",
        "mt-1"
    );
    tagSearchResult.dataset.tag = searchResult.name;

    const tagAnchor = document.createElement("a");
    tagAnchor.classList.add(
        "btn",
        "blue-alt",
        "border-0",
        "my-btn-pad2"
    );
    
    tagAnchor.innerHTML = `<i class="fas fa-plus-square"></i>&nbsp;${searchResult.name}`;
    tagSearchResult.appendChild(tagAnchor);
    
    return tagSearchResult;
}

function selectTagSearch() {
    const selectedTags = document.getElementById("ask-selected-tags");
    selectedTags.appendChild(this);

    this.classList.add("me-1");
    this.querySelector("a").firstChild.classList.replace("fa-plus-square", "fa-minus-square");
    this.onclick = removeTagSearch;

    askQuestionSelectedTags.add(this.id);
}

function removeTagSearch() {    
    const searchValue = document.getElementById("ask-search-tag").value;
    const tagName = this.id.substring(4);

    if(tagName == searchValue) {
        this.querySelector("a").firstChild.classList.replace("fa-minus-square", "fa-plus-square");
        this.classList.remove("me-1");
        document.getElementById("ask-tag-search-results").appendChild(this);
        this.onclick = selectTagSearch;
    } else {
        this.remove();
    }

    askQuestionSelectedTags.delete(this.id);
}

addEventListeners();