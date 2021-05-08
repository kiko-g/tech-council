const toggleFollow = (followButton) => {
    console.log(followButton.id);
    const tag_id = followButton.id.split("-").pop();
    if (followButton.children[0].classList.contains("far")) {
        sendAjaxRequest(
            "post",
            "/api/follow/tag",
            { id: tag_id },
            followHandler
        );
    } else {
        sendAjaxRequest(
            "post",
            "/api/unfollow/tag",
            { id: tag_id },
            unfollowHandler
        );
    }
};

function followHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText);

        // change follow button
        let followButton = document.getElementById("follow-" + response.id);
        followButton.innerHTML =
            '<i class="fa fa-star" aria-hidden="true"></i>&nbsp;Following';
        followButton.classList.remove("follow");
        followButton.classList.add("active-follow");

        // add to following section
        let followedTags = document.getElementById("followed-tags");

        let newTag = document.createElement("div");
        newTag.classList.add("btn-group");
        newTag.id = "followed-tag-" + response.id;
        newTag.innerHTML = `
        <a class="btn blue-alt border-0 my-btn-pad2">${response.name}</a>`;

        followedTags.appendChild(newTag);
    } else {
    }
}

function unfollowHandler() {
    if (this.status == 200 || this.status == 201) {
        let response = JSON.parse(this.responseText);

        // change follow button
        let followButton = document.getElementById("follow-" + response.id);
        followButton.innerHTML =
            '<i class="far fa-star" aria-hidden="true"></i>&nbsp;Follow';
        followButton.classList.remove("active-follow");
        followButton.classList.add("follow");

        // remove from following section
        let unfollowedTag = document.getElementById("followed-tag-" + response.id);
        unfollowedTag.remove();
    } else {
    }
}
