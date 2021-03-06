
$(document).ready(function() {
    $('#datatables_emplyee').DataTable( {
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
            { data: 'name'},
            { data: 'salary_type'},
            { data: 'email'},
            { data: 'phone1'},
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