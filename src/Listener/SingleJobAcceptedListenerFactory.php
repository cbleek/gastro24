<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Listener;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Gastro24\Listener\SingleJobAcceptedListener
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class SingleJobAcceptedListenerFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $jobsRepository = $repositories->get('Jobs');
        $orders  = $container->get('repositories')->get('Orders');
        $mailer  = $container->get('Core/MailService');
        $service = new SingleJobAcceptedListener($orders, $mailer, $jobsRepository);
        
        return $service;    
    }
}
