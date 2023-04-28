<?php
namespace App\EventListener;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Symfony\EventListener\DeserializeListener as Decorated;
use ApiPlatform\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * DeserializeListener
 * 
 * This helpful listener listen to the Multipart Request
 * It help us to manage files deserialization and decorate
 * object loaded arround file and denormalize multipart
 * 
 * It also generate the file path.
 */
class DeserializeListener
{
    public function __construct(
        private Decorated $deserializeListener,
        private SerializerContextBuilderInterface $serializerContextBuilder,
        private DenormalizerInterface $denormalizer,
        private StorageInterface $storage
    )
    {}

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        
        if($request->isMethodCacheable() || $request->isMethod(Request::METHOD_DELETE)) return;
        
        // Default behavior if it not multipart or form
        if($request->getRequestFormat() !== 'multipart' || $request->getContentTypeFormat() !== 'form') {
            $this->deserializeListener->onKernelRequest($event);
            return;
        }

        $attributes = RequestAttributesExtractor::extractAttributes($request);

        if(empty($attributes)) return;

        $populated = $request->attributes->get('data');
        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);

        if ($populated !== null) $context['object_to_populate'] = $populated;

        $object = $this->denormalizer->denormalize(
            [...$request->request->all(), ...$request->files->all()],
            $attributes['resource_class'],
            null,
            $context
        );

        $request->attributes->set('data', $object->setPath($this->storage->resolveUri($object)));
    }
}