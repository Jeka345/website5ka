$(document).ready(function(){
  
  $(".fa-search").click(function(){
    $(".container, .input").toggleClass("active");
    $("input[type='text']").focus();
  });
  
});