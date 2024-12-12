<?php echo $this->Session->flash(); ?>
<?php echo $this->Html->script(array('plugins/jQueryUI/jquery-ui.min', 'plugins/slimScroll/jquery.slimscroll.min', 'plugins/fastclick/fastclick', 'plugins/daterangepicker/moment.min',  'plugins/fullcalendar/fullcalendar.min')); ?>
<?php echo $this->Html->css(array(
  'plugins/fullcalendar/fullcalendar.min', 
  )); ?>
<?php echo $this->fetch('script'); ?>
<?php echo $this->fetch('css'); ?>
<style type="text/css">
.cols{padding: 1px}
.cols-1{padding-left: 15px}
.widget-user-2 .widget-user-header{padding: 5px}
.widget-user-username{margin-left: 10px !important}
.chosen-select,.chosen-container, .chosen-container-multi{width: 100% !important}
.overlay{display: none;}
.fc-time{display: none;}
.fc-title{font-weight: 600; text-shadow: 1px 1px 2px #333; font-size: 14px}
.fc-unthemed{border: 1px solid #ccc}
.fc th{padding:10px !important}
.fc-day-grid-event > .fc-content{padding: 4px}
.fc-day-grid-event{margin-bottom: 2px}
</style>
<div class="row">
	<div class="col-md-12">
    	<div id="calendar"></div>
    </div>
    <div class="col-md-12"></div>
<div>

<script>
  $().ready(function(){
  
  })
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });
    }

    ini_events($('#external-events div.external-event'));

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      // defaultDate:'01/01/2017',
      // defaultDate:'<?php echo date("d/m/Y") ?>',
      weekends:true,
      firstDay:0,
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'year,month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week: 'week',
        day: 'day',
        agendaYear: 'Year'
      },
      //Random default events
      events: <?php echo str_replace('\/','/',json_encode($events));?>,
      eventClick: function(event) {        
        if (event.url) {
            var link = event.url;
            $('.modal-body').load(link,function(result){
              $('#myModal').modal({show:true});
            });
            return false;
        }else{
          var startDate = moment(event.start).format('YYYY-MM-DD');
          var endDate = moment(event.end).format('YYYY-MM-DD');
          // alert(startDate);
          var link = '<?php echo Router::url('/', true)."internal_audit_plans/plan_add_ajax_calendar"?>/start:'+startDate+'/end:'+endDate;
          //   $('.modal-body').load(link,function(result){
          //     $('#myModal').modal({show:true});
          //   });
          $("#hidethis").hide();
          $("#plan_add_ajax").load(link);
            return false;
        }
      },
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar !!!
      drop: function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject');

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);

        // assign it the date that was reported
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        copiedEventObject.backgroundColor = $(this).css("background-color");
        copiedEventObject.borderColor = $(this).css("border-color");

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

        // is the "remove after drop" checkbox checked?
        // if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove();
        // }

      }
    });

    /* ADDING EVENTS */
    var currColor = "#3c8dbc"; //Red by default
    //Color chooser button
    var colorChooser = $("#color-chooser-btn");
    $("#color-chooser > li > a").click(function (e) {
      e.preventDefault();
      //Save color
      currColor = $(this).css("color");
      //Add color effect to button
      $('#add-new-event').css({"background-color": "#dd4b39", "border-color": "#c74433"});
    });
    $("#add-new-event").click(function (e) {
      e.preventDefault();
      //Get value and make sure it is not null
      // var val = $("#new-event").val();
      var val = 'New Audit';
      if (val.length == 0) {
        return;
      }

      //Create events
      var event = $("<div />");
      event.css({"background-color": "#dd4b39", "border-color": "#c74433", "color": "#fff"}).addClass("external-event");
      event.html(val);
      $('#external-events').prepend(event);

      //Add draggable funtionality
      ini_events(event);

      //Remove event from text input
      $("#new-event").val("");
    });
  });
</script>	
<style>
.modal-dialog{width:80%}
</style>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Task Details</h4>
      </div>
      <div class="modal-body">
        <style type="text/css">
        .chosen-container, .chosen-container-single, .chosen-select{min-width: 100% !important}
        </style>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
      </div>
    </div>
  </div>
</div>
<?php echo $this->Js->writeBuffer(); ?>
