<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(CategoryRepository $repo): Response
    {
        return $this->json($repo->findAllSorted());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $cat = new Category();
        $cat->setName($data['name']);

        $em->persist($cat);
        $em->flush();

        return $this->json($cat, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Category $cat, Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $cat->setName($data['name'] ?? $cat->getName());

        $em->flush();
        return $this->json($cat);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Category $cat, EntityManagerInterface $em): Response
    {
        $em->remove($cat);
        $em->flush();
        return $this->json(['status' => 'deleted']);
    }
}
