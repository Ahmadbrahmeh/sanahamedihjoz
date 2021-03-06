var errors = [],

// Validation configuration
conf = {
  onElementValidate : function(valid, $el, $form, errorMess) {
      if( !valid ) {
      // gather up the failed validations
      errors.push({el: $el, error: errorMess});
      }
  }
},
  arabic = {
  requiredFields: 'هذا الحقل إلزامي',
  badEmail: "الرجاء إدخال عنوان بريد إلكتروني صحيح",
  badTelephone: 'رقم الهاتف الذي قمت بإدخاله',
  badDate: "الرجاء إدخال تاريخ صحيح",
  badUrl: "الرجاء إدخال عنوان موقع إلكتروني صحيح",
  wrongFileSize: 'الملف الذي تحاول تحميله كبير جدًا (بحد أقصى٪ s)',
  wrongFileType: 'يُسمح فقط بملفات النوع٪ s',
  wrongFileDim : 'أبعاد الصورة غير صحيحة,',
  imageTooTall : 'لا يمكن تحميل صورة اكثر من طول ',
  imageTooWide : 'لا يمكن تحميل صورة اكثر من عرض ',
  imageTooSmall : 'الصورة صغيرة جداً',
  max: "الرجاء إدخال عدد أقل من أو يساوي {0}" ,
  min: "الرجاء إدخال عدد أكبر من أو يساوي {0}" ,
  imageRatioNotAccepted : 'ابعاد الصورة غير مدعومة',
  lengthTooShortStart: 'طول القيمة المدخلة اقل من  ',
  lengthBadEnd: ' احرف',
  notConfirmed: 'يجب ان تكون كلمة المرور متطابقة',

};

$.formUtils.loadModules('security');

function validateForm($form) {
  $.validate({
    validateOnEvent: true,
    language: arabic,
    form: "#"+$form,
});
}