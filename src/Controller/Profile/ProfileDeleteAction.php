<?php

namespace App\Controller\Profile;

use App\Action\AbstractAction;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/delete', name: 'profile_delete', defaults: ['username' => '-'])]
class ProfileDeleteAction extends AbstractAction
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        $imageRepository = $doctrine->getRepository(persistentObject: Image::class);
        $imageCollection = $imageRepository->findBy(['user' => $user]);
        $imageEntityManager = $doctrine->getManagerForClass(class: Image::class);

        /** @var Image $data */
        foreach ($imageCollection as $data) {
            $file = getcwd() . '/uploads/user/' . $data->getOriginalFileName();
            if (file_exists($file)) {
                unlink($file);
            }
            $imageEntityManager->remove($data);
        }
        
        $entityManager = $doctrine->getManagerForClass(class: User::class);
        $entityManager->remove($user);
        $entityManager->flush();

        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute(route: 'index');
    }
}
