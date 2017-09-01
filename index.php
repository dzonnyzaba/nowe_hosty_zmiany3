<?php
    require_once 'glowna.php';
    
    $tresc = "<a href=\"z_xml_do_bazy_oop.php\">Sprawdź czy są nowe hosty w sieci</a><br>
	 <a href=\"generuj_plik_oop.php\">Generuj plik z bazy</a>";
	 if(isset($_GET['error'])){
		 $tresc .= "<p style=\"color:red\">Brak plików do sprawdzenia</p>";
	 }
    
    $glowna = new Glowna($tresc);
    
    $glowna->wyswietlCalosc();


