<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/{id}", name="event_edit", methods={"PUT"})
     */
    public function editEvent(Request $request, Event $event, SerializerInterface $serializer): JsonResponse
    {
        $json = json_decode($request->getContent(), false);
        $event->setStart(new \DateTimeImmutable($json->start));
        $event->setEnd(new \DateTimeImmutable($json->end));
        $this->getDoctrine()->getManager()->flush();

        $eventSerialized = $serializer->serialize($event, 'json', ['groups' => 'get_events']);

        return new JsonResponse($eventSerialized, 200, [], true);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public function deleteEvent(Event $event): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        return new JsonResponse();
    }
}
