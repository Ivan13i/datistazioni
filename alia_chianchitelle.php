<?php
// Imposta l'header per il corretto encoding
header('Content-Type: text/html; charset=UTF-8');

// URL del JSON con i dati meteo
$url = "https://meteofontanamurata.altervista.org/scraping/tutte/aliachianchitelle.php";

// Recupera il contenuto JSON
$json = file_get_contents($url);
$data = json_decode($json, true);

// Controlla se il JSON è stato caricato correttamente
if (!$data) {
    die("Errore nel caricamento dei dati meteo.");
}

// Estrai i dati e riformattali
$data_string = sprintf(
    "%s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s",
    date("d/m/Y", strtotime($data['Data'])),  // Data
    date("H:i", strtotime($data['Data'])),    // Ora
    $data['Temperatura'],                     // Temp. att.
    $data['Temperatura Max'],                 // Temp. max
    $data['Temperatura Min'],                 // Temp. min
    $data['Umidità'],                         // Umid. Rel.
    $data['Vento'],                           // Vento
    $data['Direzione'],                       // Raffica
    $data['Vento Max'],                       // Vento dir.
    $data['Wind Chill'],                      // Vento max
    $data['Dew Point'],                       // DewPoint
    $data['Wind Chill'],                      // Wind chill
    $data['Day Rain'],                        // Pioggia oggi
    $data['Month Rain'],                      // Pioggia mese
    $data['Year Rain'],                       // Pioggia anno
    $data['Pressione'] ? $data['Pressione'] : "N/A"  // Pressione atm.
);

// Mostra il risultato sulla pagina
echo $data_string;
?>
