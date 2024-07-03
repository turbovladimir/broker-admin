<?php

namespace App\Controller\Loan;

use App\Controller\Session;
use App\Entity\Contact;
use App\Enums\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    #[Route('r/{contact}', name: 'redirect')]
    public function redirectAction(Contact $contact, Request $request) : Response
    {
        $request->getSession()->set(Session::Contact->value, $contact->getContactId());
        $request->getSession()->set(Session::RegistrationType->value, RegistrationType::Short->value);

        return $this->render('@loan/redirect.html.twig');
    }
}