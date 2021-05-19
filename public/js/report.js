const toggleReport = reportButton => {
  if (reportButton.children[0].classList.contains('far')) {
    reportButton.innerHTML = '<i class="fa fa-flag" aria-hidden="true"></i>&nbsp;Reported'
    reportButton.classList.remove('report')
    reportButton.classList.add('active-report')
  }
  else {
    reportButton.innerHTML = '<i class="far fa-flag" aria-hidden="true"></i>&nbsp;Report'
    reportButton.classList.remove('active-report')
    reportButton.classList.add('report')
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