<?php


class XML_do_bazy{
    //private $vlan;
    private $lista_xmli;
    private $dataczas;
    public $db;
    private $class_db_file;
	
    
    public function __construct(){
        //$this->vlan = 10;
	$this->lista_xmli = array();
        $this->dataczas = date("d-m-y");
        $this->class_db_file = 'db.php';
        if(file_exists($this->class_db_file)){
            require_once($this->class_db_file);
            $this->db = new db();
        }else{
            echo "brak pliku z klasą do łączenia z db";
        }
    }

    private function pobierzPlikiXML(){
        $dir = "xml";
        $tabtmp = array();
        $files = scandir($dir);
        foreach($files as $f){
            if($f!="." && $f!=".."){
                $tabtmp[]=$f;		
            }
        }

        if(count($tabtmp)>=1){
                return $tabtmp;
        }else{
                echo "nieeeeeeeeeeeeeeee";
                header('location: index.php?error=1');
                exit();
        }
        var_dump($tabtmp);
    }
	
	
    public function tworzStringTxt($plik_xml){
        if($plik_xml=="10_10_0_0-18.xml"){
            $vlan = 10;
        }else if($plik_xml=="10_10_64_0-18.xml"){
            $vlan = 64;
        }else{
            $vlan = 0;
        }
        $obiekt = simplexml_load_file("xml/".$plik_xml);
        $attrdate = $obiekt->runstats->finished->attributes();
        $attrdate = $obiekt->runstats->finished->attributes();
        $datatab = $attrdate['timestr'];
        $data = $this->utworzDate($datatab);
        $tablica = array();
        $string = "";
        $licznik_wierszy=0;
        foreach($obiekt as $host){
            if(isset($host->address[1]) && isset($host->address[0])){
                $attrmac = $host->address[1]->attributes();
                $ajpi = $host->address[0]->attributes();
                $mac = (string)$attrmac['addr'];
                $ip = (string)$ajpi['addr'];
                $licznik_wierszy++;
                $string .= $licznik_wierszy.",".$mac.",".$ip.",".$data.",".$vlan."\r\n";
            }
        }
        return $string;
    }
    
    public function tworzPlikTxt(){
        $this->lista_xmli = $this->pobierzPlikiXML();
        foreach($this->lista_xmli as $plik_xml){
            file_put_contents("txt/".$plik_xml.".txt", $this->tworzStringTxt($plik_xml));
        }
    }
    
    public function wypelnijTabliceTmp(){
	var_dump($this->lista_xmli)."<br>";
        foreach($this->lista_xmli as $plik_xml){
        $sql = "LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/nowe_hosty/nowe_hosty_baza/txt/$plik_xml.txt' IGNORE INTO TABLE tmp 
			FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' (@klucz, nowy_mac, nowy_ip, data, VLAN)";
	echo $sql;
        if($result = mysqli_query($this->db->connection, $sql)){
        $result = mysqli_query($this->db->connection, $sql);
	if(!file_exists('C:/xampp/htdocs/nowe_hosty/nowe_hosty_baza/stare_pliki_xml/'.$this->dataczas)){
            mkdir('C:/xampp/htdocs/nowe_hosty/nowe_hosty_baza/stare_pliki_xml/'.$this->dataczas);
	}
	rename('C:/xampp/htdocs/nowe_hosty/nowe_hosty_baza/xml/'.$plik_xml, 
	'C:/xampp/htdocs/nowe_hosty/nowe_hosty_baza/stare_pliki_xml/'.$this->dataczas.'/'.$plik_xml);
        }else{
            echo "coś źle";
            echo $sql;
        }
	}
        //var_dump($result);
        //mysqli_close($this->db->connection);
    }

public function utworzDate($s){
        $podziel = explode(" ", $s);
        $year = $podziel[4];
        $day = $podziel[2];

        switch($podziel[1]){
                case "Jan":
                        $month = "01";
                        break;
                case "Feb":
                        $month = "02";
                        break;
                case "Mar":
                        $month = "03";
                        break;
                case "Apr":
                        $month = "04";
                        break;
                case "May":
                        $month = "05";
                        break;
                case "Jun":
                        $month = "06";
                        break;
                case "Jul":
                        $month = "07";
                        break;
                case "Aug":
                        $month = "08";
                        break;
                case "Sep":
                        $month = "09";
                        break;
                case "Oct":
                        $month = "10";
                        break;
                case "Nov":
                        $month = "11";
                        break;
                case "Dec":
                        $month = "12";
                        break;
                default:
                        $month = "01";
        }	
        return $year."-".$month."-".$day;
}

}

$nowyxml = new XML_do_bazy();
$nowyxml->tworzPlikTxt();
$nowyxml->wypelnijTabliceTmp();
//$nowyxml->pobierzPlikiXML();
header('Location: roznice_oop.php');
