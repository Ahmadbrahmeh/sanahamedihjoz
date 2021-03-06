    /*  --------- Delete Modal ---------     */
    $('#deleteModal').on('show.bs.modal', function (e) {
        var $id = $(e.relatedTarget).attr('data-id');
        $("#id-delete").val($id);
    });

    $('#btn-delete').on('click', function() {
        $('#delete-form').submit();
    });

    function getDayName(dateStr, locale)
    {
        var date = new Date(dateStr);
        return date.toLocaleDateString(locale, { weekday: 'long' }).toLocaleLowerCase();        
    }

    function getTime(dateStr, locale)
    {
        var time = new Date(dateStr);
        return time.toLocaleTimeString(locale, {hour: '2-digit', minute:'2-digit'});       
    }