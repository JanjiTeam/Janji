<?php

namespace App\Controller;

use App\Entity\EventType;
use App\Form\EventTypeType;
use App\Repository\EventTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event/type")
 * @IsGranted("ROLE_PRO")
 */
class EventTypeController extends AbstractController
{
    /**
     * @Route("/", name="event_type_index", methods={"GET"})
     */
    public function index(EventTypeRepository $eventTypeRepository): Response
    {
        return $this->render('event_type/index.html.twig', [
            'event_types' => $eventTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="event_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $eventType = new EventType();
        $form = $this->createForm(EventTypeType::class, $eventType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventType);
            $entityManager->flush();

            return $this->redirectToRoute('event_type_index');
        }

        return $this->render('event_type/new.html.twig', [
            'event_type' => $eventType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EventType $eventType): Response
    {
        $form = $this->createForm(EventTypeType::class, $eventType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_type_index');
        }

        return $this->render('event_type/edit.html.twig', [
            'event_type' => $eventType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EventType $eventType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eventType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_type_index');
    }
}
