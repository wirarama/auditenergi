<?php
class viewchart extends template{
    var $title = "Chart";
    public $bidang = array();
    public $tahun = array();
    private $chart1;
    private $chart2;
    public function objdef() {
        require 'control/chart.php';
        $this->home = 'index.php?p=chart';
        $this->obj = new chart($this->home);
    }
    public function preload() {
        parent::preload();
        
    }
    public function bodymain() {
        $this->chartPanel('donut (Watt/minutes)','donut');
        $this->chartMoving('Real time data');
        $this->obj->chartQuery("SELECT * FROM sensor_input LIMIT 0,24",
                    array('id','current','voltage','power'));
        $this->chartPanel('Variable','variable');
        $this->obj->chartQuery("SELECT * FROM sensor_input LIMIT 0,24",
                    array('id','temperature','light','power'));
        $this->chartPanel('Environtment','environtment');
    }
    public function chartMoving($head){
        ?>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $head; ?>
                </div>
                <div class="panel-body">
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-line-chart-moving"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    public function chartPanel($head,$chart){
        ?>
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo ucfirst($head); ?>
                    </div>
                    <div class="panel-body">
                        <div id="<?php echo $chart; ?>"></div>
                    </div>
                </div>
            </div>
        <?php
    }
    public function headcss() {
        parent::headcss();
        $this->headcssadd();
    }
    public function bodymainjs(){
        parent::bodymainjs();
        $this->bodymainjsAdditional();
        ?>
        <script>
        Morris.Area({
            element : 'variable',
            data:[<?php echo $this->obj->chartd[0]; ?>],
            xkey:'id',
            ykeys:['current','voltage','power'],
            labels:['Current','Voltage','Power'],
            hideHover: 'auto',
            resize: true
        });
        Morris.Area({
            element : 'environtment',
            data:[<?php echo $this->obj->chartd[1]; ?>],
            xkey:'id',
            ykeys:['temperature','light','power'],
            labels:['Temperature','Light','Power'],
            hideHover: 'auto',
            resize: true
        });
        <?php
        $areas = array();
        for($i=1;$i<=5;$i++){
            array_push($areas,$this->obj->singleSelect("SELECT SUM(power) AS power1 "
                    . "FROM sensor_input WHERE area='".$i."'",array('power1')));
        }
        ?>
        Morris.Donut({
            element: 'donut',
            data: [
              {label: "Room 1", value: <?php echo $areas[0][0]; ?>},
              {label: "Room 2", value: <?php echo $areas[1][0]; ?>},
              {label: "Room 3", value: <?php echo $areas[2][0]; ?>},
              {label: "Room 4", value: <?php echo $areas[3][0]; ?>},
              {label: "Kitchen", value: <?php echo $areas[4][0]; ?>}
            ]
          });
        </script>
        <?php
    }
}