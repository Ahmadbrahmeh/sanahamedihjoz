<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>تفعيل الحساب</title>

  <!-- Custom fonts for this template-->
  <link href="{{ URL::asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ URL::asset('css/manager/sb-admin-2.min.css') }}" rel="stylesheet">
<style>
  *{
    direction: rtl;
    /* font-size: 14px;
    text-align: right; */
  }
  .has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline, .has-error.radio label, .has-error.checkbox label, .has-error.radio-inline label, .has-error.checkbox-inline label {
    color: #a94442;
    font-size: 13px;
  }
.bg-password-image {
    background: url("{{ URL::asset('images/first_login_bg.jpg') }}");
    background-position: center;
}
.card-activate{
    margin-top: 100px !important;
}

.form-group {
        text-align: right;
    }
     
</style>


</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5 card-activate">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">تفعيل حساب جديد </h1>
                    <p class="mb-4">الرجاء ادخال كلمة مرور جديدة حتى يتم تفعيل الحساب</p>
                  </div>
                  <form id="activate-form"class="user" action="{{ route('manager-activate') }}" method="post">
                    @csrf
                    <div class="form-group">
                      <input type="password" name="password_confirmation" class="form-control form-control-user" data-validation="required length" data-validation-length="min8" placeholder="كلمة مرور جديدة">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" data-validation="confirmation" placeholder="تأكيد كلمة المرور">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      تغيير كلمة المرور
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <form id="logoutForm" action="{{ route('logout') }}" method="post">
                        @csrf
                        <a  href="#" onclick="submitLogoutForm()">تسجيل الخروج</a>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ URL::asset('js/admin/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ URL::asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ URL::asset('js/jquery.form-validator.min.js') }}"></script>
  <script src="{{ URL::asset('js/validate_config.js') }}"></script>
  <script>
      $.validate({
          modules : 'security',
          validateOnEvent: true,
          language: arabic,
          form: "#activate-form",
      });
      function submitLogoutForm(currentLink)
      {
        $("#logoutForm").submit();
      }
  </script>


</body>

</html>
