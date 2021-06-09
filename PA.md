# PA: Product and Presentation

-   [A9: Product](#A9-Product)
    -   [1. Installation](#1-Installation)
    -   [2. Usage](#2-Usage)
    -   [2.1. Administration Credentials](#21-Administration-Credentials)
        -   [Administration](#Administration)
        -   [Moderation](#Moderation)
    -   [2.2. User Credentials](#22-User-Credentials)
    -   [3. Application Help](#3-Application-Help)
    -   [4. Input Validation](#4-Input-Validation)
        -   [Example of server side validation (create question)](#Example-of-server-side-validation-create-question)
        -   [Example of client side validation (report post)](#Example-of-client-side-validation-report-post)
    -   [5. Check Accessibility and Usability](#5-Check-Accessibility-and-Usability)
    -   [6. HTML & CSS Validation](#6-HTML-amp-CSS-Validation)
    -   [7. Revisions to the Project](7-Revisions-to-the-Project)
    -   [8. Implementation Details](#8-Implementation-Details)
        -   [8.1. Libraries Used](#81-Libraries-Used)
        -   [8.2 User Stories](#82-User-Stories)
-   [A10: Presentation](#A10-Presentation)
    -   [1. Product presentation](#1-Product-presentation)
    -   [2. Video presentation](#2-Video-presentation)
-   [Revision history](#Revision-history)
-   [Annex A: Group Self Evaluation](#Annex-A-Group-Self-Evaluation)

TechCouncil aims to be a platform where users can post questions and share answers for everything tech-related, whether it’s how to build a custom PC, what new smartphone is the best, or how to install a VPN.

## A9: Product

Our team's product is a platform where users can interact with each other, discussing any technology-related issues and thus creating an environment with centralized information. Whether you're having trouble with an application or can't seem to install a VPN on your computer, Tech Council is the place to look for answers.

The core elements of the platform are the questions (asked by users), which are associated with a group of tags, to help divide the information into relevant categories. Users can interact with the questions through answers, comments, and votes. They can also follow tags, save questions, search for contents (questions, tags, and users) and report what they don't find appropriate. To handle these situations, we have implemented an area to be used by moderators, who can manage all the content on the platform.

This artifact results from the implementation of the platform designed previously, using PHP and Laravel for generating dynamic web pages, and PostgreSQL as a database management system.

### 1. Installation

Final source code: https://git.fe.up.pt/lbaw/lbaw2021/lbaw2132/-/tree/PA
Full Docker command to test the group's Docker Hub image using the DBM database:

```bash
docker run -d -it -p 8080:80 -v $PWD/:/var/www/html lbaw2132/lbaw2132:latest -e DB_USERNAME="lbaw2132" -e DB_PASSWORD="pg!lol!2021"
docker-compose up
```

### 2. Usage

URL to the product: http://lbaw2132.lbaw-prod.fe.up.pt

### 2.1. Administration Credentials

#### Administration

As described in the user stories and the actors diagram, the Admin is not a User. This way, the Admin does not authenticate and interact with the platform. Instead, three commands can be executed with **php artisan**:

-   `php artisan promote {username}` - Promotes User with given username to Moderator
-   `php artisan demote {username}` - Demotes User with given username from Moderator back to User
-   `php artisan mods` - Lists all the current Moderators

All these commands will ask for a code once executed, which is: **admin-cli**.

#### Moderation

| User email                 | Password | Name          | ID  |
| -------------------------- | -------- | ------------- | --- |
| francisco.friande@fe.up.pt | abcd1234 | fiambre       | 1   |
| francisco.jpg@fe.up.pt     | abcd1234 | kikogoncalves | 2   |
| joao.romao@fe.up.pt        | abcd1234 | jdiogueiro    | 3   |
| miguel.pinto@fe.up.pt      | abcd1234 | mpintarolas   | 4   |

Moderator Area URL: http://lbaw2132.lbaw-prod.fe.up.pt/moderator

### 2.2. User Credentials

| Type         | User email                  | Password     | Name           | ID  |
| ------------ | --------------------------- | ------------ | -------------- | --- |
| Regular User | cmoore4@amazonaws.com       | AxUsJgBEn2Ho | jlo4ever       | 5   |
| Regular User | lwooder5@msu.edu            | 5s4jTtiaIF   | eliseu_goat    | 6   |
| Banned User  | byates9@blogs.com           | uwIj0udAAh1T | LilYatty       | 10  |
| Expert User  | hbetancourtc@altervista.org | 4KdRnq0P     | hakeeeeeeem123 | 13  |

### 3. Application Help

On every page, users can easily access the about page using the aside menu, where they can learn more about the concept of the platform and meet the development team. We also introduced basic rules for posting on our website, as well as small tips around the website. Below you can find some examples of help in the application.

| Main page aside                                 | Create question page tip                             | Create question aside                       |
| :---------------------------------------------- | :--------------------------------------------------- | :------------------------------------------ |
| ![](https://i.imgur.com/E9Cawvn.png)            | ![](https://i.imgur.com/7QcZdwM.png)                 | ![](https://i.imgur.com/NYBplid.png)        |
| Provides the main and basic help in the website | Provides recommendation for posting more comfortably | Presents basic posting guidelines to follow |

| FAQ page                             | Contact us / About page                               |
| :----------------------------------- | :---------------------------------------------------- |
| ![](https://i.imgur.com/VsobBx3.png) | ![](https://i.imgur.com/alw0kDD.png)                  |
| Page with Frequently Asked Questions | Page with information about the platform and the team |

### 4. Input Validation

We implemented input validation for the client-side using javascript and server-side by using Laravel validation facilities.

#### Example of server-side validation (create question)

Inside the `QuestionController`, the `create` function performs server-side validation to validate input while creating a question.

```php
$request->validate([
    'title' => ['required', 'max:' . Question::MAX_TITLE_LENGTH],
    'title' => ['required', 'min:' . Question::MIN_TITLE_LENGTH],
    'main' => ['required', 'max:' . Question::MAX_MAIN_LENGTH],
    'main' => ['required', 'min:' . Question::MIN_MAIN_LENGTH],
    'tags' => ['required', function ($attribute, $value, $fail) {
        $tags = explode(',', $value);
        if(count($tags) !== count(array_flip($tags))) {
            $fail('The '.$attribute.' must have unique tags.');
        }
        if(count($tags) < 1 || count($tags) > 5) {
            $fail('The '.$attribute.' must have between 1 and 5 tags.');
        }
    }]
]);
```

This validation will be seen handled later to inform the user about the issues with their input.

```jsx
function createQuestionHandler() {
    if (this.status != 200 && this.status != 201) {
        let responseErrors = JSON.parse(this.response).errors;
        let alertArea = document.getElementById("ask-errors");
        let alert = document.createElement("div");
        let alertErrors = document.createElement("ul");
        alert.classList.add("alert", "alert-danger");

        for (let e in responseErrors) {
            let err = document.createElement("li");
            err.innerHTML = responseErrors[e][0];
            alertErrors.appendChild(err);
        }

        alert.innerHTML = "";
        alertArea.innerHTML = "";

        alert.appendChild(alertErrors);
        alertArea.appendChild(alert);
    } else {
        let response = JSON.parse(this.response);
        window.location = `/question/${response.id}`;
    }
}
```

Below you can see the effects of the code above upon submitting the 3 fields with the invalid inputs on the image.

![](https://i.imgur.com/rrVYp5u.png)

#### Example of client side validation (report post)

Inside the `report.js` file, we created client-side helpful validation functions, for reporting a content or user.

```jsx
function validate(reportDescription, reportReason) {
    return reportReason != "" && validateTextArea(reportDescription);
}

function validateTextArea(reportDescription) {
    return reportDescription.length >= 10 && reportDescription.length <= 1000;
}
```

```jsx
if (!validate(reportDescription, reportReason)) {
    let radioValidation = document.getElementById(
        "user-report-" + userId + "-radio-invalid-feeback"
    );
    triggerWarnings(reportDescriptionTextArea, radioValidation);
    handleFormChanges(radioGroup, reportDescriptionTextArea, radioValidation);
    return;
}

function handleFormChanges(radioGroup, textArea, radioValidation) {
    Array.from(radioGroup).forEach((radio) => {
        radio.addEventListener("change", function () {
            if (radio.checked) radioValidation.classList.add("d-none");
        });
    });

    textArea.addEventListener("input", function () {
        if (!validateTextArea(textArea.value)) {
            if (textArea.classList.contains("is-valid")) {
                textArea.classList.remove("is-valid");
                textArea.classList.add("is-invalid");
            }
        } else {
            if (textArea.classList.contains("is-invalid")) {
                textArea.classList.remove("is-invalid");
                textArea.classList.add("is-valid");
            }
        }
    });
}

function triggerWarnings(textArea, radioValidation) {
    radioValidation.classList.remove("d-none");
    textArea.classList.add("is-invalid");
}
```

The JS code above will affect elements like the ones below, which provide feedback upon incorrect input.

```html
<!-- user did not choose a reason on report -->
<p
    class="d-none text-red-400 mt-2"
    id="report-{{ $content_id }}-radio-invalid-feeback"
>
    Please select a reason for reporting this content
</p>
```

```html
<!-- user did not input the correct amount of characters in description -->
<div class="invalid-feedback">
    Please enter a description with &gt; 10 and &lt; 1000 characters.
</div>
```

![](https://i.imgur.com/ed68Nvw.png)

### 5. Check Accessibility and Usability

Tested accessibility [here](https://ux.sapo.pt/checklists/acessibilidade) and usability [here](https://ux.sapo.pt/checklists/usabilidade). \
The detailed results for accessibility and usability have been submitted as PDF files on Moodle.\
The detailed results can also be found in the [docs](https://git.fe.up.pt/lbaw/lbaw2021/lbaw2132/-/tree/master/docs) folder in the repository.

| Test          | Result |
| ------------- | ------ |
| Accessibility | 16/18  |
| Usability     | 24/28  |

### 6. HTML & CSS Validation

Validated HTML [here](https://validator.w3.org/nu/) and CSS [here](https://jigsaw.w3.org/css-validator/). \
The detailed results for the HTML and CSS validation have been submitted as a zip of PDF files on Moodle. \
They can also be found in the [docs/validation](https://git.fe.up.pt/lbaw/lbaw2021/lbaw2132/-/tree/master/docs/validation) folder in the repository.

| Type | Item                  | Errors | Warnings |
| ---- | --------------------- | :----: | :------: |
| HTML | About page            |   0    |    0     |
| HTML | Ask page              |   0    |    1     |
| HTML | FAQ page              |   0    |    0     |
| HTML | Main page             |   0    |    2     |
| HTML | Moderator page        |   0    |    2     |
| HTML | Profile page          |   0    |    2     |
| HTML | Profile Settings page |   0    |    0     |
| HTML | Question page         |   0    |    1     |
| HTML | Search page           |   0    |    1     |
| HTML | Tag page              |   0    |    2     |
| CSS  | about.css             |   0    |    0     |
| CSS  | aside.css             |   0    |    0     |
| CSS  | ask.css               |   0    |    0     |
| CSS  | bootstrap.css         |   0    |    1     |
| CSS  | colors.css            |   0    |    2     |
| CSS  | fonts.css             |   0    |    0     |
| CSS  | footer.css            |   0    |    0     |
| CSS  | generic.css           |   0    |    0     |
| CSS  | login.css             |   0    |    0     |
| CSS  | moderator.css         |   0    |    0     |
| CSS  | profile.css           |   0    |    0     |
| CSS  | question.css          |   0    |    0     |
| CSS  | question-page.css     |   0    |    0     |
| CSS  | search.css            |   0    |    2     |
| CSS  | style.css             |   0    |    0     |

-   All the warnings in our HTML are related to semantic tags like `<article>` and `<section>` requiring header elements, something we could not quite address.
-   Most CSS warning are related to the use of variables defined (colors and fonts) in the `:root` element using the syntax `--<varname>: <content>;`
-   The amount of warnings we indicated in the tables above is for the type of warning and not the exact counter of warnings. For example, the colors CSS file has 126 warnings, but 125 are the interpreter not detecting variables defined in the `:root` element (`--varname` is an unknown vendor extension).

### 7. Revisions to the Project

With the advancement of the project, we made some changes to the requirements of the project.

| Action                                | Description                                                                                                                                                                                                                                                                                                   |
| :------------------------------------ | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Added US205: Password recovery        | Users should be able to reset their (priority: **High**) password                                                                                                                                                                                                                                             |
| Changed US302: Remove Account         | Changed the priority of this user story from **High** to **Medium**                                                                                                                                                                                                                                           |
| Changed US703: Tag creation           | Changed the priority of this user story from **Medium** to **Low**                                                                                                                                                                                                                                            |
| Changed US704: More significant votes | Changed the priority of this user story from **Medium** to **Low**                                                                                                                                                                                                                                            |
| Removed US705: Flag posts             | Initially, we wanted to give expert users the ability to report content but have it be more meaningful than reports coming from regular users, but concluded that this is not a high priority feature, since when a report is issued, the moderator who will solve it has the ability to see the expert badge |

After receiving feedback on the EAP artifact, we also updated some of the endpoints described in A7.

### 8. Implementation Details

#### 8.1. Libraries Used

| Name         | URL                                              | Use case                                                                                                                           | Example                                                                 |
| ------------ | ------------------------------------------------ | ---------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------- |
| Stackedit    | https://stackedit.io/                            | Fancy and powerful editor that allows users to post questions with more resources and freedom, due to the versatility of StackEdit | [Ask question page](http://lbaw2132.lbaw-prod.fe.up.pt/create/question) |
| Remarkable   | https://jonschlinkert.github.io/remarkable/demo/ | Used to convert Markdown to HTML. The user inputs markdown on the StackEdit editor and that will be converted into HTML on the DB  | Submit question (hidden)                                                |
| Turndown     | https://github.com/domchristie/turndown          | Used to convert HTML to Markdown. The posts data is stored in HTML and, upon editing, this data is converted back to Markdown      | Edit question (hidden)                                                  |
| Font Awesome | https://fontawesome.com/                         | Displaying meaningful and neat icons throughout the website.                                                                       | [Main page](http://lbaw2132.lbaw-prod.fe.up.pt)                         |

#### 8.2 User Stories

| US Identifier | Name                          | Module | Priority | Team Members                                    | State |
| ------------- | ----------------------------- | ------ | -------- | ----------------------------------------------- | ----- |
| US101         | Read Questions                | M03    | High     | **Francisco Friande** <br/> Francisco Gonçalves | 100%  |
| US102         | Read Answers                  | M03    | High     | **Francisco Gonçalves** <br/> João Romão        | 100%  |
| US103         | Home Page                     | M03    | High     | **Miguel Pinto** <br/> Francisco Friande        | 100%  |
| US108         | About Page                    | M06    | High     | **João Romão**                                  | 100%  |
| US109         | Question Page                 | M03    | High     | **Francisco Gonçalves** <br/> João Romão        | 100%  |
| US111         | View Question Date            | M03    | High     | **João Romão**                                  | 100%  |
| US112         | View Answer Date              | M03    | High     | **Francisco Gonçalves**                         | 100%  |
| US116         | FAQ Page                      | M06    | Low      | **Francisco Friande**                           | 100%  |
| US115         | Contact Page                  | M06    | Low      | **Francisco Gonçalves**                         | 100%  |
| US201         | Sign up                       | M01    | High     | **João Romão**                                  | 100%  |
| US202         | Login                         | M01    | High     | **Miguel Pinto**                                | 100%  |
| US203         | Sign up with OAuth API        | M01    | Low      | -                                               | 0%    |
| US204         | Login with OAuth API          | M01    | Low      | -                                               | 0%    |
| US301         | Log Out                       | M01    | High     | **Miguel Pinto**                                | 100%  |
| US305         | View User Profile             | M02    | High     | **Francisco Gonçalves**                         | 100%  |
| US402         | Answer Questions              | M04    | High     | **João Romão**                                  | 100%  |
| US403         | Upvote Question               | M04    | High     | **Miguel Pinto**                                | 100%  |
| US404         | Downvote Question             | M04    | High     | **Miguel Pinto**                                | 100%  |
| US405         | Upvote Answer                 | M04    | High     | **Miguel Pinto** <br/> João Romão               | 100%  |
| US406         | Downvote Answer               | M04    | High     | **Miguel Pinto** <br/> João Romão               | 100%  |
| US502         | Delete Question               | M04    | Medium   | **Francisco Gonçalves** <br/> João Romão        | 100%  |
| US601         | Delete Answer                 | M04    | Medium   | **Francisco Friande**                           | 100%  |
| US602         | Edit Answer                   | M04    | Medium   | **Francisco Friande**                           | 100%  |
| US110         | Tag Page                      | M04    | High     | **João Romão**                                  | 100%  |
| US304         | Follow Tags                   | M04    | High     | **João Romão**                                  | 100%  |
| US401         | Ask a Question                | M04    | High     | **Miguel Pinto** <br/> Francisco Gonçalves      | 100%  |
| US306         | Edit Profile                  | M02    | High     | **Francisco Gonçalves**                         | 100%  |
| US302         | Remove account                | M02    | Medium   | -                                               | 0%    |
| US801         | Moderator Area                | M05    | High     | **João Romão** <br/> Francisco Gonçalves        | 100%  |
| US802         | Remove tags from question     | M05    | High     | **João Romão** <br/> Francisco Friande          | 100%  |
| US407         | Report                        | M04    | High     | **Francisco Friande**                           | 100%  |
| US901         | Moderator election            | -      | High     | **João Romão**                                  | 100%  |
| US803         | Manage Tags                   | M05    | High     | **João Romão** <br/> Francisco Gonçalves        | 100%  |
| US303         | Save Questions                | M02    | High     | **Francisco Gonçalves**                         | 100%  |
| US701         | Expertise Shown               | M02    | High     | **Francisco Gonçalves**                         | 100%  |
| US503         | Edit Question                 | M04    | Medium   | **João Romão**                                  | 100%  |
| US702         | Priority in Answers           | M03    | High     | **Francisco Gonçalves**                         | 100%  |
| US703         | Tag creation                  | M04    | Low      | -                                               | 0%    |
| US704         | More significant votes        | M04    | Low      | -                                               | 0%    |
| US104         | Search for Questions          | M03    | High     | **Miguel Pinto**                                | 100%  |
| US105         | Search for Tags               | M03    | High     | **Miguel Pinto**                                | 100%  |
| US804         | Delete posts                  | M05    | High     | **João Romão**                                  | 100%  |
| US114         | View if post was edited       | M03    | Medium   | **Francisco Gonçalves**                         | 90%   |
| US106         | Search for Users              | M03    | High     | **Miguel Pinto**                                | 100%  |
| US113         | Sort Answers                  | M03    | Medium   | **Francisco Gonçalves**                         | 100%  |
| US107         | Filter                        | M03    | High     | **Miguel Pinto** <br/> Francisco Friande        | 100%  |
| US205         | Password Recovery             | M01    | High     | **Francisco Friande**<br/> Miguel Pinto         | 100%  |
| US307         | View Questions History        | M03    | Medium   | **Miguel Pinto**                                | 100%  |
| US308         | View Answers History          | M03    | Medium   | **Miguel Pinto**                                | 100%  |
| US117         | Share content to social media | M03    | Low      | -                                               | 0%    |
| US118         | View Edit History             | M03    | Low      | -                                               | 0%    |
| US501         | Best answer                   | M04    | High     | **Francisco Gonçalves**                         | 100%  |
| US805         | Ban users                     | M05    | High     | **João Romão**                                  | 100%  |

<br>

## A10: Presentation

### 1. Product presentation

Our team's product is a platform where users can interact with each other, discussing any technology-related issues and thus creating an environment with centralized information. Whether you're having trouble with an application or can't seem to install a VPN on your computer, Tech Council is the place to look for answers.

The team implemented multiple features to accommodate the purpose of the website. The core elements of the platform are the questions (asked by users), which are associated with a group of tags, to help divide the information into relevant categories. Users can interact with the questions through answers, comments, and votes. They can also follow tags, save questions, search for contents (questions, tags, and users) and report what they don't find appropriate. To handle these situations, we have implemented an area to be used by moderators, who can manage all the content on the platform.

URL to the product: http://lbaw2132.lbaw-prod.fe.up.pt

> Slides used during the presentation should be added, as a PDF file, to the group's repository and linked to here.

### 2. Video presentation

Screenshot of the lbaw2132.mp4 file

![](https://i.imgur.com/4J5auQy.png)

Link to the demonstration video: https://drive.google.com/file/d/1qcLiBLUGyPUPrVBCvjr2FrvgCWx5m88M/view?usp=sharing

<br>

## Revision history

-   No changes yet

---

## Annex A: Group Self Evaluation

Every group member actively participated in every artifact/step of the project.

| Name                | Grade |
| ------------------- | :---: |
| Francisco Friande   |  A-   |
| Francisco Gonçalves |   A   |
| João Romão          |   A   |
| Miguel Pinto        |   A   |

---

GROUP2132, 08/06/2021

-   Francisco Friande, up201508213@fe.up.pt (editor)
-   Francisco Gonçalves, up201704790@fe.up.pt
-   João Romão, up201806779@fe.up.pt
-   Miguel Pinto, up201706156@fe.up.pt
