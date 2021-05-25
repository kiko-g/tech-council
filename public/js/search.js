function addEventListeners() {
    const tagSearch = document.getElementById("ask-search-tag");
    try { tagSearch.addEventListener("keyup", searchAskTags); } catch(ignore) {}
}

function searchAskTags(event) {
    console.log(event.target.value);
}

addEventListeners();