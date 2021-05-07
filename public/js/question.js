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

        // reset collapses
        /*let collapses = document.getElementsByClassName("answer-collapse");
        for (collapse of collapses) {
            if (collapse.classList.contains("show"))
                collapse.classList.remove("show");
            else collapse.classList.add("show");
        }*/

        // set confirmation message
        let confirmation = document.createElement("div");
        confirmation.innerHTML = `
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <p> Answer edited successfully </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
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
        confirmation.innerHTML = `
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <p> Question deleted successfully </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
        deletedQuestion.parentNode.insertBefore(confirmation, deletedQuestion);
        deletedQuestion.remove();
    } else {
        confirmation.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <p> Error deleting question </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
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
        confirmation.innerHTML = `
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <p> Answer deleted successfully </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
        deleteAnswer.parentNode.insertBefore(confirmation, deleteAnswer);
        deleteAnswer.remove();
    } else {
        confirmation.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <p> Error deleting answer </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
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
    newAnswer.innerHTML = `
    <div class="card m-1">
    <div class="card-body">
      <article class="row row-cols-3 mb-1" data-content-id="${answerId}">
        <div class="col-auto flex-wrap">
			<div id="votes-${answerId}" class="votes btn-group-vertical mt-1 flex-wrap">
				<a id="upvote-button-${answerId}" class="upvote-button-answer my-btn-pad btn btn-outline-success teal" data-content-id="${answerId}">
					<i class="fas fa-chevron-up"></i>
				</a>
				<a id="vote-ratio-${answerId}" class="vote-ratio-answer btn my-btn-pad fake disabled"> 0 </a>
				<a id="downvote-button-${answerId}" class="downvote-button-answer my-btn-pad btn btn-outline-danger pink" data-content-id="${answerId}">
					<i class="fas fa-chevron-down"></i>
				</a>
	  		</div>
        </div>

        <div class="col-9 col-sm-9 col-md-9 col-lg-9 flex-wrap pe-0 collapse show answer-collapse-${answerId}">
          <div id="answer-content-${answerId}" class="mb-1">
            <p>
				${main}
            </p>
          </div>
        </div>
        <div class="col-2 p-0 m-0 collapse show answer-control answer-collapse-${answerId}" id="answer-control-${answerId}">
        <div class="btn-group float-end">
            <button class="btn p-0 answer-edit" id="answer-edit-${answerId}" type="button" data-bs-toggle="collapse" data-bs-target=".answer-collapse-${answerId}" aria-expanded="true" aria-controls="answer-content-${answerId} answer-control-${answerId}">
            <i class="fas fa-edit text-teal-300 mt-1 ms-2"></i>
            </button>
            
            <!-- Button trigger modal -->
            <button type="button" class="btn p-0 delete-answer-modal-trigger" data-bs-toggle="modal" data-bs-target="#delete-answer-modal-${answerId}">
            <i class="fas fa-trash text-wine mt-1 ms-2"></i>
            </button>
        </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="delete-answer-modal-${answerId}" tabindex="-1" aria-labelledby="delete-answer-modal-${answerId}-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="delete-answer-modal-${answerId}-label">Delete answer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                Deleting answer to question:
                <div class="alert alert-warning mt-2" role="alert">
                Warning! This action is not reversible. The answer and associated comments will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer">
                <form class="answer-delete" id="answer-delete-${answerId}" method="post">
					<button class="btn btn-success delete-modal" id="delete-answer-${answerId}" data-bs-dismiss="modal" type="submit">
						Delete
					</button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
            </div>
        </div>
        </div>

        <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0 collapse answer-collapse-${answerId}">
          <form class="answer-collapse-${answerId} container ps-0 answer-edit-form" id="answer-edit-form-${answerId}" method="post">
            <div class="row row-cols-2">
                <div id="answer-content-${answerId}" class="mb-1 col-10 me-auto p-0">
                    <textarea id="answer-submit-input-${answerId}" name="main" class="form-control shadow-sm border border-2 bg-light" rows="5" placeholder="Type your answer">
                        ${main}
                    </textarea>
                </div>

                <div class="col-1 p-0 m-0 collapse answer-control answer-collapse-${answerId}" id="answer-control-${answerId}">
                  <div class="btn-group float-end">
                    <button class="btn p-0" type="submit" data-bs-toggle="collapse" data-bs-target=".answer-collapse-${answerId}" aria-expanded="true" aria-controls="answer-content-${answerId} answer-control-${answerId}">
                      <i class="fas fa-check text-teal-300 mt-1 ms-2"></i>
                    </button>
                    <button class="btn p-0" type="button" data-bs-toggle="collapse" data-bs-target=".answer-collapse-${answerId}" aria-expanded="true" aria-controls="answer-content-${answerId} answer-control-${answerId}">
                      <i class="fas fa-close text-wine mt-1 ms-2"></i>
                    </button>
                  </div>
                </div>
            <div>
          </form>
        </div>
		</article>
		</div>

		<div class="card-footer text-muted text-end p-0">
			<blockquote class="blockquote mb-0">
				<p class="card-text px-1"><small class="text-muted">asked Aug 14 2020 at 15:31&nbsp;<a class="signature" href="#">user</a></small></p>
			</blockquote>
    	</div>
    `;

    // TODO: edit timestamp and user

    return newAnswer;
}

addQuestionEventListeners();
