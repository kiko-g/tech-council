function vote(vote, votes) {
  let upvoteButton = votes.children[0];
  let downvoteButton = votes.children[2];
  let ratio = parseInt(votes.children[1].innerHTML);

  let notUp = upvoteButton.classList.contains('teal');
  let notDown = downvoteButton.classList.contains('pink');
  let none = notUp && notDown;

  switch (vote) {
    case 'up':
      if (none) {
        upvoteButton.classList.remove('teal');
        upvoteButton.classList.add('active-teal');
        votes.children[1].innerHTML = ratio + 1;
      }
      else if (!notUp) {
        upvoteButton.classList.remove('active-teal');
        upvoteButton.classList.add('teal');
        votes.children[1].innerHTML = ratio - 1;
      }
      else if (!notDown) {
        upvoteButton.classList.remove('teal');
        upvoteButton.classList.add('active-teal');
        downvoteButton.classList.remove('active-pink');
        downvoteButton.classList.add('pink');
        votes.children[1].innerHTML = ratio + 2;
      }
      break;

    case 'down':
      if (none) {
        downvoteButton.classList.remove('pink');
        downvoteButton.classList.add('active-pink');
        votes.children[1].innerHTML = ratio - 1;
      }
      else if (!notUp) {
        upvoteButton.classList.remove('active-teal');
        upvoteButton.classList.add('teal');
        downvoteButton.classList.remove('pink');
        downvoteButton.classList.add('active-pink');
        votes.children[1].innerHTML = ratio - 2;
      }
      else if (!notDown) {
        downvoteButton.classList.remove('active-pink');
        downvoteButton.classList.add('pink');
        votes.children[1].innerHTML = ratio + 1;
      }
      break;

    default:
      alert('Something wrong with buttons (found: a ' + vote + ' request)')
      return;
  }
}

// add event to buttons
let buttons = document.getElementsByClassName("upvote-button");
Array.from(buttons).forEach(function (element) {
  element.addEventListener('click', function (event) {
    event.preventDefault();
    // DO SOMETHING WITH UPVOTE
  });
});

buttons = document.getElementsByClassName("downvote-button");
Array.from(buttons).forEach(function (element) {
  element.addEventListener('click', function (event) {
    event.preventDefault();
    // DO SOMETHING WITH DOWNVOTE
  });
});