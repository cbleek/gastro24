<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Factory\Controller\Plugin;

use Gastro24\Controller\Plugin\CreateSingleJob;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


/**
 * Factory for \Gastro24\Controller\Plugin\CreateSingleJob
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class CreateSingleJobFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $jobRepository= $repositories->get('Jobs');
        $orderRepository = $repositories->get('Orders');
        $orderOptions = $container->get('Orders/Options/Module');
        $plugin = new CreateSingleJob($jobRepository, $orderRepository, $orderOptions);

        return $plugin;
    }
    
}
