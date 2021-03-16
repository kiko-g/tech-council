let userTagsOrReports = document.getElementsByClassName('users-tags-or-reports-button')[0];
let userArea = document.getElementsByClassName('user-area')[0];
let tagArea = document.getElementsByClassName('tag-area')[0];
let reportArea = document.getElementsByClassName('report-area')[0];

// User area option
userTagsOrReports.children[0].addEventListener('click', (event) => {
    event.preventDefault();
    if (!event.target.classList.contains('active')) {
        event.target.classList.add('active');
        userArea.style.display = 'block';
        userTagsOrReports.children[1].classList.remove('active');
        userTagsOrReports.children[2].classList.remove('active');
        reportArea.style.display = 'none';
        tagArea.style.display = 'none';
    }
});

userTagsOrReports.children[1].addEventListener('click', (event) => {
    event.preventDefault();
    if (!event.target.classList.contains('active')) {
        event.target.classList.add('active');
        tagArea.style.display = 'block';
        userTagsOrReports.children[0].classList.remove('active');
        userTagsOrReports.children[2].classList.remove('active');
        userArea.style.display = 'none';
        reportArea.style.display = 'none';
    }
});

userTagsOrReports.children[2].addEventListener('click', (event) => {
    event.preventDefault();
    if (!event.target.classList.contains('active')) {
        event.target.classList.add('active');
        reportArea.style.display = 'block';
        userTagsOrReports.children[0].classList.remove('active');
        userTagsOrReports.children[1].classList.remove('active');
        userArea.style.display = 'none';
        tagArea.style.display = 'none';
    }
});