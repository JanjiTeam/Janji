<?php

namespace App\Controller;

use App\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * Used in AJAX call in the calendar edit.
     *
     * @Route("/{id}", name="event_edit", methods={"PUT"})
     * @IsGranted("ROLE_PRO")
     */
    public function editEvent(Request $request, Event $event, SerializerInterface $serializer): JsonResponse
    {
        if ($event->getCalendar()->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $json = json_decode($request->getContent(), false);
        $event->setStart(new \DateTimeImmutable($json->start));
        $event->setEnd(new \DateTimeImmutable($json->end));
        $this->getDoctrine()->getManager()->flush();

        $eventSerialized = $serializer->serialize($event, 'json', ['groups' => 'get_events']);

        return new JsonResponse($eventSerialized, 200, [], true);
    }

    /**
     * Used in AJAX call in the calendar edit.
     *
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     * @IsGranted("ROLE_PRO")
     */
    public function deleteEvent(Event $event): JsonResponse
    {
        if ($event->getCalendar()->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        return new JsonResponse();
    }
}
