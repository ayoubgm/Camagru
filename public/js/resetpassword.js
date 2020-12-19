const msg = document.getElementById("msg");
	
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

// Validate email by run some regex
const			validateEmail = ( email ) => {
    if ( email.value == "" ) {
        setError( email, "the email can't be empty !" ); return false;
    } else if ( !/[a-zA-Z0-9-_.]{1,50}@[a-zA-Z0-9-_.]{1,50}\.[a-z0-9]{2,10}$/.test( email.value ) ) {
        setError(email, "Invalid email address !"); return false;
    } else {
        setSuccess(email); return true;
    }
}