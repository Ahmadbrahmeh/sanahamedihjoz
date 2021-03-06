var actionsCell = function(id, type, row) {
   var action_element = 
            '<i><a href="#" class="btn btn-danger btn-circle " data-id="' + id + '"  data-toggle="modal" data-target="#deleteModal">'
           + '<i class="fas fa-trash"></i></a></i>'
           + '<i><a href="/manager/receiptpayments/edit/' + row.id + '" class="btn btn-info btn-circle">'
           + '<i class="fas fa-pen-alt"></i></a></i>'
		   + '<i><a href="/manager/receiptpayments/view/' + row.id + '" class="btn btn-info btn-circle">'
           + '<i class="fas fa-eye"></i></a></i>';

   return action_element;
}

$(document).ready(function() {
    $('#dataTables-receipt-payment').DataTable( {
        "pageLength": 10,
        "info":     false,
        "responsive": true,
        "processing": true,
        "serverSide": true,
		"order": [[ 0, "desc"]],
        ajax: $('#datatables-url').text(),
        columns: [
            {
                "data": "id",
                "render": function ( data, type, full, meta ) {return  meta.row + 1; } 
            },
            { data: 'invoice_number'},
            { data: 'customer_id'},
            { data: 'organization_id'},
			{ data: 'receipt_date'},
            { data: 'total_cash'},
            { data: 'total_cheque'},
			{ data: 'total'},
            { data: 'notes'},
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