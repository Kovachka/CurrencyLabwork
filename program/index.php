<?php
$startDate = new DateTime("-7 days");
$today = new DateTime("NOW");
$formattedDates = [];
for ($i = 0; $i < 7; $i++) {
    $startDate->add(new DateInterval('P1D'));                                               //додати 1 день до початкової дати
    array_push($formattedDates, explode("-", $startDate->format("Y-m-d")));
}
$responses = [];
foreach ($formattedDates as $date) {
    $date = implode("", $date);
    array_push($responses, json_decode(file_get_contents("https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?date=$date&json")));
}
$selectedValues = [];

foreach ($responses as $response) {
    foreach ($response as $value) {
        if (empty($selectedValues[$value->cc]["rates"]) == true)
            $selectedValues[$value->cc] = ['rates' => [], "name" => $value->txt, "cc" => $value->cc];
        else
            $selectedValues[$value->cc] = ['rates' => $selectedValues[$value->cc]["rates"], "name" => $value->txt, "cc" => $value->cc];
        array_push($selectedValues[$value->cc]['rates'], $value->rate);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ExChange</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.js"
            integrity="sha512-CWVDkca3f3uAWgDNVzW+W4XJbiC3CH84P2aWZXj+DqI6PNbTzXbl1dIzEHeNJpYSn4B6U8miSZb/hCws7FnUZA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="stylesheets/application.css">
</head>
<body>
<form action="index.php" method="post">
    <div class="content" id="content">
        <input type="text" class="form__field" id="change" placeholder="Enter the sum">
        <h5 id="changeOutput"></h5>
        <select name="currency" id="currency">
            <?php foreach ($selectedValues as $value) { ?>
                <option value='<?php echo json_encode($value); ?>'><?php echo $value["name"].": ".$value['cc']; ?></option>
                <?php
            } ?>
        </select>
        <canvas id="myChart"></canvas>
    </div>
</form>

</body>
<script src="Chart.js"></script>