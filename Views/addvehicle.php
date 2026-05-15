<?php
include '../database/db.php';
include '../Model/VehicleModel.php';

$database = new Database();
$conn     = $database->connect();
$model    = new VehicleModel($conn);

$success = '';
$error   = '';

if (isset($_POST['add_vehicle'])) {
    $description = trim($_POST['description']);
    if (!empty($description)) {
        if ($model->addVehicle($description)) {
            $success = "Vehicle class added successfully.";
        } else {
            $error = "Failed to add vehicle class.";
        }
    } else {
        $error = "Vehicle class cannot be empty.";
    }
}

$vehicles = $model->getVehicles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle Class – Vehicle Fleet Manager</title>
    <link href="../dist/css/modern.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', Arial, sans-serif; }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 16px;
        }

        .page-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.09);
            width: 100%;
            max-width: 820px;
            overflow: hidden;
        }

        .page-header {
            background: linear-gradient(135deg, #1a3c6e 0%, #2e6da4 100%);
            padding: 22px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h4 {
            margin: 0;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .header-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.18);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .page-body { display: flex; }

        .form-panel {
            width: 300px;
            flex-shrink: 0;
            padding: 32px 28px;
            border-right: 1px solid #e9ecef;
            background: #fafbfc;
        }

        .form-panel label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-panel input[type="text"] {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 0.92rem;
            color: #212529;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box;
            margin-bottom: 16px;
        }

        .form-panel input[type="text"]:focus {
            outline: none;
            border-color: #2e6da4;
            box-shadow: 0 0 0 3px rgba(46,109,164,0.12);
        }

        .btn-row { display: flex; gap: 10px; }

        .btn-clear {
            flex: 1;
            padding: 9px 0;
            border: 1px solid #ced4da;
            background: #fff;
            color: #495057;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-clear:hover { background: #f1f3f5; }

        .btn-add {
            flex: 1;
            padding: 9px 0;
            border: none;
            background: linear-gradient(135deg, #1a3c6e, #2e6da4);
            color: #fff;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .15s;
        }
        .btn-add:hover { opacity: .88; }

        .alert {
            margin-top: 16px;
            padding: 9px 12px;
            border-radius: 6px;
            font-size: 0.84rem;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }

        .table-panel { flex: 1; padding: 32px 28px; }

        .table-panel h6 {
            font-size: 0.78rem;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 14px;
        }

        .data-table { width: 100%; border-collapse: collapse; }

        .data-table thead tr { background: #1a3c6e; }

        .data-table thead th {
            color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 11px 16px;
            text-align: left;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #f0f2f5;
            transition: background .12s;
        }
        .data-table tbody tr:hover { background: #f8f9fa; }
        .data-table tbody tr:last-child { border-bottom: none; }

        .data-table tbody td {
            padding: 11px 16px;
            font-size: 0.9rem;
            color: #212529;
        }

        .badge-id {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            margin-right: 8px;
        }

        .empty-msg {
            text-align: center;
            color: #adb5bd;
            font-size: 0.88rem;
            padding: 24px 0;
        }

        .record-count {
            margin-top: 12px;
            font-size: 0.78rem;
            color: #adb5bd;
            text-align: right;
        }

        .table-scroll { max-height: 420px; overflow-y: auto; }
        .table-scroll::-webkit-scrollbar { width: 5px; }
        .table-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .table-scroll::-webkit-scrollbar-thumb { background: #ced4da; border-radius: 4px; }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="page-card">

        <div class="page-header">
            <div class="header-icon">🚘</div>
            <h4>Add Vehicle Class</h4>
        </div>

        <div class="page-body">

            <div class="form-panel">
                <form method="POST">
                    <label for="description">Class of Vehicle</label>
                    <input type="text"
                           id="description"
                           name="description"
                           placeholder="e.g. MOTOR CAR"
                           autocomplete="off">
                    <div class="btn-row">
                        <button type="button" class="btn-clear"
                                onclick="document.getElementById('description').value=''">Clear</button>
                        <button type="submit" name="add_vehicle" class="btn-add">Add</button>
                    </div>
                </form>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
            </div>

            <div class="table-panel">
                <h6>Existing Vehicle Classes</h6>
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr><th>Vehicle Class</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            while ($row = $vehicles->fetch(PDO::FETCH_ASSOC)):
                                $count++;
                            ?>
                            <tr>
                                <td>
                                    <span class="badge-id"><?php echo $row['id']; ?></span>
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($count === 0): ?>
                            <tr><td class="empty-msg">No vehicle classes found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($count > 0): ?>
                    <div class="record-count"><?php echo $count; ?> record<?php echo $count !== 1 ? 's' : ''; ?></div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
</body>
</html>
