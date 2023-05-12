<?php

namespace App\Controller\Profile;

use App\Action\AbstractAction;
use App\Entity\Image;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
class ProfileEditAction extends AbstractAction
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        $streamerSearchForm = $this->buildStreamerSearchForm($request);

        $user = $this->getUser();
        $form = $this->createForm(type: ProfileType::class, data: $user);
        $form->handleRequest(request: $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(type: 'success', message: 'user.updated_successfully');

            return $this->redirectToRoute(route: 'profile_edit');
        }
        $profileImages = $user->getImages(category: Image::CATEGORY_PROFILE_IMAGE);

        return $this->render(
            view: 'page/profile/edit.html.twig',
            parameters: [
                'user' => $user,
                'form' => $form->createView(),
                'streamerSearchForm' => $streamerSearchForm->createView(),
                'profileImages' => $profileImages,
            ]
        );
    }
}
