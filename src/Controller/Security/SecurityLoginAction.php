<?php

namespace App\Controller\Security;

use App\Form\StreamerSelectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

#[Route(data: '/login', name: 'login')]
class SecurityLoginAction extends AbstractController
{
    use TargetPathTrait;

    public function __invoke(Request $request, AuthenticationUtils $helper): Response
    {
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

        if ($this->getUser()) {
            return $this->redirectToRoute(route: 'index');
        }

        $this->saveTargetPath(
            session: $request->getSession(),
            firewallName: 'main',
            uri: $this->generateUrl(route: 'index')
        );

        return $this->render(
            view: 'page/login/login.html.twig',
            parameters: [
                'last_username' => $helper->getLastUsername(),
                'error' => $helper->getLastAuthenticationError(),
                'form' => $form->createView(),
            ]
        );
    }
}
