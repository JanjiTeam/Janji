import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin, { Draggable } from '@fullcalendar/interaction';

import frLocale from '@fullcalendar/core/locales/fr';

import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

import Swal from 'sweetalert2';

const selectedEvent = null;
const appointmentDisplayBlockEl = document.getElementById('appointment-display-block');
appointmentDisplayBlockEl.classList.add('hidden');

const updateAppointmentDisplay = (event) => {
    appointmentDisplayBlockEl.classList.remove('hidden');
    const appointmentDisplayEl = document.getElementById('appointment-display');
    const date = format(event.start, 'EEEE d LLLL Y', { locale: fr });
    const start = format(event.start, 'p', { locale: fr });
    const end = format(event.end, 'p', { locale: fr });
    appointmentDisplayEl.innerHTML = `Le <span class="font-bold capitalize">${date}</span><br> de <span class="font-bold">${start}</span> à <span class="font-bold">${end}</span>`;
};

document.addEventListener('DOMContentLoaded', () => {
    const draggableEl = document.getElementById('drag-events-wrapper');

    const drag = new Draggable(draggableEl, {
        itemSelector: '.drag-event',
        eventData: (eventEl) => ({
            duration: { minutes: eventEl.dataset.duration },
            constraint: 'slot',
        }),
    });

    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        eventSources: [
            {
                // eslint-disable-next-line no-undef
                url: `/calendar/${calendarId}/events`,
                method: 'GET',
                constraint: 'slot',
            },
            {
                // eslint-disable-next-line no-undef
                url: `/calendar/${calendarId}/slots`,
                display: 'background',
                eventDataTransform: (eventData) => ({ ...eventData, groupId: 'slot' }),
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
        droppable: true,
        allDaySlot: false,
        // eventClick: (info) => {
        //     selectedEvent = info.event;
        //     calendar.getEvents().forEach((e) => {
        //         e.setProp('color', 'blue');
        //     });
        //     info.event.setProp('color', 'green');
        //     updateAppointmentDisplay(selectedEvent);
        //     document.querySelector('input[name="appointment[event]"]').value = selectedEvent.id;
        // },

    });

    calendar.render();

    document.querySelector('form[name="appointment"]').addEventListener('submit', (e) => {
        e.preventDefault();
        if (selectedEvent !== null) {
            e.target.submit();
        } else {
            Swal.fire({
                title: 'Veuillez choisir un créneau disponible',
                icon: 'warning',
                confirmButtonText: 'OK',
            });
        }
    });
});
