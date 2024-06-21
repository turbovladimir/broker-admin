<?php

namespace App\Controller\Loan;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    #[Route('r', name: 'redirect')]
    public function redirect() : Response
    {

    }
}