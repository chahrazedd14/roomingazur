/*global $, document, window, setTimeout, navigator, console, location*/
// $(document).ready(function () {
//     "use strict";

//     var usernameError = true,
//         emailError = true,
//         passwordError = true,
//         passConfirm = true;

//     // Detect browser for css purpose
//     if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
//         $(".form form label").addClass("fontSwitch");
//     }

//     // Label effect
//     $("input").focus(function () {
//         $(this).siblings("label").addClass("active");
//     });

//     // Form validation
//     $("input").blur(function () {
//         // User Name
//         if ($(this).hasClass("name")) {
//             if ($(this).val().length === 0) {
//                 $(this)
//                     .siblings("span.error")
//                     .text("Please type your full name")
//                     .fadeIn()
//                     .parent(".form-group")
//                     .addClass("hasError");
//                 usernameError = true;
//             } else if ($(this).val().length > 1 && $(this).val().length <= 6) {
//                 $(this)
//                     .siblings("span.error")
//                     .text("Please type at least 6 characters")
//                     .fadeIn()
//                     .parent(".form-group")
//                     .addClass("hasError");
//                 usernameError = true;
//             } else {
//                 $(this)
//                     .siblings(".error")
//                     .text("")
//                     .fadeOut()
//                     .parent(".form-group")
//                     .removeClass("hasError");
//                 usernameError = false;
//             }
//         }
//         // Email
//         if ($(this).hasClass("email")) {
//             if ($(this).val().length == "") {
//                 $(this)
//                     .siblings("span.error")
//                     .text("Please type your email address")
//                     .fadeIn()
//                     .parent(".form-group")
//                     .addClass("hasError");
//                 emailError = true;
//             } else {
//                 $(this)
//                     .siblings(".error")
//                     .text("")
//                     .fadeOut()
//                     .parent(".form-group")
//                     .removeClass("hasError");
//                 emailError = false;
//             }
//         }

//         // PassWord
//         if ($(this).hasClass("pass")) {
//             if ($(this).val().length < 8) {
//                 $(this)
//                     .siblings("span.error")
//                     .text("Please type at least 8 charcters")
//                     .fadeIn()
//                     .parent(".form-group")
//                     .addClass("hasError");
//                 passwordError = true;
//             } else {
//                 $(this)
//                     .siblings(".error")
//                     .text("")
//                     .fadeOut()
//                     .parent(".form-group")
//                     .removeClass("hasError");
//                 passwordError = false;
//             }
//         }

//         // PassWord confirmation
//         if ($(".pass").val() !== $(".passConfirm").val()) {
//             $(".passConfirm")
//                 .siblings(".error")
//                 .text("Passwords don't match")
//                 .fadeIn()
//                 .parent(".form-group")
//                 .addClass("hasError");
//             passConfirm = false;
//         } else {
//             $(".passConfirm")
//                 .siblings(".error")
//                 .text("")
//                 .fadeOut()
//                 .parent(".form-group")
//                 .removeClass("hasError");
//             passConfirm = false;
//         }

//         // label effect
//         if ($(this).val().length > 0) {
//             $(this).siblings("label").addClass("active");
//         } else {
//             $(this).siblings("label").removeClass("active");
//         }
//     });

//     // form switch
//     $("a.switch").click(function (e) {
//         $(this).toggleClass("active");
//         e.preventDefault();

//         if ($("a.switch").hasClass("active")) {
//             $(this)
//                 .parents(".form-peice")
//                 .addClass("switched")
//                 .siblings(".form-peice")
//                 .removeClass("switched");
//         } else {
//             $(this)
//                 .parents(".form-peice")
//                 .removeClass("switched")
//                 .siblings(".form-peice")
//                 .addClass("switched");
//         }
//     });

//     // Form submit
//     $("form.signup-form").submit(function (event) {
//         event.preventDefault();

//         if (
//             usernameError == true ||
//             emailError == true ||
//             passwordError == true ||
//             passConfirm == true
//         ) {
//             $(".name, .email, .pass, .passConfirm").blur();
//         } else {

//             let name = document.getElementById("name");
//             let email = document.getElementById("email");
//             let phone = document.getElementById("phone");
//             let password = document.getElementById("password");
//             let formData = new FormData();
//             formData.append("type", "signup");
//             formData.append("bookingId", name.value);
//             formData.append("email", email.value);
//             if (phone.value !== "") {
//                 formData.append("phone", phone.value);
//             }
//             formData.append("password", password.value);
//             let http = new Http("./user.php", "POST", formData);
//             http.onSuccessCallback((data) => {
//                 let json = JSON.parse(data);
//                 if (json.status === "success") {
//                     $(".signup, .login").addClass("switched");

