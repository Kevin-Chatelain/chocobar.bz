<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/recipe', name: 'recipe.index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, RecipeRepository $repository, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10 
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/recipe/new', name: 'recipe.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
          $recipe = $form->getData();
          $manager->persist($recipe);
          $manager->flush();

          $this->addFlash('success', 'Recette créée avec succès !');

          return $this->redirectToRoute('recipe.index');
        }
        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") and user == subject.getUser()'),
        subject: 'recipe'
    )]
    #[Route('/recipe/modify/{id}', 'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager) : Response {
        
        $form = $this->createForm(recipeType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
          $recipe = $form->getData();
          $recipe->setUser($this->getUser());
          $manager->persist($recipe);
          $manager->flush();
          $this->addFlash('success', 'Recette modifiée avec succès !');
          return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form,
            'recipe' => $recipe
        ]);
    }

    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") and user == subject.getUser()'),
        subject: 'recipe'
    )]
    #[Route('/recipe/delete/{id}', 'recipe.delete', methods: ['POST', 'GET'])]
    public function delete(EntityManagerInterface $manager, Recipe $recipe) : Response {
        if($recipe) {
            $manager->remove($recipe);
            $manager->flush();
            $this->addFlash('success', 'Recette supprimé avec succès !');
            return $this->redirectToRoute('recipe.index');
        } else {
            $this->addFlash('warning', 'La recette n\'exsite pas');
            return $this->redirectToRoute('recipe.index');
        }
       
    }
}
