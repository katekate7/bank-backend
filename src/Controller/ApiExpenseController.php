<?php
// src/Controller/Api/ApiExpenseController.php
namespace App\Controller;

use App\Entity\Expense;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiExpenseController extends AbstractController
{
    #[Route('/expense', name: 'api_expense_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        $expense = new Expense();
        $expense->setUser($user);
        $expense->setLabel($data['label'] ?? '');
        $expense->setCategory($data['category'] ?? '');
        $expense->setAmount((float) ($data['amount'] ?? 0));
        $expense->setDate(new \DateTime($data['date'] ?? 'now'));

        $em->persist($expense);
        $em->flush();

        return new JsonResponse(['message' => 'Expense created successfully'], 201);
    }
}
