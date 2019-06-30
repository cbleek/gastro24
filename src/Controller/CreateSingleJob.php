<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller;

use Core\Entity\Collection\ArrayCollection;
use Core\Entity\Hydrator\EntityHydrator;
use Core\Form\Hydrator\Strategy\TreeSelectStrategy;
use Jobs\Entity\Classifications;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Helper\ServerUrl;
use Zend\View\Model\JsonModel;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class CreateSingleJob extends AbstractActionController
{
    /**
     *
     * @var \Gastro24\Form\CreateSingleJobForm
     */
    private $form;

    public function __construct($form)
    {
        $this->form = $form;
    }

    public function indexAction()
    {
        /* @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        $session = new Container('Gastro24_SingleJobData');

        if ($request->isPost()) {
            return $this->process();
        }

        // prefill form
        if (isset($session->values)) {
            $values = unserialize($session->values);
            //$values = array_merge_recursive($values, $this->getRequest()->getFiles()->toArray());
            $values['details']['logo'] = isset($values['details']['logo_url']) ? $values['details']['logo_url'] : null;
            $values['details']['image'] = isset($values['details']['image_url']) ? $values['details']['image_url'] : null;
            $values['classifications'] = [
                'employmentTypes' => $values['classifications']->getEmploymentTypes()->getValues()
            ];
            $this->form->setData($values);
        }

        if ('complete' == $request->getQuery('do')) {
            return $this->complete();
        }

        return [
            'form' => $this->form,
        ];
    }

    private function process()
    {
        $data = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
        $this->form->setData($data);

        if (!$this->form->isValid()) {
            return [
                'valid' => false,
                'form' => $this->form,
            ];
        }

        $values = $this->form->getData();

        if ('pdf' == $values['details']['mode']) {
            $serverUrl = new ServerUrl();
            $basePath  = $this->getRequest()->getBasePath();

            $values['details']['uri'] = $serverUrl('/' . $basePath . str_replace('public/', '', $values['details']['pdf']['tmp_name']));
        }

        if (isset($data['details']['logo_id'])) {
            $values['details']['logo_id'] = $data['details']['logo_id'];
            $values['details']['logo_url'] = $data['details']['logo_url'];
        }

        if (isset($data['details']['image_id'])) {
            $values['details']['image_id'] = $data['details']['image_id'];
            $values['details']['image_url'] = $data['details']['image_url'];
        }

        $hydrator = $this->form->get('classifications')->getHydrator();
        $employmentTypes = $hydrator->hydrateValue('employmentTypes', $values['classifications']['employmentTypes']);

        $classifications = new Classifications();
        $classifications->setEmploymentTypes($employmentTypes);
        $values['classifications'] = $classifications;

        $session = new Container('Gastro24_SingleJobData');
        $session->data = serialize($data);
        $session->values = serialize($values);

        $values['isProcessed'] = true;

        return $values;
    }

    private function complete()
    {
        $session = new Container('Gastro24_SingleJobData');
        $values  = $session->values;

        if (!$values) {
            return $this->redirect()->toRoute('lang/jobs/single');
        }

        $plugin = $this->plugin(Plugin\CreateSingleJob::class);

        try {
            $plugin(unserialize($values));
        } catch (\Exception $e) {
            return [
                'isError' => true,
            ];
        }

        $session->exchangeArray([]);

        return [ 'isSuccess' => true ];

    }

}
