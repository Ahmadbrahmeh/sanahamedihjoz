    <!-- Delete Modal-->
    <div id="deleteModal" class="modal fade" style="display: none;">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                <div class="icon-box">
                        <i class="material-icons"></i>
                    </div>
                    <br /><br />
                    <h4 class="modal-title">هل انت متأكد؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>هل تريد حقًا حذف هذا السجل؟ لا يمكن التراجع عن هذه العملية.</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ $url }}" id="delete-form" method="post">
                        @method('delete')
                        {{ csrf_field() }}
                        <input name="id" type="hidden" id="id-delete"/>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" id="btn-delete" class="btn btn-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>