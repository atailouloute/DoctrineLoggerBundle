<?php

namespace ATailouloute\DoctrineLoggerBundle\Change;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class EntityChange
{
    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var array
     */
    protected $updatedProperties;

    /**
     * EntityChange constructor.
     */
    public function __construct()
    {
        $this->updatedProperties = array();
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return string The class name of the entity
     */
    public function getEntityClass()
    {
        return get_class($this->entity);
    }

    /**
     * @return string The class short name of the entity
     */
    public function getEntityClassShortName()
    {
        $reflect = new \ReflectionClass($this->entity);

        return  $reflect->getShortName();
    }

    /**
     * @return array
     */
    public function getUpdatedProperties()
    {
        return $this->updatedProperties;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param PropertyChange $property
     */
    public function addUpdatedProperty(PropertyChange $property)
    {
        $this->updatedProperties[] = $property;
    }
}
