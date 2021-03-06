    <!-- Delay Modal-->
    <div id="delayReservationModal" class="modal fade" style="display: none;">
        <div class="modal-dialog modal-confirm modal-info">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="icon-box">
                        <i class="material-icons">update</i>
                    </div>
                    <br /><br />
                    <h4 class="modal-title">هل انت متأكد؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>هل تريد حقاً تأجيل وقت الحجز؟</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ $url }}" id="delete-form" method="post">
                        @method('put')
                        {{ csrf_field() }}
                        <input name="id" type="hidden" id="id-delay"/>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" id="btn-delete" class="btn btn-info">تأكيد</button>
                    </form>
                </div>
            </div>
        </div>
    </div>