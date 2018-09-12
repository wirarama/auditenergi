<?php
class viewrand extends template{
    var $title = "Rand";
    public function objdef() {
        require 'control/rand.php';
        $this->home = 'index.php?p=rand';
        $this->obj = new rand($this->home);
    }
    public function bodymain() {
        require 'class/data.php';
        $rand = new data();
        $rand->inputFuzzy();
        $rand->inputLoads();
        $rand->inputArea();
        $rand->inputSensor();
        $rand->randrule(100,8,6,array(1,999),array(-100,100));
    }
    public function bodymainjs(){
    }
}