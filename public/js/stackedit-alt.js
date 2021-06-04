let element = document.getElementById('answer-submit-input');
let toggleButton = document.getElementById('toggle-stackedit-answer');
console.log(element);
console.log(toggleButton);
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
    let md = new Remarkable();
    element.value = file.content.text;
    console.log(md.render(element.value));
});

stackedit.on('close', () => {
    toggleButton.classList.remove('on');
    toggleButton.classList.add('off');
});