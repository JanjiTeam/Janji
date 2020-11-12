import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import frLocale from '@fullcalendar/core/locales/fr';

import { formatISO, addMinutes } from 'date-fns';

import flatpickr from 'flatpickr';
import { French } from 'flatpickr/dist/l10n/fr';

import 'flatpickr/dist/flatpickr.min.css';

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
    flatpickr('.datepicker', {
        locale: French,
        enableTime: true,
        time_24hr: true,
    });

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
        editable: true,
        eventDrop: async (info) => {
            await editEvent(info.event.id,
                { start: formatISO(info.event.start), end: formatISO(info.event.end) });
        },
        eventResize: async (info) => {
            await editEvent(info.event.id,
                { start: formatISO(info.event.start), end: formatISO(info.event.end) });
        },
        eventClick: async (info) => {
            let eventId = info.event.id;

            if (info.event.id === '') {
                eventId = info.event.extendedProps.id;
            }

            // eslint-disable-next-line no-restricted-globals
            if (confirm('Confimer la suppression ?')) {
                await deleteEvent(eventId);
                info.event.remove();
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

    const addEventForm = document.getElementById('add-event-form');
    addEventForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const start = e.target.querySelector('input[name="event[start]"]').value;
        const end = e.target.querySelector('input[name="event[end]"]').value;

        addEvent({ start, end }).then(() => {
            calendar.refetchEvents();
        });
    });
});
