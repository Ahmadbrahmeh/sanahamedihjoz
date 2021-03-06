$(document).ready(function() {


    $("#customer-name").change(function( ) {
         if($(this).val()) {
            window.location.href = $("account-report-url").text() + "?customer_id=" + $("#customer-name").val();
         }
    } );

} );