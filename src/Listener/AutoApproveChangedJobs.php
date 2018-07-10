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


use Jobs\Entity\JobSnapshotStatus;
use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class AutoApproveChangedJobs
{
    private $snapshotRepository;
    private $jobRepository;

    public function __construct($jobRepository, $snapshotRepository)
    {
        $this->snapshotRepository = $snapshotRepository;
        $this->jobRepository = $jobRepository;
    }

    public function __invoke(JobEvent $event)
    {
        $snapshot = $event->getJobEntity();

        if (Status::ACTIVE != $event->getParam('statusWas') || !$snapshot->getStatus()->is(Status::WAITING_FOR_APPROVAL)) {
            return;
        }

        /* @var \Jobs\Entity\Job $entity */
        $snapshot->getSnapshotMeta()->setStatus(JobSnapshotStatus::ACCEPTED);
        $entity = $this->snapshotRepository->merge($snapshot);
        $this->snapshotRepository->store($snapshot);
        $entity->setDateModified();
        $entity->changeStatus(Status::ACTIVE, 'Auto approved.');

        $this->jobRepository->store($entity);
    }

}
