<!DOCTYPE html>
<html  lang="ar">
    <head>
        <title> @yield('pageTitle')</title>

        @include('admin.includes.head')

        @yield('styles')
        
    </head>
    <body>
        <!-- start navbar -->
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
           @include('admin.includes.navbar')

        </nav>
        <!-- end navbar -->
        <div id="layoutSidenav">
            <!-- start sidebar -->
            <div id="layoutSidenav_nav">

                @include('admin.includes.sidebar')

            </div>
            <!-- end sidebar -->
            <!-- start content -->        
            <div id="layoutSidenav_content">
                <main>
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
                </main>
                <!-- start footer -->
                <footer class="py-4 bg-light mt-auto">

                    @include('admin.includes.footer')

                </footer>
                <!-- end footer -->
            </div>
            <!-- end content -->
        </div>


        @include('manager.modals.logout-modal')
        <script src="{{ URL::asset('js/admin/jquery-3.4.1.min.js') }}" ></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>        
        <script src="{{ URL::asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ URL::asset('js/admin/scripts.js') }}"></script>
        <script src="{{ URL::asset('js/admin/all.min.js') }}" ></script>
        <script src="{{ URL::asset('js/admin/jquery.dataTables.min.js') }}" ></script>
        <script src="{{ URL::asset('js/admin/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ URL::asset('js/sweetalert.min.js') }}"> </script>      
        <script src="{{ URL::asset('js/jquery.form-validator.min.js') }}"></script>
        <script src="{{ URL::asset('js/validate_config.js') }}"></script>
        <script src="{{ URL::asset('js/admin/main.js') }}"></script>
        <script src="{{ URL::asset('js/alerts.js') }}"></script>
        @yield('scripts')

    </body>
</html>