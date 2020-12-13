<?php

namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {

        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->Slug($category->getName())));
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();


        return $this->render(
            'category/create.html.twig',
            [
                'formView' => $formView
            ]
        );
    }
    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     * 
     */
    public function edit(Request $request, $id, CategoryRepository $categortRepository, EntityManagerInterface $em, SluggerInterface $slugger, Security $security)
    {
        // $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Vous n'avez pas le droit d'accéder à cette ressource");

        // $user = $security->getUser();
        // if ($user === null) {
        //     return $this->redirectToRoute('security_login');
        // }
        // if ($security->isGranted("ROLE_ADMIN") === false) {

        //     throw new AccessDeniedHttpException("Vous n'avez pas le droit d'accéder à cette ressource");
        // }


        $category = $categortRepository->find($id);
        if (!$category) {
            throw new NotFoundHttpException("cette category n'existe pas");
        }
        // $this->denyAccessUnlessGranted('CAN_EDIT', $category);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->Slug($category->getName())));
            $em->flush();
            return $this->redirectToRoute('homepage');
        }


        $formView = $form->createView();


        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }
}
