<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">           
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="body-title-logout-model">هل انت متأكد من تسجيل الخروج ؟</div>
                <div class="modal-footer">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">الغاء</button>
                    <button class="btn btn-primary" href="">خروج</button>
                </form>
            </div>
        </div>
    </div>
</div>