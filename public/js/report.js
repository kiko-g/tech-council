let reportButton = null
let reportActivated = false

const toggleReport = () => {
  if (reportActivated) {
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>&nbspReported'
    reportButton.className += 'disabled'

    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
  }
};

function validate(reportDescription, reportReason) {
  return reportReason != '' && validateTextArea(reportDescription)
}
function validateTextArea(reportDescription) {
  return reportDescription.length >= 10 && reportDescription.length <= 1000
}

function submitContentReport(id) {
  if (!isAuthenticated) return;

  let idString = id
  let contentId = idString.split("-").pop()

  let reportDescriptionTextArea = document.querySelector('textarea[id="report-description-' + contentId + '"]')
  let reportDescription = reportDescriptionTextArea.value
  
  let radioGroup = document.querySelectorAll('input[name="report-radio-' + contentId + '"]')
  let reportReason = ''
  Array.from(radioGroup).forEach(radio => {
      if (radio.checked) {
          reportReason = radio.parentNode.querySelector('label').innerText
      }
  });

  if (!validate(reportDescription, reportReason)) {
    let radioValidation = document.getElementById('report-' + contentId + '-radio-invalid-feeback')
    triggerWarnings(reportDescriptionTextArea, radioValidation)
    handleFormChanges(radioGroup, reportDescriptionTextArea, radioValidation)
    return
  } 
  
  sendAjaxRequest(
    'post',
    "/api/content/" + contentId + "/report", {
    reason: reportReason,
    description: reportDescription
  },
    reportContentHandler
  );
}

function submitUserReport(id) {
    if (!isAuthenticated) return;

    let idString = id
    let userId = idString.split("-").pop()

    let reportDescriptionTextArea = document.querySelector('textarea[id="user-report-description-' + userId + '"]')
    let reportDescription = reportDescriptionTextArea.value

    let radioGroup = document.querySelectorAll('input[name="user-report-radio-' + userId + '"]')
    let reportReason = ''
    Array.from(radioGroup).forEach(radio => {
        if (radio.checked) {
            reportReason = radio.parentNode.querySelector('label').innerText
        }
    });

    if (!validate(reportDescription, reportReason)) {
        let radioValidation = document.getElementById('user-report-' + userId + '-radio-invalid-feeback')
        triggerWarnings(reportDescriptionTextArea, radioValidation)
        handleFormChanges(radioGroup, reportDescriptionTextArea, radioValidation)
        return
    }

    sendAjaxRequest(
        'post',
        "/api/user/" + userId + "/report", {
            reason: reportReason,
            description: reportDescription
        },
        reportUserHandler
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
    let closeBtn = document.getElementById('btn-close-content-' + response.content_id) //TODO: dismiss modal
    closeBtn.click()


  } else { }
}

function reportUserHandler() {
    if (this.status == 200 || this.status == 201) {

      let response = JSON.parse(this.responseText)
        
        let reportButton = document.getElementById('user-report-button-' + response.user_id)
        reportButton.innerHTML = 'Reported'
        reportButton.className += 'disabled'
        reportButton.classList.remove('report')
        reportButton.classList.add('active-report')
        
        let closeBtn = document.getElementById('user-report-modal-' + response.content_id) //TODO: dismiss modal
        closeBtn.click()

    } else {}
}

function handleFormChanges(radioGroup, textArea, radioValidation) {
  Array.from(radioGroup).forEach(radio => {
    radio.addEventListener('change', function () {
      if (radio.checked) {
        radioValidation.classList.add('d-none')
      }
    });
  });

  textArea.addEventListener('input', function () {
    if (!validateTextArea(textArea.value)) {
      if (textArea.classList.contains('is-valid')){
        textArea.classList.remove('is-valid')
        textArea.classList.add('is-invalid')
      }
    }
    else {
      if (textArea.classList.contains('is-invalid')){
        textArea.classList.remove('is-invalid')
        textArea.classList.add('is-valid')  
      }
    }
  });
}

function triggerWarnings(textArea, radioValidation) {
    radioValidation.classList.remove('d-none')
    textArea.classList.add('is-invalid')
}