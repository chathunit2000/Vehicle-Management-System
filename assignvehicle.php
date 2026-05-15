<?php
include 'includes/header.php';
include 'includes/leftnav.php';
include 'database/db.php';

$database = new Database();
$db = $database->connect();

$success = '';
$error   = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign'])) {
    $regno      = trim($_POST['regno']);
    $locationid = (int)$_POST['locationid'];
    $divisionid = (int)$_POST['divisionid'];
    $date       = trim($_POST['ownershipdate']);
    $remarks    = trim($_POST['remarks']);
    $employees  = isset($_POST['employees']) ? $_POST['employees'] : [];

    if (empty($regno) || !$locationid || !$divisionid || empty($date)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO OWNER_SHIP (regno, divisionid, locationid, ownershipdate, remarks, ownershipstatus) VALUES (:regno, :divisionid, :locationid, :date, :remarks, 1)");
            $stmt->execute([
                ':regno'      => $regno,
                ':divisionid' => $divisionid,
                ':locationid' => $locationid,
                ':date'       => $date,
                ':remarks'    => $remarks ?: '-',
            ]);
            $ownershipId = $db->lastInsertId();

            if (!empty($employees)) {
                $ownerStmt = $db->prepare("INSERT INTO OWNER (ownershipid, ownerepf) VALUES (:ownershipid, :epf)");
                foreach ($employees as $epf) {
                    $ownerStmt->execute([':ownershipid' => $ownershipId, ':epf' => $epf]);
                }
            }

            $db->commit();
            $success = "Vehicle assigned successfully.";
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Failed to assign vehicle: " . $e->getMessage();
        }
    }
}

// Load dropdowns
$locations = $db->query("SELECT id, description FROM LOCATION ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$divisions = $db->query("SELECT id, description FROM DIVISION ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$employees = $db->query("SELECT epf, employeename FROM EMPLOYEE ORDER BY employeename ASC")->fetchAll(PDO::FETCH_ASSOC);
$employeesJson = json_encode($employees);
?>

<main class="content" style="padding-top: 80px;">
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Assign Vehicle</h4>
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

            <form method="POST" id="assignForm">

                <div class="row">

                    <!-- LEFT COLUMN -->
                    <div class="col-lg-6">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reg. No <span class="text-danger">*</span></label>
                            <input type="text" name="regno" class="form-control form-control-sm"
                                   placeholder="e.g. WPKY-5249" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Location <span class="text-danger">*</span></label>
                            <select name="locationid" class="form-select form-select-sm" required>
                                <option value="">--Select--</option>
                                <?php foreach ($locations as $row): ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Division <span class="text-danger">*</span></label>
                            <select name="divisionid" class="form-select form-select-sm" required>
                                <option value="">--Select--</option>
                                <?php foreach ($divisions as $row): ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="ownershipdate" class="form-control form-control-sm" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea name="remarks" rows="3" class="form-control form-control-sm"
                                      placeholder="Optional remarks..."></textarea>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN — ownership -->
                    <div class="col-lg-6">

                        <div class="form-check mb-3 mt-2">
                            <input class="form-check-input" type="checkbox" id="addOwnerCheck">
                            <label class="form-check-label fw-semibold" for="addOwnerCheck">
                                Add other ownership
                            </label>
                        </div>

                        <div id="ownerSection" style="display:none;">

                            <div class="card border rounded p-3 bg-light">

                                <div class="row g-2 mb-3 align-items-end">
                                    <div class="col">
                                        <label class="form-label fw-semibold mb-1">Name</label>
                                        <select id="employeeSelect" class="form-select form-select-sm">
                                            <option value="">--Select Employee--</option>
                                            <?php foreach ($employees as $emp): ?>
                                                <option value="<?php echo htmlspecialchars($emp['epf']); ?>">
                                                    <?php echo htmlspecialchars($emp['employeename']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addOwner()">Add</button>
                                    </div>
                                </div>

                                <div id="ownerList" class="mb-2" style="max-height:220px; overflow-y:auto;">
                                    <!-- added owners appear here -->
                                </div>

                                <div id="noOwnerMsg" class="text-muted small text-center py-2">No owners added yet.</div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- hidden inputs for selected employees -->
                <div id="hiddenEmployees"></div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-secondary" onclick="clearOwners()">Clear</button>
                    <button type="submit" name="assign" class="btn btn-primary">Assign Vehicle</button>
                </div>

            </form>

        </div>
    </div>

</div>
</main>

<script>
var EMPLOYEES = <?php echo $employeesJson; ?>;
var selectedOwners = [];

document.getElementById('addOwnerCheck').addEventListener('change', function () {
    document.getElementById('ownerSection').style.display = this.checked ? 'block' : 'none';
});

function addOwner() {
    var sel = document.getElementById('employeeSelect');
    var epf  = sel.value;
    var name = sel.options[sel.selectedIndex].text;

    if (!epf) return;

    if (selectedOwners.find(function(o){ return o.epf === epf; })) {
        alert('This employee has already been added.');
        return;
    }

    selectedOwners.push({ epf: epf, name: name });
    renderOwnerList();
    sel.value = '';
}

function removeOwner(epf) {
    selectedOwners = selectedOwners.filter(function(o){ return o.epf !== epf; });
    renderOwnerList();
}

function renderOwnerList() {
    var list    = document.getElementById('ownerList');
    var noMsg   = document.getElementById('noOwnerMsg');
    var hidden  = document.getElementById('hiddenEmployees');

    list.innerHTML   = '';
    hidden.innerHTML = '';

    if (selectedOwners.length === 0) {
        noMsg.style.display = 'block';
        return;
    }

    noMsg.style.display = 'none';

    selectedOwners.forEach(function (o) {
        var row = document.createElement('div');
        row.className = 'd-flex align-items-center justify-content-between border rounded px-3 py-2 mb-1 bg-white';
        row.innerHTML =
            '<span><span class="badge bg-secondary me-2">' + o.epf + '</span>' + o.name + '</span>' +
            '<button type="button" class="btn btn-outline-danger btn-sm" onclick="removeOwner(\'' + o.epf + '\')">Remove</button>';
        list.appendChild(row);

        var inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'employees[]';
        inp.value = o.epf;
        hidden.appendChild(inp);
    });
}

function clearOwners() {
    selectedOwners = [];
    renderOwnerList();
    document.getElementById('addOwnerCheck').checked = false;
    document.getElementById('ownerSection').style.display = 'none';
}
</script>

<?php include 'includes/footer.php'; ?>
