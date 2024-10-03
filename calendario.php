<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse();
$idAlum=$_SESSION['idAlum'];
$tablaPeriodo=$_SESSION['periodoAlum'];


$result = mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.Periodo, A.ruta, A.ced_rep,A.grado,A.seccion, B.nombreGrado, C.representante FROM alumcer A, grado".$tablaPeriodo." B, represe C WHERE A.idAlum ='$idAlum' and B.grado=A.grado and A.ced_rep=C.cedula "); 
while ($row = mysqli_fetch_array($result))
{   
  $cedula = $row['cedula'];
  $nombre = ($row['nombre']).' '.($row['apellido']);  
  $periodo = $row['Periodo'];
  $foto_alu = 'fotoalu/'.$row['ruta'];
  $nombreGrado=($row['nombreGrado']);
  $grado=$row['grado'];
  $seccion=$row['seccion'];
  $ruta=$row['ruta'];
}
$horario_query = mysqli_query($link,"SELECT archivo FROM horario WHERE grado='$grado' and seccion='$seccion' and periodo='$periodo' and status='1' "); 
if(mysqli_num_rows($horario_query) > 0)
{
  $row=mysqli_fetch_array($horario_query);
  $archivo='horario/'.$row['archivo'];
  $exis=1;
}else{
  $archivo='imagenes/pendiente.jpg';
  $exis=2;
}
$eventos_calendario_query = mysqli_query($link,"SELECT * FROM eventos_calendario WHERE ( (grado_des <= '$grado' AND grado_has >= '$grado' AND (sec_des <= '$seccion' AND sec_has >= '$seccion') OR todos = 'S') )") or die ("NO ENCONTRO EVENTO".mysqli_error($link));
$data_calendario = [];
foreach ($eventos_calendario_query as $key => $value) {
    $data_calendario[]=['id' => $value['id'],'title' => $value['titulo'],'start' => $value['fecha_ini'],'end' => $value['fecha_fin'],'text' => $value['texto']]; 
}
 ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  <style>
