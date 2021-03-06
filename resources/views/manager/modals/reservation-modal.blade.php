    <!-- Reservation Modal-->
    <div id="reservationModal" class="modal fade" style="display: none;">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">اضافة حجز جديد</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>هل تريد حقاً حجز المناسبة في هذه الفترة <b id="event-date-show"></b> </p>
                    <form action="/manager/reservation/add" id="reservation-form" type="get">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">                                        
                                    <label for="start-time-show" class="lbl">من الساعة</label>                     
                                    <input type="text" disabled  id="start-time-show" class="timepicker form-control time" />    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end-time-show" class="lbl">الى الساعة</label>                     
                                    <input type="text" disabled  id="end-time-show" class="timepicker form-control time"/>
                                </div>
                            </div>
                            <input type="hidden" id="hall" name="hall" value="{{ $hall }}" />
                            <input type="hidden" id="start-time" name="start_time" />
                            <input type="hidden" id="end-time" name="end_time" />
                            <input type="hidden" id="event-date" name="event_date" />       
                        </div>
                    </form>                                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" id="btn-submit" class="btn btn-success">تأكيد</button>
                </div>
            </div>
        </div>
    </div>