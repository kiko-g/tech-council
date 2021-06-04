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

    console.log("hypocrticial people");
    console.log("/user/" + userID + "/edit");
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
    console.log("be like");
    console.log("yeah this one fucking sucks");
}

function editProfileHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText)
        console.log(response)
    }
    else { }
}

