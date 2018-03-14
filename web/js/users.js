$(document).ready(function(){
  common_filter("UserSearchForm");
  var block = function(){
    $("#pjax_users_grid").block({ message: "WAIT..."  , css: { top: '10px', left: '', right: '10px' }, centerY: 0 }); 
  };

  $("#pjax_users_grid").on('pjax:success', function() {
    $("#pjax_users_grid").unblock();
  });
  
  $("#pjax_users_grid").on('pjax:click', function() {
    block();
  });
  $("#pjax_users_grid input, #pjax_users_grid select").on('change', function() {
    block();
  });
});