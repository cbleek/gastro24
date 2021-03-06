<?php

namespace Gastro24\Factory\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Gastro24\Service\Adapter\User;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

/**
 * UserAdapterFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class UserAdapterFactory implements FactoryInterface
{
    /**
     * Create an User adapter
     *
     * authentication with username and password
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return User
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config     = $container->get('Config');
        $config     = isset($config['Auth']['default_user']) ? $config['Auth']['default_user'] : array();
        $repository = $container->get('repositories')->get('Auth/User');

        $adapter = new User($repository);

        if (isset($config['login']) && !empty($config['login'])
            && isset($config['password']) && !empty($config['password'])
            && isset($config['role']) && !empty($config['role'])
        ) {
            $adapter->setDefaultUser($config['login'], $config['password'], $config['role']);
        }

        return $adapter;
    }

    /**
     * Creates an instance of \Auth\Adapter\UserAdapter
     *
     * - injects the UserRepository fetched from the service manager.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Auth\Adapter\User
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, User::class);
    }
}