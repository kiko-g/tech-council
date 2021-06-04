let banCardButtons = document.getElementsByClassName("ban-card");
for (const banCardButton of banCardButtons) {
    banCardButton.addEventListener("click", banUser);
}

function banUser() {
    sendAjaxRequest(
        "post",
        "/api/ban",
        {
            user_id: this.dataset.userId
        },
        banUserHandler
    );
}

function banUserHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText);

        let banCardButton = document.getElementById(`ban-card-${response.id}`);
        let newButton = document.createElement("button");
        newButton.classList.add("btn");
        newButton.classList.add("blue-alt");
        newButton.disabled = true;
        newButton.innerHTML = "Banned";

        banCardButton.parentNode.replaceChild(newButton, banCardButton);
    } else {
        //TODO: add error message
    }
}

let solveReportButtons = document.getElementsByClassName("solve-report");
for (const solveReportButton of solveReportButtons) {
    solveReportButton.addEventListener("click", solveReport);
}

function solveReport() {
    sendAjaxRequest(
        "put",
        "/api/solve_report/" + this.dataset.reportId,
        null,
        solveReportHandler
    );
}

function solveReportHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText);

        let banCardButton = document.getElementById(`report-${response.id}`);
        banCardButton.remove();
    } else {
        //TODO: add error message
    }
}
