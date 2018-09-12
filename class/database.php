<?php
class database implements databaseInterface{
    private $host = 'localhost';
    private $db = 'auditenergi';
    private $user = 'auditenergi';
    private $pass = 'auditenergi';
    private $char = 'utf8';
    public $conn;
    protected $home;
    protected $data;
    protected $select;
    protected $insert;
    protected $delete;
    protected $update;
    protected $paramSelect=array();
    protected $paramInsert=array();
    protected $paramDelete=array();
    protected $paramUpdate=array();
    protected $paramDetail=array();
    public $foto;
    public $dir = 'uploads/';
    public function __construct() { 
        $this->connect(); 
        if(sizeof($this->paramDetail)==0){ $this->query = $this->paramInsert;}
    }
    public function connect() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->char";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->conn = new PDO($dsn,$this->user,$this->pass,$opt);
    }
    public function query($q) {
        $query = $this->conn->prepare($q);
        $query->execute();
    }
    public function queryStat($q,$p=array()) {
        $query = $this->conn->prepare($q);
        for($i=0;$i<sizeof($p);$i++){
            $query->bindParam($i+1,$p[$i]);
        }
        $query->execute();
    }
    public function querySelectExcept(){
        if(!empty(filter_input(1,'edit')) && filter_input(1,'p')=='rules'){ 
            $this->select .= " AND b.id='".filter_input(1,'edit')."'"; }
        else if(!empty(filter_input(1,'edit')) && filter_input(1,'p')=='sensor_input'){ 
            $this->select .= " AND a.id='".filter_input(1,'edit')."'"; }
        else if(!empty(filter_input(1,'edit'))){ $this->select .= " AND id='".filter_input(1,'edit')."'"; }
        else if(!empty(filter_input(0,'edit'))){ $this->select .= " AND id='".filter_input(0,'edit')."'"; }
        else if(!empty(filter_input(1,'hapus'))){ $this->select .= " AND id='".filter_input(1,'hapus')."'"; }
        else if(!empty(filter_input(1,'detail')) && filter_input(1,'p')=='sensor_input'){ 
            $this->select .= " AND a.id='".filter_input(1,'detail')."'"; }
        else if(!empty(filter_input(1,'detail')) && filter_input(1,'p')=='rules'){ 
            $this->select .= " AND b.id='".filter_input(1,'detail')."'"; }
        else if(!empty(filter_input(1,'detail'))){ $this->select .= " AND id='".filter_input(1,'detail')."'"; }
    }
    public function querySelect() {
        $this->querySelectExcept();
        //echo $this->select.'<br>';
        $query = $this->conn->prepare($this->select);
        $query->execute();
        $this->data = array();
        $i = 0;
        while($d = $query->fetch()){
            $this->data[$i] = $d;
            $i++;
        }
    }
    public function arrayfilter($a=array(),$type=0){
        $arr = array();
        foreach ($a as $d){array_push($arr,filter_input($type,$d));}
        if(!empty($this->foto)){ array_push($arr,$this->foto); }
        print_r($arr);
        return $arr;
    }
    public function qinsert(){
        $this->queryStat($this->insert,$this->arrayfilter($this->paramInsert));
    }
    public function qupdate(){
        $this->queryStat($this->update,$this->arrayfilter($this->paramUpdate));
    }
    public function qdelete(){
        $this->queryStat($this->delete,$this->arrayfilter($this->paramDelete,1));
    }
    public function queryForm(){}
    public function queryDetail(){
        $this->querySelect(); $d = $this->data[0];
        $form = new form();
        echo '<div class="col-lg-6">';
        foreach($this->paramDetail AS $p){
            if($p=='files' && !empty($d[$p])){
                $form->formFiles($d[$p],$this->dir);
            }else if($p=='foto'){
                if(!empty($d[$p])){ $form->formPic($form->dir.str_replace(".","_Thumb.",$d[$p]));
                }else{ $form->formPic($form->dir.'thumbs.jpg'); }
            }else{
                $form->formTxt($d[$p],$p);
            }
        }
        echo '</div>';
    }
    public function relatedList($q,$p,$t,$link){
        echo '<div class="col-lg-6">';
        $query = $this->conn->prepare($q);
        $query->execute();
        echo '<ul class="list-group">';
        echo '<li class="list-group-item active"><strong>'.$t.'</strong></li>';
        while($d = $query->fetch()){
            echo '<li class="list-group-item"><a href="'.$link.$d['id'].'">'.$d[$p].'</a></li>';
        }
        echo '</ul>';
        echo '</div>';
    }
    public function maincontent(){
        if(!empty(filter_input(1,'input')) || !empty(filter_input(1,'edit'))){
            $this->queryForm();
        }else if(!empty(filter_input(1,'hap'))){
            ?>
            <div class="alert alert-danger">
                <strong>Konfirmasi!</strong> Apakah yakin data ini dihapus?<br>
                <a href="<?php echo $this->home.'&hapus='.filter_input(1,'hap'); ?>" class="btn btn-info" role="button">Ya</a>
                <a href="<?php echo $this->home; ?>" class="btn btn-default" role="button">Tidak</a>
            </div>
            <?php
        }else if(!empty(filter_input(1,'detail'))){
            $this->queryDetail();
        }else{
            $this->querySelect();
            $this->queryPrintTable();
        }
    }
    public function queryPrintTable() {
        ?>
    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
        <?php $this->tableHead(); $this->tableBody(); ?>        
    </table>
        <?php       
    }
    public function tableHead(){
        ?>
        <thead>
            <tr>
                <?php foreach($this->paramSelect AS $p){ ?>
                <th><?php echo str_replace('_',' ',ucfirst($p)); ?></th>
                <?php } ?>
                <th><i class="fa fa-edit fa-fw"></i></th>
                <th><i class="fa fa-eraser fa-fw"></i></th>
                <th><i class="fa fa-file-text fa-fw"></i></th>
            </tr>
        </thead>
        <?php
    }
    public function tableBody(){
        ?>
        <tbody>
            <?php
            foreach ($this->data as $d) {
            ?>
            <tr class="odd gradeX">
                <?php foreach($this->paramSelect AS $p){ ?>
                <td><?php echo $d[$p]; ?></td>
                <?php } ?>
                <td><a href="<?php echo $this->home;?>&edit=<?php echo $d['id'];?>"><i class="fa fa-edit fa-fw"></i></a></td>
                <td><a href="<?php echo $this->home;?>&hap=<?php echo $d['id'];?>"><i class="fa fa-eraser fa-fw"></i></a></td>
                <td><a href="<?php echo $this->home;?>&detail=<?php echo $d['id'];?>"><i class="fa fa-file-text fa-fw"></i></a></td>
            </tr>
            <?php } ?>
        </tbody>
        <?php
    }
    public function uploadsPhoto(){
        require 'resizeImg.php';
        $img = new imageEdit();
        $this->foto = $img->imgRename($_FILES["file"]["tmp_name"],filter_input(0,"nama"));
        move_uploaded_file($_FILES["file"]["tmp_name"],$this->dir.$this->foto) or die("upload gagal");
        $img->imgResize($this->dir.$this->foto,$this->dir.$img->imgThumb($this->foto),500,500);
    }
    public function deletePhoto(){
        $this->querySelect();
        $d = $this->data[0];
        unlink($this->dir.$d['foto']);
        unlink($this->dir.str_replace('.','_Thumb.',$d['foto']));
    }
    public function formselectdb($name,$label,$q,$i,$j,$req,$val){
        $query = $this->conn->prepare($q);
        $query->execute();
        ?>
            <div class="form-group">
                <label for="<?php echo $name; ?>" class="control-label col-sm-2"><?php echo $label; ?></label>
                <div class="col-sm-10">
                    <select name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php if(!empty($req)){ ?>required=""<?php } ?>>
                    <?php while($d = $query->fetch()){ ?>
                        <option value="<?php echo $d[$i]; ?>" <?php if($val==$d[$i]){ ?>selected=""<?php } ?>><?php echo ucfirst($d[$j]); ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
        <?php
    }
    public function deleteFiles($id,$dir){
        $query = $this->conn->prepare("SELECT files FROM laporan WHERE id='".$id."'");
        $query->execute();
        $d = $query->fetch();
        $files = explode(',',$d['files']);
        foreach($files AS $files1){
            unlink($dir.$files1);
        }
    }
    public function multifileupload($dir,$name){
        $i = 0;
        $filelist = array();
        foreach($_FILES["file"]["tmp_name"] AS $files){
            $ext = explode('.',$_FILES["file"]["name"][$i]);
            $filename = str_replace(' ','_',$name).'-'.$i.'.'.$ext[1];
            move_uploaded_file($files,$dir.$filename);
            array_push($filelist,$filename);
            $i++;
        }
        return implode(',',$filelist);
    }
    public function relatedListTable($t,$q,$p = array(),$link){
        ?>
        <div class="col-lg-6 table-responsive">
        <h4><?php echo $t; ?></h4>
        <table class="table table-striped">
        <thead><tr>
        <?php
        foreach($p AS $p1){
            ?><th><?php echo $p1; ?></th><?php
        } ?>
        </tr></thead><tbody>
        <?php
        $query = $this->conn->prepare($q);
        $query->execute();
        $total = 0;
        $max = sizeof($p);
        while($d = $query->fetch()){ ?>
            <tr>
            <?php foreach($p AS $p1){
                echo '<td>'.$d[$p1].'</td>';
            } ?>
            <td><a href="<?php echo $link.$d['id']; ?>"><i class="fa fa-file-text fa-fw"></i></a></td>
            </tr>
            <?php
            $total += $d[$p[$max-1]];
        } ?>
        </tbody>
        </table>
        </div>
        <?php
    }
    public function singleSelect($q,$id=array()){
        $query = $this->conn->prepare($q);
        $query->execute();
        $out=array();
        while($d = $query->fetch()){ 
            foreach($id AS $id1){ array_push($out,$d[$id1]); }}
        return $out;
    }
    public function __destruct() {
        $this->conn=NULL;
    }
}
