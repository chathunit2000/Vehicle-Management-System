<?php

include '../database/db.php';

$db = new Database();
$conn = $db->connect();

/* INSERT DATA */

if(isset($_POST['add_vehicle'])){

    $description = $_POST['description'];

    if(!empty($description)){

        $query = "INSERT INTO taxation_cla(description)
                  VALUES(:description)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":description", $description);

        $stmt->execute();
    }
}

/* FETCH DATA */

$query = "SELECT * FROM taxation_class ORDER BY id DESC";

$vehicles = $conn->prepare($query);

$vehicles->execute();

?>

<!DOCTYPE html>
<html>
<head>

    <title>Vehicle Fleet Manager</title>

    <style>

        body{
            font-family: Arial;
            background:#f4f4f4;
        }

        .container{
            width:900px;
            margin:auto;
            margin-top:40px;
        }

        .form-box{
            width:300px;
            float:left;
        }

        .list-box{
            width:500px;
            float:right;
        }

        input[type=text]{
            width:100%;
            padding:10px;
            margin-bottom:10px;
        }

        button{
            padding:10px 20px;
            border:none;
            background:#007bff;
            color:white;
            cursor:pointer;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
        }

        table th{
            background:#222;
            color:white;
            padding:10px;
        }

        table td{
            padding:10px;
            border-bottom:1px solid #ccc;
        }

    </style>

</head>

<body>

<div class="container">

    <!-- FORM -->

    <div class="form-box">

        <h2>Add Taxation of Vehicle</h2>

        <form method="POST">

            <label>Taxation of Vehicle</label>

            <input type="text"
                   name="description"
                   required>

            <button type="submit"
                    name="add_vehicle">

                Add

            </button>

        </form>

    </div>

    <!-- TABLE -->

    <div class="list-box">

        <table>

            <tr>
                <th>Taxation Description</th>
            </tr>

            <?php if($vehicles): ?>

                <?php while($row = $vehicles->fetch(PDO::FETCH_ASSOC)): ?>

                    <tr>
                        <td>
                            <?php echo $row['description']; ?>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td>No records found</td>
                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

</body>
</html>