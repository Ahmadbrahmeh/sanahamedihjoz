$(document).ready(function() {
    $('#datatables_currencies').DataTable( {
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
            { data: 'code'},
            { data: 'sign'},
            { data: 'created_at'},
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

    $('#to-currency').change(function () {
        $id = $(this).children("option:selected").val();
        resetExhangeRateForm();
        if($id != "NOT_SELECTED"){
            $.ajax({
                type: 'GET',
                url: $('#getExchangeRate').text() + "/" + $id,
            })
            .done(function (response) {
                if(response.status) {
                    $("#exchange-rate").prop("disabled", false);
                    $("#save").prop("disabled", false);
                    $("#exchange-rate").val(response.result.value);
                    if(!response.result.have_exhange_rate)
                    {
                        $("#exhange-rate-message").prop("hidden", false);
                    }
                }
                else {
                    alert("حدث خطأ اثناء المعالجة, الرجاء تحديث الصفحة")
                }
            });
        }
    });

    function resetExhangeRateForm()
    {
        $("#exchange-rate").prop("disabled", true);
        $("#exchange-rate").val("");
        $("#save").prop("disabled", true);
        $("#exhange-rate-message").prop("hidden", true);
    }
} );