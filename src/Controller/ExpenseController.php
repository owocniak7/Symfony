<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/expenses')]
#[IsGranted('ROLE_USER')]
class ExpenseController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(ExpenseRepository $repo): Response
    {
        $expenses = $repo->findByUser($this->getUser());
        return $this->json($expenses);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Expense $expense): Response
    {
        $this->denyAccessUnlessGranted('view', $expense);
        return $this->json($expense);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepo,
        SerializerInterface $serializer
    ): Response {
        $data = json_decode($request->getContent(), true);
        $category = $categoryRepo->find($data['category_id']);

        if (!$category) {
            return $this->json(['error' => 'Invalid category'], 400);
        }

        $expense = new Expense();
        $expense->setAmount($data['amount']);
        $expense->setDescription($data['description']);
        $expense->setDate(new \DateTime($data['date']));
        $expense->setUser($this->getUser());
        $expense->setCategory($category);

        $em->persist($expense);
        $em->flush();

        return $this->json($expense, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Expense $expense,
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepo
    ): Response {
        $this->denyAccessUnlessGranted('edit', $expense);

        $data = json_decode($request->getContent(), true);

        $expense->setAmount($data['amount'] ?? $expense->getAmount());
        $expense->setDescription($data['description'] ?? $expense->getDescription());
        $expense->setDate(new \DateTime($data['date'] ?? $expense->getDate()->format('Y-m-d')));

        if (isset($data['category_id'])) {
            $category = $categoryRepo->find($data['category_id']);
            if ($category) {
                $expense->setCategory($category);
            }
        }

        $em->flush();
        return $this->json($expense);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Expense $expense, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('delete', $expense);
        $em->remove($expense);
        $em->flush();
        return $this->json(['status' => 'deleted']);
    }
}
