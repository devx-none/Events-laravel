@extends('layouts.main')
@extends('layouts.nav')

@section('content')
<div class="row">
    <div class='mx-auto my-4'>
        Timezone:
        <select id='time-zone-selector'>
            <option value='local'>local</option>
            <option value='UTC' selected>UTC</option>
        </select>
    </div>


    <div class="col-md-10 mx-auto my-4">


        <div id='calendar'></div>
       

    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var initialTimeZone = 'UTC';
        var timeZoneSelectorEl = document.getElementById('time-zone-selector');
        var calendarEl = document.getElementById('calendar'); //your dive id of calendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: initialTimeZone,
            plugins: ['interaction', 'dayGrid'],
            header: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            defaultDate: `{{date('Y-m-d')}}`,
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            selectable: true,
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                timeZoneName: 'short'
            },

            select: function(selectionInfo) { //trigger select event
                let title = prompt("Entrer le titre de l'événement : ");
                let description = prompt("Entrer la description de l'événement : ");
                let c = prompt('Voulez-vous avoir une couleur de fond pour votre event oui/non : ');
                let bgcolor;

                switch (c) {
                    case 'non':
                        bgcolor = 'blue';
                        break;
                    case 'oui':
                        color = prompt("Entrez la couleur que vous voulez sur le fond...");
                        bgcolor = color;
                }
                let formatted_start_date = selectionInfo.start.getFullYear() + "-" + (selectionInfo.start.getMonth() + 1) + "-" + selectionInfo.start.getDate() + " " + selectionInfo.start.getHours() + ":" + selectionInfo.start.getMinutes() + ":" + selectionInfo.start.getSeconds();
                let formatted_end_date = selectionInfo.end.getFullYear() + "-" + (selectionInfo.end.getMonth() + 1) + "-" + selectionInfo.end.getDate() + " " + selectionInfo.end.getHours() + ":" + selectionInfo.end.getMinutes() + ":" + selectionInfo.end.getSeconds();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "/events",
                    data: {
                        title: title,
                        start_date: formatted_start_date,
                        end_date: formatted_end_date,
                        description: description,
                        color: bgcolor
                    },
                    success: function() {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Événement ajouté',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    }
                });
            },
            eventLimit: true, // allow "more" link when too many events
            events: '/api/events',
            eventClick: function(info) { // trigger click event
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-info mx-2',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Veuillez choisir une action',
                    icon: 'info',
                    showCancelButton: true,
                    cancelButtonText: 'Supprimer',
                    confirmButtonText: 'Voir',
                    reverseButtons: false
                }).then((result) => {
                    if (result.value) {
                        let link = `http://127.0.0.1:8000/events/${info.event.id}`;
                        window.location.href = link;


                    } else {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'DELETE',
                            url: `/events/${info.event.id}`,
                            success: function() {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Événement supprimé',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                location.reload();
                            }
                        });
                    }
                })
            },
            eventDrop: function(info) { //trigger drop event
                let formatted_start_date = info.event.start.getFullYear() + "-" + (info.event.start.getMonth() + 1) + "-" + info.event.start.getDate() + " " + info.event.start.getHours() + ":" + info.event.start.getMinutes() + ":" + info.event.start.getSeconds();
                let formatted_end_date = info.event.end.getFullYear() + "-" + (info.event.end.getMonth() + 1) + "-" + info.event.end.getDate() + " " + info.event.end.getHours() + ":" + info.event.end.getMinutes() + ":" + info.event.end.getSeconds();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'PUT',
                    url: `/events/${info.event.id}`,
                    data: {
                        title: info.event.title,
                        start: formatted_start_date,
                        end: formatted_end_date
                    },
                    success: function() {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Événement modifié',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            },
            eventResize: function(eventResizeInfo) { //trigger event resize
                let formatted_start_date = eventResizeInfo.event.start.getFullYear() + "-" + (eventResizeInfo.event.start.getMonth() + 1) + "-" + eventResizeInfo.event.start.getDate() + " " + eventResizeInfo.event.start.getHours() + ":" + eventResizeInfo.event.start.getMinutes() + ":" + eventResizeInfo.event.start.getSeconds();
                let formatted_end_date = eventResizeInfo.event.end.getFullYear() + "-" + (eventResizeInfo.event.end.getMonth() + 1) + "-" + eventResizeInfo.event.end.getDate() + " " + eventResizeInfo.event.end.getHours() + ":" + eventResizeInfo.event.end.getMinutes() + ":" + eventResizeInfo.event.end.getSeconds();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'PUT',
                    url: `/events/${eventResizeInfo.event.id}`,
                    data: {
                        title: eventResizeInfo.event.title,
                        start: formatted_start_date,
                        end: formatted_end_date
                    },
                    success: function() {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Événement modifié',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
        });

        FullCalendar.requestJson('GET', 'https://fullcalendar.io/api/demo-feeds/timezones.json', {}, function(timeZones) {
            timeZones.forEach(function(timeZone) {
                var optionEl;

                if (timeZone !== 'UTC') { // UTC is already in the list
                    optionEl = document.createElement('option');
                    optionEl.value = timeZone;
                    optionEl.innerText = timeZone;
                    timeZoneSelectorEl.appendChild(optionEl);
                }
            });
        }, function() {
            // failure

        });
        // when the timezone selector changes, dynamically change the calendar option
        timeZoneSelectorEl.addEventListener('change', function() {
            calendar.setOption('timeZone', this.value);
        });
        // calendar.setOption('locale', 'fr');
        calendar.render();
        document.querySelector('.fc-dayGridMonth-button').innerHTML = "Mois";
        document.querySelector('.fc-dayGridWeek-button').innerHTML = "Semaine";
        document.querySelector('.fc-dayGridDay-button').innerHTML = "Jour";
        document.querySelector('.fc-today-button').innerHTML = "Ajourd'hui";
    });
</script>
@endsection
