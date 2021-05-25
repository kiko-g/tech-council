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
  else {
    reportButton.innerHTML = '<i class="far fa-flag" aria-hidden="true"></i>&nbspReport'
    reportButton.classList.remove('active-report')
    reportButton.classList.add('report')
  }
}

const toggleReportSimple = () => {
  rb = reportButton
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
}