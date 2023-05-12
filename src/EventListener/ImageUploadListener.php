<?php

namespace App\EventListener;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;

class ImageUploadListener
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
    ) {
    }

    public function onUpload(PostPersistEvent $event): ResponseInterface
    {
        $response = $event->getResponse();
        $file = $event->getFile();
        $image = match ($event->getType()) {
            'user_avatar' => $this->handleUploadUserAvatar($event),
        };

        $image->setCreatedAt(new \DateTime());
        $image->setOriginalFileName(originalFileName: $file->getFilename());
        $image->setCryptedFileName(cryptedFileName: \md5($file->getFilename()) . '.' . $file->getExtension());

        $this->managerRegistry->getManager()->persist($image);
        $this->managerRegistry->getManager()->flush();

        $response['success'] = true;

        return $response;
    }

    private function handleUploadUserAvatar(PostPersistEvent $event): Image
    {
        $image = new Image();

        $userName = $this->getValueFromQueryParam(event: $event, param: 'username');
        $user = $this->managerRegistry->getRepository(persistentObject: User::class)
            ->findOneBy(
                ['username' => $userName]
            );

        if ($user !== null) {
            $image->setCategory(category: Image::CATEGORY_PROFILE_IMAGE);
            $image->setUser(user: $user);
        }

        return $image;
    }

    public function getValueFromQueryParam(PostPersistEvent $event, string $param): string
    {
        $requestUri = parse_url(url: $event->getRequest()->getRequestUri());
        return str_replace(search: $param . '=', replace: '', subject: $requestUri['query']);
    }
}
