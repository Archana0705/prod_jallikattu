// Wait for the DOM to be fully loaded
$(document).ready(function() {
  alert('ready');
  $('#preloader').hide();
});
$(document).on({
  ajaxStart: function () {
    alert('ajstart');
    $('#preloader').show();
    $("body").addClass("user_loading");
  },
  ajaxStop: function () {
    $('#preloader').hide();
    $("body").removeClass("user_loading");
  }
});
