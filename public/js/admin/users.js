var actionsCell = function(id, type, row) {
    var action_element = '<a href="/admin/users/edit/' + row.id + '" class="btn btn-info btn-circle">'
            + '<i class="fas fa-pen-alt"></i></a>&nbsp;&nbsp'
            + '<a href="/admin/users/' + row.id + '" class="btn btn-primary btn-circle">'
            + '<i class="fas fa-info-circle"></i></a>';

    return action_element;
 }

$(document).ready(function() {
    $('#dataTables_users').DataTable( {
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
            { data: 'certifcate'},
            { data: 'organizations_name'},
            { data: 'organization_type'},
            { data: 'phone1'},
            { data: 'email'},
            { data: 'address'},
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




$(document).ready( function() {

$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    var id = $(this).data("id");
    var input = $(this).parents('.file-group').find(':text'),
    log = label;

    if( input.length ) {
        input.val(log);
    } else {
        if( log ) alert(log);
    }
  input.trigger('fileselect', [label]);
  });

  function readURL(input, id) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#img-upload').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
      }
  }

  $(".fileInp").change(function() {
      var id = $(this).data("id");
      readURL(this, id);
    }); 	

    $(document).on('click', "#add-field", function(currentBtn) {
        addField();        
    });
});
  
  var count = $('#attachments-count').text();
  function addField() {
    var maxNumberOfAttachemnts = $("#max-attachments").text();
    if (count <= (maxNumberOfAttachemnts -1)) {
        count++;
        $attachmentRow = "<tr class='file-group'>"
            +"<td style='width:20px;'>"
            +"<button type='button' href='#' class='btn btn-danger btn-circle btn-sm' onclick='removeField(this)'>"
            +"<i class='fas fa-trash'></i></button></td>"
            +"<td style='width: 104px;'><span class='input-group-btn'>"
            +"<span class='btn btn-default btn-file'>"
            +"اضف ملفاً <input type='file' class='fileInp' name='attachment[]' data-id='" + count + "' data-validation='required'"
            +"data-validation-error-msg-container='#file-error-block-" + count + "' data-validation-error-msg='الرجاء ارفاق ملف'>"
            +"</span></span> </td>"
            +"<td><input type='text' name='fileUpload' id='fileUpload-" + count + "' class='form-control uploadfile' readonly>"
            +"<div class='custom-error-container' id='file-error-block-" + count + "'></div></td>"
            +"</tr>";

        $($attachmentRow).appendTo('.attachments-table');
        notifyChanges();
        if ((maxNumberOfAttachemnts) == count) {
            $("#add-field").attr("disabled", "disabled");
        }
    }
}

    function removeField(currentBtn) {
        if(count > 0) {
        $(currentBtn).parents('tr').remove();
            count--;
            if ($("#add-field").is(':disabled')) {
                $("#add-field").prop("disabled", false);
            }
        }
    }

    function removeSavedField(currentBtn) {
        $id = $(currentBtn).data("id");
        $(currentBtn).parents('li').replaceWith("<input type='hidden'  name='attachment_to_delete[]' value='"+ $id  +"' />");
        count--;
    }


    

/* This is basic - uses default settings */

$("a.single_image").fancybox();

/* Using custom settings */

$("a#inline").fancybox({
    'hideOnContentClick': true
});

/* Apply fancybox to multiple items */

$("a.group").fancybox({
    transitionIn: "elastic",
    transitionOut: "elastic",
    overlayShow: false,
    target: this,
    orig: $("img", this),
    titlePosition: "inside",
});


