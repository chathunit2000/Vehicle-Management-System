<?php
include 'includes/header.php';
include 'includes/leftnav.php';
include 'database/db.php';

$database = new Database();
$db = $database->connect();

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_licence'])) {
    $regno       = trim($_POST['regno']);
    $typeid      = (int)$_POST['licencetypeid'];
    $instituteid = (int)$_POST['instituteid'];
    $serialno    = trim($_POST['serialno']);
    $fromdate    = trim($_POST['fromdate']);
    $expdate     = trim($_POST['expdate']);
    $amount      = trim($_POST['amount']);
    $remarks     = trim($_POST['remarks']);

    if (empty($regno) || !$typeid || !$instituteid || empty($fromdate) || empty($expdate)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $db->prepare("INSERT INTO LICENCE (regno, licencetypeid, instituteid, serialno, fromdate, expdate, amount, remarks, status)
                              VALUES (:regno, :typeid, :instituteid, :serialno, :fromdate, :expdate, :amount, :remarks, 1)");
        if ($stmt->execute([
            ':regno'       => $regno,
            ':typeid'      => $typeid,
            ':instituteid' => $instituteid,
            ':serialno'    => $serialno,
            ':fromdate'    => $fromdate,
            ':expdate'     => $expdate,
            ':amount'      => $amount ?: 0,
            ':remarks'     => $remarks ?: '-',
        ])) {
            $success = "Vehicle licence added successfully.";
        } else {
            $error = "Failed to add vehicle licence.";
        }
    }
}

// Dropdowns
$licenceTypes = $db->query("SELECT id, description FROM REGISTRATION_TYPE ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$institutes   = $db->query("SELECT id, typeid, description FROM INSTITUTE ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$institutesJson = json_encode($institutes);
?>

<main class="content" style="padding-top: 80px;">
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Vehicle Licence</h4>
        </div>

        <div class="card-body">

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Reg. No <span class="text-danger">*</span></label>
                        <input type="text" name="regno" class="form-control form-control-sm"
                               placeholder="e.g. WPKY-5249" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Licence Type <span class="text-danger">*</span></label>
                        <select name="licencetypeid" id="licenceTypeSelect" class="form-select form-select-sm"
                                onchange="filterInstitutes(this.value)" required>
                            <option value="">--Select--</option>
                            <?php foreach ($licenceTypes as $row): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Institute <span class="text-danger">*</span></label>
                        <select name="instituteid" id="instituteSelect" class="form-select form-select-sm" required>
                            <option value="">--Select Licence Type first--</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Serial No</label>
                        <input type="text" name="serialno" class="form-control form-control-sm"
                               placeholder="Serial number">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Valid <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" name="fromdate" class="form-control form-control-sm" required>
                            <span class="text-muted fw-semibold">to</span>
                            <input type="date" name="expdate" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Amount</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rs.</span>
                            <input type="number" name="amount" class="form-control form-control-sm"
                                   placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Remarks</label>
                        <textarea name="remarks" rows="3" class="form-control form-control-sm"
                                  placeholder="Optional remarks..."></textarea>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-secondary" onclick="resetInstitutes()">Clear</button>
                    <button type="submit" name="add_licence" class="btn btn-primary">Add Licence</button>
                </div>

            </form>

        </div>
    </div>

</div>
</main>

<script>
var ALL_INSTITUTES = <?php echo $institutesJson; ?>;

function filterInstitutes(typeId) {
    var sel = document.getElementById('instituteSelect');
    sel.innerHTML = '<option value="">--Select--</option>';
    if (!typeId) {
        sel.innerHTML = '<option value="">--Select Licence Type first--</option>';
        return;
    }
    ALL_INSTITUTES.forEach(function (inst) {
        if (String(inst.typeid) === String(typeId)) {
            var opt = document.createElement('option');
            opt.value = inst.id;
            opt.text  = inst.description;
            sel.add(opt);
        }
    });
}

function resetInstitutes() {
    document.getElementById('instituteSelect').innerHTML = '<option value="">--Select Licence Type first--</option>';
}
</script>

<?php include 'includes/footer.php'; ?>
