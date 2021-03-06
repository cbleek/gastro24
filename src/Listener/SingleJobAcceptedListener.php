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

use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class SingleJobAcceptedListener 
{
    private $orders;
    private $mailer;
    protected $jobsRepository;

    public function __construct(\Orders\Repository\Orders $orders, \Core\Mail\MailService $mailer, $jobsRepository)
    {
        $this->orders = $orders;
        $this->mailer = $mailer;
        $this->jobsRepository = $jobsRepository;
    }

    public  function __invoke(JobEvent $event)
    {
        $job = $event->getJobEntity();

        if ($job->getUser()) { return; }

        /* @var \Orders\Entity\Order $order */
        $order = $this->orders->findByJobId($job->getId());

        if (!$order) { return; }

        // check for publishDat in future
        if ($job->getTemplateValues()->get('publishDate')) {
            // convert to valid date format
            list($day, $month, $year) = explode('/', $job->getTemplateValues()->get('publishDate'));
            $job->setDatePublishStart(new \DateTime($year . '-' . $month . '-' . $day));
            $job->changeStatus(Status::ACTIVE, 'single job was activated.');
            $this->jobsRepository->store($job);
        }

        $invoice = $order->getInvoiceAddress();

        $this->mailer->send($this->mailer->get(
            'Gastro24/SingleJobMail',
            [
                'template' => 'gastro24/mail/single-job-accepted',
                'subject'  => 'Ihre Anzeige wurde veröffentlicht.',
                'email'    => $invoice->getEmail(),
                'name'     => $invoice->getName(),
                'vars'     => [
                    'job' => $job,
                    'invoice' => $invoice,
                ],
            ]
        ));
    }
}
