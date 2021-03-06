
var daysOfWeek = {sunday: 0, monday: 1, tuesday: 2, wednesday: 3, thursday: 4, friday: 5, saturday: 6};
var work_days = $("#work-days").val().split(",");
var off_days =  $("#off-days").val().split(",");
var start_work = $("#start-work").val();
var end_work = $("#end-work").val();
var calendarEl = document.getElementById('calendar');
var initialTimeZone = 'local';
var initialLocaleCode = 'ar';
var colors = {A: "green", B: "red"};
var events = [];
for (event of jsonEvents) {
    var item = {
        title: event.title,
        start: event.reservation_date + "T" + event.from_time,
        end: event.reservation_date + "T" + event.to_time,
        groupId: event.groupId,
        backgroundColor: colors[event.groupId],
        url: event.type == 1 ? '/manager/reservation/' + event.reservations_code : "",
    }
    events.push(item);
}

var calendar = new FullCalendar.Calendar(calendarEl, {
  plugins: [ 'interaction', 'dayGrid', 'timeGrid','bootstrap' ],
  height: 650,
  selectable: true,
  businessHours: true,
  placeholder: true,
  // eventLimit: true, // allow "more" link when too many events
  buttonIcons: false, // show the prev/next text
  defaultView: 'dayGridMonth',
  displayEventEnd: true,
  eventOverlap: false,
  navLinks: true,      
  locale: initialLocaleCode , 
  themeSystem: 'bootstrap',
  eventColor: 'green',
  header: {
    left: 'next,prev, today',
    center: 'title',
//    right: 'dayGridMonth,timeGridDay,listWeek'
    right: 'dayGridMonth,listWeek'
  },  
  bootstrapFontAwesome : false,
  businessHours: {
    daysOfWeek: work_days.map(function (day) { 
      return daysOfWeek[day];
    }),    
    startTime: start_work,  
    endTime: end_work, 
  },
  events: events,
  eventClick: function(info) {
    switch(info.event.groupId) {
      case 'A': break;          
      case 'B':
        Swal.fire({
          icon: 'info',
          title: '',
          text: 'هنا وقت استراحة لا يمكن عرض المناسبة',
        });
        break;                 
    }
  },
  // when click on day 
  dateClick: function(info) {
    var dayName = getDayName(info.date);
    if(off_days.includes(dayName)){
      Swal.fire({
        icon: 'error',
        title: '',
        text: 'لا يمكن اضافة حجز في يوم عطلة',
      });
    }
    else{
//      calendar.changeView('timeGridDay', info.dateStr);

		console.log(info);

      $start_time_show = "08:00 AM";
      $end_time_show = "08:00 AM";
      $start_time = "08:00 AM";
      $end_time = "08:00 AM";
      $date_show = new Date(info.dateStr).toLocaleDateString("en",  {format: "dd/mm/yyy"});
      $date = new Date(info.dateStr).toLocaleDateString("en",  {format: "dd/mm/yyy"});
      $("#start-time-show").val($start_time_show);
      $("#end-time-show").val($end_time_show);
      $("#start-time").val($start_time);
      $("#end-time").val($end_time);
      $("#event-date").val($date);
      $("#event-date-show").text($date_show);
	    $("#reservation-form").submit();

    }
  },

  longPressDelay:100,
  selectLongPressDelay:100,
  // when select row time or day
  select: function(info) {
    var start_hour = start_work.split(":")[0];
    var end_hour = end_work.split(":")[0];
    var dayName = getDayName(info.start);
    if(info.allDay || isOverlapping(info)) {
      //do nothing
    }
    else if(off_days.includes(dayName) || info.start.getHours() < parseInt(start_hour) || info.end.getHours() > parseInt(end_hour)) {
      Swal.fire({
        icon: 'error',
        title: '',
        text: 'لا يمكن الحجز خارج اوقات الدوام',
      });
    }
    else {
      $("#reservationModal").modal("show");
      $start_time_show = getTime(info.start, "ar");
      $end_time_show = getTime(info.end, "ar");
      $start_time = getTime(info.start, "en");
      $end_time = getTime(info.end, "en");
      $date_show = info.start.toLocaleDateString("ar-EG",  {format: "dd/mm/yyy"});
      $date = info.start.toLocaleDateString("en",  {format: "dd/mm/yyy"});
      $("#start-time-show").val($start_time_show);
      $("#end-time-show").val($end_time_show);
      $("#start-time").val($start_time);
      $("#end-time").val($end_time);
      $("#event-date").val($date);
      $("#event-date-show").text($date_show);
    }
  },
});
calendar.render();

function isOverlapping(event){
  var array = calendar.getEvents();
  for(i in array){
      if (event.end > array[i].start && event.start < array[i].end){
         return true;
      }
  }
  return false;
}

$("#btn-submit").click(function() {
  $("#reservation-form").submit();
});
