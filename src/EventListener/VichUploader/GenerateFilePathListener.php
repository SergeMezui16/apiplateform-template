<?php
namespace App\EventListener\VichUploader;

use App\Entity\Image;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Storage\StorageInterface;


class GenerateFilePathListener
{
    public function __construct(
        private StorageInterface $storage
    ) {}

    public function onVichUploaderPostUpload(Event $event)
    {
        /** @var Image $object */
        $object = $event->getObject();
        $object->setPath($this->storage->resolveUri($object));
    }
}