<?php
class loads extends database{
    public function __construct($home) {
        $this->select = "SELECT * FROM loads WHERE 1";
        $this->insert = "INSERT INTO loads (name,description)VALUES(?,?)";
        $this->update = "UPDATE loads SET name=?,description=? WHERE id=?";
        $this->delete = "DELETE FROM loads WHERE id=?";
        $this->paramSelect = array('name','description');
        $this->paramInsert = array('name','description');
        $this->paramUpdate = array('name','description','edit');
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
            if(!empty(filter_input(1,'edit'))){ $form->formHidden("edit",filter_input(1,'edit')); }
            $form->formSubmit("submit","Send");
            ?>
        </form>
        <?php
    }
}
