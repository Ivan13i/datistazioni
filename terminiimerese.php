<?php
// URL JSON della tua stazione (sostituire col tuo reale)
$url = "https://www.weatherlink.com/embeddablePage/summaryData/13ac57c7c766420d842c4457f843e709";

// Scarica i dati JSON
$jsonData = file_get_contents($url);
if ($jsonData === false) {
    die(json_encode(["errore" => "Impossibile recuperare i dati da Weatherlink."]));
}

// Decodifica JSON
$data = json_decode($jsonData, true);
if ($data === null) {
    die(json_encode(["errore" => "Errore nel parsing dei dati JSON."]));
}

// Funzioni di supporto
function getCurrConvertedValue($data, $name) {
    foreach ($data['currConditionValues'] as $sensor) {
        if ($sensor['sensorDataName'] === $name) {
            return str_replace(',', '.', $sensor['convertedValue']);
        }
    }
    return null;
}

function getHighLowConvertedValue($data, $name) {
    foreach ($data['highLowValues'] as $sensor) {
        if ($sensor['sensorDataName'] === $name) {
            return str_replace(',', '.', $sensor['convertedValue']);
        }
    }
    return null;
}

function getRainConvertedValue($data, $type) {
    foreach ($data['aggregatedValues'] as $sensor) {
        if ($sensor['sensorDataName'] === "Rain") {
            return isset($sensor['convertedValues'][$type]) ? str_replace(',', '.', $sensor['convertedValues'][$type]) : 0;
        }
    }
    return 0;
}

function getCurrNonConvertedValue($data, $name) {
    foreach ($data['currConditionValues'] as $sensor) {
        if ($sensor['sensorDataName'] === $name) {
            return $sensor['value'];
        }
    }
    return null;
}

// Trasforma i dati nel formato JSON puro
$output = [
    "nome" => "Termini Imerese - ",
    "altitudine" => 40,
    "provincia" => "PA",
    "temperatura" => floatval(getCurrConvertedValue($data, "Temp")),
    "temperatura_max" => floatval(getHighLowConvertedValue($data, "High Temp")),
    "temperatura_min" => floatval(getHighLowConvertedValue($data, "Low Temp")),
    "umidita" => floatval(getCurrConvertedValue($data, "Hum")),
    "pressione" => floatval(getCurrConvertedValue($data, "Barometer")),
    "vento" => floatval(getCurrConvertedValue($data, "Wind Speed")),
    "vento_max" => floatval(getHighLowConvertedValue($data, "High Wind Speed")),
    "direzione" => floatval(getCurrNonConvertedValue($data, "Wind Direction")),
    "Wind Chill" => floatval(getCurrConvertedValue($data, "Wind Chill")),
    "Wet Bulb" => floatval(getCurrConvertedValue($data, "Wet Bulb")),
    "Dew Point" => floatval(getCurrConvertedValue($data, "Dew Point")),
    "rate_rain" => floatval(getCurrConvertedValue($data, "Rain Rate")),
    "day_rain" => floatval(getRainConvertedValue($data, "DAY")),
    "month_rain" => floatval(getRainConvertedValue($data, "MONTH")),
    "year_rain" => floatval(getRainConvertedValue($data, "YEAR")),
    "data" => date("Y-m-d H:i:s"),
    // Da inserire manualmente
    "Lat" => 37.975833,
    "Lon" => 13.697222,
    "immagini" => ["https://live-meteo-alia-e-dintorni.pantheonsite.io/wp-content/uploads/2024/08/termini-imerese-filippo.jpg", ""],
    "link_stazione" => "https://live-meteo-alia-e-dintorni.pantheonsite.io/stazioni-meteo/stazioni-meteo-amiche/stazione-termini-imerese-filippo-brugnone/",
    "link_webcam" => ""
];

// Output JSON puro senza escape extra
header('Content-Type: application/json');
echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>