function addEventListeners() {
  let buttons = document.getElementsByClassName("upvote-button-question");
  Array.from(buttons).forEach(element => {
    element.addEventListener('click', function (event) {
      event.preventDefault();
      if(!isAuthenticated) return;
      vote('up', element.parentNode, this.dataset, true);
    });
  });

  buttons = document.getElementsByClassName("downvote-button-question");
  Array.from(buttons).forEach(element => {
    element.addEventListener('click', function (event) {
      event.preventDefault();
      if(!isAuthenticated) return;
      vote('down', element.parentNode, this.dataset, true);
    });
  });

  buttons = document.getElementsByClassName("upvote-button-answer");
  Array.from(buttons).forEach(element => {
    element.addEventListener('click', function (event) {
      event.preventDefault();
      if(!isAuthenticated) return;
      vote('up', element.parentNode, this.dataset, false);
    });
  });

  buttons = document.getElementsByClassName("downvote-button-answer");
  Array.from(buttons).forEach(element => {
    element.addEventListener('click', function (event) {
      event.preventDefault();
      if(!isAuthenticated) return;
      vote('down', element.parentNode, this.dataset, false);
    });
  });
}

const vote = (vote, votes, dataset, isQuestion) => {
  let baseEndpoint = isQuestion ? '/api/question/' : '/api/answer/';
  let upvoteButton = votes.children[0];
  let downvoteButton = votes.children[2];
  let ratio = parseInt(votes.children[1].innerHTML);

  let notUp = upvoteButton.classList.contains('teal');
  let notDown = downvoteButton.classList.contains('pink');
  let none = notUp && notDown;

  switch (vote) {
    case 'up':
      // add QuestionVote
      if (none) {
        upvoteButton.classList.remove('teal');
        upvoteButton.classList.add('active-teal');
        sendAjaxRequest('post', baseEndpoint + dataset.contentId + '/vote', { value: 1 }, null);
        votes.children[1].innerHTML = ratio + 1;
      }
      // delete QuestionVote
      else if (!notUp) {
        upvoteButton.classList.remove('active-teal');
        upvoteButton.classList.add('teal');
        sendAjaxRequest('delete', baseEndpoint + dataset.contentId + '/vote', null, null);
        votes.children[1].innerHTML = ratio - 1;
      }
      // edit QuestionVote
      else if (!notDown) {
        upvoteButton.classList.remove('teal');
        upvoteButton.classList.add('active-teal');
        downvoteButton.classList.remove('active-pink');
        downvoteButton.classList.add('pink');
        sendAjaxRequest('put', baseEndpoint + dataset.contentId + '/vote', { value: 1 }, null);
        votes.children[1].innerHTML = ratio + 2;
      }
      break;

    case 'down':
      // add QuestionVote
      if (none) {
        downvoteButton.classList.remove('pink');
        downvoteButton.classList.add('active-pink');
        sendAjaxRequest('post', baseEndpoint + dataset.contentId + '/vote', { value: -1 }, null);
        votes.children[1].innerHTML = ratio - 1;
      }
      // delete QuestionVote
      else if (!notUp) {
        upvoteButton.classList.remove('active-teal');
        upvoteButton.classList.add('teal');
        downvoteButton.classList.remove('pink');
        downvoteButton.classList.add('active-pink');
        sendAjaxRequest('put', baseEndpoint + dataset.contentId + '/vote', { value: -1 }, null);
        votes.children[1].innerHTML = ratio - 2;
      }
      // edit QuestionVote
      else if (!notDown) {
        downvoteButton.classList.remove('active-pink');
        downvoteButton.classList.add('pink');
        sendAjaxRequest('delete', baseEndpoint + dataset.contentId + '/vote', null, null);
        votes.children[1].innerHTML = ratio + 1;
      }
      break;

    default:
      alert('Something wrong with buttons (found: a ' + vote + ' request)')
      return;
  }
}

const toggleStar = starButton => {
  if (starButton.children[0].classList.contains('far')) {
    starButton.innerHTML = '<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;Saved'
    starButton.classList.remove('bookmark')
    starButton.classList.add('active-bookmark')
  }
  else {
    starButton.innerHTML = '<i class="far fa-bookmark" aria-hidden="true"></i>&nbsp;Save'
    starButton.classList.remove('active-bookmark')
    starButton.classList.add('bookmark')
  }
};

const toggleFollow = followButton => {
  if (followButton.children[0].classList.contains('far')) {
    followButton.innerHTML = '<i class="fa fa-star" aria-hidden="true"></i>&nbsp;Following'
    followButton.classList.remove('follow')
    followButton.classList.add('active-follow')
  }
  else {
    followButton.innerHTML = '<i class="far fa-star" aria-hidden="true"></i>&nbsp;Follow'
    followButton.classList.remove('active-follow')
    followButton.classList.add('follow')
  }
};

const toogleText = textDropdown => {
  if (textDropdown.classList.contains('active-dark')) {
    textDropdown.classList.remove('active-dark');
    textDropdown.classList.add('dark');
  }
  else {
    textDropdown.classList.remove('dark');
    textDropdown.classList.add('active-dark');
  }
}

addEventListeners();