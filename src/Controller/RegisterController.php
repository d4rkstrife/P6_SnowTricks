<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\ProfilPicture;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
    public function index(Request $request, FlashBagInterface $flash, SluggerInterface $slugger, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, UriSigner $urisigner): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
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
            $user->setMailIsValidate(false);
            $user->setRegistrationKey('toto');
            $em->persist($user);
            $em->flush();

            $url = $this->generateUrl('validate', ['user' => $user->getId(), 'key' => $user->getRegistrationKey()], UrlGeneratorInterface::ABSOLUTE_URL);
            $signedUrl = $urisigner->sign($url);

            $email = (new TemplatedEmail())
                ->from($this->websiteAdress)
                ->to($user->getEmail())
                ->subject('Bienvenue sur Snowtricks')
                ->text('localhost:8000/validate/' . $user->getId() . '/' . $user->getRegistrationKey())
                ->context([
                    'user' => $user,
                    'url' => $signedUrl,
                ])
                ->htmlTemplate('email/validation.html.twig');

            $mailer->send($email);

            $flash->add('success', 'Compte créé avec succès');

            return $this->redirectToRoute('home');

            //
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/validate/{user}/{key}', name: 'validate')]
    public function validate(User $user, string $key, FlashBagInterface $flash, EntityManagerInterface $em, Request $request, UriSigner $uriSigner): Response
    {
        if (!$uriSigner->checkRequest($request) || $user->getRegistrationKey() !== $key) {
            return $this->redirectToRoute('home');
            $flash->add('error', 'Adresse incorrecte');
        }

        if ($user->getRegistrationKey() === $key) {
            $user->setMailIsValidate('true');
            $user->setRegistrationKey('');

            $em->persist($user);
            $em->flush();

            $flash->add('success', 'Adresse mail confirmée');

            return $this->redirectToRoute('app_login');
        }
    }
}
