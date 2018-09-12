<?php
require 'class/viewinterface.php';
require 'class/view.php';
require 'class/template.php';
require 'class/databaseInterface.php';
require 'class/database.php';
require 'class/form.php';
switch(filter_input(1,'p')){
    case('area'):
        require 'view/area.php';
        $view = new viewarea();
        break;
    case('loads'):
        require 'view/loads.php';
        $view = new viewloads();
        break;
    case('fuzzy'):
        require 'view/fuzzy.php';
        $view = new viewfuzzy();
        break;
    case('fuzzy_membership'):
        require 'view/fuzzy_membership.php';
        $view = new viewfuzzy_membership();
        break;
    case('rules'):
        require 'view/rules.php';
        $view = new viewrules();
        break;
    case('sensor_input'):
        require 'view/sensor_input.php';
        $view = new viewsensor_input();
        break;
    case('login'):
        require 'view/login.php';
        $view = new viewlogin();
        break;
    case('chart'):
        require 'view/chart.php';
        $view = new viewchart();
        break;
    case('rand'):
        require 'view/rand.php';
        $view = new viewrand();
        break;
    default:
        require 'view/index.php';
        $view = new viewindex();
        break;
}