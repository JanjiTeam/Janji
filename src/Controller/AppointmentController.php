<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Event;
use App\Entity\User;
use App\Form\AppointmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appointment")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AppointmentController extends AbstractController
{
    /**
     * @Route("/", name="appointment")
     */
    public function index(): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $pros = $userRepository->findByRoleWithCalendar('ROLE_PRO');

        return $this->render('appointment/index.html.twig', [
            'pros' => $pros,
        ]);
    }

    /**
     * @Route("/pro/{id}", name="appointment_pro")
     */
    public function appointmentPro($id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->redirectToRoute('appointment');
        }

        return $this->render('appointment/pro.html.twig', [
            'pro' => $user,
        ]);
    }

    /**
     * @Route("/calendar/{id}", name="appointment_calendar")
     */
    public function appointmentCalendar(Request $request, $id): Response
    {
        $calendar = $this->getDoctrine()->getRepository(Calendar::class)->find($id);
        if (!$calendar) {
            return $this->redirectToRoute('appointment');
        }

        $form = $this->createForm(AppointmentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $eventId = $formData['id'];
            $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

            if ($event && $event->getUser() === null) {
                $event->setUser($this->getUser());
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('appointment_confirmation');
            }
        }

        return $this->render('appointment/calendar.html.twig', [
            'calendar' => $calendar,
            'appointmentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm", name="appointment_confirmation", methods={"GET"})
     */
    public function appointmentConfirmation()
    {
        return $this->render('appointment/confirmation.html.twig');
    }
}
