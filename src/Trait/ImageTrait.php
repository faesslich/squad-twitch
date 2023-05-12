<?php

namespace App\Trait;

use App\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

trait ImageTrait
{
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getImages(string $category = null): array|Collection
    {
        if ($category === null || $this->images->count() === 0) {
            return $this->images->toArray();
        }

        $images = $this->images;
        if ($images instanceof PersistentCollection) {
            $images = $images->toArray();
        }

        return array_filter(
            $images,
            function (Image $image) use ($category) {
                return $image->getCategory() === $category;
            }
        );
    }
}
