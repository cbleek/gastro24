<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Factory\Controller;

use Gastro24\Controller\CreateSingleJob;
use Gastro24\Form\CreateSingleJobForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Gastro24\Controller\CreateSingleJob
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class CreateSingleJobFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $forms = $container->get('forms');
        $form  = $forms->get(CreateSingleJobForm::class);
        $controller = new CreateSingleJob($form);

        return $controller;
    }
}
