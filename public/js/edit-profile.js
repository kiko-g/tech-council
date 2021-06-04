function getBase64Image(img) {
    var canvas = document.createElement("canvas");
    canvas.width = img.width;
    canvas.height = img.height;
    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
    var dataURL = canvas.toDataURL("image/png");
    return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
}
// ---------------------------------------------------------------------------
const saveEditButton = document.querySelector('[id^=save-edit]');   // submit
const userID = saveEditButton.id.split("-").pop()                   // user id
const userImage = document.getElementById()
let inputImage = document.getElementById('inputImage')
let inputEmail = document.getElementById('inputEmail')
let inputUsername = document.getElementById('inputUsername')
let inputBio = document.getElementById('inputBio')

// let newImage = null;
// inputImage.addEventListener("change", function (event) {
//     newImage = inputImage.files[0];
// })

function submitEditProfile() {
    if (!isAuthenticated) return
    let argEmail = inputEmail.value
    let argUsername = inputUsername.value
    let argBio = inputBio.value

    sendAjaxRequest(
        'put',
        "/user/" + userID + "/edit",
        {
            user_id: userID,
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

        // displayImgData = getBase64Image(newImage);
        // localStorage.setItem("storage/app/public/assets/photos/user" + userID + ".png", displayImgData);
    }
    else { }
}

