$(document).ready(function() {
    $("#cityModalBox").modal('show');

    window.setTimeout(function() {
        $(".flash").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 5000);
});
