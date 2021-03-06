let count = {payment: 1}
let maxNumberOfFields = {
    payment: $("#max-payments-fields").val(),
};

let containerFields = {
    payment: "#payments",
};

function switchForm(currentElement) {
    $type = $(currentElement).data("type");
    let parent_content =  $(currentElement).parents(".payment_content");
    let cash_content = $(parent_content).find(".cash-content");
    let cheque_content = $(parent_content).find(".cheque-content");
    if (currentElement.checked && $type === "cash") {  
        $(cash_content).show();
        $(cheque_content).hide();
    } else if(currentElement.checked && $type === "cheque") {
        $(cash_content).hide();
        $(cheque_content).show();
    }
}

$(document).on('click', "#btn-add-payment", function(e) {
    currentBtn = e.target;
    addField(currentBtn, "payment");
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
    console.log(count[type]);
    if(count[type] > 0) {
        handleRemoveField(currentBtn, type);
        count[type]--;
    }
}

function handleRemoveField(currentBtn, type) {
    switch(type) {  
        case 'payment': return removePaymentForm(currentBtn);break;
    }
}

function removePaymentForm(currentBtn) {
    $(currentBtn).parents('.payment_content').remove();
    if ($("#btn-add-payment").is(':disabled')) {
        $("#btn-add-payment").prop("disabled", false);
    }
}

function buildform(type) {
    switch(type) {
        case 'payment': return buildPaymentForm();break;
    }
}

function buildPaymentForm() {
    let payment_count = count["payment"]  - 1;
    let cash_content = $("#payment-form").find(".cash-content").clone();
    let cheque_content = $("#payment-form").find(".cheque-content").clone();
    
    $(cash_content).find(":input").each((key, element) => {
        let name = $(element).attr("name").replace("payment\[0\]","payment[" + payment_count + "]");
        $(element).attr("name", name);
    });

    $(cheque_content).find(":input").each((key, element) => {
        let name = $(element).attr("name").replace("payment\[0\]","payment[" + payment_count + "]");
        $(element).attr("name", name);
    });

    let payment_form =  "<div class='row col-md-12 table_direction payment_content'>"
                        + "   <div class='row'>"
                        + "       <div class='form-group'>"
                        + "           <button type='button' data-type='payment' class='btn btn-danger btn-circle btn-sm btn-delete' onclick='removeField(this)'>"
                        + "                <i class='fas fa-trash'></i>"
                        + "           </button>"
                        + "       </div>"
                        + "   </div>"
                        + "   <div class='col-md-12'>"
                        + "       <div class='row'>"
                        + "           <div class='col-md-10'>"
                        + "               <div class='form-group'>"
                        + "                    <label for='service_name' class='lbl'>طريقة الدفع</label>"
                        + "                    <div class='form-group'>"
                        + "                        <input type='radio' data-type='cash' value='cash' checked class='pay-type' name='payment[" + payment_count + "][pay_type]' onclick='switchForm(this)'> نقدي"
                        + "                        <input type='radio' data-type='cheque' value='cheque' class='pay-type' name='payment[" + payment_count + "][pay_type]' onclick='switchForm(this)'> شيك"
                        + "                   </div>"
                        + "               </div>"
                        + "            </div>"                
                        + "        </div>"
                        + "        <div class='cash-content'>"
                        +               $(cash_content).html()     
                        + "        </div>"        
                        + "        <div class='cheque-content'>"
                        +              $(cheque_content).html()
                        + "       </div>"
                        + "   </div>"     
                        + "</div>";
    return payment_form;
}