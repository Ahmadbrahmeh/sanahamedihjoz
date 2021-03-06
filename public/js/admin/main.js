
/**
 * 
 * @param {*} url 
 * navigate to another page
 */
function navigateTo(url)
{   
    window.location.href = url;
}

/*  --------- Delete Modal ---------     */
$('#deleteModal').on('show.bs.modal', function (e) {
    var $id = $(e.relatedTarget).attr('data-id');
    $("#id-delete").val($id);
});

$('#btn-delete').on('click', function() {
    $('#delete-form').submit();
});