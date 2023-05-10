<?php

namespace App\Controller\Watch;

use App\Form\StreamerSelectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    data: '/watch/{slug1}/{slug2}/{slug3}/{slug4}',
    name: 'watch',
    defaults: ['slug1' => null, 'slug2' => null, 'slug3' => null, 'slug4' => null]
)]
class WatchAction extends AbstractController
{

    public function __invoke(?string $slug1, ?string $slug2, ?string $slug3, ?string $slug4, Request $request): Response
    {
        $slugs = [
            $slug1,
            $slug2,
            $slug3,
            $slug4
        ];

        $form = $this->createForm(type: StreamerSelectType::class);
        $form->handleRequest(request: $request);
        if ($form->isSubmitted() && $form->isValid()) {
            $values = array_values(array_filter($form->getData()));

            return $this->redirectToRoute(
                route: 'watch',
                parameters: [
                    'slug1' => $values[0],
                    'slug2' => $values[1] ?? null,
                    'slug3' => $values[2] ?? null,
                    'slug4' => $values[3] ?? null
                ]
            );
        }

        return $this->render(
            view: '@App/page/watch/watch.html.twig',
            parameters: [
                'form' => $form->createView(),
                'streamers' => array_values(array_filter($slugs)),
            ],
        );
    }
}
