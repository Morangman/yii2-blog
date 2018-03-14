function common_filter(container_name){
  var shideShow = false;
  var cPart = container_name;
  
  if (!$("#"+cPart).hasClass("hidden")){
    $("#"+cPart+"Toggle").html($("#"+cPart+"Toggle").html()+ " (скрыть)");
    shideShow = true;
  }
  $("#"+cPart+"Toggle").click(function(e){
    e.preventDefault();
    var html = $("#"+cPart+"Toggle").html();
    if (shideShow){
      html = html.replace(" (скрыть)","");
    } else {
      html = html + " (скрыть)";
    }
    $("#"+cPart+"Toggle").html(html);
    if ($("#"+cPart).hasClass("hidden")){
      $("#"+cPart).removeClass("hidden");
    } else {
      $("#"+cPart).slideToggle();
    }
    shideShow = !shideShow;
    return false;
  });
  $("a.clear-interval").click(function(e){
    var part_id = $(this).attr("data-clear");
    e.preventDefault();
    $("#"+part_id+"0").val("");
    $("#"+part_id+"1").val("");
    return false;
  });
  
}