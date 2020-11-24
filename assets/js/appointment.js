import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin, { Draggable } from '@fullcalendar/interaction';

import frLocale from '@fullcalendar/core/locales/fr';

import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

import Swal from 'sweetalert2';

import '../css/calendar.css';

let selectedEvent = null;
const appointmentDisplayBlockEl = document.getElementById('appointment-display-block');
appointmentDisplayBlockEl.classList.add('hidden');

const draggableEl = document.getElementById('drag-events-wrapper');

const updateAppointmentDisplay = (event) => {
    draggableEl.classList.add('hidden');
    appointmentDisplayBlockEl.classList.remove('hidden');
    const appointmentDisplayEl = document.getElementById('appointment-display');
    const date = format(event.start, 'EEEE d LLLL Y', { locale: fr });
    const start = format(event.start, 'p', { locale: fr });
    const end = format(event.end, 'p', { locale: fr });
    appointmentDisplayEl.innerHTML = `Le <span class="font-bold capitalize">${date}</span><br> de <span class="font-bold">${start}</span> à <span class="font-bold">${end}</span>`;
};

const updateEventForm = (event) => {
    const eventStartInput = document.getElementById('event_start');
    const eventEndInput = document.getElementById('event_end');

    eventStartInput.value = event.startStr;
    eventEndInput.value = event.endStr;
};

document.addEventListener('DOMContentLoaded', () => {
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
                editable: false,
                className: 'assigned-event',
                overlap: false,
                eventDataTransform: (eventData) => ({ ...eventData, groupId: 'assigned-event' }),
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
        eventDurationEditable: false,
        editable: true,
        droppable: true,
        allDaySlot: false,
        eventReceive: (info) => {
            selectedEvent = info.event;
            updateAppointmentDisplay(selectedEvent);
            updateEventForm(selectedEvent);
        },
        eventDrop: (info) => {
            selectedEvent = info.event;
            updateAppointmentDisplay(selectedEvent);
            updateEventForm(selectedEvent);
        },
        eventClick: (info) => {
            if (!info.jsEvent.target.classList.contains('fc-bg-event') && info.event.groupId !== 'assigned-event') {
                info.event.remove();
                selectedEvent = null;
                draggableEl.classList.remove('hidden');
                appointmentDisplayBlockEl.classList.add('hidden');
            }
        },
    });

    calendar.render();

    document.querySelector('form[name="event"]').addEventListener('submit', (e) => {
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
    document.querySelector('form[name="event"]').addEventListener('reset', () => {
        selectedEvent.remove();
        selectedEvent = null;
        draggableEl.classList.remove('hidden');
        appointmentDisplayBlockEl.classList.add('hidden');
    });
});
