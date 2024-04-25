<?php

namespace App\Controller\Admin;

use App\Entity\OfferCheckerRelation;
use App\Form\OfferCheckerRelationType;
use App\Repository\OfferCheckerRelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/checker/relations', name: 'admin_checker_relations')]
class OfferCheckerRelationController extends AbstractController
{
    #[Route('/list', name: '_list')]
    public function list(OfferCheckerRelationRepository $repository): Response
    {
        $offers = $repository->findAll();

        return $this->render('admin/checker/relations/list.html.twig', ['entities' => $offers]);
    }

    #[Route('/upsert/{relation}', name: '_upsert')]
    public function upsert(Request $request, EntityManagerInterface $entityManager, ?OfferCheckerRelation $relation = null): Response
    {
        $relation = $relation ?? new OfferCheckerRelation();
        $form = $this->createForm(OfferCheckerRelationType::class, $relation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $relation
                ->setAddedAt(new \DateTime())
            ;

            $entityManager->persist($relation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_checker_relations_list');
        }

        $params = ['form' => $form->createView()];

        if ($relation->getId()) {
            $params['relation'] = $relation;
        }

        return $this->render('@admin/checker/relations/upsert.html.twig', $params);
    }
}
