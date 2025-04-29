<?php
// src/Controller/CategoryController.php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'categories', methods: ['GET'])]
    public function list(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();

        // Повертаємо просто масив назв
        $categoryNames = array_map(fn($category) => $category->getName(), $categories);

        return $this->json($categoryNames);
    }
}
