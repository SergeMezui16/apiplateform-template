<?php
namespace App\EventListener;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Symfony\EventListener\SerializeListener as Decorated;
use App\Entity\Image;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * SerializeListener
 * 
 * This helpful listener listen to the Multipart Request
 * It help us to manage files serialization and decorate
 * object loaded arround file and denormalize multipart as jsonld
 */
class SerializeListener
{
    public function __construct(
        private Decorated $serializeListener,
        private SerializerContextBuilderInterface $serializerContextBuilder,
        private SerializerInterface $serializer
    ) {}

    public function onKernelView(ViewEvent $event)
    {
        $request = $event->getRequest();
        $result = $event->getControllerResult();

        if ($result instanceof Image && $request->getRequestFormat() === 'multipart') {
            $image = $result->setFile(null);

            $request->setRequestFormat('jsonld');
            $request->attributes->set('data', $image);
            $event->setControllerResult($image);
            $this->serializeListener->onKernelView($event);
            return;
        }

        $this->serializeListener->onKernelView($event);
        return;
    }
}