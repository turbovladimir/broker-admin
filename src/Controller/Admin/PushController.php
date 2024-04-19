<?php

namespace App\Controller\Admin;

use App\Entity\Push;
use App\Form\PushType;
use App\Repository\PushRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/push', name: 'admin_push_')]
class PushController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(PushRepository $repository): Response
    {
        $pushes = $repository->findAll();

        return $this->render('admin/push/list.html.twig', ['pushes' => $pushes]);
    }

    #[Route('/upsert/{push}', name: 'upsert')]
    public function upsert(Request $request, EntityManagerInterface $entityManager, ?Push $push = null): Response
    {
        $push = $push ?? new Push();
        $form = $this->createForm(PushType::class, $push);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $push
                ->setAddedAt(new \DateTime())
                ->setIsActive(true)
            ;

            $entityManager->persist($push);
            $entityManager->flush();

            return $this->redirectToRoute('admin_push_list');
        }

        $params = ['form' => $form->createView()];

        if ($push->getId()) {
            $params['push'] = $push;
        }

        return $this->render('@admin/push/upsert.html.twig', $params);
    }
}