<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Art Order Form</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f2f2f2;
        }

        body h1 {
            text-align: center;
        }

        #header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
        #rightContainer{
            position: absolute;
            flex-wrap: wrap;
            justify-content: flex-start;
            top: 160px;
            right: 0;
            height: 100vh;
            width: 49%;
            padding-left: 1%;
        }
        #labelInput{
            display: inline-block;
            float: left;
            clear: left;
            width: 100%;
            text-align: left;
            padding: 5px;
        }
        #addInput{
            display: inline-block;
            float: left;
            padding: 5px;
            margin-bottom: 5%;
            width: 51%;
        }

        #submitButton{
            width: 30%;
        }

        #submitButton button {
            position: relative;
            align-items: center;
            padding: 12px;
            background-color: #333;
            width: 100%;
            color: white;
            border: none;
            cursor: pointer;
            font-size: larger;
            transition: background-color 0.3s;
        }
        #submitButton button:hover {
            background-color: #2980b9;
        }
        #details {
            word-break: break-word;
            text-align: left;
            margin: 10px;
        }
        #finish{
            text-align: center;
            margin-top: 20%;
            color: darkgoldenrod;
            font-size: xxx-large;
            font-weight: bold;

        }
        #Empty{
            position: absolute;
            text-align: center;
            top: 40%;
            font-size: xxx-large;
            font-weight: bold;
            width: 80%;
            margin-left: 10%;
        }
        .container {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: stretch;
            height: 100vh;
            width: 50%;
        }
        #blockContent{
            position: absolute;
            background-color: #f2f2f2;
            margin: 5px;
            padding: 10px;
            width: 250px;
            text-align: center;
            bottom: 0;
            right: 0;
        }
        #Text {
            margin-bottom: 3%;
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
    <h1>Order Page</h1>
</div>

<?php
    $fname = strip_tags(isset($_POST["Fname"]) && ($_POST["Fname"] !== "") ? $_POST["Fname"] : "");
    $sname = strip_tags(isset($_POST["Sname"]) && ($_POST["Sname"] !== "") ? $_POST["Sname"] : "");
    $phone = strip_tags(isset($_POST["Phone"]) && ($_POST["Phone"] !== "") ? $_POST["Phone"] : "");
    $email = strip_tags(isset($_POST["Email"])) && ($_POST["Email"] !== "") ? $_POST["Email"] : "";
    $address = strip_tags(isset($_POST["Address"])) && ($_POST["Address"] !== "") ? $_POST["Address"] : "";
    $postcode = strip_tags(isset($_POST["Postcode"])) && ($_POST["Postcode"] !== "") ? $_POST["Postcode"] : "";
    $country = strip_tags(isset($_POST["Country"])) && ($_POST["Country"] !== "") ? $_POST["Country"] : "";
    $city = strip_tags(isset($_POST["City"])) && ($_POST["City"] !== "") ? $_POST["City"] : "";

if ((!empty($_SESSION["basket"])) || isset($_POST["Order"])){

        if(isset($_POST["Order"])) {
            if (!in_array($_POST["Order"], $_SESSION["basket"])) {
                $_SESSION['basket'][] = $_POST["Order"];
            }
        }

    $ItemArr = $_SESSION["basket"];

    //Connecting to Database:
    $host = -; // info taken out
    $user = -; // info taken out
    $pass = -; // info taken out
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error){
        die("Connection failed : ".$conn->connect_error);
    }

    if ($fname && $sname && preg_match("/^[0-9]{8,15}$/", $phone) && $email && filter_var($email,FILTER_VALIDATE_EMAIL) && $address && $postcode && $country && $city) {

        foreach ($ItemArr as $ItemID) {
            $insert = "INSERT INTO `ArtOrdersDB` (`OrderID`, `Fname`, `Sname`, `Phone`, `Email`, `Address`, `Postcode`, `Country`, `City`, `ItemID`) VALUES (NULL, '$fname', '$sname', '$phone', '$email', '$address', '$postcode', '$country', '$city', '$ItemID');";


            if (!$conn->query($insert)) {
                die("Insert Failed" . $conn->error);
            }
        }
            echo "<div id='finish'>Your Item/s Have Been Ordered!!!!</div>";
            unset($_SESSION["basket"]);
        ?>

        <div id="sidenav">
            <form action='index.php'> <button name='toBasket' type='submit' >Home Page</button> </form>
            <form action="admin.php"> <button name='admin' type='submit' >Admin</button> </form>
        </div>

        <?php
    }else{
        ?>
        <h1>Welcome to Your Order Form </h1>

        <div id="rightContainer">
        <?php
            if (isset($_POST["submit"])) {
                if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                    echo "<p style='color: darkred; font-size: larger;'><b>Form completion errors please check all fields:</b></p>";

                    if(!$fname){
                        echo "<p>Forename - Cannot be Empty</p>";
                    }elseif((!(is_string($fname))) || (strlen($fname) > 50)){
                        echo "<p>Forename - Enter a valid Firstname less than 50 characters</p>";
                    }

                    if(!$sname){
                        echo "<p>Surname - Cannot be Empty</p>";
                    }elseif((!(is_string($sname))) || (strlen($sname) > 50)){
                        echo "<p>Surname - Enter a valid Surname less than 50 characters</p>";
                    }

                    if(!$phone) {
                        echo "<p>Phone - Cannot be Empty</p>";
                    }elseif(!preg_match("/^[0-9]{8,15}$/", $phone)){
                        echo "<p>Phone - Enter a valid Phone-number (8-15 numbers long)</p>";
                    }

                    if(!$email) {
                        echo "<p>Email - Cannot be Empty</p>";
                    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                        echo "<p>Email - Enter a valid Email</p>";
                    }

                    if(!$address){
                        echo "<p>Address - Cannot be Empty</p>";
                    }elseif((!(is_string($address))) || (strlen($address) > 255)){
                        echo "<p>Address - Enter a valid Address less than 255 characters</p>";
                    }

                    if(!$postcode){
                        echo "<p>Postcode - Cannot be Empty</p>";
                    }elseif((!(is_string($postcode))) || (strlen($postcode) > 20)){
                        echo "<p>Postcode - Enter a valid Postcode less than 20 characters</p>";
                    }

                    if(!$country){
                        echo "<p>Country - Cannot be Empty</p>";
                    }elseif((!(is_string($country))) || (strlen($country) > 50)){
                        echo "<p>Country - Enter a valid Country less than 50 characters</p>";
                    }

                    if(!$city){
                        echo "<p>City - Cannot be Empty</p>";
                    }elseif((!(is_string($city))) || (strlen($city) > 50)){
                        echo "<p>City - Enter a valid City less than 50 characters</p>";
                    }

                    echo "<br><br>";
            }
        }
