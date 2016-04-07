<?php

namespace ATailouloute\DoctrineLoggerBundle\Formatter;

use ATailouloute\DoctrineLoggerBundle\Change\EntityChange;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
interface OutputFormatterInterface
{
    /**
     * @param EntityChange $entityChange
     *
     * @return string
     */
    public function formatEntityChangeCreate(EntityChange $entityChange);

    /**
     * @param EntityChange $entityChange
     *
     * @return string
     */
    public function formatEntityChangeUpdate(EntityChange $entityChange);

    /**
     * @param EntityChange $entityChange
     *
     * @return string
     */
    public function formatEntityChangeDelete(EntityChange $entityChange);
}
