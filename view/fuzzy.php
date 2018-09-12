<?php
class viewfuzzy extends template{
    var $title = "Fuzzy";
    public function objdef() {
        require 'control/fuzzy.php';
        $this->home = 'index.php?p=fuzzy';
        $this->obj = new fuzzy($this->home);
    }
    public function bodymain() {
        $this->obj->maincontent();
    }
    public function bodymainjs(){
        parent::bodymainjs();
    }
}