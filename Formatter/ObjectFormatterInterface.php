<?php

namespace ATailouloute\DoctrineLoggerBundle\Formatter;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
interface ObjectFormatterInterface
{
    /**
     * Transform an object or a scalar variable to a string.
     *
     * @param mixed $object
     *
     * @return string
     */
    public function format($object);
}
