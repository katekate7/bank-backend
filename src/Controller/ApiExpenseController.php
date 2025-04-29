<?php
// src/Controller/Api/ApiExpenseController.php
namespace App\Controller;

use App\Entity\Expense;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

#[Route('/api')]
class ApiExpenseController extends AbstractController
{
    #[Route('/expense', name: 'api_expense_create', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository): JsonResponse
{
    try {
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

        // Тут ми шукаємо об'єкт Category по імені
        $categoryName = $data['category'] ?? null;
        if ($categoryName) {
            $category = $categoryRepository->findOneBy(['name' => $categoryName]);
            if (!$category) {
                return new JsonResponse(['error' => 'Category not found'], 404);
            }
            $expense->setCategory($category);
        } else {
            return new JsonResponse(['error' => 'Category is required'], 400);
        }

        $expense->setAmount((float) ($data['amount'] ?? 0));

        if (!empty($data['date'])) {
            $expense->setDate(new \DateTimeImmutable($data['date']));
        } else {
            $expense->setDate(new \DateTimeImmutable());
        }

        $em->persist($expense);
        $em->flush();

        return new JsonResponse(['message' => 'Expense created successfully'], 201);

    } catch (\Throwable $e) {
        return new JsonResponse([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
}

}