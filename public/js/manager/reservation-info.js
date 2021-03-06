let popup;

function openWinEvent() {
  if (popup && !popup.closed) {
    popup.focus();
    /* or do something else, e.g. close the popup or alert a warning */
  } 
  else
  {
    let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
    width=650px,height=700px,left=200,top=0`;    
    open('/manager/reservation/eventlist', 'test', params);
  }
}


function openWinAccountInfo() {
  if (popup && !popup.closed) {
    popup.focus();
    /* or do something else, e.g. close the popup or alert a warning */
  } 
  else
  {
    let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
    width=650px,height=700px,left=200,top=0`;    
    open('/manager/reservation/account-info', 'test', params);
  }
}


$('#cancelReservationModal').on('show.bs.modal', function (e) {
  var $id = $(e.relatedTarget).attr('data-id');
  $("#id-delete").val($id);
});

$('#delayReservationModal').on('show.bs.modal', function (e) {
  var $id = $(e.relatedTarget).attr('data-id');
  $("#id-delay").val($id);
});

$("#reservation-invoice-btn").click(function(e) {
  let currentBtn = e.target;
  let url = $(currentBtn).data("url");
  openPopupWindow(url);
});

$("#reservation-eventlist-btn").click(function(e) {
  let currentBtn = e.target;
  let url = $(currentBtn).data("url");
  openPopupWindow(url);
});

$(".btn-receipt").click(function(e) {
  let currentBtn = e.target;
  let url = $(currentBtn).data("url");
  openPopupWindow(url);
});

$("#reservation-acc-invoice-btn").click(function(e) {
  let currentBtn = e.target;
  let url = $(currentBtn).data("url");
  openPopupWindow(url);
});

$("#reservation-acc-invoice-btn").click(function(e) {
  let currentBtn = e.target;
  let url = $(currentBtn).data("url");
  openPopupWindow(url);
});

function openPopupWindow(url) {
  let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=760px,height=700px,left=200,top=0`;
  open(url, "invoice", params);
}