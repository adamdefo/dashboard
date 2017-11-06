<?php

class DBconfig {
    protected $dbHost;
    protected $dbUserName;
    protected $dbUserPass;
    protected $dbName;

    function DBconfig() {
        $this -> dbHost = 'localhost';
        $this -> dbUserName = 'root';
        $this -> dbUserPass = 'root';
        $this -> dbName = 'dashboard';
    }
}