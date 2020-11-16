<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Event;
use App\Form\CalendarType;
use App\Form\EventType;
use App\Repository\CalendarRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{
    /**
     * @Route("/", name="calendar_index", methods={"GET"})
     * @IsGranted("ROLE_PRO")
     */
    public function index(CalendarRepository $calendarRepository): Response
    {
        $user = $this->getUser();

        return $this->render('calendar/index.html.twig', [
            'calendars' => $calendarRepository->findBy(['owner' => $user]),
        ]);
    }

    /**
     * @Route("/new", name="calendar_new", methods={"GET","POST"})
     * @IsGranted("ROLE_PRO")
     */
    public function new(Request $request): Response
    {
        $calendar = new Calendar();

        $form = $this->createForm(CalendarType::class, $calendar);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendar->setOwner($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('calendar/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_show", methods={"GET","POST"})
     * @IsGranted("ROLE_PRO")
     */
    public function edit(Request $request, Calendar $calendar): Response
    {
        $formCalendar = $this->createForm(CalendarType::class, $calendar);
        $formEvent = $this->createForm(EventType::class);
        $formCalendar->handleRequest($request);

        if ($formCalendar->isSubmitted() && $formCalendar->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
            'form' => $formCalendar->createView(),
            'formEvent' => $formEvent->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_delete", methods={"DELETE"})
     * @IsGranted("ROLE_PRO")
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendar_index');
    }

    /**
     * @Route("/{id}/events", name="get_calendar_events", methods={"GET"})
     */
    public function getCalendarEvents(Request $request, Calendar $calendar, SerializerInterface $serializer): JsonResponse
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $free = (bool) $request->query->get('free', false);

        $startDate = null;
        $endDate = null;

        if ($start) {
            $startDate = new \DateTimeImmutable($start);
        }

        if ($end) {
            $endDate = new \DateTimeImmutable($end);
        }

        $events = $this->getDoctrine()->getRepository(Event::class)->findCalendarEventsByPeriod($calendar->getId(), $startDate, $endDate, $free);

        $json = $serializer->serialize($events, 'json', ['groups' => ['get_events']]);

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/{id}/events", name="add_calendar_events", methods={"POST"})
     * @IsGranted("ROLE_PRO")
     */
    public function addCalendarEvents(Request $request, Calendar $calendar, SerializerInterface $serializer): JsonResponse
    {
        $json = json_decode($request->getContent(), false);
        $event = new Event();
        $event->setStart(new \DateTimeImmutable($json->start));
        if (property_exists($json, 'end')) {
            $event->setEnd(new \DateTimeImmutable($json->end));
        } else {
            $event->setEnd($event->getStart()->add(new \DateInterval('PT1H')));
        }

        $calendar->addEvent($event);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $jsonResponse = $serializer->serialize($event, 'json', ['groups' => 'get_events']);

        return new JsonResponse($jsonResponse, 201, [], true);
    }
}
