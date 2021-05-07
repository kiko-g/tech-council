function addQuestionEventListeners() {
    let answerSubmitForm = document.getElementById("answer-submit-form");

    try {
        answerSubmitForm.addEventListener("submit", submitAnswer);
    } catch (e) {}

    let answerEditButtons = document.getElementsByClassName("answer-edit");
    for (answerEditButton of answerEditButtons)
        answerEditButton.addEventListener("click", editingAnswer);

    let answerDeleteButtons = document.getElementsByClassName(
        "delete-answer-modal-trigger"
    );
    if (answerDeleteButtons.length > 0) {
        for (button of answerDeleteButtons)
            button.addEventListener("click", handleDeleteAnswerModal);
    }

    let questionDeleteButtons = document.getElementsByClassName(
        "delete-question-modal-trigger"
    );
    if (questionDeleteButtons.length > 0) {
        for (button of questionDeleteButtons)
            button.addEventListener("click", handleDeleteQuestionModal);
    }

    //TODO: question_edit_form
}

function editingAnswer() {
    let idArray = this.id.split("-");
    let answerId = idArray.pop();
    idArray.push("form");
    idArray.push(answerId);
    let formId = idArray.join("-");

    let editForm = document.getElementById(formId);
    editForm.addEventListener("submit", editAnswer);
}

function editAnswer(event) {
    event.preventDefault();
    let idString = this.id;
    let answerId = idString.split("-").pop();
    let main = document.getElementById("answer-submit-input-" + answerId).value;

    sendAjaxRequest(
        "put",
        "/api/answer/" + answerId + "/edit",
        { main: main },
        editAnswerHandler
    );
}

function editAnswerHandler() {
    let response = JSON.parse(this.responseText);
    let answer = document.getElementById("answer-content-" + response.id);
    console.log("answer: " + answer);
    if (this.status == 200 || this.status == 201) {
        console.log("response: " + response.main);
        // set edited content
        answer.innerHTML = `
            <p>
                ${response.main}
            </p>
        `;

        // set confirmation message
        let confirmation = document.createElement("div");
        confirmation.innerHTML = successAlert("Answer edited successfully");
        let answerCard = document.getElementById("answer-" + response.id);
        answerCard.parentNode.insertBefore(confirmation, answerCard);
    } else {
    }
}

function handleDeleteQuestionModal() {
    let deleteButton = document.getElementsByClassName("delete-modal");

    if (deleteButton.length > 0) {
        for (button of deleteButton)
            button.addEventListener("click", deleteQuestion);
    }
}

function deleteQuestion(event) {
    event.preventDefault();
    let idString = this.id;
    let questionId = idString.split("-").pop();

    sendAjaxRequest(
        "delete",
        "/api/question/" + questionId + "/delete",
        null,
        deleteQuestionHandler
    );
}

function deleteQuestionHandler() {
    let response = JSON.parse(this.responseText);

    let deletedQuestion = document.getElementById("question-" + response.id);
    let confirmation = document.createElement("div");
    if (this.status == 200 || this.status == 201) {
        confirmation.innerHTML = successAlert("Question deleted successfully");
        deletedQuestion.parentNode.insertBefore(confirmation, deletedQuestion);
        deletedQuestion.remove();
    } else {
        confirmation.innerHTML = errorAlert("Error deleting question");
        deletedQuestion.parentNode.insertBefore(confirmation, deletedQuestion);
    }
}

function handleDeleteAnswerModal() {
    let deleteButton = document.getElementsByClassName("delete-modal");

    if (deleteButton.length > 0) {
        for (button of deleteButton)
            button.addEventListener("click", deleteAnswer);
    }
}

function deleteAnswer(event) {
    event.preventDefault();
    let idString = this.id;
    let answerId = idString.split("-").pop();

    sendAjaxRequest(
        "put",
        "/api/answer/" + answerId + "/delete",
        null,
        deleteAnswerHandler
    );
}

function deleteAnswerHandler() {
    let response = JSON.parse(this.responseText);

    let deleteAnswer = document.getElementById("answer-" + response.id);
    let confirmation = document.createElement("div");
    if (this.status == 200 || this.status == 201) {
        confirmation.innerHTML = successAlert("Answer deleted successfully");
        deleteAnswer.parentNode.insertBefore(confirmation, deleteAnswer);
        deleteAnswer.remove();
    } else {
        confirmation.innerHTML = errorAlert("Error deleting answer");
        deleteAnswer.parentNode.insertBefore(confirmation, deleteAnswer);
    }
}

function submitAnswer(event) {
    event.preventDefault();
    if (!isAuthenticated) return;

    let fields = getFormValues(this);
    // TODO clear form input feedback here
    // TODO validate form input here

    let questionId = this.dataset.questionId;
    // TODO add loading here
    sendAjaxRequest(
        "post",
        "/api/question/" + questionId + "/answer",
        { main: fields.main },
        answerAddedHandler
    );
}

function answerAddedHandler() {
    // TODO clear loading here
    let response = JSON.parse(this.responseText);

    if (this.status == 200 || this.status == 201) {
        let newAnswer = createAnswer(
            response.main,
            response.id,
            response.author_id
        );

        // Add new question
        let answers = document.getElementById("answer-section");
        answers.prepend(newAnswer);

        // Reset form value
        let form = document.getElementById("answer-submit-input");
        form.value = "";

        addQuestionEventListeners();
        addVoteEventListeners();
    } else {
        // TODO set input error
    }
}

//TODO: check if needed
function createdAnswerEventListeners(answerId) {
    const upvoteButton = document.getElementById(`upvote-button-${answerId}`);
    upvoteButton.addEventListener('click', function (event) {
        event.preventDefault();
        if(!isAuthenticated) return;
        vote('up', this.parentNode, this.dataset, false);
    });

    const downvoteButton = document.getElementById(`downvote-button-${answerId}`);
    downvoteButton.addEventListener('click', function (event) {
        event.preventDefault();
        if(!isAuthenticated) return;
        vote('down', this.parentNode, this.dataset, false);
    });
}

function createAnswer(main, answerId, authorId) {
    let newAnswer = document.createElement("div");
	newAnswer.id = "answer-" + answerId;
    newAnswer.classList.add(
        "card",
        "mb-4",
        "border-0",
        "p-0",
        "rounded",
        "bg-background-color"
    );

    console.log("sending request...");

    // send request
    sendAjaxRequest(
        "get",
        "/api/answer/" + answerId,
        null,
        function() {
            console.log("request status: " + this.status);
            if (this.status == 200 || this.status == 201) {
                console.log(this.responseText);
                newAnswer.innerHTML = this.responseText;
            }
        }
    );

    // TODO: edit timestamp and user

    return newAnswer;
}

addQuestionEventListeners();
