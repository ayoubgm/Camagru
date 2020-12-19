const buttonSubmit = document.querySelector('#btn-submit');
const msg = document.getElementById("msg");
const btn_profile = document.querySelector("nav .btn-auth #profile-img");
const oldpassword = document.getElementById('inputOldPass');
const newpassword = document.getElementById('inputNewPass');
const confirmationPassword = document.getElementById('inputConfirmationPass');

const 			setError = ( target, msgerror ) => {
    target.style.border = "1px solid red";
    msg.classList.add("text-danger");
    msg.classList.remove("text-success");
    msg.innerHTML = msgerror;
}
const 			setSuccess = ( target ) => {
    msg.innerHTML = "";
    msg.classList.remove("text-danger");
    msg.classList.add("text-success");
    target.style.border = "1px solid green";
}
const 			validatePassword = ( password ) => {
    if ( !/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*_-]).{8,}$/.test( password.value ) ) {
        setError(password, "The password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !");
        return false;
    } else {
        setSuccess(password);
        return true;
    }
}
const			validateConfPass = ( ) => {
    if ( newpassword.value !== confirmationPassword.value ) {
        setError(confirmationPassword, "Passwords doesn't match");
        return false;
    } else {
        setSuccess(confirmationPassword);
        return true;
    }
}