//                     setTimeout(function () {
//                         $(".signup, .login").hide();
//                     }, 700);
//                     setTimeout(function () {
//                         $(".brand").addClass("active");
//                     }, 300);
//                     setTimeout(function () {
//                         $(".heading").addClass("active");
//                     }, 600);
//                     setTimeout(function () {
//                         $(".success-msg p").addClass("active");
//                     }, 900);
//                     setTimeout(function () {
//                         $(".success-msg a").addClass("active");
//                     }, 1050);
//                     setTimeout(function () {
//                         $(".form").hide();
//                     }, 700);
//                 }
//             });
//             http.start();

//         }
//     });

//     // Reload page
//     $("a.profile").on("click", function () {
//         location.reload(true);
//     });

//     let login = document.getElementById("login");
//     login.addEventListener("click", (e) => {
//         e.preventDefault();
//         let loginEmail = document.getElementById("loginemail");
//         let loginPassword = document.getElementById("loginPassword");
//         let formData = new FormData();
//         formData.append("type", "login");
//         formData.append("email", loginEmail.value);
//         formData.append("password", loginPassword.value);
//         let http = new Http("./user.php", "POST", formData);
//         http.onSuccessCallback((data) => {
//             let json = JSON.parse(data);
//             if (json.status === "success") {
//                 location.href = "../views/formclient.html";
//             } else {

//             }
//         })
//         http.start();
//     })
// });


class FormVerification {
    static inputs = [];

    static switchTheme(themeName = `light`) {
        document.body.dataset.theme = themeName;
    }

    static handleInput(target) {
        let nextInput = target.nextElementSibling;

        /* NOTE :
         * if any input avaialbe in next and current input
         * filled with value, then, focus next input and if next input
         * value exist, select the text to change new value
         */
        if (nextInput && target.value) {
            nextInput.focus();
            nextInput.value && nextInput.select();
        }
    }

    static handleBackspace(target) {
        return target.value
            ? (target.value = "")
            : target.previousElementSibling.focus();
    }

    static handleArrowLeft(target) {
        const previousInput = target.previousElementSibling;
        return !previousInput ? undefined : previousInput.focus();
    }

    static handleArrowRight(target) {
        const nextInput = target.nextElementSibling;
        return !nextInput ? undefined : nextInput.focus();
    }

    static handlePaste(event, inputs) {
        // NOTE : get last text saved on clipboard
        let pasteText = (event.clipboardData || window.clipboardData).getData(
            "text"
        );

        // NOTE : change inputs value with clipboard text
        inputs.forEach((input, index) => {
            input.value = pasteText[index] || ``;
        });
        event.preventDefault();
    }
}

function shortcut(element, key, handle, params) {
    element.addEventListener(`keydown`, (e) => {
        return e.key.toString().toLowerCase() == key && handle(element);
    });
};

(function () {
    FormVerification.switchTheme();
    const ThemeSwitcher = document.querySelector(`.theme`);
    ThemeSwitcher.onclick = () => {
        let currentTheme = ThemeSwitcher.innerHTML.toString().toLowerCase();
        let nextTheme =
            currentTheme === ` light `
                ? ` dark `
                : currentTheme === ` dark `
                    ? ` dark-2 `
                    : ` light `;

        FormVerification.switchTheme(nextTheme.trim());
        ThemeSwitcher.innerHTML = nextTheme.toUpperCase();
    };

    const verification = document.querySelector(`.verification`);
    const inputs = document.querySelectorAll(`.verification__input`);
    const sendNew = document.querySelector(`.verification__send_new`);

    sendNew.onclick = () => {
        if (sendNew.classList.contains("disabled")) {
            return;
        }
        $(".warning").text("");
        let formData = new FormData();
        formData.append("type", "login");
        formData.append("email", document.getElementById("email").value);
        formData.append("code", document.getElementById("password").value);
        let loader = document.createElement("div");
        loader.classList.add("loader");
        sendNew.append(loader);
        sendNew.classList.add("disabled");
        let http = new Http("./verification", "POST", formData);
        http.onSuccessCallback((data) => {
            let json = JSON.parse(data);
            sendNew.classList.remove("disabled");
            loader.remove();
            if (json.status === "success") {
                sendNew.style.display = "none";
                timer(10);
            } else {
                $(".warning").text("L'e-mail ou l'identifiant de réservation ne correspond pas");
            }
        });
        http.start();
    };

    verification.addEventListener("input", ({target}) =>
        FormVerification.handleInput(target)
    );
    verification.addEventListener("paste", (e) =>
        FormVerification.handlePaste(e, inputs)
    );

    inputs[0].onfocus = () => inputs[0].select();
    inputs[1].onfocus = () => inputs[1].select();
    inputs[2].onfocus = () => inputs[2].select();
    inputs[3].onfocus = () => inputs[3].select();

    shortcut(inputs[0], `backspace`, FormVerification.handleBackspace);
    shortcut(inputs[1], `backspace`, FormVerification.handleBackspace);
    shortcut(inputs[2], `backspace`, FormVerification.handleBackspace);
    shortcut(inputs[3], `backspace`, FormVerification.handleBackspace);

    shortcut(inputs[0], `arrowleft`, FormVerification.handleArrowLeft);
    shortcut(inputs[1], `arrowleft`, FormVerification.handleArrowLeft);
    shortcut(inputs[2], `arrowleft`, FormVerification.handleArrowLeft);
    shortcut(inputs[3], `arrowleft`, FormVerification.handleArrowLeft);

    shortcut(inputs[0], `arrowright`, FormVerification.handleArrowRight);
    shortcut(inputs[1], `arrowright`, FormVerification.handleArrowRight);
    shortcut(inputs[2], `arrowright`, FormVerification.handleArrowRight);
    shortcut(inputs[3], `arrowright`, FormVerification.handleArrowRight);

    // TODO : give code sending status `'sended' | true` then start below timeout counter
    // timer(10);
})();

