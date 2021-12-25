
function checkValidation() {
  let nric = document.getElementById("nricID").value;
  let phoneno = document.getElementById("phonenoID");

  let errormsg = document.getElementById("errormsg");

  if(nric.length < 12 || nric.length > 12) {
    errormsg.innerHTML = "Invalid NRIC";
    return false;
  } else {
    errormsg.innerHTML = "";
    //return true;
  }

  if(checkPassword() === false) {
    errormsg.innerHTML = "Password are not the same";
    return false;
  } else {
    errormsg.innerHTML = "";
  }

}

function checkPassword() {
  let password1 = document.getElementById("passwordID").value;
  let password2 = document.getElementById("passwordconfirmID").value;
  if(password1 === password2) {
    return true;
  } else {
    return false;
  }
}
