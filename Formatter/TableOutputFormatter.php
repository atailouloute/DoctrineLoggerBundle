<?php

namespace ATailouloute\DoctrineLoggerBundle\Formatter;

use ATailouloute\DoctrineLoggerBundle\Formatter\Helper\Table;
use ATailouloute\DoctrineLoggerBundle\Change\EntityChange;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class TableOutputFormatter implements OutputFormatterInterface
{
    /**
     * @param EntityChange $entityChange
     *
     * @return string
     */
    public function formatEntityChangeCreate(EntityChange $entityChange)
    {
        $table = new Table();
        $table->setHeader(array('Property', 'Value'));

        foreach ($entityChange->getUpdatedProperties() as $property) {
            $table->addRow(array($property->getPropertyName(), $property->getNewValue()));
        }

        $logMessage = PHP_EOL.'Entity created';
        $logMessage .= PHP_EOL.'Class : '.$entityChange->getEntityClass();
        $logMessage .= PHP_EOL.$table->render();

        return $logMessage.PHP_EOL;
    }

    /**
     * @param EntityChange $entityChange
     *
     * @return string
     */
    public function formatEntityChangeUpdate(EntityChange $entityChange)
    {
        $table = new Table();
        $table->setHeader(array('Property', 'Previous Value', 'New Value'));

        foreach ($entityChange->getUpdatedProperties() as $property) {
            $table->addRow(array($property->getPropertyName(), $property->getPreviousValue(), $property->getNewValue()));
        }

        $logMessage = PHP_EOL.'Entity updated';
        $logMessage .= PHP_EOL.'Class : '.$entityChange->getEntityClass();
        $logMessage .= PHP_EOL.$table->render();

        return $logMessage.PHP_EOL;
    }

    /**
     * @param EntityChange $entityChange
     *
     * @return string
     */
    public function formatEntityChangeDelete(EntityChange $entityChange)
    {
        $logMessage = PHP_EOL.'Entity deleted';
        $logMessage .= PHP_EOL.'Class : '.$entityChange->getEntityClass();

        return $logMessage.PHP_EOL;
    }
}
