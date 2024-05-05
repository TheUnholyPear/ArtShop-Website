<?php
session_start();
unset($_SESSION["password"]);

if(isset($_POST["clearBasket"])){
    unset($_SESSION['basket']);
}

if (!isset($_SESSION["basket"])) {
    $_SESSION['basket'] = array();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0">
    <title>Shop front page</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f2f2f2;
            padding-bottom: 150px;
        }
        .container {
            overflow: hidden;
         }

        #header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }
        .block {

            position: relative;
            background-color: #fff;
            margin: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            float: left;
            padding: 10px;
            height: 300px;
            width: 250px;
            text-align: center;
            display: flex;
            flex-direction: column;

        }

        #formButton{
            position: relative;
            padding: 5px;
            margin-top: auto;

        }

        .block p{
            height: 70%;
            margin-top: 2%;
            margin-bottom: 0;
        }
        #feedback{
            text-align: center;
        }
        #Text {
            margin-bottom: 3%;
        }
        .block img {
            max-height: 55%;
            max-width: 100%;
            height: auto;
            width: auto;
            display: block;
            margin: auto;
        }

        .block button {
            position: relative;
            padding: 5px;
            background-color: #333;
            width: 100%;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .block button:hover {
            background-color: #2980b9;
        }
        .page {
            text-align: center;
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            bottom: 2%;
            padding: 10px;
            z-index: 1;
        }

        .page a{
            text-decoration: none;
            padding: 5px 10px;
            background-color: coral;
            color: #fff;
            border-radius: 4px;
            margin: 0 5px;
        }

        .page a:hover {
            background-color: chocolate;
        }

        #sidenav {
            position: fixed;
            bottom: 0;
            right: 0;
            width: auto;
            background-color: #333;
            padding: 15px;
            z-index: 2;
        }

        #sidenav button {
            color: white;
            font-size: 15px;
            text-decoration: none;
            background-color: #333;
            display: block;
            padding: 10px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        #sidenav button:hover {
            background-color: #555;
        }
    </style>

</head>
<body>

<div id="header">
    <h1>Cara's Art</h1>
</div>

<div id="feedback">
<?php
    if(isset($_POST["clearBasket"])){
        unset($_SESSION['basket']);
        echo "Basket has been cleared";
    }

    if(isset($_POST["addToBasket"])){

        if (!isset($_SESSION["basket"])) {
            $_SESSION['basket'] = array();
        }

        if (in_array($_POST["addToBasket"], $_SESSION["basket"])) {
            echo "Item is already in your basket.";
        }else{
            $_SESSION['basket'][] = $_POST["addToBasket"];
            echo "Item has been added to your basket";
        }
    }
?>
</div>

<?php
$host = -; //info taken out
$user = -; //info taken out
$pass = -; //info taken out
$dbname = $user;
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recordsPerPage = 12;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

$sql = "SELECT * FROM `ArtDB` LIMIT $offset,$recordsPerPage";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed " . $conn->error);
}
?>

<div class="container">
<?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo
                "<div class='block'>".
                "<div id='Text'>".
                $row["Name"].
                "</div>".
                "<img src='data:image/jpeg;base64,". base64_encode($row['Image']) ."'alt='". $row["Description"]."'> " .
                "<div id='formButton'>".
                "<form action='order.php' method='post'>" .
                "<button name='Order' type='submit' value='" . $row["Id"] . "'>Order</button>" .
                "</form>".
                "</div>" .
                "<div id='formButton'>".
                "<form method='post'>" .
                "<button name='addToBasket' type='submit' value='" . $row["Id"] . "'>Add to Basket</button>" .
                "</form>" .
                "</div>" .
                "</div>";
        }
    }

    ?>
</div>

<?php
$sql = "SELECT COUNT(*) AS total_pages FROM `ArtDB`";
$result = $conn->query($sql);

$totalRecords = 0;

if ($result && $row = $result->fetch_assoc()) {
    $totalRecords = $row['total_pages'];
}

$totalPages = ceil($totalRecords / $recordsPerPage);

?>

<div class="page">
    <?php
    echo "<p>";

    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        echo "<a href='index.php?page=$prevPage'>&#8249 Previous</a> ";
    }

    echo "Page ".$currentPage;

    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        echo "<a href='index.php?page=$nextPage'>Next &#8250</a> ";
    }

    echo "</p>"; ?>
</div>

<div id="sidenav">
    <?php
    if(!empty($_SESSION["basket"])) {
        echo "<form action='basket.php' method='post'> <button name='toBasket' type='submit' >Basket</button> </form> ";

        echo "<form method='post'> <button name='clearBasket' type='submit' >Clear Basket</button> </form> ";

        echo "<form action='order.php'> <button name='toOrder' type='submit' >Order Items</button> </form> ";
    }
    ?>
    <form action="admin.php"> <button name='admin' type='submit' >Admin</button> </form>
</div>

<?php
// Disconnect
$conn->close();
?>
</body>
</html>