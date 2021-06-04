let toast = document.querySelector("#toast-tip-1")
let classes = toast.classList

function hideToast() {
  if (classes.contains('show')) {
    classes.remove('show')
    classes.add('hide')
  }
  else {
    classes.add('show')
    classes.remove('hide')
  }
}

let stepTime = 200;
let totalTime = 0;
toast.style.opacity = 1;
let intervalId = setInterval(function () {

  if (classes.contains('show') && totalTime > 10 * stepTime) {
    toast.style.opacity -= 0.04;
  }
  if (toast.style.opacity == 0) {
    classes.remove('show')
    classes.add('hide')
    clearInterval(intervalId)
  }

  totalTime += stepTime;
}, 200);