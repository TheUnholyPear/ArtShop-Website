<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Table</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f2f2f2;
            padding-bottom: 150px;
        }

        #header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .block {
            position: relative;
            background-color: #fff;
            margin: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 10px;
            width: 250px;
            min-height: 350px;
            text-align: center;
        }

        .block img {
            max-height: 55%;
            max-height: 165px;
            max-width: 100%;
            height: auto;
            width: auto;
            display: block;
            margin: auto;
        }

        .block p{
            overflow: hidden;
            word-break: break-word;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .block p:hover{
            position: relative;
            text-overflow: initial;
            white-space: normal;
            display: block;
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
            background-color: crimson;
        }

        #Text {
            margin-bottom: 3%;
        }

        #details {
            text-align: left;
            margin: 10px;
        }
        #Empty{
            text-align: center;
            margin-top: 15%;
            color: darkred;
            font-size: xxx-large;
            font-weight: bold;

        }

        #feedback{
            text-align: center;
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
    <h1>Your Basket</h1>
</div>

<div id="feedback">
    <?php
    if(isset($_POST["clearBasket"])){
        unset($_SESSION['basket']);
        echo "Basket has been cleared";
    }

    if(isset($_POST["remove"]) && (!empty($_SESSION["basket"]))) {
        if (($key = array_search($_POST["remove"], $_SESSION['basket'])) !== false) {
            unset($_SESSION['basket'][$key]);
            echo "Item has been removed from you Basket";
        }else{
            echo "Removing item unsuccessful";
        }
    }
?>
</div>

<?php
if (!empty($_SESSION["basket"])) {
    $ItemArr = $_SESSION["basket"];
    $host = -; // info taken out
    $user =  -; // info taken out
    $pass = -;  // info taken out
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>

    <div class="container">
        <?php
        foreach ($ItemArr as $ItemID) {
            $sql = "SELECT * FROM `ArtDB`  WHERE `Id` = '$ItemID'";
            $result = $conn->query($sql);

            if (!$result) {
                die("Query failed " . $conn->error);
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='block'>" .
                        "<div id='Text'>" .
                        $row["Name"] .
                        "</div>" .
                        "<img src='data:image/jpeg;base64," . base64_encode($row['Image']) . "'alt='" . $row["Description"] . "'> " .
                        "<div id='details'>" .
                        "<div id='removeButton'>".
                        "<form method='post'>" .
                        "<button name='remove' type='submit' value='" . $row["Id"] . "'>Remove from Basket</button>" .
                        "</form>" .
                        "</div>" .
                        "<br>" .
                        " Details:".
                        "<br> Date: " . date('Y-m-d', strtotime($row["DateOfCompletion"])) .
                        "<br> Dimentions: " . $row["Width"] . "x" . $row["Height"] ."mm" .
                        "<br> Price: £" . $row["Price"] .
                        "<p> Description: <br>"  . $row["Description"] . "</p>" .
                        "</div>" .
                        "</div>";
                }
            }
            $result->data_seek(0);
        }
        ?>
    </div>

    <div id="sidenav">
        <form action='index.php'> <button name='toBasket' type='submit' >Home Page</button> </form>
            <?php
            echo "<form method='post'> <button name='clearBasket' type='submit' >Clear Basket</button> </form> ";
            echo "<form action='order.php'> <button name='toOrder' type='submit' >Order Items</button> </form> ";
            ?>
        <form action="admin.php"> <button name='admin' type='submit' >Admin</button> </form>
    </div>

    <?php
    // Disconnect
    $conn->close();
}else{
    echo "<div id='Empty'> There is nothing in your Basket</div>";
    ?>

    <div id="sidenav">
        <form action='index.php'> <button name='toBasket' type='submit' >To Home</button> </form>
        <form action="admin.php"> <button name='admin' type='submit' >Admin</button> </form>
    </div>

<?php
}
?>
</body>
</html>