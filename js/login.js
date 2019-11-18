//Login validation
$(document).ready(function(){
  //To Do
  
  $("#loginForm").submit(function(evt){
    let errors = validateForm();
    
    if (errors.length == 0) {      
      return true;
    }
    else {
      removeErrors();
      displayErrors(errors);
      evt.preventDefault();      
      return false;
    }
  });
    
  function validateForm() {
    let errors = [];
      
    //Check required empty fields input    
    if ($("#email").val().length == 0) {
      errors.push("email");
    }
    
    if ($("#password").val().length == 0) {
      errors.push("password");
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
      $("#" + errors[i] + " ~ .errorMess").addClass("active");
    }      
    $("#errorDiv").html("Errors found");
  }
    
  function removeErrors() {
    $(".errorMess.active").removeClass("active");
  }
});
