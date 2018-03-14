$(document).ready(function(){
  common_filter("PostSearchForm");
  $(".fileinput-button").ready(function(){
    $(".fileinput-button").removeClass("btn");
    $(".fileinput-button").removeClass("btn-success");
    $(".fileinput-button .glyphicon.glyphicon-plus").removeClass("glyphicon-plus");
    $(".fileinput-button .glyphicon").addClass("glyphicon-file");
    $(".fileinput-button").css("height", "16px");
    $(".fileinput-button span").html("");
  });
});