<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientType;
use App\Repository\IngredientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController {
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientsRepository $repository, PaginatorInterface $paginator, Request $request): Response {

        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10 
        );

        return $this->render('pages/ingredient/index.html.twig', [
            "ingredients" => $ingredients
        ]);
    }

    #[Route('/ingredient/new', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response {
        $ingredient = new Ingredients();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
          $ingredient = $form->getData();
          $manager->persist($ingredient);
          $manager->flush();

          $this->addFlash('success', 'Ingrédient crée avec succès !');

          return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/modify/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Ingredients $ingredient, Request $request, EntityManagerInterface $manager) : Response {
        
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
          $ingredient = $form->getData();
          $manager->persist($ingredient);
          $manager->flush();
          $this->addFlash('success', 'Ingrédient modifié avec succès !');
          return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form,
            'ingredient' => $ingredient
        ]);
    }

    #[Route('/ingredient/delete/{id}', 'ingredient.delete', methods: ['POST', 'GET'])]
    public function delete(EntityManagerInterface $manager, Ingredients $ingredient) : Response {
        if($ingredient) {
            $manager->remove($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Ingrédient supprimé avec succès !');
            return $this->redirectToRoute('ingredient.index');
        } else {
            $this->addFlash('warning', 'L\'ingrédient n\'exsite pas');
            return $this->redirectToRoute('ingredient.index');
        }
       
    }
}
