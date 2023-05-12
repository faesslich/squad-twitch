<?php

namespace App\Controller\Profile;

use App\Action\AbstractAction;
use App\Entity\Image;
use App\Form\Type\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route(data: '/profile/change-password', name: 'profile_change_password', methods: ['GET', 'POST'])]
class ProfileChangePasswordAction extends AbstractAction
{
    public function __invoke(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var PasswordAuthenticatedUserInterface $user */
        $user = $this->getUser();

        $form = $this->createForm(type: ChangePasswordType::class);
        $form->handleRequest(request: $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                password: $passwordHasher->hashPassword(
                    user: $user,
                    plainPassword: $form->get('newPassword')->getData()
                )
            );
            $entityManager->flush();

            return $this->redirectToRoute(route: 'logout');
        }

        $profileImages = $user->getImages(category: Image::CATEGORY_PROFILE_IMAGE);

        return $this->render(
            view: 'page/profile/change-password.html.twig',
            parameters: [
                'user' => $user,
                'form' => $form->createView(),
                'profileImages' => $profileImages,
            ]
        );
    }
}
