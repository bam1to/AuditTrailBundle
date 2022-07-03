<?php

namespace Bam1to\AuditTrailBundle\EventSubscriber;

use Bam1to\AuditTrailBundle\Service\Serialize;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

class EntityAffectionSubscriber implements EventSubscriber
{
    const SAVE_ACTIVITY = 'save';
    const UPDATE_ACTIVITY = 'update';
    const DELETE_ACTIVITY = 'delete';

    private array $tables;
    private LoggerInterface $logger;
    private Serialize $serializer;

    public function __construct(array $tables, LoggerInterface $auditLogger, Serialize $serialize)
    {
        $this->tables = $tables;
        $this->logger = $auditLogger;
        $this->serializer = $serialize;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->logAction('prePersist', $args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->logAction('preUpdate', $args);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->logAction('preRemove', $args);
    }

    private function logAction(string $action, LifecycleEventArgs $args)
    {
        if (empty($this->tables)) {
            return;
        }

        $entity = $args->getObject();

        // get table name
        $metaEntity = $args->getObjectManager()->getClassMetadata(get_class($entity));
        $tableName = $metaEntity->table['name'];

        $actions = [];

        // check if the table is in the list of tables specified by the configuration
        foreach ($this->tables as $table) {
            if ($table['table']['name'] === $tableName) {
                $actions = explode(',', $table['table']['actions']);
            }
        }

        if (empty($actions)) {
            return;
        }

        $jsonEntity = $this->serializer->serializeJson($entity);

        // save action
        if (in_array(self::SAVE_ACTIVITY, $actions) && Events::prePersist === $action) {
            $this->logger->info("Saved $tableName: $jsonEntity");
        }

        // update action
        if (in_array(self::UPDATE_ACTIVITY, $actions) && Events::preUpdate === $action) {
            $this->logger->info("Updated $tableName: $jsonEntity");
        }

        // delete action
        if (in_array(self::DELETE_ACTIVITY, $actions) && Events::preRemove === $action) {
            $this->logger->info("Deleted $tableName: $jsonEntity");
        }

        return;
    }
}
