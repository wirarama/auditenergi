<?php
class chart extends database{
    public $chartd = array();
    public function __construct($home) {
        $this->home = $home;
        parent::__construct();
    }
    public function chartQuery($q,$p=array()) {
        $chartd = array();
        $query = $this->conn->prepare($q);
        $query->execute();
        while($d = $query->fetch()){
            $chartdr = '{';
            $chartdc = array();
            $i=0;
            foreach($p AS $p1){
                if($p1=='current'){ $data = ($d[$p1]/10000)*100;
                }else if($p1=='voltage'){ $data = ($d[$p1]/240)*100;
                }else if($p1=='power'){ $data = ($d[$p1]/(2400000))*100; 
                }else{ $data = "'".$d[$p1]."'"; }
                array_push($chartdc,$p1.':'.$data);
                $i++;
            }
            $chartdr .= implode(',',$chartdc);
            $chartdr .= '}';
            array_push($chartd,$chartdr);
        }
        array_push($this->chartd,implode(",\n",$chartd));
    }
    public function chartQueryMulti($q=array(),$p=array(),$y=array()) {
        $chartd = array();
        $i=0;
        foreach($q AS $q1){
            $query = $this->conn->prepare($q1);
            $query->execute();
            $chartdr = '{';
            $chartdc = array();
            array_push($chartdc,"tahun:'".$y[$i]."'");
            while($d = $query->fetch()){
                array_push($chartdc,$d[$p[0]].':'.$d[$p[1]]);
            }
            $chartdr .= implode(',',$chartdc);
            $chartdr .= '}';
            array_push($chartd,$chartdr);
            $i++;
        }
        return implode(",\n",$chartd);
    }
}
