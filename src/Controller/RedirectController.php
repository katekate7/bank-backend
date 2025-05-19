<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController
{
    #[Route('/', name: 'redirect_to_login')]
    public function redirectToHome(): RedirectResponse
    {
        return new RedirectResponse('/login');
    }
}
