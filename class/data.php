<?php

class data extends database{
    public function inputFuzzy() {
        $trunc = $this->conn->prepare("TRUNCATE TABLE fuzzy_features");
        $trunc->execute();
        $features = array('current','voltage','duration','periode','temperature','light intencity');
        $type = array('processing','judgement');
        $i = 0;
        foreach ($features as $feat){
            if($i>3){ $tipe = $type[1]; }else{ $tipe = $type[0]; }
            $q = "INSERT INTO fuzzy_features VALUES"
                . "(null,'".$feat."','".$tipe."')";
            $ins = $this->conn->prepare($q);
            $ins->execute();
            $i++;
        }
        $this->inputFuzzyMember();
    }
    public function inputFuzzyMember(){
        $trunc1 = $this->conn->prepare("TRUNCATE TABLE fuzzy_memberships");
        $trunc1->execute();
        $qfeat = $this->conn->prepare("SELECT id FROM fuzzy_features ORDER BY id ASC");
        $qfeat->execute();
        while($d = $qfeat->fetch()){
            switch($d['id']){
                case(1):
                    $this->randmembership($d['id'],500,12,array(200,400),array(100,300),array(300,500));break;
                case(2):
                    $this->randmembership($d['id'],210,10,array(-10,10),array(-4,5),array(-8,13));break;
                case(3):
                    $this->randmembership($d['id'],5,5,array(30,60),array(30,120),array(40,80));break;
                case(4):
                    $this->randmembership($d['id'],0,6,array(1,4),array(1,4),array(3,6));break;
                case(5):
                    $this->randmembership($d['id'],24,3,array(1,3),array(1,2),array(2,4));break;
                case(6):
                    $this->randmembership($d['id'],30,3,array(10,20),array(5,10),array(15,30));break;
            }
        }
    }
    public function randrule($n,$th1,$th2,$sp = array(1,999), $cf = array(-100,100)){
        $trunc = $this->conn->prepare("TRUNCATE TABLE rules");
        $trunc->execute();
        for($i=0;$i<$n;$i++){
            $sup = rand($sp[0],$sp[1]);
            $conf = $sup+rand($cf[0],$cf[1]);
            $score = (($sup*$th1)+($conf*$th2)/20)/1000;
            $loads = $this->singleSelect("SELECT id FROM loads "
                    . "ORDER BY RAND()",array('id'));
            $areas = $this->singleSelect("SELECT id FROM area "
                    . "ORDER BY RAND()",array('id'));
            $q = "INSERT INTO rules VALUES"
                . "(null,'".$loads[0]."','".$areas[0]."',null,'".($sup/1000)."',"
                    . "'".($conf/1000)."','".$score."',null)";
            $ins = $this->conn->prepare($q);
            $ins->execute();
        }
        $this->randRuleParts();
    }
    public function randRuleParts(){
        $trunc = $this->conn->prepare("TRUNCATE TABLE rules_parts");
        $trunc->execute();
        $qrule = $this->conn->prepare("SELECT id FROM rules ORDER BY id ASC");
        $qrule->execute();
        while($d = $qrule->fetch()){
            $qfuzzmem = $this->conn->prepare("SELECT id,name FROM fuzzy_features ORDER BY id ASC");
            $qfuzzmem->execute(); $i=1;
            $ruletxt = array();
            while($dfm = $qfuzzmem->fetch()){
                $fuzz = $this->singleSelect("SELECT id,fm_index FROM fuzzy_memberships "
                    . "WHERE feature='".$dfm['id']."' ORDER BY RAND()",array('id','fm_index'));
                $qins = "INSERT INTO rules_parts VALUES"
                . "(null,'".$d['id']."','".$fuzz[0]."','".$i."')";
                $ins = $this->conn->prepare($qins);
                $ins->execute();
                array_push($ruletxt, ucwords($dfm['name'][0]).$fuzz[1]);
                $i++;
            }
            $ruletxt1 = implode('^',$ruletxt);
            $ruletxt2 = str_replace('^T','->T',$ruletxt1);
            echo $ruletxt2.'<br>';
            $qruleUp = $this->conn->prepare("UPDATE rules SET "
                    . "rules='".$ruletxt2."' WHERE id='".$d['id']."'");
            $qruleUp->execute();
        }
    }
    public function inputLoads() {
        $trunc = $this->conn->prepare("TRUNCATE TABLE loads");
        $trunc->execute();
        $loads = array('phone','power bank','laptop','desktop','monitor',
            'dispencer','refrigator','light bulp','air conditioners','electric cattle');
        foreach ($loads as $load){
            $q = "INSERT INTO loads VALUES"
                . "(null,'".$load."','description for ".$load."')";
            $ins = $this->conn->prepare($q);
            $ins->execute();
        }
    }
    public function inputArea() {
        $trunc = $this->conn->prepare("TRUNCATE TABLE area");
        $trunc->execute();
        $areas = array('Room 1','Room 2','Room 3','Room 4','Kitchen');
        $outlets = array(4,4,4,6,6); $i=0;
        foreach ($areas as $area){
            $q = "INSERT INTO area VALUES"
                . "(null,'".$area."','description for ".$area."','".$outlets[$i]."')";
            $ins = $this->conn->prepare($q);
            $ins->execute(); $i++;
        }
    }
    public function inputSensor() {
        $trunc = $this->conn->prepare("TRUNCATE TABLE sensor_input");
        $trunc->execute();
        for($i=0;$i<1000;$i++){
            $c = rand(200,240);
            $v = rand(500,10000);
            $t = rand(24,32);
            $l = rand(30,80);
            $q = "INSERT INTO sensor_input VALUES"
                . "(null,'".rand(1,5)."','".$c."','".$v."',"
                    . "'".($c*$v+rand(0.001,0.999))."','".$t."','".$l."',null)";
            $ins = $this->conn->prepare($q);
            $ins->execute(); $i++;
        }
    }
    public function randmembership($id,$n,$r,$i1=array(),$i2=array(),$i3=array()){
        for($j=0;$j<$r;$j++){
            $n1 = $n+rand($i1[0],$i1[1]);
            $n2 = $n1+rand($i2[0],$i2[1]);
            if($n < $n1){ $temp = $n;$n = $n1;$n1 = $temp;}
            if($n < $n2){ $temp = $n;$n = $n2;$n2 = $temp;}
            $q = "INSERT INTO fuzzy_memberships "
                . "VALUES(null,'".$id."','".$n."','".$n1."','".$n2."','".($j+1)."')";
            $ins = $this->conn->prepare($q);
            $ins->execute();
            $n += rand($i3[0],$i3[1]);
            if($id==4 && $n>23){ break; }
            elseif($id==5 && $n>35){ break; }
            elseif($id==2 && $n>240){ break; }
            elseif($id==1 && $n>10000){ break; }
        }
    }
}
