<?php

class Zakoncz{

    public $db;
    private $class_db_file;
    
    private $zapytanie_zakoncz;
    private $zapytanie_zeruj;

    public function __construct(){

        $this->zapytanie_zakoncz = "DELETE FROM tmp";
        $this->zapytanie_zeruj = "ALTER TABLE tmp AUTO_INCREMENT=0";
        
        $this->class_db_file = 'db.php';

        if(file_exists($this->class_db_file)){
            require_once($this->class_db_file);
            $this->db = new db();
        }else{
            echo "brak pliku z klasą do łączenia z db";
        }
    }
        
    public function czyscTabele(){
	$rezultat_zakoncz = mysqli_query($this->db->connection, $this->zapytanie_zakoncz);        
        $rezultat_zeruj = mysqli_query($this->db->connection, $this->zapytanie_zeruj);
    }
    
    public function __destruct(){
        //mysqli_close($this->db->connection);
	header('Location: index.php');
    }
}
$koniec = new Zakoncz();
$koniec->czyscTabele();

	
