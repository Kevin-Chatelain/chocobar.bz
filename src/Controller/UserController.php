<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") and user == subject'),
        subject: 'chosenUser'
    )]
    #[Route('/user/edit/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $manager, User $chosenUser, Request $request, UserPasswordHasherInterface $hasher): Response {
        $form = $this->createForm(UserType::class, $chosenUser);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($chosenUser, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Informations enregistrées !');
                return $this->redirectToRoute('recipe.index');
            } else {
                $this->addFlash('warning', 'Mot de passe incorrect');
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") and user == subject'),
        subject: 'chosenUser'
    )]
    #[Route('/user/edit/{id}/password', name: 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(User $chosenUser, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($chosenUser, $form->getData()['plainPassword'])) {
                $chosenUser->settimeUpdate(new DateTimeImmutable());
                $chosenUser->setPlainPassword($form->getData()['newPassword']);
                $manager->persist($chosenUser);
                $manager->flush();
                $this->addFlash('success', 'Nouveau mot de passe enregistré !');
                return $this->redirectToRoute('recipe.index');
            } else {
                $this->addFlash('warning', 'Mot de passe incorrect');
            }
        }
        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
