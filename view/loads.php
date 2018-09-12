<?php
class viewloads extends template{
    var $title = "Loads";
    public function objdef() {
        require 'control/loads.php';
        $this->home = 'index.php?p=loads';
        $this->obj = new loads($this->home);
    }
    public function bodymain() {
        $this->obj->maincontent();
    }
    public function bodymainjs(){
        parent::bodymainjs();
    }
}