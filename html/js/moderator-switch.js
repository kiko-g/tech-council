let usersOrTags = document.getElementsByClassName('users-or-tags-button')[0];
let userArea = document.getElementsByClassName('user-area')[0];
let tagArea = document.getElementsByClassName('tag-area')[0];

// User area option
usersOrTags.children[0].addEventListener('click', (event) => {
    event.preventDefault();
    if (!event.target.classList.contains('active')) {
        event.target.classList.add('active');
        userArea.style.display = 'block';
        usersOrTags.children[1].classList.remove('active');
        tagArea.style.display = 'none';
    }
});

usersOrTags.children[1].addEventListener('click', (event) => {
    event.preventDefault();
    if (!event.target.classList.contains('active')) {
        event.target.classList.add('active');
        tagArea.style.display = 'block';
        usersOrTags.children[0].classList.remove('active');
        userArea.style.display = 'none';
    }
});