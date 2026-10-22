<?php

namespace App\Model;

use App\Entity\Event;
use App\Service\SnapshotService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EventDto
{
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [Event::ACTION_ENTRY, Event::ACTION_EXIT])]
    public ?string $action = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    public ?string $licensePlate = null;

    #[Assert\Image(mimeTypes: [SnapshotService::MIME_TYPE])]
    public ?UploadedFile $snapshot = null;
}
