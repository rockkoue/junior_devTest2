<?php
define('USER', "root");
define('PASSWORD', "");
define('SERVER', "localhost");
define('DB', "test_billet");

$event_id = $_POST['event_id'];
$event_date = $_POST['event_date'];
$ticket_adult_price = $_POST['ticket_adult_price'];
$ticket_adult_quantity = $_POST['ticket_adult_quantity'];
$ticket_kid_price = $_POST['ticket_kid_price'];
$ticket_kid_quantity = $_POST['ticket_kid_quantity'];
$code_barres;


function insert($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity)
{

    try {
        $db = new PDO("mysql:host=" . SERVER . ";dbname=" . DB, USER, PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    //check if $code bare exist

    do {
        $message = '';
        $verify = 0;
        $code_barres = rand(1000000,  9000000);
        $query = "SELECT * FROM billet WHERE code_barres= $code_barre";
        $response = $db->query($query);
        $db->$response->execute();
        $verify = count($db->$response->fetchAll());

        if ($verify > 0) {
            $message = 'barcode already exists';
        } else {

            $statement = $db->prepare("INSERT INTO TABLE (event_id, event_date, ticket_adult_price,ticket_adult_quantity,ticket_kid_price,ticket_kid_quantity,code_barres)
    VALUES (:event_id,
           :event_date, 
           :ticket_adult_price,
           :ticket_adult_quantity,
           :ticket_kid_price,
           :ticket_kid_quantity,
           :$code_barres
           )");
            $ans = $statement->execute([
                'event_id' => $event_id,
                'event_date' => $event_date,
                'ticket_adult_price' => $ticket_adult_price,
                'ticket_adult_quantity' => $ticket_adult_quantity,
                'ticket_kid_price' => $ticket_kid_price,
                'ticket_kid_quantity' => $ticket_kid_quantity,
                'code_barres' => $code_barres
            ]);

            if ($ans) {

                $url = "http://localhost/testembauche/proved.php";

                $arraydata = array(
                    'code_barres' => $code_barres
                );
                $data = http_build_query($arraydata);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $res = curl_exec($ch);
                if (curl_exec($ch)) {
                    $decoded = json_encode($res);
                    echo $decoded;
                } else {
                    echo 'url not found';
                }

                curl_close($ch);
            }
            $message = 'order successfully booked';
        }
    } while ($verify > 0);

    echo $message;
}
