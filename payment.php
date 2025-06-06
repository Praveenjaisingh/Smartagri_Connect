<?php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "agri_connect";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $conn->real_escape_string(trim($_POST["fullname"]));
    $email = $conn->real_escape_string(trim($_POST["email"]));
    $amount = floatval($_POST["amount"]);
    $cardnumber = $conn->real_escape_string(trim($_POST["cardnumber"]));
    $expdate = $conn->real_escape_string($_POST["expdate"]);
    $cvv = $conn->real_escape_string(trim($_POST["cvv"]));

    
    if (empty($fullname) || empty($email) || empty($amount) || empty($cardnumber) || empty($expdate) || empty($cvv)) {
        die("All fields are required.");
    }

    $sql = "INSERT INTO payments (fullname, email, amount, cardnumber, expdate, cvv)
            VALUES ('$fullname', '$email', '$amount', '$cardnumber', '$expdate', '$cvv')";

    if ($conn->query($sql) === TRUE) {
        header("Location: paymentthankyou.html");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
