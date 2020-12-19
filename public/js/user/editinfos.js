const msg = document.getElementById("msg");
const firstname = document.getElementById('inputFirstName');
const lastname = document.getElementById('inputLastName');
const username = document.getElementById('inputUsername');
const email = document.getElementById('inputEmail');
const address = document.getElementById('inputAddress');
const gender = document.getElementById('choice-gender');

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
// Validate firstname by run some regex
const 			validateFirstName = ( firstname ) => {
	if ( !/^[a-zA-Z]{3,30}$/.test( firstname.value ) ) {
		setError(firstname, "The firstname must contains letters only ( between 3 and 30 ) !");
		return false;
	} else {
		setSuccess(firstname);
		return true;
	}
}
// Validate lastname by run some regex
const 			validateLastName = ( lastname ) => {
	if ( !/^[a-zA-Z]{3,30}$/.test( lastname.value ) ) {
		setError(lastname, "The lastname must contains letters only ( between 3 and 30 ) !");
		return false;
	} else {
		setSuccess(lastname);
		return true;
	}
}
// Validate lastname by run some regex
const 			validateUsername = ( username ) => {
	if ( !/^(?=.{3,20}$)(?![-_.])(?!.*[-_.]{2})[a-zA-Z0-9._-]+(?<![-_.])$/.test( username.value ) ) {
		setError(username, "The username should contain between 3 and 20 letters or numbers ( -, _ or . ) !");
		return false;
	} else {
		setSuccess(username);
		return true;
	}
}
// Validate email by run some regex
const			validateEmail = ( email ) => {
	if ( !/[a-zA-Z0-9-_.]{1,50}@[a-zA-Z0-9-_.]{1,50}\.[a-zA-Z0-9]{2,10}$/.test( email.value ) ) {
		setError(email, "Invalid email address !");
		return false;
	} else {
		setSuccess(email);
		return true;
	}
}
// Validate address by run adress
const 			validateAddress = ( address ) => {
	if ( !/^[a-zA-Z0-9\s,'-]*$/.test( address.value ) ) {
		setError(address, "The address should be contains letters or numbers ( ',', ' or - ) !");
		return false;
	} else {
		setSuccess(address);
		return true;
	}
}
// Validate gender
const			validateGender = ( gender ) => {
	if ( ![ "male", "female" ].includes( gender.value ) ) {
		setError( gender, "The gender must either male or female" );
		return false;
	} else {
		setSuccess( gender );
		return true;
	}
}
const validateEditData = () => {
	if ( !firstname.value || !lastname.value || !username.value || !email.value || !gender.value ) {
		setError(firstname, "Invalid data provided");
		return false;
	}
	else if (
		!validateFirstName( firstname ) ||
		!validateLastName( lastname ) ||
		!validateUsername( username ) ||
		!validateEmail( email ) || 
		!validateAddress( address ) ||
		!validateGender( gender )
	) { return false; }
	else { return true; }
}