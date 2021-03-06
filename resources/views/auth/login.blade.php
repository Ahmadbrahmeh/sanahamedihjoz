<html>
    <head>
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" id="bootstrap-css">
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <title>تسجيل دخول</title>
    </head>
    <body id="LoginForm">
        <div class="container">
        <form id="Login" method="POST" action="{{ route('login') }}" >                
            <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-12 col-md-9 panel_login">
                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                                    <div class="row login-card">
                                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                        <div class="col-lg-6">
                                            <div class="p-5">
                                                <div class="text-center">
                                                    <h1 class="h4 text-gray-900 mb-4">اهلاً وسهلاً بكم في نظام احجز</h1>
                                                </div>
                                                <div class="form-group">
                                                        @csrf
                                                        <input type="email" class="form-control-user form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="البريد الإلكتروني" required autofocus> @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert" style="display:block">
                                                        <strong>{{ __('البريد الإلكتروني الذي أدخلته لا يطابق أي حساب') }}</strong>
                                                        </span> @endif
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class=" form-control-user form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="كلمة المرور" required>
                                                    @if ($errors->has('password') and !$errors->has('email'))
                                                        <span class="invalid-feedback" role="alert" style="display:block">
                                                            <strong>{{ __('كلمة السر التي أدخلتها غير صحيحة.') }}</strong>
                                                        </span> 
                                                    @endif
                                                </div>
                                                <input type="submit" class="btn btn-primary btn-user btn-block" id="btn-login" value="تسجيل الدخول">                                            </a>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>                           
        </div>
    </body>
</html>