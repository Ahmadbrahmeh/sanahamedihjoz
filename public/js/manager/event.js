$(document).ready(function() {
    $('#dataTables_event').DataTable( {
        "pageLength": 10,
        "info":     false,
        "responsive": true,
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