let stackedit = new Stackedit();
let td = new TurndownService();
let toggleButton = document.querySelector('#toggle-stackedit');
let toggleButtonTip = document.querySelector('#toggle-stackedit-tip');
let element = document.querySelector('#input-body');
element.value = td.turndown(element.value);

toggleStackEdit = (event) => {
  if (toggleButton.classList.contains('off')) {
    toggleButton.classList.remove('off');
    toggleButton.classList.add('on');
  }
  else {
    toggleButton.classList.remove('on');
    toggleButton.classList.add('off');
  }

  // Open the iframe
  stackedit.openFile({
    name: 'Filename', // with an optional filename
    content: {
      text: element.value // and the Markdown content.
    }
  });
}

toggleButton.addEventListener('click', toggleStackEdit);
toggleButtonTip.addEventListener('click', toggleStackEdit);


// Listen to StackEdit events and apply the changes to the textarea.
stackedit.on('fileChange', (file) => {
  element.value = file.content.text;
});

stackedit.on('close', () => {
  toggleButton.classList.remove('on');
  toggleButton.classList.add('off');
  toggleButtonTip.classList.remove('on');
  toggleButtonTip.classList.add('off');
});

