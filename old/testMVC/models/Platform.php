<?php



class Platform {

    public $id;
    public $name;
    public $short_name;
    public $icon;

    public function __construct($id, $name, $short_name, $icon = "default.png") {
        $this->id = (int) $id;
        $this->name = $name;
        $this->short_name = $short_name;
        $this->icon = $icon;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getShort_name() {
        return $this->short_name;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setShort_name($short_name) {
        $this->short_name = $short_name;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

}
