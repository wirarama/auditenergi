<?php
class area extends database{
    public function __construct($home) {
        $this->select = "SELECT * FROM area WHERE 1";
        $this->insert = "INSERT INTO area (name,description,outlet)VALUES(?,?,?)";
        $this->update = "UPDATE area SET name=?,description=?,outlet=? WHERE id=?";
        $this->delete = "DELETE FROM area WHERE id=?";
        $this->paramSelect = array('name','description','outlet');
        $this->paramInsert = array('name','description','outlet');
        $this->paramUpdate = array('name','description','outlet','edit');
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
            $form->formArea('description','Description',60,5,false,$d['description']);
            $form->formInput("outlet","text","Outlet",4,1,true,$d['outlet']);
            if(!empty(filter_input(1,'edit'))){ $form->formHidden("edit",filter_input(1,'edit')); }
            $form->formSubmit("submit","Send");
            ?>
        </form>
        <?php
    }
}
