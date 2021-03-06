<div class="modal fade" id="service-modal" tabindex="-1" role="dialog" aria-labelledby="service-add-modal" aria-hidden="true">           
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="service-add-modal">اضافة خدمة</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="body-title-service-model">
                <h6 style="font-weight: bold">
                    الرجاء ادخال السعر المناسب للخدمة
                </h6>    
                <div class="form-group">
                    <label for="modal-service-price" class="lbl"> السعر </label>
                    <input id="modal-service-price"  type="number" class="form-control" />
                    <input id="modal-service-id" type="hidden" />
                </div>
                <button class="btn btn-danger" id="btn-service-cancel">الغاء الخدمه</button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">الغاء</button>
                <button class="btn btn-success" id="btn-service-save">حفظ</button>                    
            </div>
        </div>
    </div>
</div>
