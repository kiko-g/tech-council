function addEventListeners() {
    let answerSubmitForm = document.getElementById("answer-submit-form");

    try {
        answerSubmitForm.addEventListener("submit", submitAnswer);
    } catch (e) {}

    let editButtons = document.getElementsByClassName(
        "answer-edit"
    );
    for (editButton of editButtons)
        editButton.addEventListener("click", editingAnswer);

    //TODO: answer_delete_form

    //TODO: question_edit_form
    let questionDeleteForms = document.getElementsByClassName(
        "delete-question"
    );

    for (form of questionDeleteForms)
        form.addEventListener("submit", deleteQuestion);
}

function editingAnswer() {
    console.log("editing");
    let idArray = this.id.split("-");
    let answerId = idArray.pop();
    idArray.push('form');
    idArray.push(answerId);
    let formId = idArray.join('-');

    let editForm = document.getElementById(formId);
    console.log(editForm);
    editForm.addEventListener("submit", editAnswer);
}

function editAnswer(event) {
    event.preventDefault();
    console.log("edit");
    let idString = this.id;
    let answerId = idString.split('-').pop();
    let main = document.getElementById('answer-submit-input-' + answerId).value;
    console.log('main = ' + main);

    sendAjaxRequest(
        "put",
        "/api/answer/" + answerId + "/edit",
        { main: main },
        editAnswerHandler
    );
}

function editAnswerHandler() {
    console.log('status: ' + this.status);
    if (this.status == 200 || this.status == 201) {
        // set edited content
        let response = JSON.parse(this.responseText);
        let answer = document.getElementById('answer-content-' + response.id);
        answer.innerHTML = `
            <p>
                ${response.main}
            </p>
        `;

        // reset collapses
        let collapses = document.getElementsByClassName('answer-collapse');
        for (collapse of collapses) {
            if (collapse.classList.contains('show'))
                collapse.classList.remove('show');
            else 
                collapse.classList.add('show');
        }
    } else {
        // TODO set input error
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

    if (this.status == 200 || this.status == 201) {
        document.getElementById("question-" + response.id).remove();
    } else {
        // TODO set input error
    }
}

function submitAnswer(event) {
    event.preventDefault();
    if (!isAuthenticated) return;

    let fields = getFormValues(this);
    console.log(fields);
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
    } else {
        // TODO set input error
    }
}

function createAnswer(main, answerId, authorId) {
    let newAnswer = document.createElement("div");
    newAnswer.classList.add(
        "card",
        "mb-4",
        "border-0",
        "p-0",
        "rounded",
        "bg-background-color"
    );
    newAnswer.innerHTML = `
    <div class="card m-1">
        <div class="card-body">
        <p class="mb-3">
            ${main}
        </p>

        <div class="row row-cols-3 mb-4" data-content-id="${answerId}">
            <div class="col-lg flex-wrap">
                <div id="votes-${answerId}" class="votes btn-group-special btn-group-vertical-when-responsive mt-1 flex-wrap">
                <a id="upvote-button-${answerId}" class="upvote-button-answer my-btn-pad btn btn-outline-success teal" data-content-id="${answerId}">
                    <i class="fas fa-chevron-up"></i>
                </a>
                <a id="vote-ratio-${answerId}" class="vote-ratio-answer btn my-btn-pad fake disabled"> 0 </a>
                <a id="downvote-button-${answerId}" class="downvote-button-answer my-btn-pad btn btn-outline-danger pink" data-content-id="${answerId}">
                    <i class="fas fa-chevron-down"></i>
                </a>
                </div>
            </div>
        </div>

        </div>

        <div class="card-footer text-muted text-end p-0">
        <blockquote class="blockquote mb-0">
        <p class="card-text px-1"><small class="text-muted">asked Aug 14 2020 at 15:31&nbsp;<a class="signature" href="#">user</a></small></p>
        </blockquote>
        </div>
    </div>
    `;

    // TODO: edit timestamp and user

    return newAnswer;
}

addEventListeners();
