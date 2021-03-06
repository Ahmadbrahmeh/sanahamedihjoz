var actionsCell = function(id, type, row) {
    var action_element = 
             '<a href="#" class="btn btn-danger btn-circle" data-id="' + id + '"  data-toggle="modal" data-target="#deleteModal">'
            + '<i class="fas fa-trash"></i></a>&nbsp;&nbsp';
            if(!row.is_tail)
            {
                action_element += '<a href="/admin/address/add?code=' + row.code + '" class="btn btn-success btn-plus btn-circle" data-id="' + id + '" ><i class="fas fa-plus"></i></a>&nbsp;&nbsp';
            }
 
    return action_element;
 }

 var nameCell = function(id, type, row) {
    var name_element = row.name;
     if(row.is_parent)
     {
        name_element =  "<a href='/admin/address/lookup?code="+ row.code +"'>" + row.name +"</a>";
     }
     return name_element;
 }
 
 $(document).ready(function() {
     $type = $("#address-type").text();
     $('#dataTables_address').DataTable( {
         "pageLength": 10,
         "info":     false,
         "responsive": true,
         "processing": true,
         "serverSide": true,
         ajax: {
             "url": $('#datatables-url').text(),
             "data": function(params) {
                params.code = $("#address-code").text();
             }
         },
         columns: [
             {
                 "data": "id",
                 "render": function ( data, type, full, meta ) {return  meta.row + 1; } 
             },
             { 
                "mData": 'name',
                "mRender": nameCell
             },
             { data: 'code'},
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