//Startup

$(document).ready(function(){    
  
  let checkWidth = setInterval(
    function(){
      let outDivWidth = $(".meter").width();
      let innDivWidth = $(".meter > span").width();
  
      if (outDivWidth == innDivWidth) {
        clearInterval(checkWidth);
        let message = "Database tables are ready.";
        $("#message").text(message);
      }
    }, 1000);
    
});