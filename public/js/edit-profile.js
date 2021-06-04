const saveEditButton = document.querySelector('[id^=save-edit]');
const userID = saveEditButton.id.split("-").pop()
let inputImage = document.getElementById('inputImage')
let inputEmail = document.getElementById('inputEmail')
let inputUsername = document.getElementById('inputUsername')
let inputBio = document.getElementById('inputBio')

let image;
const reader = new FileReader();
inputImage.addEventListener("change", function (event) {
    image = inputImage.files[0]
    document.getElementById("photoError").innerText = ""
    const file = event.target.files[0]
    inputImage.setAttribute('data-hasnewfile', "yes")
    reader.readAsDataURL(file)
})

function submitEditProfile() {
    if (!isAuthenticated) return
    let argEmail = inputEmail.value
    let argUsername = inputUsername.value
    let argBio = inputBio.value
    if (inputImage.value === undefined) image = null;

    sendAjaxRequest(
        'put',
        "/user/" + userID + "/edit",
        {
            user_id: userID,
            image: image,
            email: argEmail,
            username: argUsername,
            bio: argBio,
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

