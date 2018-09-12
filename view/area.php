<?php
class viewarea extends template{
    var $title = "Area";
    public function objdef() {
        require 'control/area.php';
        $this->home = 'index.php?p=area';
        $this->obj = new area($this->home);
    }
    public function bodymain() {
        $this->obj->maincontent();
    }
    public function bodymainjs(){
        parent::bodymainjs();
    }
}