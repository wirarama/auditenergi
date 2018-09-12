<?php
class viewsensor_input extends template{
    var $title = "Sensor Logs";
    public function objdef() {
        require 'control/sensor_input.php';
        $this->home = 'index.php?p=sensor_input';
        $this->obj = new sensor_input($this->home);
    }
    public function bodymain() {
        $this->obj->maincontent();
    }
    public function bodymainjs(){
        parent::bodymainjs();
    }
}