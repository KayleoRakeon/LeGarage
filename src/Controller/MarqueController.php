<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarqueController extends AbstractController
{
    /**
     * @Route("/marque", name="marque")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($marque);
            $em->flush();

            $this->addFlash('success', 'Marque ajoutée avec succès !');
        }

        $marque = $em->getRepository(Marque::class)->findAll();

        return $this->render('marque/index.html.twig', [
            'marque' => $marque,
            'ajout' => $form->createView()
        ]);
    }

    /**
     * @Route("/marque/{id}", name="une_marque")
     */
    public function edit(Marque $marque = null, Request $request){
        if($marque == null){
            $this->addFlash('danger', 'La marque demandée n\'existe pas');
            return $this->redirectToRoute('marque');
        }

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush();

            $this->addFlash('success', 'Marque modifiée avec succès !');
        }

        return $this->render('marque/edit.html.twig', [
            'marque' => $marque,
            'edit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/marque/delete/{id}", name="delete_marque")
     */
    public function delete(Marque $marque = null){
        if($marque == null){
            $this->addFlash('danger', 'La marque demandée n\'existe pas');
            return $this->redirectToRoute('marque');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($marque);
        $em->flush();

        $this->addFlash('success', 'Marque supprimmée avec succès !');

        return $this->redirectToRoute('marque');
    }
}
