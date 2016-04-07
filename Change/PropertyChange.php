<?php

namespace ATailouloute\DoctrineLoggerBundle\Change;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class PropertyChange
{
    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var mixed
     */
    protected $previousValue;

    /**
     * @var mixed
     */
    protected $newValue;

    /**
     * EntityChangeProperty constructor.
     *
     * @param mixed $previousValue
     * @param mixed $newValue
     */
    public function __construct($propertyName, $previousValue, $newValue)
    {
        $this->propertyName = $propertyName;
        $this->previousValue = $previousValue;
        $this->newValue = $newValue;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getPreviousValue()
    {
        return $this->previousValue;
    }

    /**
     * @return mixed
     */
    public function getNewValue()
    {
        return $this->newValue;
    }
}
