<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\ProfilPicture;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    /**
     * @var string
     */
    private $websiteAdress;
    public function __construct(string $websiteAdress)
    {
        $this->websiteAdress = $websiteAdress;
    }

    #[Route('/register', name: 'register')]
    public function index(Request $request, FlashBagInterface $flash, SluggerInterface $slugger, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFiles = $form->get('profilPicture')->getData();

            foreach ($pictureFiles as $pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);

                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictureFile->move(
                        $this->getParameter('profilPicture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file uploads
                }
                $profilPicture = new ProfilPicture();
                $profilPicture->setLink($newFilename);
                $user->setProfilPicture($profilPicture);
            }
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $user->setRegistrationKey('random');
            $user->setMailIsValidate(false);
            $user->setRegistrationKey('toto');
            $em->persist($user);
            $em->flush();

            $email = (new Email())
                ->from($this->websiteAdress)
                ->to($user->getEmail())
                ->subject('Bienvenue sur Snowtricks')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $mailer->send($email);

            $flash->add('success', 'Compte créé avec succès');

            //   return $this->redirectToRoute('email', ['user' => $user->getId()]);
            return $this->redirectToRoute('home');

            //
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
