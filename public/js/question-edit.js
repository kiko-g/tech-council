let editQuestion = document.getElementsByClassName("edit-question-button")[0];
let tagsSelected = [];
let addedEventListeners = false;

if (editQuestion != null) {
    editQuestion.addEventListener("click", function () {
        let confirmEdit = document.getElementById("id=^[confirm-edit]");
        let cancelEdit = document.getElementById("id=^[cancel-edit]");

        if (!addedEventListeners) {
            addEventListeners(confirmEdit, cancelEdit);

            let tags = Array.from(document.getElementsByClassName("tag-edit"));
            tags.forEach((tag) => {
                tag.addEventListener("click", () => {
                    tag.style.display = "none";
                    tagsSelected.push(tag);
                });
            });

            addedEventListeners = true;
        }
    });
}

function addEventListeners(confirmEdit, cancelEdit) {
    confirmEdit.addEventListener("click", function () {
        for (const tag of tagsSelected) {
            sendAjaxRequest(
                "delete",
                "/api/question/remove_tag",
                {
                    tag_id: tag.dataset.tagId,
                    question_id: tag.dataset.questionId,
                },
                removeTagHandler
            );
        }
    });

    cancelEdit.addEventListener("click", function () {
        for (const tag of tagsSelected) tag.style.display = "inline";
        tagsSelected = [];
    });
}

function removeTagHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText);
        let tag = document.getElementById("question-tag-" + response.tag_id + "-q" + response.question_id);
        tag.remove();
        let editTag = document.getElementById(
            "question-tag-edit-" + response.tag_id + "-q" + response.question_id
        );
        editTag.remove();
    } else {
        //TODO: add error message
    }
}

let submitEditQuestion = document.getElementById("edit-question-submit");
if (submitEditQuestion != undefined) submitEditQuestion.addEventListener("click", editQuestionInPage);

function editQuestionInPage(event) {
    event.preventDefault();
    let title = document.getElementById("input-title").value;
    let main = document.getElementById("input-body").value;
    let tagElements = document.getElementById("ask-selected-tags").children;

    let tags = [];
    for (const tag of tagElements) tags.push(tag.dataset.tag);
    let id = document.getElementById("edit-question-header").dataset.id;

    console.log({
        id: id,
        title: title,
        main: main,
        tags: tags
    });

    sendAjaxRequest(
        "put",
        "/api/edit/question/" + id,
        {
            id: id,
            title: title,
            main: main,
            tags: tags
        },
        editQuestionHandler
    );
}

function editQuestionHandler() {
    if (this.status != 200 && this.status != 201) {
        let header = document.getElementById("edit-question-header");
        let confirmation = document.createElement("div");
        confirmation.innerHTML = errorAlert("Question could not be updated!");
        header.parentNode.parentNode.insertBefore(confirmation, header.parentNode);
    } else {
        document.documentElement.innerHTML = this.responseText;
    }
}