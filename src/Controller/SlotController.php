<?php

namespace App\Controller;

use App\Entity\Slot;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/slot")
 */
class SlotController extends AbstractController
{
    /**
     * Used in AJAX call in the calendar edit.
     *
     * @Route("/{id}", name="slot_edit", methods={"PUT"})
     * @IsGranted("ROLE_PRO")
     */
    public function editSlot(Request $request, Slot $slot, SerializerInterface $serializer): JsonResponse
    {
        if ($slot->getCalendar()->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $json = json_decode($request->getContent(), false);
        $slot->setStart(new \DateTimeImmutable($json->start));
        $slot->setEnd(new \DateTimeImmutable($json->end));
        $this->getDoctrine()->getManager()->flush();

        $slotSerialized = $serializer->serialize($slot, 'json', ['groups' => 'get_events']);

        return new JsonResponse($slotSerialized, 200, [], true);
    }

    /**
     * Used in AJAX call in the calendar edit.
     *
     * @Route("/{id}", name="slot_delete", methods={"DELETE"})
     * @IsGranted("ROLE_PRO")
     */
    public function deleteSlot(Slot $slot): JsonResponse
    {
        if ($slot->getCalendar()->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($slot);
        $entityManager->flush();

        return new JsonResponse();
    }
}
