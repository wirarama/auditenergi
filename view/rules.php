<?php
class viewrules extends template{
    var $title = "Rules";
    public function objdef() {
        require 'control/rules.php';
        $this->home = 'index.php?p=rules';
        $this->obj = new rules($this->home);
    }
    public function bodymain() {
        $this->obj->maincontent();
    }
    public function bodymainjs(){
        parent::bodymainjs();
    }
}