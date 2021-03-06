$('#address-city').change(function () {
    resetList("address-region");
    resetList("address-street");
    $code = $(this).children("option:selected").val();
    if($code != ""){
        $.ajax({
            type: 'GET',
            url: $('#address-list-url').text() + "/" + $code,
        })
        .done(function (response) {
            if(response.status) {
                $addresses = response.result.addresses;
                if($addresses.length > 0)
                {
                    $("#form-address-region").show();
                    $("#address-region").append("<option value=''>اختر المنطقة</option>");
                    $addresses.forEach(address => $("#address-region").append("<option value='" + address.code+ "'>" + address.name + "</option>"));
                }
            }
            else {
                alert("حدث خطأ اثناء المعالجة, الرجاء تحديث الصفحة")
            }
        });
    }
});

$('#address-region').change(function () {
    resetList("address-street");
    $code = $(this).children("option:selected").val();
    if($code != ""){
        $.ajax({
            type: 'GET',
            url: $('#address-list-url').text() + "/" + $code,
        })
        .done(function (response) {
            if(response.status) {
                $addresses = response.result.addresses;
                if($addresses.length > 0)
                {
                    $("#form-address-street").show();
                    $("#address-street").append("<option value=''>اختر الشارع</option>");
                    $addresses.forEach(address => $("#address-street").append("<option value='" + address.code+ "'>" + address.name + "</option>"));
                }
            }
            else {
                alert("حدث خطأ اثناء المعالجة, الرجاء تحديث الصفحة")
            }
        });
    }
});

function resetList(element_name) {
    $("#form-" + element_name).hide();
    $("#" + element_name).find('option').remove();
}
