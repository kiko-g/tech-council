function addQuestionEventListeners() {
    answerButtonsListeners();
    questionButtonsListeners();
}

function answerButtonsListeners(htmlNode = document) {
    let answerSubmitForm = document.getElementById("answer-submit-form");
    try {
        answerSubmitForm.addEventListener("submit", submitAnswer);
    } catch (e) { }

    let answerEditButtons = htmlNode.getElementsByClassName("answer-edit");
    for (answerEditButton of answerEditButtons)
        answerEditButton.addEventListener("click", editingAnswer);

    let answerDeleteButtons = htmlNode.getElementsByClassName(
        "delete-answer-modal-trigger"
    );
    if (answerDeleteButtons.length > 0) {
        for (button of answerDeleteButtons)
            button.addEventListener("click", handleDeleteAnswerModal);
    }
}

function questionButtonsListeners(htmlNode = document) {
    let questionSubmit = document.getElementById("ask-question");
    try {
        questionSubmit.addEventListener("click", submitQuestion);
    } catch (e) { }

    let questionDeleteButtons = htmlNode.getElementsByClassName(
        "delete-question-modal-trigger"
    );
    if (questionDeleteButtons.length > 0) {
        for (button of questionDeleteButtons)
            button.addEventListener("click", handleDeleteQuestionModal);
    }
}

function editingAnswer() {
    let idArray = this.id.split("-");
    let answerId = idArray.pop();

    let confirmEdit = document.getElementById(`confirm-edit-${answerId}`);
    confirmEdit.addEventListener("click", editAnswer);
}

function editAnswer(event) {
    let id = this.dataset.id;
    let main = document.getElementById("answer-submit-input-" + id).value;
    sendAjaxRequest(
        "put",
        "/api/answer/" + id + "/edit",
        { main: main },
        editAnswerHandler
    );
}

function editAnswerHandler() {
    let response = JSON.parse(this.responseText);
    let answer = document.getElementById("answer-content-" + response.id);

    if (this.status == 200 || this.status == 201) {
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
        //TODO: set error alert
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
        confirmation.style.opacity = 1;
        confirmation.innerHTML = successAlert("Question deleted successfully");
        deletedQuestion.parentNode.insertBefore(confirmation, deletedQuestion);
        deletedQuestion.remove();

        let timer = 0;
        let interval = 200;
        let intervalId = setInterval(function () {
            timer += 200;
            if (timer > 2000) confirmation.style.opacity -= 0.05;
            if (confirmation.style.opacity == 0) {
                confirmation.remove();
                clearInterval(intervalId)
            }
        }, interval);
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
        "delete",
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
        confirmation.style.opacity = 1;
        confirmation.innerHTML = successAlert("Answer deleted successfully");
        deleteAnswer.parentNode.insertBefore(confirmation, deleteAnswer);
        deleteAnswer.remove();

        let intervalId = setInterval(function () {
            confirmation.style.opacity -= 0.02;
            if (confirmation.style.opacity == 0) {
                confirmation.remove();
                clearInterval(intervalId)
            }
        }, 200);
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

    let md = new Remarkable();
    let questionId = this.dataset.questionId;
    // TODO add loading here

    sendAjaxRequest(
        "post",
        "/api/question/" + questionId + "/answer",
        { main: md.render(fields.main) },
        answerAddedHandler
    );
}

function answerAddedHandler() {
    // TODO clear loading here
    let response = JSON.parse(this.responseText);

    if (this.status == 200 || this.status == 201) {
        let newAnswer = createAnswer(response.id);

        // Add new question
        let answers = document.getElementById("answers");
        answers.prepend(newAnswer);

        // Reset form value
        let form = document.getElementById("answer-submit-input");
        form.value = "";
    } else {
        // TODO: set input error
    }
}

function createAnswer(answerId) {
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

    // send request
    sendAjaxRequest("get", "/api/answer/" + answerId, null, function () {
        if (this.status == 200 || this.status == 201) {
            newAnswer.innerHTML = this.responseText;
            answerVoteListeners(newAnswer);
            answerButtonsListeners(newAnswer);
        }
    });

    // TODO: edit timestamp and user

    return newAnswer;
}

function submitQuestion(event) {
    event.preventDefault();

    let title = document.getElementById("input-title").value;
    let main = document.getElementById("input-body").value;
    let tagElements = document.getElementById("ask-selected-tags").children;
    
    let tags = [];
    for (const tag of tagElements) tags.push(tag.dataset.tag);

    console.log({
        title: title,
        main: main,
        tags: tags
    });

    sendAjaxRequest(
        "post",
        "/api/create/question",
        {
            title: title,
            main: main,
            tags: tags
        },
        createQuestionHandler
    );
}

function createQuestionHandler() {
    if (this.status != 200 && this.status != 201) {
        let header = document.getElementById("create-question-header");
        let confirmation = document.createElement("div");
        confirmation.innerHTML = errorAlert("Question could not be created!");
        header.parentNode.parentNode.insertBefore(confirmation, header.parentNode);
    } else {
        console.log("got here");
        document.documentElement.innerHTML = this.responseText;
    }
}

/**
 * @brief script execution
 */
addQuestionEventListeners();