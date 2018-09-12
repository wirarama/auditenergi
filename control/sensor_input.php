<?php
class sensor_input extends database{
    public function __construct($home) {
        $this->select = "SELECT a.id AS id,b.name AS area,a.current AS current,"
                . "a.voltage AS voltage,a.power AS power, "
                . "a.temperature AS temperature,a.light AS light, a.datetime AS datetime "
                . "FROM sensor_input AS a,area AS b "
                . "WHERE b.id=a.area ";
        $this->insert = "INSERT INTO sensor_input (area,current,voltage,power)VALUES(?,?,?,?)";
        $this->update = "UPDATE sensor_input SET area=?,current=?,voltage=?,power=? WHERE id=?";
        $this->delete = "DELETE FROM sensor_input WHERE id=?";
        $this->paramSelect = array('area','current','voltage','power','temperature','light','datetime');
        $this->paramInsert = array('area','current','voltage','power','temperature','light');
        $this->paramUpdate = array('area','current','voltage','power','temperature','light','edit');
        $this->paramDelete = array('hapus');
        $this->paramDetail = $this->paramSelect;
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
            $this->formselectdb('area','Area',"SELECT id,name FROM area",'id','name',true,$d['areaid']);
            $form->formInput("current","text","Current",60,4,true,$d['current']);
            $form->formInput("voltage","text","Voltage",60,4,true,$d['voltage']);
            $form->formInput("power","text","Power",60,4,true,$d['power']);
            $form->formInput("temperature","text","Temperature",60,4,true,$d['temperature']);
            $form->formInput("light","text","Light",60,4,true,$d['light']);
            if(!empty(filter_input(1,'edit'))){ $form->formHidden("edit",filter_input(1,'edit')); }
            $form->formSubmit("submit","Send");
            ?>
        </form>
        <?php
    }
}
