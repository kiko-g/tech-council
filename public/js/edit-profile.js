const saveEditButton = document.querySelector('[id^=save-edit]');
const userID = saveEditButton.id.split("-").pop()

function submitEditProfile() {
    if (!isAuthenticated) return

    let inputImage = document.getElementById('inputImage')
    let inputEmail = document.getElementById('inputEmail')
    let inputUsername = document.getElementById('inputUsername')
    let inputBio = document.getElementById('inputBio')

    // inputImage.value === undefined ? null : 
    // inputEmail.value === originalEmail.value ? null :
    // inputUsername.value === originalUsername.value ? null : 
    // inputBio.value === originalBio.value ? null : 

    let imageArg = inputImage.value
    let emailArg = inputEmail.value
    let usernameArg = inputUsername.value
    let bioArg = inputBio.value
    if (imageArg === undefined) imageArg = null;

    sendAjaxRequest(
        'put',
        "/user/" + userID + "/edit",
        {
            user_id: userID,
            image: imageArg,
            email: emailArg,
            username: usernameArg,
            bio: bioArg,
        },
        editProfileHandler
    )
}

function editProfileHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText)
        console.log(response);
        let displayName = document.getElementById('user-name')
        let displayEmail = document.getElementById('user-email')
        let displayBio = document.getElementById('user-biography')

        displayName.innerHTML = response.username;
        displayEmail.innerHTML = `<strong> Email</strong>:&nbsp;` + response.email;
        displayBio.innerHTML = response.bio;
    }
    else { }
}

