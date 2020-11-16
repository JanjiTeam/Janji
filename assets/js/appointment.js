import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import frLocale from '@fullcalendar/core/locales/fr';

import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

let selectedEvent = null;

const updateAppointmentDisplay = (event) => {
    const appointmentDisplayEl = document.getElementById('appointment-display');
    const date = format(event.start, 'EEEE d LLLL Y', { locale: fr });
    const start = format(event.start, 'p', { locale: fr });
    const end = format(event.end, 'p', { locale: fr });
    appointmentDisplayEl.innerHTML = `Le ${date} de ${start} à ${end}`;
};

document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        eventSources: [
            {
                // eslint-disable-next-line no-undef
                url: `/calendar/${calendarId}/events`,
                method: 'GET',
                extraParams: {
                    free: 1,
                },
            },
        ],
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
            selectedEvent = info.event;
            calendar.getEvents().forEach((e) => {
                e.setProp('color', 'blue');
            });
            info.event.setProp('color', 'green');
            updateAppointmentDisplay(selectedEvent);
            document.querySelector('input[name="appointment[event]"]').value = selectedEvent.id;
        },

    });

    calendar.render();

    document.querySelector('form[name="appointment"]').addEventListener('submit', (e) => {
        e.preventDefault();
        if (selectedEvent !== null) {
            e.target.submit();
        } else {
            alert('Veuillez choisir un créneau disponible');
        }
    });
});
