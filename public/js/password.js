const hide = document.querySelector('#hide');
const pass = document.querySelector('#inputPass');

hide.addEventListener('click', function () {
  if (pass.type == 'text') {
    pass.type = 'password';
    hide.innerHTML = '<i class="fas fa-eye"></i>';
  } else {
    pass.type = 'text';
    hide.innerHTML = '<i class="fas fa-eye-slash"></i>';
  }
});