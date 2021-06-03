let reportButton = null
let reportActivated = false

const saveReportButton = button => {
  reportButton = button
}

const deleteReport = () => {
  reportActivated = false
  toggleReport(reportButton);
}

const toggleReport = () => {
  if (reportActivated) {
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>&nbspReported'
    reportButton.className += 'disabled'

    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
  }
};

const toggleReportSimple = () => {
  rb = reportButton
  if (reportButton.children[0].classList.contains('far')) {
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>'
    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
  } else {
    reportButton.innerHTML = '<i class="far fa-flag" aria-hidden="true"></i>'
    reportButton.classList.remove('active-report')
    reportButton.classList.add('report')
  }
};

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
  
  console.log(contentId)
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
    reportButton.className += 'disabled'
    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
    let modal = document.getElementsByClassName('report-modal-' + response.content_id) //TODO: dismiss modal

  } else { }
}

