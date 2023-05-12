<?php

namespace App\Controller\Watch;

use App\Action\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    data: '/watch/{slug1}/{slug2}/{slug3}/{slug4}',
    name: 'watch',
    defaults: ['slug1' => null, 'slug2' => null, 'slug3' => null, 'slug4' => null]
)]
class WatchAction extends AbstractAction
{

    public function __invoke(?string $slug1, ?string $slug2, ?string $slug3, ?string $slug4, Request $request): Response
    {
        $slugs = [
            $slug1,
            $slug2,
            $slug3,
            $slug4
        ];

        $streamerSearchForm = $this->buildStreamerSearchForm($request);

        return $this->render(
            view: '@App/page/watch/watch.html.twig',
            parameters: [
                'streamerSearchForm' => $streamerSearchForm->createView(),
                'streamers' => array_values(array_filter($slugs)),
            ],
        );
    }
}
