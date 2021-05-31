const moderatorTagEditButtons = document.getElementsByClassName("tag-editing");
for (const editButton of moderatorTagEditButtons) {
    editButton.addEventListener("click", editingTag);
}

function editingTag() {
    const tagId = this.dataset.tagId;
    const confirmButton = document.getElementById(`confirm-tag-${tagId}-edit`);
    const cancelButton = document.getElementById(`cancel-tag-${tagId}-edit`);
    
    const name = document.getElementById(`tag-${tagId}-name`);
    const originalName = name.value;
    const description = document.getElementById(`tag-${tagId}-description`);
    const originalDescription = description.value;

    confirmButton.addEventListener("click", confirmEditTag);
    cancelButton.addEventListener("click", () => {
        confirmButton.removeEventListener("click", confirmEditTag);
        name.value = originalName;
        description.value = originalDescription;
    });
}

function confirmEditTag() {
    const tagId = this.dataset.tagId;
    const name = document.getElementById(`tag-${tagId}-name`);
    const description = document.getElementById(`tag-${tagId}-description`);

    sendAjaxRequest(
        "put",
        "/api/tag/" + tagId + "/edit",
        { name: name.value, description: description.value },
        editTagHandler
    );
}

function editTagHandler() {
    let response = JSON.parse(this.responseText);
    const name = document.getElementById(`tag-${response.id}-name`);
    const description = document.getElementById(`tag-${response.id}-description`);
    const tagName = document.getElementById(`tag-${response.id}-redirect`);

    let confirmation = document.createElement("div");
    let tagTable = document.getElementById("tag-table");
    if (this.status == 200 || this.status == 201) {
        tagName.innerHTML = response.name;
        name.value = response.name;
        description.value = response.description;

        confirmation.innerHTML = successAlert("Tag edited successfully");
    } else {
        confirmation.innerHTML = successAlert("Error editing tag");
    }
    tagTable.parentNode.insertBefore(confirmation, tagTable);
}

const moderatorTagModalTriggers = document.getElementsByClassName(
    "delete-tag-moderator-modal-trigger"
);
if (moderatorTagModalTriggers.length > 0) {
    for (button of moderatorTagModalTriggers)
        button.addEventListener("click", handleDeleteTagModal);
}

function handleDeleteTagModal() {
    let deleteButton = document.getElementsByClassName("delete-tag-modal");

    if (deleteButton.length > 0) {
        for (button of deleteButton)
            button.addEventListener("click", deleteTag);
    }
}

function deleteTag() {
    let id = this.dataset.tagId;

    sendAjaxRequest(
        "delete",
        "/api/tag/" + id + "/delete",
        null,
        deleteTagHandler
    );
}

function deleteTagHandler() {
    console.log(this.responseText);
    let confirmation = document.createElement("div");
    let tagTable = document.getElementById("tag-table");
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText);
        confirmation.innerHTML = successAlert("Tag deleted successfully");
        tagTable.parentNode.insertBefore(confirmation, tagTable);
        document.getElementById(`tag-${response.id}-row`).remove();
    } else {
        confirmation.innerHTML = errorAlert("Error deleting tag");
    }
    tagTable.parentNode.insertBefore(confirmation, tagTable);
}