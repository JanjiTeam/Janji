<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\CurrentPasswordType;
use App\Form\UserInformationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="profile")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $userInformationForm = $this->createForm(UserInformationType::class, $user);
        $userInformationForm->handleRequest($request);

        $changePasswordFrom = $this->createForm(ChangePasswordType::class);
        $changePasswordFrom->handleRequest($request);

        if ($userInformationForm->isSubmitted() && $userInformationForm->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        if ($changePasswordFrom->isSubmitted() && $changePasswordFrom->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $changePasswordFrom->get('plainPassword')->getData()
                )
            );
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('profile/index.html.twig', [
            'userInformationForm' => $userInformationForm->createView(),
            'changePasswordFrom' => $changePasswordFrom->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="delete_account")
     */
    public function deleteProfile(Request $request)
    {
        $deleteForm = $this->createForm(CurrentPasswordType::class);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $user = $this->getUser();
            if ($user) {
                $this->getDoctrine()->getManager()->remove($user);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('app_logout');
            }
        }

        return $this->render('profile/delete.html.twig', [
           'deleteForm' => $deleteForm->createView(),
        ]);
    }
}
