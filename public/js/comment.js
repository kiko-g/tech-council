let addComments = document.getElementsByClassName("add-comment");
for (const addComment of addComments) addComment.addEventListener("click", addListeners);

function addListeners() {
    let id = this.dataset.parentId;
    let button = document.getElementById(`submit-comment-${id}`);
    if (button.dataset.parentType == "question")
        button.addEventListener('click', submitQuestionComment);
    else if (button.dataset.parentType == "answer")
        button.addEventListener('click', submitAnswerComment);
}

function submitQuestionComment() {
    let id = this.dataset.parentId;
    let main = document.getElementById(`comment-main-${id}`).value;

    sendAjaxRequest(
        'post',
        "/api/question/comment/insert",
        {
            question_id: id,
            main: main
        },
        submitCommentHandler
    );
}

function submitAnswerComment() {
    let id = this.dataset.parentId;
    let main = document.getElementById(`comment-main-${id}`).value;

    sendAjaxRequest(
        'post',
        "/api/answer/comment/insert",
        {
            answer_id: id,
            main: main
        },
        submitCommentHandler
    );
}

function submitCommentHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText);
        let comments = document.getElementById(`comments-${response.parent_id}`);
        let commentNode = document.createElement('div');
        commentNode.innerHTML = response.comment;
        comments.appendChild(commentNode);
        document.getElementById(`comment-main-${response.parent_id}`).value = "";
    } else { }
}