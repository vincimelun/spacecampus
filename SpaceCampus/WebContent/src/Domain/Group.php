<?php

namespace MicroCMS\Domain;

class Group 
{
    /**
     * Group id.
     *
     * @var integer
     */
    private $id;

    /**
     * Group name.
     *
     * @var string
     */
    private $name;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}