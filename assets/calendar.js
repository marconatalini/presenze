import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';


document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    let urlJson = document.getElementById('urlTimbratureJson');

    let calendar = new Calendar(calendarEl, {
        locale: 'it',
        timeZone: 'Europe/Rome',
        contentHeight: 'auto',
        plugins: [ dayGridPlugin, bootstrap5Plugin],
        themeSystem: 'bootstrap5',
        initialView: 'dayGridMonth',
        // forceEventDuration: true,
        // defaultTimedEventDuration: '1:00',

        headerToolbar: {
            start: 'prev', //today
            center: 'title', //title
            end: 'next', //dayGridMonth
        },
        hiddenDays: [ 0 ], //domenica
        fixedWeekCount: false,

        eventSources: [
            {
                url: urlJson.getAttribute('href'),
                method: 'GET',
                failure: function() {
                    alert('there was an error while fetching events!');
                },
            },
        ],

        // eventColor: '#e6e6e6',
        // height: 'parent',
    });

    calendar.render();
    // calendar.updateSize();
});

require('./styles/calendar.css');