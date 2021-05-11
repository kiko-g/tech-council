const successAlert = (message) => {
    return `
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <p> ${message} </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    `;
}

const errorAlert = (message) => {
    return `
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <p> ${message} </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    `;
}