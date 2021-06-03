function submitBestAnswer(id) {
    if (!isAuthenticated) return;

    let contentId = id.split("-").pop()
    console.log(contentId)

    sendAjaxRequest(
        'post',
        "/api/answer/" + contentId + "/best",
        null,
        bestAnswerHandler
    );
}

function bestAnswerHandler() {

    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText)

        console.log(bestButton);
        let bestButton = document.getElementById('best-button-' + response.id)
        bestButton.remove()
    } else { }
}
