<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Montage;
use App\Entity\Piece;
class MontageController extends AbstractController
{
    // /**
    //  * @Route("/montage", name="app_montage")
    //  */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $montage = new Montage();
        $montage->setNomlMontage('Montage1');
        $montage->setClient('Naoures');
        $montage->setCreatedAt(new \DateTime());
        $montage->setCout(10);
    

        $piece1=new Piece();
        $piece1->setNomPiece("piece1");
        $piece1->setQuantite("quantite1");
        $piece1->setunite(3);

        $piece2=new Piece();
        $piece2->setNomPiece("piece2");
        $piece2->setQuantite("quantite2");
        $piece2->setunite(4);


        $piece1->setMontage($montage);
        $piece2->setMontage($montage);

        $entityManager->persist($montage);
        $entityManager->persist($piece1);
        $entityManager->persist($piece2);

        $entityManager->flush();
        return $this->render('montage/index.html.twig', [
            'id' =>$montage->getId(),
        ]);
    }
     /**
     *
     * @Route("/add",name="ajout_montage")
     */
    public function ajouter2(Request $request)
    {
       
        $montage = new Montage();
        $fb = $this->createFormBuilder($montage)
        ->add('nomlMontage', TextType::class)
        ->add('client', TextType::class)
        ->add('cout', TextType::class)
      //  ->add('created_at', DateType::class)
        ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($montage);
            $em->flush();
            return $this->redirectToRoute('liste_montage');
        }
        return $this->render('montage/ajouter.html.twig',
            ['f'=>$form->createView()]);
    }

    /**
     * @Route("/ajout", name="Ajouter")
     */
    public function ajouter_piece(Request $request)
    {
        $piece = new Piece();
        $fb = $this->createFormBuilder($piece)
            ->add('nomPiece', TextType::class)
            ->add('quantite', TextType::class)
             ->add('unite', TextType::class)
            ->add('montage', EntityType::class, [
                'class' => montage::class,
                'choice_label' => 'nomlMontage',
            ])
            ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        //injection dans la base de donnees
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($piece);
            $em->flush();
            return $this->redirectToRoute('liste_montage');
        }
        return $this->render('montage/ajouter.html.twig',
            ['f' => $form->createView()]);
    }

 //delete article
     /**
     *
     * @Route("/montage/delete/{id}",name="delete_montage")
     */
 public function delete(Request $request, $id) {
     $c = $this -> getDoctrine()
     ->getRepository(montage::class)
     ->find($id);
     if (!$c) {
         throw $this->createNotFoundException(
             "No montage found for id".$id
         );
     }
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->remove($c);
     $entityManager->flush();
     return $this->redirectToRoute('liste_montage');
 }
         /**
     * @Route("/edit/{id}", name="edit_piece")
     * Method({"GET","POST"})
     */
    public function edit(Request $request, $id)
    {   $piece = new Piece();
        $piece = $this->getDoctrine()
            ->getRepository(Piece::class)
            ->find($id);

        if (!$piece) {
            throw $this->createNotFoundException(
                'No piece found for id '.$id
            );
        }
        $fb = $this->createFormBuilder($piece)
        ->add('nomPiece', TextType::class)
        ->add('quantite', TextType::class)
         ->add('unite', TextType::class)
        ->add('montage', EntityType::class, [
            'class' => montage::class,
            'choice_label' => 'nomlMontage',
        ])
        ->add('Valider', SubmitType::class);
        // générer le formulaire à partir du FormBuilder
        $form = $fb->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('liste_montage');
        }
        return $this->render('montage/ajouter.html.twig',
            ['f' => $form->createView()] );
    }
     /**
     * @Route("/editMontage/{id}", name="edit_montage")
     * Method({"GET","POST"})
     */
    public function editMontage(Request $request, $id)
    {   $montage = new Montage();
        $montage = $this->getDoctrine()
            ->getRepository(Montage::class)
            ->find($id);

        if (!$montage) {
            throw $this->createNotFoundException(
                'No montage found for id '.$id
            );
        }

        $fb = $this->createFormBuilder($montage)
        ->add('nomlMontage', TextType::class)
        ->add('client', TextType::class)
        ->add('cout', TextType::class)
        ->add('Valider', SubmitType::class);

        // générer le formulaire à partir du FormBuilder
        $form = $fb->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('liste_montage');
        }
        return $this->render('montage/ajouter.html.twig',
            ['f' => $form->createView()] );
    }







      /**
    * @Route ("/liste",name="liste_montage")
    */
    public function  liste(){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Montage::class);
        $lesMontages = $repo->findAll();
        return $this->render('montage/liste.html.twig',
        [ 'lesMontages' => $lesMontages ]);
       }




/**
* @Route("/montage/{id}", name="montage_show")
*/
public function show($id)
{
    $montage = $this->getDoctrine()
    ->getRepository(Montage::class)
    ->find($id);
        $em=$this->getDoctrine()->getManager();
        $listPieces=$em->getRepository(Piece::class)
            ->findBy(['Montage'=>$montage]);
        if (!$montage) {
            throw $this->createNotFoundException(
                'No montage found for id '.$id
            );
        }
        return $this->render('montage/show.html.twig', [
            'listPieces'=> $listPieces,
            'montage' =>$montage
        ]);
        }
}

