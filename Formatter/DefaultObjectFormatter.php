<?php

namespace ATailouloute\DoctrineLoggerBundle\Formatter;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class DefaultObjectFormatter implements ObjectFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format($object)
    {
        if (is_scalar($object)) {
            return $object;
        }

        if (is_array($object)) {
            return json_encode($object);
        }

        if (is_object($object)) {
            if ($object instanceof \DateTime) {
                return $object->format(\DateTime::ATOM);
            }
            if (method_exists($object, 'getId')) {
                return $object->getId();
            }
            if (method_exists($object, '__toString')) {
                return $object->__toString();
            }
        }

        return json_encode($object);
    }
}