?>
            <form method="post">
                <div id="addInput">
                    <h2>Personal Information:</h2>
                    <label id="labelInput">Firstname: <input type="text" name="Fname" value="<?php echo $fname ?>"></label>
                    <label id="labelInput">Surname: <input type="text" name="Sname" value="<?php echo $sname ?>"></label>
                    <label id="labelInput">Phone Number: <input type="text" name="Phone" value="<?php echo $phone ?>"></label>
                    <label id="labelInput">Email: <input type="text" name="Email" value="<?php echo $email ?>"</label>
                </div>
                <div id="addInput">
                    <h2>Address:</h2>
                    <label id="labelInput">Address: <input type="text" name="Address" value="<?php echo $address ?>"></label>
                    <label id="labelInput">Postcode: <input type="text" name="Postcode" value="<?php echo $postcode ?>"></label>
                    <label id="labelInput">Country: <input type="text" name="Country" value="<?php echo $country ?>"></label>
                    <label id="labelInput">City: <input type="text" name="City" value="<?php echo $city ?>"></label>
                </div>

                <div id="submitButton">
                    <button type="submit" name="submit">Order Your Items</button>
                </div>
            </form>
        </div>

        <div class="container">
            <?php
            foreach ($ItemArr as $ItemID){
                $sql = "SELECT * FROM `ArtDB` WHERE `Id` = '$ItemID'";;
                $result = $conn->query($sql);

                if (!$result){
                    die("Query failed ".$conn->error);
                }


                if($result->num_rows>0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div id=\"sideBlock\" style=\"background-image: url('data:image;base64," . base64_encode($row['Image']) . "'); position: relative; background-size: cover; background-repeat:no-repeat; background-position: center center; width: 100%;height: 100vh;background-repeat: no-repeat;margin-bottom: 10px; \">".
                            "<div id='blockContent'>".
                                 "<div id='Text'>" .
                                 $row["Name"] .
                                 "</div>".
                                 "<div id='details'>" .
                                 "<br>" .
                                 " <b>Details:</b>".
                                 "<br> Date: " . date('Y-m-d', strtotime($row["DateOfCompletion"])) .
                                 "<br> Dimentions: " . $row["Width"] . "x" . $row["Height"] ."mm" .
                                 "<br> Price: £" . $row["Price"] .
                                 "<br> Item ID:"  . $row["Id"] .
                                 "<p> Description: <br>"  . $row["Description"] . "</p>" .
                                 "</div>" .
                            "</div>".
                            "</div>";
                    }
                }
                $result->data_seek(0);
            }
            ?>
        </div>

        <div id="sidenav">
            <form action='index.php'> <button name='toBasket' type='submit' >Home Page</button> </form>
            <form action="admin.php"> <button name='admin' type='submit' >Admin</button> </form>
        </div>

    <?php
    //Disconnect
    $conn->close();
    }
}else{
    ?>
    <div id="Empty">There Is No Item Selected, Please Go Back To The Home Page and Select An Item</div>

    <div id="sidenav">
        <form action='index.php'> <button name='toBasket' type='submit' >Home Page</button> </form>
        <form action="admin.php"> <button name='admin' type='submit' >Admin</button> </form>
    </div>

    <?php
}
?>
</body>
</html>


