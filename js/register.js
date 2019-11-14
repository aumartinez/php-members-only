//Registration validation
$(document).ready(function(){
  //To Do
  
  $("#userForm").submit(function(evt){
    let errors = validateForm();
    
    if (errors.length == 0) {      
      return true;
    }
    else {
      removeErrors();
      displayErrors(errors);
      evt.preventDefault();
      window.scrollTo(0,0);
      return false;
    }
  });
    
  function validateForm() {
    let errors = [];
      
    //Check required empty fields input
    if ($("#firstName").val().length == 0) {
      errors.push("firstName");
    }
    
    if ($("#lastName").val().length == 0) {
      errors.push("lastName");
    }
    
    if ($("#email").val().length == 0) {
      errors.push("email");
    }
    
    if ($("#password").val().length == 0) {
      errors.push("password");
    }
    
    if ($("#verify").val().length == 0) {
      errors.push("verify");
    }
    
    //Check passwords match
    if ($("#password").val() != $("#verify").val()) {
      errors.push("verify");
    }
    
    //Validate email
    let email = $("#email").val();
    let regExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    let testEmail = regExp.exec(email);
    
    if (!testEmail) {
       errors.push("email");
    }
      
    return errors;
  }//End function
    
  function displayErrors(errors) {  
    for (let i = 0, len = errors.length; i < len; i++) {
      $("#" + errors[i] + " + .errorMess").addClass("active");
    }      
    $("#errorDiv").html("Errors found");
  }
    
  function removeErrors() {
    $(".errorMess.active").removeClass("active");
  }
});

let email = "acm_asd@hotmail.com";


let test = regExp.exec(email);