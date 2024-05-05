<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0">
    <title>SQL Table</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f2f2f2;
            padding-bottom: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-break: break-word;
        }

        th {
            border: 1px solid #ddd;
            padding: 8px;
            width: 1%;
            background-color: #333;
            color: white;
        }

        th:last-child {
            width: 3%;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
        table button{
            position: relative;
            padding: 5px;
            background-color: crimson;
            width: 100%;
            height 100%;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px
        }
        table button:hover{
            background-color: #333333;
        }
        #title{
            text-align: center;
            font-size: larger;
            font-weight: bold;
        }
        #addTitle{
            margin-top: 12px;
            margin-left: 7%;
            text-align: left;
            font-size: x-large;
            font-weight: bold;
        }
        #finish {
            text-align: center;
            margin-top: 20%;
            color: darkgoldenrod;
            font-size: xxx-large;
            font-weight: bold;
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
            margin-left: 10%;
            display: inline-block;
            float: left;
            padding: 5px;
        }
        #addErrors{
            margin-left: 8%;
        }
        #feedback{
            text-align: center;
        }
        #AddButton{
            margin-left: 10%;
            width: 40%;
        }
        #AddButton button {
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
        #AddButton button:hover {
            background-color: #2980b9;
        }
        #SubAddButton{
            text-align: center;
            position: fixed;
            align-items: center;
            left: 50%;
            transform: translateX(-50%);
            bottom: 2%;
        }
        #SubAddButton button {
            padding: 1.2vh 10vh;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            font-size: larger;
            transition: background-color 0.3s;

        }
        #SubAddButton button:hover {
            background-color: #2980b9;
        }

        #header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }

        #passLabel button{
            align-items: center;
            background-color: forestgreen;
            padding: 3px 10px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 10px;
            transition: background-color 0.3s;
        }
        #passLabel button:hover{
            background-color: darkgreen;
        }
        #password{
            font-size: larger;
            position:absolute;
            text-align:center;
            top:30%;
            width:100%;
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
    <h1>Admin Page</h1>
</div>

<?php
$name = strip_tags(isset($_POST["na"]) && ($_POST["na"] !== "") ? $_POST["na"] : "");
$width = strip_tags(isset($_POST["wi"]) && ($_POST["wi"] !== "") ? $_POST["wi"] : "");
$height = strip_tags(isset($_POST["he"]) && ($_POST["he"] !== "") ? $_POST["he"] : "");
$price = strip_tags(isset($_POST["pr"]) && ($_POST["pr"] !== "") ? $_POST["pr"] : "");
$description = strip_tags(isset($_POST["de"]) && ($_POST["de"] !== "") ? $_POST["de"] : "");

$PassInput = strip_tags(isset($_POST["Password"]) && ($_POST["Password"] !== "") ? $_POST["Password"] : "");

$fileType = isset($_FILES["image"]["type"]) && ($_FILES["image"]["type"] !== "") ? $_FILES["image"]["type"] : "";

$allowedTypes = ['image/jpeg', 'image/png'];

if(!isset($_SESSION["password"]) || ($_SESSION["password"] !== /* password taken out */)) {
    $_SESSION["password"] = $PassInput && $PassInput !== "" ? $PassInput : "";
}

$password = $_SESSION["password"];

