<?php
class rules extends database{
    public function __construct($home) {
        $this->select = "SELECT a.name AS name,a.id AS loads,b.rules AS rules,b.supports AS supp,"
                . "b.confidents AS conf,b.score AS score,b.id AS id,c.name AS area,c.id AS areaid "
                . "FROM loads AS a,rules AS b,area AS c "
                . "WHERE a.id=b.loads AND c.id=b.area ";
        $this->insert = "INSERT INTO rules (loads,rules,supports,confidents,score)VALUES(?,?,?,?,?)";
        $this->update = "UPDATE rules SET loads=?,rules=?,supports=?,confidents=?,score=? WHERE id=?";
        $this->delete = "DELETE FROM rules WHERE id=?";
        $this->paramSelect = array('name','area','rules','supp','conf','score');
        $this->paramDetail = $this->paramSelect;
        $this->paramInsert = array('loads','rules','supports','confidents','score');
        $this->paramUpdate = array('loads','rules','supports','confidents','score','edit');
        $this->paramDelete = array('hapus');
        $this->home = $home;
        parent::__construct();
    }
    public function queryForm(){
        if(!empty(filter_input(1,'edit'))){ $this->querySelect(); $d = $this->data[0]; }
        ?>
        <form action="" method="POST" id="menuForm" class="form-horizontal">
            <?php
            foreach($this->paramInsert AS $p){ if(empty($d[$p])){ $d[$p]="";}}
            $form = new form();
            $this->formselectdb('loads','Load',"SELECT id,name FROM loads",'id','name',true,$d['loads']);
            $this->formselectdb('area','Area',"SELECT id,name FROM area",'id','name',true,$d['areaid']);
            $form->formArea('rules','Rules',60,5,false,$d['rules']);
            $form->formInput("supports","text","Supports",60,4,true,$d['supp']);
            $form->formInput("confidents","text","Confidents",60,4,true,$d['conf']);
            $form->formInput("score","text","Score",60,4,true,$d['score']);
            if(!empty(filter_input(1,'edit'))){ $form->formHidden("edit",filter_input(1,'edit')); }
            $form->formSubmit("submit","Send");
            ?>
        </form>
        <?php
    }
    public function queryDetail() {
        parent::queryDetail();
        $this->relatedListTable('Rules Parts',
                "SELECT a.fm_index AS no,a.std1 AS std1,"
                . "a.median AS median,a.std2 AS std2,d.name AS name "
                . "FROM fuzzy_memberships AS a,rules_parts AS b, rules AS c,"
                . "fuzzy_features AS d "
                . "WHERE a.id=b.fuzzy_membership AND b.rules=c.id AND "
                . "d.id = a.feature AND "
                . "c.id='".filter_input(1,'detail')."'",
                array('no','name','std1','median','std2'),'index.php?p=fuzzy_memberships&detail=');    
    }
}
