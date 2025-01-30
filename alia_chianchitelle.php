<?php
header('Content-Type: text/html; charset=UTF-8');

// URL del file con i dati meteo
$url = "https://meteofontanamurata.altervista.org/scraping/tutte/converted/alia_chianchitelle.php";

// File di cache per evitare richieste troppo frequenti
$cacheFile = 'cache.json';
$cacheTime = 300; // 5 minuti in secondi

// Controlla se il file di cache esiste e se è ancora valido
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    // Leggi i dati dalla cache
    $result = file_get_contents($cacheFile);
} else {
    // Recupera il contenuto del file
    $response = file_get_contents($url);

    // Controlla se ci sono errori
    if (!$response) {
        die("Errore nel caricamento dei dati meteo.");
    }

    // Suddivide la stringa in array basato sugli spazi
    $dataArray = explode(" ", trim($response));

    // Controlla se il numero di elementi è corretto
    if (count($dataArray) < 15) {
        die("Errore nei dati ricevuti.");
    }

    // Mappa dei dati
    $data = [
        "Nome" => "Alia Chianc.",
        "Altitudine" => 710,
        "Data" => $dataArray[0],
        "Ora" => $dataArray[1],
        "Temperatura" => $dataArray[2],
        "Temperatura Max" => $dataArray[3],
        "Temperatura Min" => $dataArray[4],
        "Umidità" => $dataArray[5],
        "Vento" => $dataArray[6],
        "Raffica" => $dataArray[7],
        "Direzione" => $dataArray[8],
        "Vento Max" => $dataArray[9],
        "Dew Point" => $dataArray[10],
        "Wind Chill" => $dataArray[11],
        "Day Rain" => $dataArray[12],
        "Month Rain" => $dataArray[13],
        "Year Rain" => $dataArray[14],
        "Pressione" => isset($dataArray[15]) ? $dataArray[15] : "N/A"
    ];

    // Converte i dati in JSON
    $result = json_encode($data, JSON_UNESCAPED_UNICODE);

    // Salva i dati nella cache
    file_put_contents($cacheFile, $result);
}

// Decodifica i dati JSON
$data = json_decode($result, true);
$iconUrl = "https://meteofontanamurata.altervista.org/Screenshot_2025-01-29_alle_20.55.38.png"; // Icona della fonte dati

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Forza la modalità standard su IE -->
    <title>Dati Stazione Meteo</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #005aa7;
            color: white;
        }
        .temp-max {
            color: red;
            font-weight: bold;
        }
        .temp-min {
            color: blue;
            font-weight: bold;
        }
        .icon {
            width: 30px;
            height: auto;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Nome</th>
            <th>Data</th>
            <th>Ora</th>
            <th>Temperatura</th>
            <th>Temp Max</th>
            <th>Temp Min</th>
            <th>Umidità</th>
            <th>Vento</th>
            <th>Direzione</th>
            <th>Vento Max</th>
            <th>Pioggia Giornaliera</th>
            <th>Pioggia Mensile</th>
            <th>Pioggia Annuale</th>
            <th>Pressione</th>
            <th>Fonte</th>
        </tr>
        <tr>
            <td><a href="https://dev-meteo-alia-e-dintorni.pantheonsite.io/stazioni-meteo/stazione-di-alia-contrada-chianchitelle/" target="_blank"><?php echo $data['Nome']; ?></a></td>
            <td><?php echo $data['Data']; ?></td>
            <td><?php echo $data['Ora']; ?></td>
            <td><?php echo $data['Temperatura']; ?></td>
            <td class="temp-max"><?php echo $data['Temperatura Max']; ?></td>
            <td class="temp-min"><?php echo $data['Temperatura Min']; ?></td>
            <td><?php echo $data['Umidità']; ?></td>
            <td><?php echo $data['Vento']; ?></td>
            <td><?php echo $data['Direzione']; ?></td>
            <td><?php echo $data['Vento Max']; ?></td>
            <td><?php echo $data['Day Rain']; ?></td>
            <td><?php echo $data['Month Rain']; ?></td>
            <td><?php echo $data['Year Rain']; ?></td>
            <td><?php echo $data['Pressione']; ?></td>
            <td><img src="<?php echo $iconUrl; ?>" class="icon" alt="Fonte dati"></td>
        </tr>
    </table>
</body>
</html>
