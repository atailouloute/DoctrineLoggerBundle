<?php

namespace ATailouloute\DoctrineLoggerBundle\EventListener;

use ATailouloute\DoctrineLoggerBundle\Formatter\ObjectFormatterInterface;
use ATailouloute\DoctrineLoggerBundle\Formatter\OutputFormatterInterface;
use ATailouloute\DoctrineLoggerBundle\Change\PropertyChange;
use ATailouloute\DoctrineLoggerBundle\Change\EntityChange;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class EntityChangeListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var OutputFormatterInterface
     */
    protected $outputFormatter;

    /**
     * @var ObjectFormatterInterface
     */
    protected $objectFormatter;

    /**
     * @var bool
     */
    protected $loggingEntityCreationEnabled;

    /**
     * @var bool
     */
    protected $loggingEntityUpdateEnabled;

    /**
     * @var bool
     */
    protected $loggingEntityDeletionEnabled;

    /**
     * @var array
     */
    protected $skippedEntities;

    /**
     * @param LoggerInterface          $logger
     * @param OutputFormatterInterface $outputFormatter
     * @param ObjectFormatterInterface $objectFormatter
     */
    public function __construct(LoggerInterface $logger, OutputFormatterInterface $outputFormatter, ObjectFormatterInterface $objectFormatter)
    {
        $this->logger = $logger;
        $this->outputFormatter = $outputFormatter;
        $this->objectFormatter = $objectFormatter;
    }

    /**
     * Entry function to audit updates/insertions/deletion in entities.
     *
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        // Compute changes
        $unitOfWork->computeChangeSets();

        // Audit changes on update
        if ($this->isLoggingEntityUpdateEnabled()) {
            foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
                if ($this->shouldSkipEntity($entity)) {
                    continue;
                }

                $changeSet = $unitOfWork->getEntityChangeSet($entity);
                $entityChange = $this->getEntityChange($changeSet, $entity);

                $this->logger->log(LogLevel::INFO, $this->outputFormatter->formatEntityChangeUpdate($entityChange));
            }
        }

        // Audit changes on Creation
        if ($this->isLoggingEntityCreationEnabled()) {
            foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
                if ($this->shouldSkipEntity($entity)) {
                    continue;
                }

                $changeSet = $unitOfWork->getEntityChangeSet($entity);
                $entityChange = $this->getEntityChange($changeSet, $entity);

                $this->logger->log(LogLevel::INFO, $this->outputFormatter->formatEntityChangeCreate($entityChange));
            }
        }

        // Audit changes on Deletion
        if ($this->isLoggingEntityDeletionEnabled()) {
            foreach ($unitOfWork->getScheduledEntityDeletions() as $entity) {
                if ($this->shouldSkipEntity($entity)) {
                    continue;
                }

                $changeSet = $unitOfWork->getEntityChangeSet($entity);
                $entityChange = $this->getEntityChange($changeSet, $entity);

                $this->logger->log(LogLevel::INFO, $this->outputFormatter->formatEntityChangeDelete($entityChange));
            }
        }
    }

    /**
     * Checks whether the current entity should be logged or skipped.
     *
     * @param $entity
     *
     * @return bool
     */
    protected function shouldSkipEntity($entity)
    {
        foreach ($this->skippedEntities as $skippedEntity) {
            if ($entity instanceof $skippedEntity) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $changeSet
     * @param mixed $entity
     *
     * @return EntityChange
     */
    private function getEntityChange($changeSet, $entity)
    {
        $entityChange = new EntityChange();
        $entityChange->setEntity($entity);

        foreach ($changeSet as $field => $change) {
            $propertyChange = new PropertyChange($field, $this->objectFormatter->format($change[0]), $this->objectFormatter->format($change[1]));
            $entityChange->addUpdatedProperty($propertyChange);
        }

        return $entityChange;
    }

    /**
     * @return bool
     */
    public function isLoggingEntityCreationEnabled()
    {
        return $this->loggingEntityCreationEnabled;
    }

    /**
     * @param bool $loggingEntityCreationEnabled
     */
    public function setLoggingEntityCreationEnabled($loggingEntityCreationEnabled)
    {
        $this->loggingEntityCreationEnabled = $loggingEntityCreationEnabled;
    }

    /**
     * @return bool
     */
    public function isLoggingEntityUpdateEnabled()
    {
        return $this->loggingEntityUpdateEnabled;
    }

    /**
     * @param bool $loggingEntityUpdateEnabled
     */
    public function setLoggingEntityUpdateEnabled($loggingEntityUpdateEnabled)
    {
        $this->loggingEntityUpdateEnabled = $loggingEntityUpdateEnabled;
    }

    /**
     * @return bool
     */
    public function isLoggingEntityDeletionEnabled()
    {
        return $this->loggingEntityDeletionEnabled;
    }

    /**
     * @param bool $loggingEntityDeletionEnabled
     */
    public function setLoggingEntityDeletionEnabled($loggingEntityDeletionEnabled)
    {
        $this->loggingEntityDeletionEnabled = $loggingEntityDeletionEnabled;
    }

    /**
     * @param array $skippedEntities
     */
    public function setSkippedEntities(array $skippedEntities)
    {
        $this->skippedEntities = $skippedEntities;
    }
}
