<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

#[Route(data: '/logout', name: 'logout')]
class SecurityLogoutAction extends AbstractController
{
    use TargetPathTrait;

    public function __invoke(): void
    {
        throw new \Exception(message: 'This should never be reached!');
    }
}