function timeFormat(duration = 0) {
    let minutes = ~~((duration % 3600) / 60);
    let seconds = ~~duration % 60;
    let min = minutes < 10 ? `0${minutes}` : minutes;
    let sec = seconds < 10 ? `0${seconds}` : seconds;
    return `${min} : ${sec}`;
}

function timer(
    seconds = 120,
    target = document.querySelector(`.verification__counter`)
) {
    document.getElementById("verification-counter-text").style.display = "block";
    target.innerHTML = timeFormat(seconds);
    if (seconds < 0) {
        target.innerHTML = `00 : 00`;
        document.querySelector(`.verification`).classList.add(`verification--timed-out`);
        document.getElementById("verification-counter-text").style.display = "none";
        document.getElementById("verification-send-new").style.display = "block";
        return;
    }
    return window.setTimeout(() => timer(seconds - 1), 1100);
    // return timer(seconds - 1);
}


setTimeout(() => {
    $(".email > input").focus();
}, 300);

$(".email > input").on("keydown", (event) => {
    if (event.which === 13 || event.keyCode === 13) {
        $(".email > input").blur();
        $(".next").click();
    }
});

$(".password > input").on("keydown", (event) => {
    if (event.which === 13 || event.keyCode === 13) {
        $(".login").click();
    }
});

let email;
$(".next").on("click", (event) => {
    let emailInput = $(".email > input").val();
    if (validateEmail(emailInput)) {
        event.preventDefault();
        $(".inputs").addClass("shift");
        $(".back").addClass("active-back");
        $(".email > input").css({
            border: "1px solid #cccccc"
        });
        $(".warning").empty();
        setTimeout(() => {
            $(".password > input").focus();
        }, 400);
    } else {
        event.preventDefault();
        $(".warning").empty();
        $(".email > input").css({
            border: "1px solid red"
        });
        $(".warning").append("Adresse e-mail invalide");
    }
});

$(".back").on("click", (event) => {
    event.preventDefault();
    $(".inputs").removeClass("shift");
    $(".back").removeClass("active-back");
    setTimeout(() => {
        $(".email > input").focus();
    }, 300);
});

$(".login").on("click", (event) => {
    event.preventDefault();
    if ($(".login").hasClass("disabled")) {
        return;
    }
    $(".warning").text("");
    let formData = new FormData();
    formData.append("type", "login");
    formData.append("email", document.getElementById("email").value);
    formData.append("code", document.getElementById("password").value);
    let loader = document.createElement("div");
    loader.classList.add("loader");
    $(".login").append(loader);
    $(".login").addClass("disabled");
    let http = new Http("./verification", "POST", formData);
    http.onSuccessCallback((data) => {
        let json = JSON.parse(data);
        $(".login").removeClass("disabled");
        if (json.status === "success") {
            $(".form").css("display", "none");
            $(".verification").css("display", "flex");
            let des = document.getElementById("verification-description");
            des.innerText = des.innerText.replace("{{email}}", formData.get("email"));
            timer(10);
        } else {
            loader.remove();
            $(".warning").text("El'e-mail ou l'identifiant de réservation ne correspond pas");
        }
    });
    http.start();

});

$(".verification__verify_btn").on("click", (event) => {
    event.preventDefault();
    let code = $("#verification-input-1").val() + $("#verification-input-2").val() +
        $("#verification-input-3").val() + $("#verification-input-4").val();

    let formData = new FormData();
    formData.append("type", "verify");
    formData.append("code", code);
    formData.append("booking_id",  document.getElementById("password").value);
    formData.append("email", document.getElementById("email").value);
    let http = new Http("./verification", "POST", formData);
    http.onSuccessCallback((data) => {
        let json = JSON.parse(data);
        if (json.status === "success") {
            location.href = "../views/admin";
        } else {
            $(".warning").text("Le code ne correspond pas");
        }
    });
    http.start();
});


const validateEmail = (email) => {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
};
