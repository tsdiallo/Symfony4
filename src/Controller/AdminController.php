<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Form\UserType;
use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use App\Repository\PartenaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/profil/show", name="admin.profil.show")
     */
    public function showProfil(ProfilRepository $repo )
    {
       $profils= $repo->findAll();
        return $this->render('admin/show.html.twig', [
            'profils' => $profils,
        ]);
    }

   /**
     * @Route("/admin/profil/add", name="admin.profil.add")
     */
    public function addProfil( Request $request)
    {
         $profil=new Profil();
        
        $form = $this->createFormBuilder($profil)
            ->add('libelle', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();


            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                

                
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($profil);
                 $entityManager->flush();

                return $this->redirectToRoute('admin.profil.show');
            }
       
        return $this->render('admin/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/profil/edit/{id}", name="admin.profil.edit")
     */
    public function editProfil($id,Request $request,ProfilRepository $repo)
    {
         $profil=$repo->find($id);
        
        $form = $this->createFormBuilder($profil)
            ->add('libelle', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();


            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                

                
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($profil);
                 $entityManager->flush();

                return $this->redirectToRoute('admin.profil.show');
            }
       
        return $this->render('admin/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/profil/delete/{id}", name="admin.profil.delete")
     */
    public function deleteProfil($id,ProfilRepository $repo)
    {
        $profil=$repo->find($id);
        
         
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($profil);
        $entityManager->flush();
   

       return $this->redirectToRoute('admin.profil.show');
       
    }

     /**
     * @Route("/admin/caissier/show", name="admin.caissier.show")
     */
    public function showCaissier(UserRepository $repo,ProfilRepository $repoProf  )
    {
        $profil=$repoProf->findOneBy([
            'libelle'=>'Caissier'
        ]);
       $caissiers= $repo->findBy([
                 'profil'=>$profil
           ]
       );

        return $this->render('admin/caissier/show.html.twig', [
            'caissiers' => $caissiers,
        ]);
    }


   /**
     * @Route("/admin/caissier/add", name="admin.caissier.add")
     */
    public function addCaissier( Request $request)
    {
         $caissier=new User();
        
      
         $form = $this->createForm(UserType::class, $caissier);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($caissier);
                 $entityManager->flush();

                return $this->redirectToRoute('admin.caissier.show');
            }
       
        return $this->render('admin/caissier/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/caissier/bloquer/{id}/{etat}", name="admin.caissier.bloquer")
     */
    public function bloquerCaissier($id,$etat,UserRepository $repo)
    {
                $caissier=$repo->find($id);
                $caissier->setEtat($etat);

                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($caissier);
                 $entityManager->flush();

                return $this->redirectToRoute('admin.caissier.show');
          
    }


    /**
     * @Route("/admin/partenaire/add", name="admin.partenaire.add")
     */
    public function addPartenaire( Request $request,ProfilRepository $repoProf )
    {
         $partenaire=new Partenaire();
        
      
         $form = $this->createForm(PartenaireType::class, $partenaire);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user=new User();
                $user->setNom($form->get("nom")->getData());
                $user->setPrenom($form->get("prenom")->getData());
                $user->setLogin($form->get("login")->getData());
                $user->setPassword($form->get("password")->getData());
                $user->setEtat($form->get("etat")->getData());
                
                $profil=$repoProf->findOneBy([
                    'libelle'=>'Partenaire'
                ]);
                $user->setProfil($profil);
                $partenaire->setUser($user);

                $compte=new Compte();

                $compte->setNumero("OO1");
                $compte->setSolde($form->get("solde")->getData());

                $partenaire->addCompte($compte);

               // dd($partenaire);

                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($partenaire);
                 $entityManager->flush();

                return $this->redirectToRoute('admin.partenaire.show');
            }
       
        return $this->render('admin/partenaire/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/partenaire/show", name="admin.partenaire.show")
     */
    public function showPartenaire(PartenaireRepository $repo )
    {
       
       $partenaires= $repo->findAll();

        return $this->render('admin/partenaire/show.html.twig', [
            'partenaires' => $partenaires,
        ]);
    }

    
    /**
     * @Route("/admin/partenaire/bloquer/{id}/{etat}", name="admin.partenaire.bloquer")
     */
    public function bloquerPartenaire($id,$etat,PartenaireRepository $repo)
    {
                $partenaire=$repo->find($id);
                $partenaire->getUser()->setEtat($etat);

                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($partenaire);
                 $entityManager->flush();

                return $this->redirectToRoute('admin.partenaire.show');
          
    }

     /**
     * @Route("/admin/comptes/show/{id}", name="admin.partenaire.showCompte")
     */
    public function showComptePartenaire($id,PartenaireRepository $repo)
    {
              $partenaire=$repo->find($id);
              

              return $this->render('admin/partenaire/compte.html.twig', [
                'partenaire' => $partenaire,
            ]);
          
    }


      /**
     * @Route("/admin/compte/add/{id}", name="admin.compte.add")
     */
    public function addCompte( $id,Request $request,PartenaireRepository $repo )
    {
        $partenaire=$repo->find($id);
       
               
                
      
         $form = $this->createForm(PartenaireType::class, $partenaire);
         $form->get("nom")->setData($partenaire->getUser()->getNom());

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
              
                $compte=new Compte();
                $compte->setNumero("OO2");
                $compte->setSolde($form->get("solde")->getData());
                $partenaire->addCompte($compte);

               // dd($partenaire);

                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($partenaire);
                 $entityManager->flush();

                return $this->redirectToRoute('admin.partenaire.showCompte',[
                    'id' => $partenaire->getId()
                ]);
            }
       
        return $this->render('admin/partenaire/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
