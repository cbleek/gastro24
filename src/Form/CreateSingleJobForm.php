<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Form;

use Core\Form\Form;
use Jobs\Entity\Location;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class CreateSingleJobForm extends Form implements InputFilterProviderInterface
{

    public function init()
    {
        $this->setIsDescriptionsEnabled(true);
        $this->setDescription('Hier können Sie ein Einzelinserat aufgeben');
        $this->setAttribute('id', 'createsinglejob');

        $this->add([
            'type' => 'Text',
            'name' => 'title',
            'options' => [
                'description' => 'Geben Sie den Job-Titel ein.',
                'label' => 'Titel',
            ],
            'attributes' => [
                'require' => true,
            ]
        ]);

        $this->add([
            'type' => 'LocationSelect',
            'name' => 'locations',
            'options' => [
                'description' => 'Geben Sie den Beschäftigungsort an.<br><br>Ihre Vorschläge werden auto-vervollständigt.',
                'label' => 'Ort',
                'location_entity' => Location::class
            ],
            'attributes' => [
                'required' => true,
                'multiple' => true,
                'data-placeholder' => '',
            ],
        ]);

        $this->add([
            'type' => 'Text',
            'name' => 'uri',
            'options' => [
                'description' => 'Geben Sie die Online-Addresse des Inserats an.',
                'label' => 'Online-Inserat',
            ],
            'attributes' => [
                'placeholder' => 'http://'
            ]
        ]);

        $this->add([
            'type' => 'Orders/InvoiceAddressFieldset',
            'options' => [
                'label' => 'Rechnungsanschrift',
            ],
        ]);

        $this->add([
            'type' => 'DefaultButtonsFieldset',
            'options' => [
                'save_label' => 'Inserat anlegen',
            ]
        ]);

    }

    public function getInputFilterSpecification()
    {
        return [

                'title' => [
                    'require' => true,
                ],
                'uri' => [
                    'require' => true,
                ],
                'locations' => [
                    'require' => true,
                ],

        ];
    }

    public function isValid()
    {
        return \Zend\Form\Form::isValid(); // TODO: Change the autogenerated stub
    }


}
