<?php

namespace App\Controller;

use App\Entity\Modele;
use App\Form\ModeleType;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModeleController extends AbstractController
{
    /**
     * @Route("/modele", name="modele")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $modele = new Modele();
        $form = $this->createForm(ModeleType::class, $modele);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($modele);
            $em->flush();

            $this->addFlash('success', 'Modèle ajouté avec succès !');
        }

        $modele = $em->getRepository(Modele::class)->findAll();

        return $this->render('modele/index.html.twig', [
            'modele' => $modele,
            'ajout' => $form->createView()
        ]);
    }

    /**
     * @Route("/modele/{id}", name="un_modele")
     */
    public function edit(Modele $modele = null, Request $request){
        if($modele == null){
            $this->addFlash('danger', 'Le modèle renseignée est introuvable.');
            return $this->redirectToRoute('modele');
        }

        $form = $this->createForm(ModeleType::class, $modele);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($modele);
            $em->flush();

            $this->addFlash('success', 'Modèle modifié avec succès !');
        }

        return $this->render('modele/edit.html.twig', [
            'modele' => $modele,
            'edit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modele/delete/{id}", name="delete_modele")
     */
    public function delete(Modele $modele = null){
        if($modele == null){
            $this->addFlash('danger', 'Le modèle renseignée est introuvable.');
            return $this->redirectToRoute('modele');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($modele);
        $em->flush();

        $this->addFlash('success', 'Modèle supprimé avec succès !');

        return $this->redirectToRoute('modele');
    }
}
