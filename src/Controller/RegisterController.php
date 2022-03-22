<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\ProfilPicture;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManager;
use App\Form\ForgotPasswordType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, UriSigner $urisigner): Response
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
            $user->setRegistrationKey(md5(random_bytes(8)));
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

            $this->addFlash('success', 'Compte créé avec succès');

            return $this->redirectToRoute('home');

            //
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/validate/{user}/{key}', name: 'validate')]
    public function validate(User $user, string $key, EntityManagerInterface $em, Request $request, UriSigner $uriSigner): Response
    {
        if (!$uriSigner->checkRequest($request) || $user->getRegistrationKey() !== $key) {
            $this->addFlash('error', 'Lien incorrect');
            return $this->redirectToRoute('home');
        }

        if ($user->getRegistrationKey() === $key) {
            $user->setMailIsValidate('true');
            $user->setRegistrationKey('');

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Adresse mail confirmée');

            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/forgotPassword', name: 'forgotPassword')]
    public function forgotPassword(Request $request, UserRepository $userRepository,  MailerInterface $mailer, EntityManagerInterface $em, UriSigner $urisigner): Response
    {
        if ($this->getUser()) {
            $this->addFlash('error', "vous n'êtes pas autorisé à acceder à cette page");
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user === null) {
                $this->addFlash('error', 'Utilisateur introuvable');
                return $this->redirectToRoute('forgotPassword');
            }

            $user->setResetPasswordKey(md5(random_bytes(8)));
            $em->persist($user);
            $em->flush();

            $url = $this->generateUrl('resetPassword', ['user' => $user->getId(), 'key' => $user->getResetPasswordKey()], UrlGeneratorInterface::ABSOLUTE_URL);
            $signedUrl = $urisigner->sign($url);

            $email = (new TemplatedEmail())
                ->from($this->websiteAdress)
                ->to($user->getEmail())
                ->subject('Réinitialisation mot de passe')
                ->context([
                    'user' => $user,
                    'url' => $signedUrl,
                ])
                ->htmlTemplate('email/resetPassword.html.twig');
            $mailer->send($email);

            $this->addFlash('success', 'Demande de reinitialisation de mot de passe envoyée avec succès');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/forgotPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/resetPassword/{user}/{key}', name: 'resetPassword')]
    public function resetPassword(User $user, string $key, UriSigner $uriSigner, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->getUser()) {
            $this->addFlash('error', "Vous n'êtes pas autorisé à acceder à cette page");
            return $this->redirectToRoute('home');
        }
        if (!$uriSigner->checkRequest($request) || $user->getResetPasswordKey() !== $key) {

            $this->addFlash('error', 'Lien incorrect');
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $user->setResetPasswordKey(null);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Mot de passe mis à jour');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
