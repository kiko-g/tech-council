let reportButton = null
let reportActivated = false

const saveReportButton = button => {
  reportButton = button
  console.log(reportButton);
  console.log(reportActivated);
}

const submitReport = () => {
  reportActivated = true
  toggleReport(reportButton);
}

const deleteReport = () => {
  reportActivated = false
  toggleReport(reportButton);
}

const toggleReport = () => {
  if (reportActivated) {
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>&nbspReported'
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

function submitReport(event) {
  event.preventDefault();
  if (!isAuthenticated) return;

  let idString = this.id
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

    console.log(response.id)
    let reportButton = document.getElementById('report-button-' + response.id)
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>&nbsp;Reported'
    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
  } else { }
}

function addReportEventListener() {
  let reportForms = document.querySelectorAll("[id^='report-form']");
  let submitReportButtons = document.querySelectorAll("[id^='submit-report-button']");
  let buttons = document.getElementsByClassName("submit-report-button");
  Array.from(buttons).forEach(element => {
    element.addEventListener('click', submitReport)
  });
}

addReportEventListener();
