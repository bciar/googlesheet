<?php
require __DIR__ . '/vendor/autoload.php';
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\DefaultServiceRequest;
ini_set('max_execution_time', 1000);
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/client_secret.json');
$client = new Google_Client;

//$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
//$client->setHttpClient($guzzleClient);

$client->useApplicationDefaultCredentials();

$client->setApplicationName("Something to do with my representatives");
$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);

if ($client->isAccessTokenExpired()) {
    $client->refreshTokenWithAssertion();
}


$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
//print_r($accessToken);
ServiceRequestFactory::setInstance(
    new DefaultServiceRequest($accessToken)
);

// Get our spreadsheet
$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)->getSpreadsheetFeed()->getByTitle('Forecast Ex - Google Dev');

//Get FileID
$fullID = $spreadsheet->getId(); 
$arr_temp = explode('/',$fullID);
$fileID = $arr_temp[count($arr_temp)-1];
//echo $fileID; exit;

//Google Service Drive
$service = new Google_Service_Drive($client);

//$revisions = new Google_Service_Drive_Resource_Revisions;
$revisions = $service->revisions->listRevisions($fileID)->revisions;
$param_date = '5/31/2017';
foreach ($revisions as $revision)
{
    $dtA = new DateTime($revision->modifiedTime);
    $date_A = $dtA->format('m/d/Y');

    $dtB = new DateTime($param_date);
    $date_B = $dtB->format('m/d/Y'); 
    if ($date_A == $date_B)
    {
        var_dump($revision); exit;
        echo $revision->id;
        /*$ff = $service->files->get($fileID, ['revisionId'=>$revision->id]);
        var_dump($ff); exit;*/
        $xx = $service->revisions->get($fileID, $revision->id, ['alt'=>'media']);
        var_dump($xx);
        exit;
        //$service->revisions->update($fileID, $revision->id, $revision->postBody);
        /*
echo '<br>';
echo 'Success!';*/
       // exit;
    }
}
//var_dump($revisions);

// Get the first worksheet (tab)
$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();

$worksheet = $worksheets[0];
$cellFeed = $worksheet->getCellFeed();
$rows = $cellFeed->toArray();
echo $rows[4][2];
//echo gettype($rows);

$goalsheet = $worksheets[1];
//$goalCellFeed = $goalsheet->getCellFeed();
//$goalRows = $goalCellFeed->toArray();
$cellFeed = $goalsheet->getCellFeed();
$cellFeed->editCell(5, 5, $rows[4][2]);
$cellFeed->editCell(5, 6, $rows[4][3]);
$cellFeed->editCell(5, 7, $rows[4][4]);


$cellFeed->editCell(7, 5, $rows[22][2]);
$cellFeed->editCell(7, 6, $rows[22][3]);
$cellFeed->editCell(7, 7, $rows[22][4]);


$cellFeed->editCell(9, 5, $rows[24][2]);
$cellFeed->editCell(9, 6, $rows[24][3]);
$cellFeed->editCell(9, 7, $rows[24][4]);

$cellFeed->editCell(11, 5, $rows[26][2]);
$cellFeed->editCell(11, 6, $rows[26][3]);
$cellFeed->editCell(11, 7, $rows[26][4]);


$cellFeed->editCell(13, 5, $rows[28][2]);
$cellFeed->editCell(13, 6, $rows[28][3]);
$cellFeed->editCell(13, 7, $rows[28][4]);


$cellFeed->editCell(14, 5, $rows[29][2]);
$cellFeed->editCell(14, 6, $rows[29][3]);
$cellFeed->editCell(14, 7, $rows[29][4]);
//$firstEntryLastName->update('Doe');

//echo $topLeftCornerCell->content; // "last_name"
?>
<!--
<html>
<head>
<title>GoogleSheet Processing</title>
</head>
<body onload="loading('myDiv');">
<div id="myDiv"></div>
<script>
function loading(div_id){
    var display = document.getElementById(div_id);
    display.innerHTML = "Please Wait...";
}
</script>
</body>
</html>
-->