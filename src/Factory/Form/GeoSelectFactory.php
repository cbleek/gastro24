<?php

namespace Gastro24\Factory\Form;

use Gastro24\Form\GeoSelect;
use Geo\Form\GeoSelectHydratorStrategy;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * GeoSelectFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class GeoSelectFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var \Geo\Options\ModuleOptions $geoOptions */
        $geoOptions = $container->get('Geo/Options');
        $helpers = $container->get('ViewHelperManager');
        $assetHelper = $helpers->get('Asset');

        $select = new GeoSelect();

        //$select->setAttribute('data-type', $geoOptions->getPlugin());

        $client = $container->get('Geo/Client');
        $strategy = new GeoSelectHydratorStrategy($client);

        $select->setHydratorStrategy($strategy);
        $select->setAssetHelper($assetHelper);

        return $select;
    }
}