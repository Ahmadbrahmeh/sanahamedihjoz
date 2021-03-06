var actionsCell = function(id, type, row) {
    var action_element = '<a href="/manager/customers/' + row.id + '/payments/add" class="btn btn-primary btn-circle">' 
                        + '<i class="fa fa-credit-card"></i></a>';
    return action_element;
 }

$(document).ready(function() {
    $('#datatables_customers').DataTable({
        "pageLength": 10,
        "info":     false,
        "responsive": true,
        "processing": true,
        "serverSide": true,
        ajax: $('#datatables-url').text(),
        columns: [
            {
                "data": "id",
                "render": function ( data, type, full, meta ) {return  meta.row + 1; } 
            },
            { data: 'code'},
            { data: 'name'},
            { data: 'phone1'},
            { data: 'email'},
            { data: 'address'},
            { data: 'created_at'},
            { data: 'updated_at'},
        ],
        "language": {
            "sEmptyTable":   	"لا يوجد اي بيانات تم تخزينها ",
            "sSearch":          "ابحث: &nbsp;",
            "lengthMenu":       "عدد السجلات لكل صفحة _MENU_ ",
            "sProcessing":   	"جاري التحميل ....",
            "oPaginate": {
                "sFirst":    "الأول",
                    "sPrevious": "«",
                    "sNext":     "»",
                    "sLast":     "الأخير"
            }
        }        
    } );
} );