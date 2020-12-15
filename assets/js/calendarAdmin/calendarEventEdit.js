import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import frLocale from '@fullcalendar/core/locales/fr';

import { formatISO, addMinutes } from 'date-fns';

import Swal from 'sweetalert2';

import '../../css/calendar.css';

const addEvent = async (payload) => {
    // eslint-disable-next-line no-undef
    const response = await fetch(`/calendar/${calendarId}/events`, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        throw new Error(message);
    }

    return response.json();
};

const editEvent = async (id, payload) => {
    const response = await fetch(`/event/${id}`, {
        method: 'PUT',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        throw new Error(message);
    }
};

const deleteEvent = async (id) => {
    const response = await fetch(`/event/${id}`, {
        method: 'DELETE',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        throw new Error(message);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        eventSources: [
            {
                // eslint-disable-next-line no-undef
                url: `/calendar/${calendarId}/events`,
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
        editable: true,
        nowIndicator: true,
        eventClassNames: (arg) => {
            if (arg.event.extendedProps.user !== null) {
                return ['assigned-event'];
            }
            return ['free-event'];
        },
        eventDrop: async (info) => {
            let eventId = info.event.id;

            if (info.event.id === '') {
                eventId = info.event.extendedProps.id;
            }

            await editEvent(eventId,
                { start: formatISO(info.event.start), end: formatISO(info.event.end) });
        },
        eventResize: async (info) => {
            let eventId = info.event.id;

            if (info.event.id === '') {
                eventId = info.event.extendedProps.id;
            }
            await editEvent(eventId,
                { start: formatISO(info.event.start), end: formatISO(info.event.end) });
        },
        eventClick: async (info) => {
            if (!info.jsEvent.target.classList.contains('fc-bg-event')) {
                let eventId = info.event.id;

                if (info.event.id === '') {
                    eventId = info.event.extendedProps.id;
                }
                const input = await Swal.fire({
                    title: 'Confirmer la suppression ?',
                    text: 'La supression d\'un créneau est définitif.',
                    icon: 'warning',
                    confirmButtonText: 'Valider',
                    showCancelButton: true,
                    cancelButtonText: 'Annuler',
                });
                if (input.isConfirmed) {
                    await deleteEvent(eventId);
                    info.event.remove();
                }
            }
        },
        dateClick: async (info) => {
            const start = info.date;
            const end = addMinutes(info.date, 30);
            const newEvent = calendar.addEvent({
                start,
                end,
            });

            const response = await addEvent({ start: formatISO(start), end: formatISO(end) });
            newEvent.setProp('id', response.id);
            newEvent.setExtendedProp('id', response.id);
        },
    });

    calendar.render();
});
