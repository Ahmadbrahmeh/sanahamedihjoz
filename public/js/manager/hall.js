var actionsCell = function(id, type, row) {
   var action_element = 
            '<a href="#" class="btn btn-danger btn-circle " data-id="' + id + '"  data-toggle="modal" data-target="#deleteModal">'
           + '<i class="fas fa-trash"></i></a>&nbsp;&nbsp'
           + '<a href="/manager/halls/edit/' + row.id + '" class="btn btn-info btn-circle">'
           + '<i class="fas fa-pen-alt"></i></a>';

   return action_element;
}

$(document).ready(function() {
    $('#dataTables-hall').DataTable( {
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
            { data: 'price'},
            { data: 'capacity'},
            { data: 'created_at'},
            { data: 'updated_at'},
            { 
                "mData": 'id',
                "mRender": actionsCell
            }
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