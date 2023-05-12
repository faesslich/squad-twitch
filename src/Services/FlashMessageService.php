<?php

namespace App\Services;

use App\Enum\FlashMessageType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlashMessageService
{
    public function __construct(
        private readonly FlashBagInterface $flashBag,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function addSuccess(string $message): void
    {
        $this->flashBag->add(type: FlashMessageType::Success->value, message: $this->translator->trans($message));
    }

    public function addWarning(string $message): void
    {
        $this->flashBag->add(type: FlashMessageType::Warning->value, message: $this->translator->trans($message));
    }

    public function addError(string $message): void
    {
        $this->flashBag->add(type: FlashMessageType::Error->value, message: $this->translator->trans($message));
    }
}
