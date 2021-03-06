function upvote(upvoteButton) {
  let ratio = parseInt(upvoteButton.parentElement.childNodes[3].innerHTML);
  upvoteButton.parentElement.childNodes[3].innerHTML = ratio + 1;
}

function downvote(downvoteButton) {
  let ratio = parseInt(downvoteButton.parentElement.childNodes[3].innerHTML);
  downvoteButton.parentElement.childNodes[3].innerHTML = ratio - 1;
}


// add event to buttons
let buttons = document.getElementsByClassName("upvote-button");
Array.from(buttons).forEach(function (element) {
  element.addEventListener('click', function (event) {
    event.preventDefault();
    console.log("Upvote!");
  });
});

buttons = document.getElementsByClassName("downvote-button");
Array.from(buttons).forEach(function (element) {
  element.addEventListener('click', function (event) {
    event.preventDefault();
    console.log("Downvote!");
  });
});