if($password === /* password taken out */ ){

    //connecting to database
    $host = -; // info taken out
    $user =  -; // info taken out
    $pass = -;  // info taken out
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error){
        die("Connection failed : ".$conn->connect_error);
    }

    if(isset($_POST["items"])){  ?>

    <form method="post">
        <div id='title'> <p>Art Database:</p> </div>
        <?php
        //Issue sql the query
        $sql = "SELECT * FROM `ArtDB`";
        $result = $conn->query($sql);

        if (!$result){
            die("Query failed ".$conn->error);
        }
        ?>

        <table>
            <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date Of Completion</th>
            <th>Width</th>
            <th>Height</th>
            <th>Price</th>
            <th>Description</th>
            </tr>

            <?php
            if($result->num_rows>0){
                while ($row = $result->fetch_assoc()){
                    echo
                        "<tr>" .
                        "<td>" . $row["Id"] . "</td>" .
                        "<td>" . $row["Name"] . "</td>" .
                        "<td>"  .date('Y-m-d', strtotime($row["DateOfCompletion"])) . "</td>" .
                        "<td>" . $row["Width"] . "mm</td>" .
                        "<td>" . $row["Height"] . "mm</td>" .
                        "<td>£" . $row["Price"] . "</td>" .
                        "<td>" . $row["Description"] . "</td>" .
                        "</tr>";
                }
            }
            $result->data_seek(0); ?>
        </table>

        <div id="SubAddButton">
            <button type="submit" name="SubAdd">Add new painting</button>
        </div>
    </form>

    <div id="sidenav">
        <form action='index.php'> <button name='Home' type='submit' >Home Page</button> </form>

        <form action="admin.php" method="post">
            <button type="submit" name="orders">View Orders</button>
        </form>
    </div>

        <?php
    }elseif(isset($_POST["SubAdd"])){
        ?>
    <form method="post" enctype="multipart/form-data">
        <div id="addTitle">
            Add New Painting:
        </div>

        <div id="addInput">
            <label id="labelInput">Name: <input type="text" name="na" value="<?php echo $name ?>"> </label>
            <label id="labelInput">Width: <input type="text" name="wi" placeholder="mm" value="<?php echo $width ?>"></label>
            <label id="labelInput">Height: <input type="text" name="he" placeholder="mm" value="<?php echo $height ?>"></label>
            <label id="labelInput">Price: <input type="text" name="pr" value="<?php echo $price ?>"></label>
            <label id="labelInput">Description: <input type="text" name="de" value="<?php echo $description ?>"></label>
            <label id="labelInput">Image: <input type='file' accept="image/*" name='image' required></label>
        </div>

        <div id="AddButton">
            <button type="submit" name="SubAddItem" id="insert">Add Item</button>
        </div>
    </form>

    <div id="sidenav">
        <form action='index.php'> <button name='Home' type='submit' >Home Page</button> </form>

        <form action="admin.php" method="post">
            <button type="submit" name="orders">View Orders</button>
            <button type="submit" name="items">View Items</button>
        </form>
    </div>

        <?php
    }elseif(isset($_POST["SubAddItem"]) && $name && (is_numeric($width) && is_int($width + 0)) && ($width > 0) && (is_numeric($height) && is_int($height + 0)) && ($height > 0) && (is_numeric($price)) && $description && (strlen($description) <= 500) && (in_array($fileType, $allowedTypes))){
        $date = date('Y-m-d');

        $image = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));

        $safeDescription = addslashes($description);

        $sql = "INSERT INTO `ArtDB` (`ID`, `Name`, `DateOfCompletion`, `Width`, `Height`, `Price`, `Description`, `image`) VALUES (NULL,'$name','$date', '$width', '$height', '$price', '$safeDescription','$image');";
        $result = $conn->query($sql);

        if (!$result) {
            die("Query failed " . $conn->error);
        }else {
            echo "<div id='finish'> Item Has been Added </div>";
        }
        ?>

        <div id="sidenav">
            <form action='index.php'> <button name='Home' type='submit' >Home Page</button> </form>

            <form action="admin.php" method="post">
                <button type="submit" name="orders">View Orders</button>
                <button type="submit" name="items">View Items</button>
            </form>
        </div>

    <?php
    }elseif(isset($_POST["SubAddItem"])){
    ?>
        <div id="addTitle">
            Add New Painting:
        </div>

        <div id="addErrors">
    <?php
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                echo "<p style='color: darkred; font-size: larger;'><b>Form completion errors please check all fields:</b></p>";

                if(!$name){
                    echo "<p>Name - Cannot be empty</p>";
                }elseif((strlen($name) > 50)){
                    echo "<p>Name - Enter a valid input less than 50 characters</p>";
                }

                if(!$width) {
                    echo "<p>Width - Enter a numeric value</p>";
                }elseif(!($width>0)){
                    echo "<p>Width - Cannot be Nothing</p>";
                }elseif(!(is_numeric($width) && is_int($width + 0))){
                    echo "<p>Width - Enter A whole Numeric Number</p>";
                }

                if(!$height) {
                    echo "<p>Height - Enter a numeric value</p>";
                }elseif(!($height>0)){
                    echo "<p>Height - Cannot be Nothing</p>";
                }elseif(!(is_numeric($height) && is_int($height + 0))){
                    echo "<p>Height - Enter A whole Numeric Number</p>";
                }

                if(!$price) {
                 echo"<p>Price - Cannot Be Empty</p>";
                }elseif(!(is_numeric($price))){
                    echo "<p>Price - Enter a Valid Price</p>";
                }

                if(!$description){
                    echo "<p>Description - Cannot be empty</p>";
                }elseif((strlen($description) > 500)){
                    echo "<p>Description - Enter a valid input less than 500 characters -". " Currently it is ".strlen($description)."Characters Long </p>";
                }

                if (!in_array($fileType, $allowedTypes)) {
                    echo "<p>Image: Only JPEG and PNG files are allowed</p>";
                }

        }
        ?>
    </div>

        <form method="post" enctype="multipart/form-data">
            <div id="addInput">
                <label id="labelInput">Name: <input type="text" name="na" value="<?php echo $name ?>"> </label>
                <label id="labelInput">Width: <input type="text" name="wi" placeholder="mm" value="<?php echo $width ?>"></label>
                <label id="labelInput">Height: <input type="text" name="he" placeholder="mm" value="<?php echo $height ?>"></label>
                <label id="labelInput">Price: <input type="text" name="pr" value="<?php echo $price ?>"></label>
                <label id="labelInput">Description: <input type="text" name="de" value="<?php echo $description ?>"></label>
                <label id="labelInput">Image: <input type='file' accept="image/*" name='image' required></label>
            </div>

            <div id="AddButton">
                <button type="submit" name="SubAddItem" id="insert">Add Item</button>
            </div>
        </form>

        <div id="sidenav">
            <form action='index.php'> <button name='Home' type='submit' >Home Page</button> </form>

            <form action="admin.php" method="post">
                <button type="submit" name="orders">View Orders</button>
                <button type="submit" name="items">View Items</button>
            </form>
        </div>

    <?php
    }elseif(isset($_POST["orders"]) || isset($_POST["Sub"]) || isset($_POST["Remove"]) || $password === "bum"){

        echo "<div id='title'> <p>Customer Orders:</p> </div>";

        echo "<div id='feedback'>";
            if (isset($_POST["Remove"])) {
            $data = $_POST["Remove"];
            //Issue sql the query
            $sql = "DELETE FROM `ArtOrdersDB` WHERE OrderID = $data ";
            $result = $conn->query($sql);

            if (!$result) {
                die("Remove Query failed " . $conn->error);
            }else{
                echo "Order has been removed";
            }
        }
        echo "</div>";

            //Issue the query
        $sql = "SELECT * FROM `ArtOrdersDB`";
        $result = $conn->query($sql);

        if (!$result){
            die("Query failed ".$conn->error);
        }
        ?>

        <form action="admin.php" method="post">
            <table>
                <tr>
                    <th>OrderID</th>
                    <th>Forename</th>
                    <th>Surname</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Postcode</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>ItemID</th>
                    <th>Remove</th>
                </tr>

                <?php
                if($result->num_rows>0){
                    while ($row = $result->fetch_assoc()){
                        echo
                            "<tr>" .
                            "<td>" . $row["OrderID"] . "</td>" .
                            "<td>" . $row["Fname"] . "</td>" .
                            "<td>" . $row["Sname"] . "</td>" .
                            "<td>" . $row["Phone"] . "</td>" .
                            "<td>" . $row["Email"] . "</td>" .
                            "<td>" . $row["Address"]."</td>".
                            "<td>" . $row["Postcode"] . "</td>" .
                            "<td>" . $row["Country"] . "</td>" .
                            "<td>" . $row["City"] . "</td>" .
                            "<td>" . $row["ItemID"] . "</td>" .
                            "<td>" . "<button name='Remove' type='submit' value='".$row["OrderID"]."'>Remove</button>" . "</td>" .
                            "</tr>";
                    }
                }
                $result->data_seek(0); ?>
            </table>
        </form>

        <div id="sidenav">
            <form action='index.php'> <button name='Home' type='submit' >Home Page</button> </form>

            <form action="admin.php" method="post">
                <button type="submit" name="items">View Art</button>
            </form>
        </div>


<?php
    }
}else{
    ?>

    <div id='password'>
        <?php
        if (isset($_POST["Sub"])) {
            if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                echo "<p>Wrong Password:</p>";
            }
            }else{
                echo "<p>Enter Password:</p>";
            }
        ?>
        <form method="post">
            <label id="passLabel">
                <input type="password" name="Password" placeholder="Password..." required> <button name='Sub' type='submit' id="Sub">Enter</button>
            </label>
        </form>

    </div>

    <div id="sidenav">
        <form action='index.php'> <button name='toBasket' type='submit' >Home Page</button> </form>
    </div>

    <?php
}
?>

</body>
</html>
