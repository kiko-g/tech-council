let element = document.getElementById('answer-submit-input');
let toggleButton = document.getElementById('toggle-stackedit-answer');
let stackedit = new Stackedit();

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

// Listen to StackEdit events and apply the changes to the textarea.
stackedit.on('fileChange', (file) => {
    element.value = file.content.text;
});

stackedit.on('close', () => {
    toggleButton.classList.remove('on');
    toggleButton.classList.add('off');
});