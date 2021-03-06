<?php

namespace Gastro24\Factory\Service;

use Gastro24\Service\Register;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * RegisterFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterFactory implements FactoryInterface
{
    /**
     * Create a Register service
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return Register
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var \Auth\Repository\User $userRepository */
        $userRepository = $container->get('repositories')->get('Auth/User');

        /* @var \Core\Mail\MailService $mailService */
        $mailService = $container->get('Core/MailService');

        /* @var \Core\Options\ModuleOptions $config */
        $config = $container->get('Core/Options');

        $service = new Register($userRepository, $mailService, $config);

        $events = $container->get('Auth/Events');
        $service->setEventManager($events);

        return $service;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Register
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Register::class);
    }
}