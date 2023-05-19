<?php

namespace App\Controller\Dashboard;

use App\Action\AbstractAction;
use App\Form\StreamerSelectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(data: '/', name: 'index')]
class DashboardAction extends AbstractAction
{
    public function __invoke(Request $request): Response
    {

        $streamerSearchForm = $this->buildStreamerSearchForm($request);
        $form = $this->createForm(type: StreamerSelectType::class);
        $form->handleRequest(request: $request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inputValues = $form->getData();

            $values = array_values(array_filter([
                $inputValues['favorites'][0] ?? null,
                $inputValues['favorites'][1] ?? null,
                $inputValues['favorites'][2] ?? null,
                $inputValues['favorites'][3] ?? null,
                $inputValues['slug1'],
                $inputValues['slug2'],
                $inputValues['slug3'],
                $inputValues['slug4']
            ]));

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
                'streamerSearchForm' => $streamerSearchForm->createView(),
                'form2' => $form->createView(),
            ],
        );
    }
}
