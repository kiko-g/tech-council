const toggleSave = (saveButton) => {
  const question_id = saveButton.id.split("-").pop()
  if (saveButton.children[0].classList.contains("far")) {
    sendAjaxRequest(
      "post",
      "/api/save/question",
      { id: question_id, },
      saveHandler
    )
  }
  else {
    sendAjaxRequest(
      "delete",
      "/api/unsave/question",
      { id: question_id },
      unsaveHandler
    )
  }
}

function saveHandler() {
  if (this.status == 200 || this.status == 201) {
    let response = JSON.parse(this.responseText)

    // change save button
    let saveButton = document.getElementById("save-" + response.content_id)
    console.log(saveButton);
    saveButton.innerHTML = `<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;Saved`
    saveButton.classList.remove("bookmark")
    saveButton.classList.add("active-bookmark")
  }
  else { }
}

function unsaveHandler() {
  if (this.status == 200 || this.status == 201) {
    let response = JSON.parse(this.responseText)

    // change save button
    let saveButton = document.getElementById("save-" + response.content_id)
    saveButton.innerHTML = `<i class="far fa-bookmark" aria-hidden="true"></i>&nbsp;Save`
    saveButton.classList.remove("active-bookmark")
    saveButton.classList.add("bookmark")
    console.log(saveButton);
  }
  else { }
}