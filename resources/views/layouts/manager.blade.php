<!DOCTYPE html>
<html  lang="ar">
    <head>
        <title> @yield('pageTitle')</title>
        @include('manager.includes.head')

        @yield('styles')
        
    </head>
    <body>
        <div id="wrapper">
           

            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <nav id="page-top" class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                        @include('manager.includes.navbar')

                    </nav>
                    <div class="container-fluid">
                        <div class="alert custom-alert alert-success alert-dismissible" id="success_message">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span class="message"></span>
                        </div>
                        <div class="alert custom-alert alert-danger alert-dismissible" id="error_message">
                            <span class="message"><span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span class="message"></span>
                        </div>

                        @yield('content')

                    </div>
                </div>
                <footer class="sticky-footer bg-white">
                
                    @include('manager.includes.footer')

                </footer>
            </div>
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            @include('manager.includes.sidebar')

            </ul>
        </div>
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        @include('manager.modals.logout-modal')


        <!-- Bootstrap core JavaScript-->
        <script src="{{ URL::asset('vendor/jquery/jquery.min.js') }}"></script>
        <!-- References: https://github.com/fancyapps/fancyBox -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
        <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>        <script src="{{ URL::asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{ URL::asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{ URL::asset('js/bootstrap.file-input.js') }}"></script>
        <script src="{{ URL::asset('js/jquery.form-validator.min.js') }}"></script>
        <script src="{{ URL::asset('js/validate_config.js') }}"></script>
        <script src="{{ URL::asset('js/manager/sb-admin-2.min.js') }}"></script>
        <script src="{{ URL::asset('js/sweetalert.min.js') }}"> </script>
        <script src="{{ URL::asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ URL::asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ URL::asset('js/manager/main.js') }}"></script>
        <script src="{{ URL::asset('js/alerts.js') }}"></script>

        @yield('scripts')

    </body>
</html>