<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security.login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/logout', 'security.logout', methods: ['GET'])]
    public function logout() {}

    #[Route('/register', 'security.register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $manager): Response {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
          $recipe = $form->getData();
          $manager->persist($recipe);
          $manager->flush();
          return $this->redirectToRoute('security.login');
        }
        return $this->render('pages/security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
