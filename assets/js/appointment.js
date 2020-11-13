import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import frLocale from '@fullcalendar/core/locales/fr';

document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        // eslint-disable-next-line no-undef
        events: `/calendar/${calendarId}/events`,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek',
        },
        locale: frLocale,
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'timeGridWeek',
        displayEventEnd: true,
        editable: false,
        eventClick: (info) => {
            console.log(info);
        },

    });

    calendar.render();
});
