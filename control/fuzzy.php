<?php
class fuzzy extends database{
    public function __construct($home) {
        $this->select = "SELECT name,id,type FROM fuzzy_features WHERE 1 ";
        $this->insert = "INSERT INTO fuzzy (name,type)VALUES(?,?)";
        $this->update = "UPDATE fuzzy SET name=?,type=? WHERE id=?";
        $this->delete = "DELETE FROM fuzzy WHERE id=?";
        $this->paramSelect = array('name','type');
        $this->paramDetail = $this->paramSelect;
        $this->paramInsert = array('name','type');
        $this->paramUpdate = array('name','type','edit');
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
            $form->formInput("name","text","Name",60,4,true,$d['name']);
            $form->formRadio("type","Type",array('processing','judgement'),true,$d['type']);
            if(!empty(filter_input(1,'edit'))){ $form->formHidden("edit",filter_input(1,'edit')); }
            $form->formSubmit("submit","Send");
            ?>
        </form>
        <?php
    }
    public function queryDetail() {
        parent::queryDetail();
        $this->relatedListTable('Memberships',
                "SELECT a.fm_index AS no,a.std1 AS std1,"
                . "a.median AS median,a.std2 AS std2 "
                . "FROM fuzzy_memberships AS a,fuzzy_features AS b "
                . "WHERE a.feature=b.id AND "
                . "b.id='".filter_input(1,'detail')."'",
                array('no','std1','median','std2'),'index.php?p=fuzzy_memberships&detail=');    
    }  
}
