<?php

namespace App\Controller\Dashboard;

use App\Form\StreamerSelectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(data: '/', name: 'index')]
class DashboardAction extends AbstractController
{
    public function __invoke(Request $request): Response
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

        return $this->render(
            view: '@App/page/dashboard/dashboard.html.twig',
            parameters: [
                'form' => $form->createView(),
                'form2' => $form->createView(),
            ],
        );
    }
}
