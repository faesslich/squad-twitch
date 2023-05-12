<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\StreamerSelectType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

#[Route(data: '/register', name: 'register')]
class SecurityRegisterAction extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(Request $request, AuthenticationUtils $helper, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(route: 'index');
        }

        $form = $this->createForm(type: StreamerSelectType::class);
        $form->handleRequest(request: $request);
        if ($form->isSubmitted() && $form->isValid()) {
            $values = array_values(array_filter($form->getData()));

            return $this->redirectToRoute(
                route: 'watch',
                parameters: [
                    'slug1' => $values[0] ?? null,
                    'slug2' => $values[1] ?? null,
                    'slug3' => $values[2] ?? null,
                    'slug4' => $values[3] ?? null
                ]
            );
        }

        $user = new User();
        $formRegister = $this->createForm(RegistrationFormType::class, $user);
        $formRegister->handleRequest($request);
        if ($formRegister->isSubmitted() && $formRegister->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $formRegister->get('plainPassword')->getData()
                )
            );
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render(
            view: 'page/register/register.html.twig',
            parameters: [
                'last_username' => $helper->getLastUsername(),
                'error' => $helper->getLastAuthenticationError(),
                'form' => $form->createView(),
                'registrationForm' => $formRegister->createView(),
            ]
        );
    }
}
