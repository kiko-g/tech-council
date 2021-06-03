function submitBestAnswer(id) {
    if (!isAuthenticated) return;

    let contentId = id.split("-").pop()
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

        let answerID = response.content_id;
        let removeButton = document.getElementById('best-button-' + answerID)
        let answerBodyDiv = document.getElementById('answer-body-' + answerID)
        let bestAnswerBadge = document.getElementById('best-badge-' + answerID)

        if (bestAnswerBadge.classList.contains('hidden')) bestAnswerBadge.classList.remove('hidden');
        answerBodyDiv.className = "card-body bg-great"
        removeButton.remove()
    } else { }
}
