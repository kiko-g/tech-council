function addEventListeners() {
    let answerSubmitForm = document.getElementById('answer-submit-form');
    
    try {
        answerSubmitForm.addEventListener('submit', submitAnswer);
    } catch(e) {}

    //TODO: answer_edit_form
    //TODO: answer_delete_form

    //TODO: question_edit_form
    //TODO: question_delete_form
}

function submitAnswer(event) {
    event.preventDefault();
    if(!isAuthenticated)
        return;

    let fields = getFormValues(this);
    console.log(fields);
    // TODO clear form input feedback here
    // TODO validate form input here

    let questionId = this.dataset.questionId;
    // TODO add loading here
    sendAjaxRequest('post', '/api/question/' + questionId + '/answer', { main: fields.main }, answerAddedHandler);
}

function answerAddedHandler() {
    // TODO clear loading here
    let response = JSON.parse(this.responseText);

    if(this.status == 200 || this.status == 201) {
        let newAnswer = createAnswer(response.main, response.id, response.author_id);

        // Add new question
        let answers = document.getElementById('answer-section');
        answers.prepend(newAnswer);

        // Reset form value
        let form = document.getElementById('answer-submit-input');
        form.value = "";
    } else {
        // TODO set input error
    }
}

function createAnswer(main, answerId, authorId) {
    let newAnswer = document.createElement('div');
    newAnswer.classList.add('card', 'mb-4', 'border-0', 'p-0', 'rounded', 'bg-background-color');
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
    `

    // TODO: edit timestamp and user

    return newAnswer;
}

addEventListeners();