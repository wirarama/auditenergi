<?php
class view implements viewinterface{
    public $title = "";
    public $obj;
    public $home;
    public $mainTitle = 'Smart Energy Audit System';
    public function objdef(){
        $this->obj = new database();
    }
    public function preload(){
        $this->objdef();
        if(!empty(filter_input(0,'edit'))){
            $this->obj->qupdate();
            header('location:'.$this->home);
        }else if(!empty(filter_input(0,'submit'))){
            $this->obj->qinsert();
            header('location:'.$this->home);
        }else if(!empty(filter_input(1,'hapus'))){
            $this->obj->qdelete();
            header('location:'.$this->home);
        }
    }
    public function bodyheader() {
        if(!empty(filter_input(2,'login'))){
        ?>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $this->title; ?></h1>
            </div>
        </div>
        <?php }
    }
    public function bodyheadernav(){
        if(!empty(filter_input(2,'login'))){
        ?>
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo $this->mainTitle; ?> - <?php echo $this->title; ?></a>
            </div>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <?php $this->bodyheadernavcontent(); ?>
                    </ul>
                </div>
            </div>
        </nav>
        <?php }
    }
    public function navadmin(){ ?>
        <li><a href="index.php?p=area"><i class="fa fa-android fa-fw"></i> Area</a></li>
        <li><a href="index.php?p=loads"><i class="fa fa-anchor fa-fw"></i> Loads</a></li>
        <li><a href="index.php?p=rules"><i class="fa fa-book fa-fw"></i> Rules</a></li>
        <li><a href="index.php?p=fuzzy"><i class="fa fa-cc-discover fa-fw"></i> Fuzzy</a></li>
        <li><a href="index.php?p=sensor_input"><i class="fa fa-bank fa-fw"></i> Sensor Input</a></li>
        <li><a href="index.php?p=chart"><i class="fa fa-pie-chart fa-fw"></i> Chart</a></li>
        <li><a href="index.php?p=login"><i class="fa fa-user fa-fw"></i> Admin</a></li>
            <?php
    }
    public function navpegawai(){
        ?>
        <li><a href="index.php?p=rules"><i class="fa fa-book fa-fw"></i> Rules</a></li>
        <li><a href="index.php?p=chart"><i class="fa fa-pie-chart fa-fw"></i> Chart</a></li>
        <li><a href="index.php?p=login&edit=<?php echo filter_input(2,'login'); ?>"><i class="fa fa-user fa-fw"></i>Edit Admin</a></li>
        <?php
    }
    public function bodyheadernavcontent(){
        ?>
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i> Home</a></li>
        <?php switch(filter_input(2,'status')){
            case('admin'): $this->navadmin(); break;
            case('user'): $this->navuser(); break;
        }
        ?>
        <li><a href="index.php?logout=1"><i class="fa fa-outdent fa-fw"></i> Logout</a></li>
        <?php
    }
    public function headmeta(){
        ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo $this->mainTitle; ?> - <?php echo $this->title; ?></title>
        <?php
    }
    public function headcss(){
        ?>
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="dist/css/sb-admin-2.min.css" rel="stylesheet">
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <?php 
        if((empty(filter_input(1,'input')) && empty(filter_input(1,'edit')))){ $this->headcssTable(); }
    }
    public function headcssTable(){
        ?>
        <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
        <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
        <?php
    }
    public function headcssadd(){
        ?>
        <link href="vendor/morrisjs/morris.css" rel="stylesheet">
        <?php
    }
    public function bodymain() {}
    public function bodymainjs(){
        ?>
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="dist/js/sb-admin-2.min.js"></script>
        <script src="vendor/metisMenu/metisMenu.min.js"></script>
        <?php 
        if((empty(filter_input(1,'input')) && empty(filter_input(1,'edit'))) 
                || empty(filter_input(1,'p'))){ $this->bodymainjsTable(); } 
    }
    public function bodymainjsTable(){
        ?>
        <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
        <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>
        <script>$(document).ready(function() { $('#dataTables-example').DataTable({ responsive:true });});</script>
        <?php
    }
    public function bodymainjsAdditional(){
        ?>
        <script src="vendor/raphael/raphael.min.js"></script>
        <script src="vendor/morrisjs/morris.min.js"></script>
        <!-- Flot Charts JavaScript -->
        <script src="vendor/flot/excanvas.min.js"></script>
        <script src="vendor/flot/jquery.flot.js"></script>
        <script src="vendor/flot/jquery.flot.pie.js"></script>
        <script src="vendor/flot/jquery.flot.resize.js"></script>
        <script src="vendor/flot/jquery.flot.time.js"></script>
        <script src="vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>
        <script src="vendor/flot/flot-data.js"></script>
        <?php
    }
    public function bodyfooter() {
        ?>
        <h4>copyright &COPY; 2018</h4>
        <?php
    }
}