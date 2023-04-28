<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * UserPasswordHasherProcessor
 * 
 * This Processor hash password of new User before persistence.
 */
class UserPasswordHasherProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack $requestStack
    ){}

    /**
     * @param User $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // dd($data, $operation, $uriVariables, $context);
        $request = $this->requestStack->getCurrentRequest();
        $user = $request->attributes->get('previous_data') ?? $request->attributes->get('data');

        if ($request->getMethod() === Request::METHOD_POST || $user->getPassword() !== $data->getPassword()) {
            $data = $data->setPassword($this->passwordHasher->hashPassword(
                $data,
                $data->getPassword()
            ));
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
