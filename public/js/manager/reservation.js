var actionsCell = function(id, type, row) {
    let action_element = "";
    if(row.display_cancel == true) {
        action_element +=   '<button href="#" class="btn btn-danger btn-circle" ' + (row.disable_cancel ? "disabled='disabled'" : "") + ' data-id="' + id + '"  data-toggle="modal" data-target="#cancelReservationModal" >'
                            + '<i class="fas fa-ban"></i></button>&nbsp;&nbsp';
    }
    action_element += '<a href="/manager/reservation/' + row.id + '/edit" class="btn btn-info btn-circle ' + (row.disable_cancel ? "disabled" : "") + '">' 
    + '<i class="fa fa-pen-alt"></i></a>&nbsp;&nbsp';

    action_element += '<a href="/manager/reservation/' + row.id + '/payments/add" class="btn btn-primary btn-circle ' + (row.disable_cancel ? "disabled" : "") + '">' 
                        + '<i class="fa fa-credit-card"></i></a>&nbsp;&nbsp';
    return action_element;
 };

var codeCell = function(code, type, row) {
    var action_element = '<a href="/manager/reservation/' + code +'">' + code + '</a>'
    return action_element;
} 

$(document).ready(function() {
    $('#dataTables_reservation').DataTable( {
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
            { 
                "mData": 'code',
                "mRender": codeCell
            },
            { data: 'customer_name'},
            { data: 'title'},
            { data: 'status'},
            { data: 'phone1'},
            { data: 'creator_name'},
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

let count = {
    hall: $("#halls-count").val(),
    note: $("#notes-count").val(),
    term: $("#terms-count").val(),
    eventlist: $("#eventlists-count").val()
}

let maxNumberOfFields = {
    hall: $("#max-hall-fields").val(),
    note: $("#max-note-fields").val(),
    term: $("#max-term-fields").val(),
    eventlist: $("#max-eventlist-fields").val()
}
let containerFields = {
    hall: "#all-hall",
    note: "#notes",
    term: "#terms",
    eventlist: "#all-eventlist"
}

$(document).ready( function() {
    $(document).on('click', "#btn-add-hall", function(e) {
        currentBtn = e.target;
        addField(currentBtn, "hall");
        updateSwitchForm();
    });

    $(document).on('click', "#btn-add-note", function(e) {
        currentBtn = e.target;
        addField(currentBtn, "note");
    });

    $(document).on('click', "#btn-add-term", function(e) {
        currentBtn = e.target;
        addField(currentBtn, "term");
    });

    $(document).on('click', "#btn-add-eventlist", function(e) {
        currentBtn = e.target;
        addField(currentBtn, "eventlist");
    });
});
  
  function addField(currentBtn, type) {
    if (count[type] <= (maxNumberOfFields[type] - 1)) {
        count[type]++;
        $filedRow = buildform(type);
        $($filedRow).appendTo(containerFields[type]);
        if ((maxNumberOfFields[type]) == count[type]) {
            $(currentBtn).attr("disabled", "disabled");
        }
    }
}


function removeField(currentBtn) {
    let type = $(currentBtn).data("type");
    if(count[type] > 0) {
        handleRemoveField(currentBtn, type);
        count[type]--;
    }
}

function handleRemoveField(currentBtn, type) {
    switch(type) {  
        case 'hall': return removeHallForm(currentBtn);break;
        case 'note': return removeNoteForm(currentBtn);break;
        case 'term': return removeTermForm(currentBtn);break;
        case 'eventlist': return removeEventListForm(currentBtn);break;
    }
}

function removeHallForm(currentBtn) {
    $(currentBtn).parents('.hall').remove();
    if ($("#btn-add-hall").is(':disabled')) {
        $("#btn-add-hall").prop("disabled", false);
    }
}

function removeNoteForm(currentBtn) {
    $(currentBtn).parents('.note-block').remove();
    if ($("#btn-add-note").is(':disabled')) {
        $("#btn-add-note").prop("disabled", false);
    }
}

function removeTermForm(currentBtn) {
    $(currentBtn).parents('.term-block').remove();
    if ($("#btn-add-term").is(':disabled')) {
        $("#btn-add-term").prop("disabled", false);
    }
}

function removeEventListForm(currentBtn) {
    $(currentBtn).parents('.eventlist-block').remove();
    if ($("#btn-add-eventlist").is(':disabled')) {
        $("#btn-add-eventlist").prop("disabled", false);
    }
}

function switchForm(currentElement) {
    $type = $(currentElement).data("type");
    if (currentElement.checked && $type === "form1") {
        $("#form1").show();
        $(".hall .form2").hide();
    } else {
        $("#form1").hide();
        $(".hall .form2").show();
    }
}

function updateSwitchForm() {
    if($("#pay-type1").prop("checked")) {
        $(".hall .form2").hide();
    }
    else {
        $(".hall .form2").show();
    }
}

function buildform(type) {
    switch(type) {
        case 'hall': return buildHallForm();break;
        case 'note': return buildNoteForm();break;
        case 'term': return buildTermForm();break;
        case 'eventlist': return buildEventlistForm();break;
    }
}
function buildHallForm() {
    var halls = $("#hall-" + count["hall"]).clone();
    $(halls).find("option:selected").remove();
    var hall_form =   "<div class='col-md-10 hall'>"
                    + " <div class='row'>"
                    + "	    <div class='form-group'>"
                    + "		    <button type='button' data-type='hall' class='btn btn-danger btn-circle btn-sm btn-delete' onclick='removeField(this)'>"
                    + "			    <i class='fas fa-trash'></i>"
                    + "		    </button>"
                    + "	    </div>"
                    + "  </div>"
                    + "  <div class='col-md-8'>"
                    + "     <div class='form-group'>"
                    + "         <label for='hall_id' class='lbl'> القاعة </label>" 
                    + "         <select class='form-control' id='hall-" + (count["hall"] + 1) +"' name='hall[" + count["hall"] +"][id]'>" 
                    +       $(halls).html()
                    + "         </select>"
                    + "     </div>"
                    + "     <div class='form-group'>"
                    + "         <label for='hall_note' class='lbl'> الملاحظات </label>"
                    + "         <input type='text' class='form-control' name='hall[" + count["hall"] +"][note]' />"
                    + "     </div>"
                    + "  </div>"
                    + "  <div class='col-md-8'>"
                    + "     <div class='col-md-12 form2'>"
                    + "         <div class='form-group'>"
                    + "             <label for='person_number' class='lbl'> عدد الافراد </label>"
                    + "             <input type='number' min='1' id='person-number' class='form-control ' name='hall[" + count["hall"] +"][capacity]' value='' data-validation='required'>"
                    + "         </div>"
                    + "         <div class='form-group'>"
                    + "             <label for='hall_person_price' class='lbl'> السعر لكل فرد </label>"
                    + "             <input type='number' min='1' class='form-control ' name='hall[" + count["hall"] +"][person_price]' value='' data-validation='required'>"
                    + "         </div>"
                    + "     </div>"
                    + "  </div>"
                    + "</div>";
    return hall_form;
}

function buildNoteForm() {
    let note_form =  "<div class='form-group note-block'>"
                    +   "<button type='button' data-type='note' class='btn btn-danger btn-circle btn-sm btn-delete' onclick='removeField(this)'><i class='fas fa-trash'></i>"
                    +   "</button>"
                    +   "<input type='text' class='form-control col-md-10' name='note[" + count["note"] + "][value]'  data-validation='required'>"
                    +"</div>"
    return note_form;
}

function buildTermForm() {
    let term_form = "<div class='form-group term-block'>"
                    + "  <button type='button' data-type='term' class='btn btn-danger btn-circle btn-sm btn-delete' onclick='removeField(this)'>"
                    + "      <i class='fas fa-trash'></i>"
                    + "  </button>"
                    + "  <input type='text' name='term[" + count["term"] + "][value]' class='form-control col-md-10' data-validation='required'>"
                    + "</div>"
    return term_form;
}

function buildEventlistForm() {
    let eventlist_questions = $("#eventlist-questions").clone();
    let eventlist_form =  "<div class='row eventlist-block'>"
	                    + " <button type='button' data-type='eventlist' class='btn btn-danger btn-circle btn-sm attachment-delete' onclick='removeField(this);'>"
		                + "     <i class='fas fa-trash'></i>"
	                    + " </button>"
	                    + " <div class='col-md-5'>"
                        + "     <div class='form-group'>"
                        + "         <select class='form-control' name='eventlist[" + count["eventlist"] + "][question]' data-validation='required'>"
                        + $(eventlist_questions).html()
                        + "         </select>"                      
                        + "     </div>"
                        + " </div>"
                        + " <div class='col-md-5'>"
                        + "     <div class='form-group'>"
                        + "         <input type='text' class='form-control' name='eventlist[" + count["eventlist"] + "][answer]'  data-validation='required' />"
                        + "     </div>"
                        + " </div>"
                        + "</div>";
    return eventlist_form;
}
let popup;
function openWin() {
  if (popup && !popup.closed) {
    popup.focus();
    /* or do something else, e.g. close the popup or alert a warning */
  } else {
    let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
    width=650px,height=700px,left=200,top=0`;    
    open('/manager/reservation/bill', 'test', params);
  }
}


$('#service-modal').on('show.bs.modal', function (e) {
    let service_price = $(e.relatedTarget).data('service-price');
    let service_id = $(e.relatedTarget).data('service-id');
    $("#modal-service-price").val(service_price);
    $("#modal-service-id").val(service_id);
});


$(document).on('click', "#btn-service-save", function(e) {
    currentBtn = e.target;
    let id = $("#modal-service-id").val();
    let price =  $("#modal-service-price").val();
    let service_btn = $("#service-btn-" + id);
    $(service_btn).find("#service-price-label").text(price);
    $("#service-id-" + id).val(id).attr("disabled", false);
    $("#service-price-" + id).val(price).attr("disabled", false);
    $(service_btn).data("service-selected", true);
    $(service_btn).data("service-price", price);
    if(!$(service_btn).hasClass("service-selected")) {
        $(service_btn).addClass("service-selected");
    }
    $('#service-modal').modal("hide");
});

$(document).on('click', "#btn-service-cancel", function(e) {
    let id = $("#modal-service-id").val();
    let service_btn = $("#service-btn-" + id);
    $("#service-id-" + id).val("").attr("disabled", true);
    $("#service-price-" + id).val("").attr("disabled", true);
    $(service_btn).data("service-selected", false);
    if($(service_btn).hasClass("service-selected")) {
        $(service_btn).removeClass("service-selected");
    }
    $('#service-modal').modal("hide");
});

$('#service-modal').on('hidden.bs.modal', function () {
    resetServiceModal();
});

function resetServiceModal() {
    $("#modal-service-price").val("");
    $("#modal-service-id").val("");
}

$('#cancelReservationModal').on('show.bs.modal', function (e) {
    var $id = $(e.relatedTarget).attr('data-id');
    $("#id-delete").val($id);
});

$("#reservation-invoice-btn").click(function(e) {
    let currentBtn = e.target;
    let url = $(currentBtn).data("url");
    openPopupWindow(url);
  });

  function openPopupWindow(url) {
    let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=760px,height=700px,left=200,top=0`;
    open(url, "invoice", params);
  }