.calendar-day {
  width: 100px;
  min-width: 100px;
  max-width: 100px;
  height: 80px;
}
.calendar-table {
  margin: 0 auto;
}
.selected {
  background-color: #eee;
}
.outside .date {
  color: #ccc;
}
.timetitle {
  white-space: nowrap;
  text-align: right;
}
.event {
  border-top: 1px solid #b2dba1;
  border-bottom: 1px solid #b2dba1;
  background-image: linear-gradient(to bottom, #dff0d8 0px, #c8e5bc 100%);
  background-repeat: repeat-x;
  color: #3c763d;
  border-width: 1px;
  font-size: .75em;
  padding: 0 .75em;
  line-height: 2em;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 1px;
  cursor: pointer;
}
.event.begin {
  border-left: 1px solid #b2dba1;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
}
.event.end {
  border-right: 1px solid #b2dba1;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
}
.event.all-day {
  border-top: 1px solid #9acfea;
  border-bottom: 1px solid #9acfea;
  background-image: linear-gradient(to bottom, #d9edf7 0px, #b9def0 100%);
  background-repeat: repeat-x;
  color: #31708f;
  border-width: 1px;
}
.event.all-day.begin {
  border-left: 1px solid #9acfea;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
}
.event.all-day.end {
  border-right: 1px solid #9acfea;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
}
.event.clear {
  background: none;
  border: 1px solid transparent;
}


</style>
    <div class="modal fade" id="publicaNueva" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actividad</h5>
                    <!--button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="$('#publicaNueva').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                    </button-->
                </div>
                <div class="modal-body" style="background-image: url('assets/img/portada_772.jpg'); background-position: center top; background-size: cover;">
                    <div class="container-fluid">
                        <div class="row" style="color:black " >
                            <div class="col-md-6">
                                <label><b>Comienza</b> </label>
                                <input type="datetime-local" style="opacity: 0.9;" disabled id="fecha_ini" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label><b>Termina</b></label>
                                <input type="datetime-local" style="opacity: 0.9;" disabled id="fecha_fin" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label><b>Título</b></label>
                                <textarea disabled id="titulo" class="form-control" rows="3" style="opacity:0.9; "></textarea>
                            </div>
                            <div class="col-md-12" >
                                <label><b>Descripción</b></label>
                                <textarea disabled id="texto" class="form-control" rows="5" style="opacity:0.9; "></textarea>
                            </div>                                
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#publicaNueva').modal('hide')">Cerrar Ventana</button>
                </div>
            </div>
        </div>
    </div> 
    <script src="assets/vendor/jquery/jquery.min.js"></script>
	<script src="assets/vendor/jquery/jquery.js"></script>
<!--script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script-->
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Calendario de Actividades</h2>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" >
        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col text-center" style="overflow-x: scroll;">
                    <div class="row">
                        <div id="holder" class="col-12"></div>
                    </div>
                    <script type="text/tmpl" id="tmpl">
                        {{ var date = date || new Date(),
                            month = date.getMonth(), 
                            year = date.getFullYear(), 
                            first = new Date(year, month, 1), 
                            last = new Date(year, month + 1, 0),
                            startingDay = first.getDay(), 
                            thedate = new Date(year, month, 1 - startingDay),
                            dayclass = lastmonthcss,
                            today = new Date(),
                            i, j; 
                            if (mode === 'week') {
                                thedate = new Date(date);
                                thedate.setDate(date.getDate() - date.getDay());
                                first = new Date(thedate);
                                last = new Date(thedate);
                                last.setDate(last.getDate()+6);
                            } else if (mode === 'day') {
                                thedate = new Date(date);
                                first = new Date(thedate);
                                last = new Date(thedate);
                                last.setDate(thedate.getDate() + 1);
                            }
                        
                        }}
                        <table class="calendar-table table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="7">
                                        <div class="row py-2">
                                            <div class="col">
                                                <span class="btn-group">
                                                    <button class="js-cal-prev btn btn-primary">
                                                        <</button> <button class="js-cal-next btn btn-primary">>
                                                    </button>
                                                </span>
                                                <button class="js-cal-option btn btn-primary {{: first.toDateInt() <= today.toDateInt() && today.toDateInt() <= last.toDateInt() ? 'active':'' }}" data-date="{{: today.toISOString()}}" data-mode="month">{{: todayname }}</button>
                                            </div>
                                            <div class="col text-center">
                                                <span class="btn-group btn-group-lg">
                                                        {{ if (mode !== 'day') { }}
                                                        {{ if (mode === 'month') { }}<button class="js-cal-option btn btn-link" data-mode="year">{{: months[month] }}</button>{{ } }}
                                                        {{ if (mode ==='week') { }}
                                                        <button class="btn btn-link disabled">{{: shortMonths[first.getMonth()] }} {{: first.getDate() }} - {{: shortMonths[last.getMonth()] }} {{: last.getDate() }}</button>
                                                        {{ } }}
                                                        <button class="js-cal-years btn btn-link">{{: year}}</button>
                                                        {{ } else { }}
                                                        <button class="btn btn-link disabled">{{: date.toDateString() }}</button>
                                                        {{ } }}
                                                </span>
                                            </div>
                                            <div class="col text-right">
                                                <span class="btn-group">
                                                    <button class="js-cal-option btn btn-primary {{: mode==='year'? 'active':'' }}" data-mode="year">Año</button>
                                                    <button class="js-cal-option btn btn-primary {{: mode==='month'? 'active':'' }}" data-mode="month">Mes</button>
                                                    <button class="js-cal-option btn btn-primary {{: mode==='week'? 'active':'' }}" data-mode="week">Semana</button>
                                                    <button class="js-cal-option btn btn-primary {{: mode==='day'? 'active':'' }}" data-mode="day">Día</button>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            {{ if (mode ==='year') {
                        month = 0;
                        }}
                            <tbody>
                                {{ for (j = 0; j < 3; j++) { }}
                                <tr>
                                    {{ for (i = 0; i < 4; i++) { }}
                                    <td class="calendar-month month-{{:month}} js-cal-option" data-date="{{: new Date(year, month, 1).toISOString() }}" data-mode="month">
                                        {{: months[month] }}
                                        {{ month++;}}
                                    </td>
                                    {{ } }}
                                </tr>
                                {{ } }}
                            </tbody>
                            {{ } }}
                            {{ if (mode ==='month' || mode ==='week') { }}
                            <thead>
                                <tr class="c-weeks">
                                    {{ for (i = 0; i < 7; i++) { }}
                                    <th class="c-name">
                                        {{: days[i] }}
                                    </th>
                                    {{ } }}
                                </tr>
                            </thead>
                            <tbody>
                                {{ for (j = 0; j < 6 && (j < 1 || mode === 'month'); j++) { }}
                                <tr>
                                    {{ for (i = 0; i < 7; i++) { }}
                                    {{ if (thedate > last) { dayclass = nextmonthcss; } else if (thedate >= first) { dayclass = thismonthcss; } }}
                                    <td class="calendar-day {{: dayclass }} {{: thedate.toDateCssClass() }} {{: date.toDateCssClass() === thedate.toDateCssClass() ? 'selected':'' }} {{: daycss[i] }} js-cal-option" data-date="{{: thedate.toISOString() }}">
                                        <div class="date">{{: thedate.getDate() }}</div>
                                        {{ thedate.setDate(thedate.getDate() + 1);}}
                                    </td>
                                    {{ } }}
                                </tr>
                                {{ } }}
                            </tbody>
                            {{ } }}
                            {{ if (mode ==='day') { }}
                            <tbody>
                                <tr>
                                    <td colspan="7">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th> </th>
                                                    <th style="text-align: center; width: 100%">{{: days[date.getDay()] }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="timetitle">Todo el Día</th>
                                                    <td class="{{: date.toDateCssClass() }}"> </td>
                                                </tr>
                                                <tr>
                                                    <!--th class="timetitle">Antes de las 6 AM</th-->
                                                    <th class="timetitle"></th>
                                                    <td class="time-0-0"> </td>
                                                </tr>
                                                {{for (i = 6; i < 22; i++) { }}
                                                <tr>
                                                    <!--th class="timetitle">{{: i <= 12 ? i : i - 12 }} {{: i < 12 ? "AM" : "PM"}}</th-->
                                                    <th class="timetitle"></th>
                                                    <td class="time-{{: i}}-0"> </td>
                                                </tr>
                                                <tr>
                                                    <!--th class="timetitle">{{: i <= 12 ? i : i - 12 }}:30 {{: i < 12 ? "AM" : "PM"}}</th-->
                                                    <th class="timetitle"></th>
                                                    <td class="time-{{: i}}-30"> </td>
                                                </tr>
                                                {{ } }}
                                                <tr>
                                                    <!--th class="timetitle">Después de las 10 PM</th-->
                                                    <th class="timetitle"></th>
                                                    <td class="time-22-0"> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                            {{ } }}
                        </table>
                    </script>
                    <script>
                        var $currentPopover = null;
                        $(document).on('shown.bs.popover', function (ev) {
                            var $target = $(ev.target);
                            if ($currentPopover && ($currentPopover.get(0) != $target.get(0))) {
                                $currentPopover.popover('toggle');
                            }
                            $currentPopover = $target;
                            }).on('hidden.bs.popover', function (ev) {
                            var $target = $(ev.target);
                            if ($currentPopover && ($currentPopover.get(0) == $target.get(0))) {
                                $currentPopover = null;
                            }
                        });

                        //quicktmpl is a simple template language I threw together a while ago; it is not remotely secure to xss and probably has plenty of bugs that I haven't considered, but it basically works
                        //the design is a function I read in a blog post by John Resig (http://ejohn.org/blog/javascript-micro-templating/) and it is intended to be loosely translateable to a more comprehensive template language like mustache easily
                        $.extend({
                            quicktmpl: function (template) {return new Function("obj","var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('"+template.replace(/[\r\t\n]/g," ").split("{{").join("\t").replace(/((^|\}\})[^\t]*)'/g,"$1\r").replace(/\t:(.*?)\}\}/g,"',$1,'").split("\t").join("');").split("}}").join("p.push('").split("\r").join("\\'")+"');}return p.join('');")}
                        });

                        $.extend(Date.prototype, {
                            //provides a string that is _year_month_day, intended to be widely usable as a css class
                            toDateCssClass:  function () { 
                            return '_' + this.getFullYear() + '_' + (this.getMonth() + 1) + '_' + this.getDate(); 
                            },
                            //this generates a number useful for comparing two dates; 
                            toDateInt: function () { 
                            return ((this.getFullYear()*12) + this.getMonth())*32 + this.getDate(); 
                            },
                            toTimeString: function() {
                            var hours = this.getHours(),
                                minutes = this.getMinutes(),
                                hour = (hours > 12) ? (hours - 12) : hours,
                                ampm = (hours >= 12) ? ' pm' : ' am';
                            if (hours === 0 && minutes===0) { return ''; }
                            if (minutes > 0) {
                                return hour + ':' + minutes + ampm;
                            }
                            return hour + ampm;
                            }
                        });


                        (function ($) {
                            //t here is a function which gets passed an options object and returns a string of html. I am using quicktmpl to create it based on the template located over in the html block
                            var t = $.quicktmpl($('#tmpl').get(0).innerHTML);
                            
                            function calendar($el, options) {
                            //actions aren't currently in the template, but could be added easily...
                            $el.on('click', '.js-cal-prev', function () {
                                switch(options.mode) {
                                case 'year': options.date.setFullYear(options.date.getFullYear() - 1); break;
                                case 'month': options.date.setMonth(options.date.getMonth() - 1); break;
                                case 'week': options.date.setDate(options.date.getDate() - 7); break;
                                case 'day':  options.date.setDate(options.date.getDate() - 1); break;
                                }
                                draw();
                            }).on('click', '.js-cal-next', function () {
                                switch(options.mode) {
                                case 'year': options.date.setFullYear(options.date.getFullYear() + 1); break;
                                case 'month': options.date.setMonth(options.date.getMonth() + 1); break;
                                case 'week': options.date.setDate(options.date.getDate() + 7); break;
                                case 'day':  options.date.setDate(options.date.getDate() + 1); break;
                                }
                                draw();
                            }).on('click', '.js-cal-option', function () {
                                var $t = $(this), o = $t.data();
                                if (o.date) { o.date = new Date(o.date); }
                                $.extend(options, o);
                                draw();
                            }).on('click', '.js-cal-years', function () {
                                var $t = $(this), 
                                    haspop = $t.data('popover'),
                                    s = '', 
                                    y = options.date.getFullYear() - 2, 
                                    l = y + 5;
                                if (haspop) { return true; }
                                for (; y < l; y++) {
                                s += '<button type="button" class="btn btn-default btn-lg btn-block js-cal-option" data-date="' + (new Date(y, 1, 1)).toISOString() + '" data-mode="year">'+y + '</button>';
                                }
                                $t.popover({content: s, html: true, placement: 'auto top'}).popover('toggle');
                                return false;
                            }).on('click', '.event', function () {
                                var $t = $(this), 
                                    index = +($t.attr('data-index')), 
                                    haspop = $t.data('popover'),
                                    data, time;
                                
                                if (haspop || isNaN(index)) { return true; }
                                data = options.data[index];
                                time = data.start.toTimeString();
                                if (time && data.end) { time = time + ' - ' + data.end.toTimeString(); }

                                verEvento(data.id)
                                
                                //$t.data('popover',true);
                                //$t.popover({content: '<p><strong>' + time + '</strong></p>'+data.text, html: true, placement: 'auto left'}).popover('toggle');
                                return false;
                            });
                            function dayAddEvent(index, event) {
                                if (!!event.allDay) {
                                monthAddEvent(index, event);
                                return;
                                }
                                var $event = $('<div/>', {'class': 'event', text: event.title, title: event.title, 'data-index': index}),
                                    start = event.start,
                                    end = event.end || start,
                                    time = event.start.toTimeString(),
                                    hour = start.getHours(),
                                    timeclass = '.time-22-0',
                                    startint = start.toDateInt(),
                                    dateint = options.date.toDateInt(),
                                    endint = end.toDateInt();
                                if (startint > dateint || endint < dateint) { return; }
                                
                                if (!!time) {
                                $event.html('<strong>' + time + '</strong> ' + $event.html());
                                }
                                $event.toggleClass('begin', startint === dateint);
                                $event.toggleClass('end', endint === dateint);
                                if (hour < 6) {
                                timeclass = '.time-0-0';
                                }
                                if (hour < 22) {
                                timeclass = '.time-' + hour + '-' + (start.getMinutes() < 30 ? '0' : '30');
                                }
                                $(timeclass).append($event);
                            }
                            function monthAddEvent(index, event) {
                                var $event = $('<div/>', {'class': 'event', text: event.title, title: event.title, 'data-index': index}),
                                    e = new Date(event.start),
                                    dateclass = e.toDateCssClass(),
                                    day = $('.' + e.toDateCssClass()),
                                    empty = $('<div/>', {'class':'clear event', html:' '}), 
                                    numbevents = 0, 
                                    time = event.start.toTimeString(),
                                    endday = event.end && $('.' + event.end.toDateCssClass()).length > 0,
                                    checkanyway = new Date(e.getFullYear(), e.getMonth(), e.getDate()+40),
                                    existing,
                                    i;
                                $event.toggleClass('all-day', !!event.allDay);
                                if (!!time) {
                                $event.html('<strong>' + time + '</strong> ' + $event.html());
                                }
                                if (!event.end) {
                                $event.addClass('begin end');
                                $('.' + event.start.toDateCssClass()).append($event);
                                return;
                                }
                                    
                                while (e <= event.end && (day.length || endday || options.date < checkanyway)) {
                                if(day.length) {
                                    existing = day.find('.event').length;
                                    numbevents = Math.max(numbevents, existing);
                                    for(i = 0; i < numbevents - existing; i++) {
                                    day.append(empty.clone());
                                    }
                                    day.append(
                                        $event.
                                        toggleClass('begin', dateclass === event.start.toDateCssClass()).
                                        toggleClass('end', dateclass === event.end.toDateCssClass())
                                    );
                                    $event = $event.clone();
                                    $event.html();
                                }
                                e.setDate(e.getDate() + 1);
                                dateclass = e.toDateCssClass();
                                day = $('.' + dateclass);
                                }
                            }
                            function yearAddEvents(events, year) {
                                var counts = [0,0,0,0,0,0,0,0,0,0,0,0];
                                $.each(events, function (i, v) {
                                if (v.start.getFullYear() === year) {
                                    counts[v.start.getMonth()]++;
                                }
                                });
                                $.each(counts, function (i, v) {
                                if (v!==0) {
                                    $('.month-'+i).append('<span class="badge">'+v+'</span>');
                                }
                                });
                            }
                            function draw() {
                                $el.html(t(options));
                                //potential optimization (untested), this object could be keyed into a dictionary on the dateclass string; the object would need to be reset and the first entry would have to be made here
                                $('.' + (new Date()).toDateCssClass()).addClass('today');
                                if (options.data && options.data.length) {
                                if (options.mode === 'year') {
                                    yearAddEvents(options.data, options.date.getFullYear());
                                } else if (options.mode === 'month' || options.mode === 'week') {
                                    $.each(options.data, monthAddEvent);
                                } else {
                                    $.each(options.data, dayAddEvent);
                                }
                                }
                            }
                            
                            draw();    
                            }
                            
                            ;(function (defaults, $, window, document) {
                            $.extend({
                                calendar: function (options) {
                                return $.extend(defaults, options);
                                }
                            }).fn.extend({
                                calendar: function (options) {
                                options = $.extend({}, defaults, options);
                                return $(this).each(function () {
                                    var $this = $(this);
                                    calendar($this, options);
                                });
                                }
                            });
                            })({
                            days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                            shortMonths: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                            date: (new Date()),
                                daycss: ["c-sunday", "", "", "", "", "", "c-saturday"],
                                todayname: "Hoy",
                                thismonthcss: "current",
                                lastmonthcss: "outside",
                                nextmonthcss: "outside",
                            mode: "month",
                            data: []
                            }, jQuery, window, document);
                            
                        })(jQuery);

                        var data = <?= json_encode($data_calendario) ?>;
                        // Convertir las fechas en objetos Date
                        data.forEach(function(evento) {
                            evento.start = new Date(evento.start);
                            if (evento.end) {
                                evento.end = new Date(evento.end);
                            }
                        });

                        console.log(data);
                        //Actually do everything
                        $('#holder').calendar({
                            data: data
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>
<script>
    function verEvento(id){
        //alert(id)
        $.get("calendario-get.php?id="+id, data,
            function (data, textStatus, jqXHR) {
                $('#publicaNueva').modal('show');
                $('#fecha_ini').val(data.fecha_ini);
                $('#fecha_fin').val(data.fecha_fin);
                $('#titulo').val(data.titulo);
                $('#texto').val(data.texto);
            },
            "json"
        );
    }
</script>

</html>