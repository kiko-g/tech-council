// let votes = document.querySelectorAll("#vote-ratio-");
// votes.forEach(votes => votes.innerHTML = parseInt(votes.innerHTML) + 1)

function upvote(upvoteButton) {
  let ratio = parseInt(upvoteButton.parentElement.childNodes[3].innerHTML);
  upvoteButton.parentElement.childNodes[3].innerHTML = ratio + 1;
}

function downvote(downvoteButton) {
  let ratio = parseInt(downvoteButton.parentElement.childNodes[3].innerHTML);
  downvoteButton.parentElement.childNodes[3].innerHTML = ratio - 1;
}