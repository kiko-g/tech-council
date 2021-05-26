const toggleReport = reportButton => {
  if (reportButton.children[0].classList.contains('far')) {
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>&nbsp;Reported'
    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
  }
};

const toggleReportSimple = reportButton => {
  if (reportButton.children[0].classList.contains('far')) {
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>'
    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
  }
  else {
    reportButton.innerHTML = '<i class="far fa-flag" aria-hidden="true"></i>'
    reportButton.classList.remove('active-report')
    reportButton.classList.add('report')
  }
};

function addReportEventListener(htmlNode = document) {
    let buttons = htmlNode.getElementsByClassName("submit-report-button");
    Array.from(buttons).forEach(element => {
      element.addEventListener('click', submitReport)
    });
}

function submitReport (event) {
  event.preventDefault();
  if (!isAuthenticated) return;

  let idString = this.id
  let contentId = idString.split("-").pop()

  let radioGroupIdString = 'input[name="report-radio-'+ contentId +'"]'
  let radioGroup = document.querySelectorAll(radioGroupIdString)

  let reportReason = ''
  Array.from(radioGroup).forEach(radio => {
    if (radio.checked) {
      reportReason = radio.parentNode.querySelector('label').innerText
    }
  });

  let descriptionIdString = 'textarea[id="report-description-' + contentId + '"]'
  let reportDescription = this.getRootNode().querySelector(descriptionIdString).value

  sendAjaxRequest(
          'post',
          "/api/content/" + contentId + "/report",
          {
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

    } else {
    }
}

addReportEventListener()

// 

function addReportEventListener() {
    let reportForms = document.querySelectorAll("[id^='report-form']");
    let submitReportButtons = document.querySelectorAll("[id^='submit-report-button']");
}

addReportEventListener();