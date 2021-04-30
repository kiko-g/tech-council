function getFormInput(form, name) {
    return form.querySelector(`[name="${name}"]`);
}

function getFormValues(form) {
    let values = {};

    // Inputs
    let inputs = form.querySelectorAll('input, textarea');
    inputs.forEach((input) => {
        switch (input.getAttribute('type')) {
            case 'submit': return;
            case 'checkbox': values[input.getAttribute('name')] = (input.checked ? 1 : 0); return;
            case 'radio': if (input.checked) values[input.getAttribute('name')] = input.value; return;
            // file -> if needed
            default: values[input.getAttribute('name')] = input.value; return; 
        }
    });
    
    // Selects
    let selects = form.querySelectorAll('select');
    selects.forEach((select) => {
        values[select.getAttribute('name')] = select.options[select.selectedIndex].value;
    });
    
    return values;
}

function clearInputFeedback(element) {
    // Remove error/success highlighting
    element.classList.remove('valid-input');
    element.classList.remove('invalid-input');

    // Remove error/success feedback
    element.parentNode.querySelectorAll('.valid-input-feedback').forEach((el) => el.remove());
    element.parentNode.querySelectorAll('.invalid-input-feedback').forEach((el) => el.remove());
}

function clearFormFeedback(form) {
    form.querySelectorAll('.valid-input, .invalid-input').forEach(clearInputFeedback);
}

function setInputSuccess(element, message) {
    clearInputFeedback(element);
    element.classList.add('valid-input');

    let feedbackDiv = document.createElement('div');
    feedbackDiv.classList.add('valid-input-feedback');
    feedbackDiv.innerHTML = message;
    element.parentNode.insertBefore(feedbackDiv, element);
}

function setInputError(element, message) {
    clearInputFeedback(element);
    element.classList.add('invalid-input');

    let feedbackDiv = document.createElement('div');
    feedbackDiv.classList.add('invalid-input-feedback');
    feedbackDiv.innerHTML = message;
    element.parentNode.insertBefore(feedbackDiv, element);
}