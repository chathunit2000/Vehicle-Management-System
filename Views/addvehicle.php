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

    <div class="form-box">

        <h2>Add Class of Vehicle</h2>

        <form method="POST">

            <label>Class of Vehicle</label>

            <input type="text" name="class_name" required>

            <button type="submit" name="add_vehicle">
                Add
            </button>

        </form>

    </div>

    <div class="list-box">

        <table>

            <tr>
                <th>Vehicle Class</th>
            </tr>
            

           <?php if(isset($vehicles) && $vehicles != null): ?>

    <?php while($row = $vehicles->fetch(PDO::FETCH_ASSOC)): ?>

        <tr>
            <td><?php echo $row['class_name']; ?></td>
        </tr>

    <?php endwhile; ?>

<?php else: ?>

    <tr>
        <td>No vehicles found</td>
    </tr>

<?php endif; ?>
        </table>

    </div>

</div>

</body>
</html>