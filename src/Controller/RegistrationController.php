<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\SearchForm;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator, CategoryRepository $categoryRepository, ProductRepository $productRepository, MarqueRepository $marqueRepository): Response
    {
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findBy(['status' => 1]);


        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $formSearch = $this->createForm(SearchForm::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('milan.forrat@gmail.com', 'Milan FORRAT - Développeur Web Freelance'))
                    ->to($user->getEmail())
                    ->subject('Confirmez votre compte')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            
            );
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }
        
        if ($request->isMethod('GET')) {
            $searchRequest = $formSearch->handleRequest($request);

            if ($formSearch->isSubmitted() && $formSearch->isValid()) {
                $products = $productRepository->findSearch(
                    $searchRequest->get('search')->getData()
                );
                return $this->render('main/search-results.html.twig', [
                    'formSearch' => $formSearch->createView(),
                    'products' => $products,
                    'categories' => $categories,
                    'marques' => $marques,
            ]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'formSearch' => $formSearch->createView(),
            'products' => $products,
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', 'Veuillez confirmer votre compte via votre adresse email');

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse e-mail a été vérifié avec succès.');

        return $this->redirectToRoute('app_home');
    }
}
