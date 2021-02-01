import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import frLocale from '@fullcalendar/core/locales/fr';

const baseOptions = {
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
};

export default baseOptions;
