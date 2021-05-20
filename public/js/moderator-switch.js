let userTagsOrReports = document.getElementsByClassName(
    "users-tags-or-reports-button"
)[0];
let userArea = document.getElementsByClassName("user-area")[0];
let tagArea = document.getElementsByClassName("tag-area")[0];
let reportArea = document.getElementsByClassName("report-area")[0];

userArea.style.display = "block";
tagArea.style.display = "none";
reportArea.style.display = "none";

// User area option
userTagsOrReports.children[0].addEventListener("click", (event) => {
    event.preventDefault();
    if (!event.target.classList.contains("active")) {
        event.target.classList.add("active");
        userArea.style.display = "block";
        userTagsOrReports.children[1].classList.remove("active");
        userTagsOrReports.children[2].classList.remove("active");
        reportArea.style.display = "none";
        tagArea.style.display = "none";
    }
});

userTagsOrReports.children[1].addEventListener("click", (event) => {
    event.preventDefault();
    if (!event.target.classList.contains("active")) {
        event.target.classList.add("active");
        tagArea.style.display = "block";
        userTagsOrReports.children[0].classList.remove("active");
        userTagsOrReports.children[2].classList.remove("active");
        userArea.style.display = "none";
        reportArea.style.display = "none";
    }
});

userTagsOrReports.children[2].addEventListener("click", (event) => {
    event.preventDefault();
    if (!event.target.classList.contains("active")) {
        event.target.classList.add("active");
        reportArea.style.display = "block";
        userTagsOrReports.children[0].classList.remove("active");
        userTagsOrReports.children[1].classList.remove("active");
        userArea.style.display = "none";
        tagArea.style.display = "none";
    }
});

let userReportsButton = document.getElementById("user-reports-button");
let contentReportsButton = document.getElementById("content-reports-button");

let userReports = document.getElementById("user-reports");
let contentReports = document.getElementById("content-reports");
contentReports.style.display = "none";

userReportsButton.addEventListener("click", (event) => {
    event.preventDefault();
    if (!event.target.classList.contains("active")) {
        event.target.classList.add("active");
        userReports.style.display = "block";
        contentReportsButton.classList.remove("active");
        contentReports.style.display = "none";
    }
});

contentReportsButton.addEventListener("click", (event) => {
    event.preventDefault();
    if (!event.target.classList.contains("active")) {
        event.target.classList.add("active");
        contentReports.style.display = "block";
        userReportsButton.classList.remove("active");
        userReports.style.display = "none";
    }
});
