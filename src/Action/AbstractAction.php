<?php

namespace App\Action;

use App\Form\StreamerSelectType;
use App\Services\FlashMessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractAction extends AbstractController
{

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'flash.message.service' => '?' . FlashMessageService::class,
        ]);
    }

    public function buildStreamerSearchForm(Request $request): RedirectResponse|FormInterface
    {
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
        return $form;
    }

}
