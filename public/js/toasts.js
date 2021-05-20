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

toast.style.opacity = 1;
let intervalId = setInterval(function () {
  if (classes.contains('show')) {
    toast.style.opacity -= 0.01;
    //console.log(toast.style.opacity);
  }
  if (toast.style.opacity == 0) {
    classes.remove('show')
    classes.add('hide')
    clearInterval(intervalId)
  }
}, 200);