<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface; // Utilisation du service de hachage
use Doctrine\ORM\EntityManagerInterface; // Injection de Doctrine
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request, HasherUserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Créer un nouvel objet User
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide et soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Définir les rôles par défaut
            $user->setRoles(['ROLE_USER']); // Assurez-vous que chaque utilisateur ait au moins un rôle par défaut

            // Enregistrer l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger ou afficher un message de succès
            return $this->redirectToRoute('app_home'); // Exemple de redirection
        }

        // Afficher le formulaire
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
            'form' => $form->createView(),
        ]);
    }
}
