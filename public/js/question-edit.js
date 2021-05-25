let editQuestion = document.getElementsByClassName("edit-question-button")[0];
let tagsSelected = [];
let addedEventListeners = false;

if (editQuestion != null) {
    editQuestion.addEventListener("click", function () {
        let confirmEdit = document.getElementById("confirm-edit");
        let cancelEdit = document.getElementById("cancel-edit");

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
    let response = JSON.parse(this.responseText);
    if (this.status == 200 || this.status == 201) {
        let tag = document.getElementById("question-tag-" + response.tag_id);
        tag.remove();
        let editTag = document.getElementById(
            "question-tag-edit-" + response.tag_id
        );
        editTag.remove();
    } else {
        //TODO: add error message
    }
}
