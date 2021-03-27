const selected = document.getElementsByClassName("tag-selected");
for (const tag of selected) {
    tag.addEventListener("click", () => tag.remove());
}

const tags = document.getElementById("tags");
const searchTags = document.getElementsByClassName("search-tag");
for (const tag of searchTags) {
    tag.addEventListener("click", selectTag);
}

function selectTag() {
    tags.appendChild(this);
    this.classList.add("me-1");
    this.querySelector("a").firstChild.classList.replace("fa-plus-square", "fa-minus-square");
    this.addEventListener("click", () => this.remove());
}