function submitReport(id) {
  if (!isAuthenticated) return;

  let idString = id
  let contentId = idString.split("-").pop()
  let radioGroup = document.querySelectorAll('input[name="report-radio-' + contentId + '"]')

  let reportReason = ''
  Array.from(radioGroup).forEach(radio => {
    if (radio.checked) {
      reportReason = radio.parentNode.querySelector('label').innerText
    }
  });

  let reportDescription = document.querySelector('textarea[id="report-description-' + contentId + '"]').value
  sendAjaxRequest(
    'post',
    "/api/content/" + contentId + "/report", {
    reason: reportReason,
    description: reportDescription
  },
    reportContentHandler
  );
}

function reportContentHandler() {

  if (this.status == 200 || this.status == 201) {
    let response = JSON.parse(this.responseText)

    let reportButton = document.getElementById('report-button-' + response.content_id)
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>&nbsp;Reported'
    reportButton.classList.add('disabled')
    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
    let modal = document.getElementsByClassName('report-modal-' + response.content_id) //TODO: dismiss modal

  } else { }
}

