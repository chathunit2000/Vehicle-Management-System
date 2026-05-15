<?php
include 'includes/header.php';
include 'includes/leftnav.php';
include 'database/db.php';

$database = new Database();
$db = $database->connect();

$results   = [];
$imported  = 0;
$skipped   = 0;
$errors    = [];

// Download template
if (isset($_GET['template'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="insurance_template.csv"');
    echo "Reg No,Institute,Serial No,From Date (YYYY-MM-DD),Expiry Date (YYYY-MM-DD),Amount,Remarks\r\n";
    echo "WPKY-5249,CELINCO,INS-001,2024-01-01,2025-01-01,50000,\r\n";
    exit;
}

// Load institutes for insurance (typeid=1)
$institutes = $db->query("SELECT id, description FROM INSTITUTE WHERE typeid = 1 ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$instituteMap = [];
foreach ($institutes as $inst) {
    $instituteMap[strtolower(trim($inst['description']))] = $inst['id'];
}

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvfile'])) {
    $file = $_FILES['csvfile'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error. Please try again.";
    } elseif (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'csv') {
        $errors[] = "Only CSV files are accepted.";
    } else {
        $handle = fopen($file['tmp_name'], 'r');
        $header = fgetcsv($handle); // skip header row
        $row_num = 1;

        $stmt = $db->prepare("INSERT INTO LICENCE (regno, licencetypeid, instituteid, serialno, fromdate, expdate, amount, remarks, status)
                              VALUES (:regno, 1, :instituteid, :serialno, :fromdate, :expdate, :amount, :remarks, 1)");

        while (($row = fgetcsv($handle)) !== false) {
            $row_num++;
            if (count($row) < 5) { $skipped++; continue; }

            $regno       = trim($row[0]);
            $instName    = strtolower(trim($row[1]));
            $serialno    = trim($row[2]);
            $fromdate    = trim($row[3]);
            $expdate     = trim($row[4]);
            $amount      = isset($row[5]) ? trim($row[5]) : 0;
            $remarks     = isset($row[6]) ? trim($row[6]) : '';

            if (empty($regno)) { $skipped++; continue; }

            $instituteid = $instituteMap[$instName] ?? null;
            if (!$instituteid) {
                $results[] = ['row' => $row_num, 'regno' => $regno, 'status' => 'error', 'msg' => 'Institute "' . htmlspecialchars($row[1]) . '" not found'];
                $skipped++;
                continue;
            }

            try {
                $stmt->execute([
                    ':regno'       => $regno,
                    ':instituteid' => $instituteid,
                    ':serialno'    => $serialno,
                    ':fromdate'    => $fromdate ?: null,
                    ':expdate'     => $expdate  ?: null,
                    ':amount'      => is_numeric($amount) ? $amount : 0,
                    ':remarks'     => $remarks ?: '-',
                ]);
                $results[] = ['row' => $row_num, 'regno' => $regno, 'status' => 'ok', 'msg' => 'Imported'];
                $imported++;
            } catch (Exception $e) {
                $results[] = ['row' => $row_num, 'regno' => $regno, 'status' => 'error', 'msg' => $e->getMessage()];
                $skipped++;
            }
        }
        fclose($handle);
    }
}
?>

<main class="content" style="padding-top: 80px;">
<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Vehicle Licence Upload</h4>
        </div>
        <div class="card-body">

            <!-- Insurance Upload Panel -->
            <div class="row">
                <div class="col-lg-5">
                    <div class="card border-0" style="background: linear-gradient(135deg,#42a5f5,#1e88e5); border-radius:12px;">
                        <div class="card-body p-4">

                            <h5 class="text-white fw-bold border-bottom border-white border-opacity-50 pb-2 mb-4">
                                Insurance Upload
                            </h5>

                            <form method="POST" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label class="text-white fw-semibold mb-2 d-block">Select CSV File</label>
                                    <input type="file"
                                           name="csvfile"
                                           accept=".csv"
                                           class="form-control form-control-sm"
                                           id="csvInput"
                                           onchange="previewFile(this)">
                                    <small class="text-white-50">Accepted format: CSV only</small>
                                </div>

                                <div id="fileInfo" class="alert alert-light py-2 small mb-3" style="display:none;"></div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-light fw-semibold px-4">
                                        Upload
                                    </button>
                                    <a href="?template=1" class="btn btn-outline-light fw-semibold">
                                        Download Template
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>

                    <!-- CSV Format Guide -->
                    <div class="card border mt-3">
                        <div class="card-header bg-light fw-semibold py-2 small">CSV Column Format</div>
                        <div class="card-body p-3">
                            <table class="table table-sm table-bordered mb-0 small">
                                <thead class="table-dark">
                                    <tr><th>#</th><th>Column</th><th>Example</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>1</td><td>Reg No</td><td>WPKY-5249</td></tr>
                                    <tr><td>2</td><td>Institute</td><td>CELINCO</td></tr>
                                    <tr><td>3</td><td>Serial No</td><td>INS-001</td></tr>
                                    <tr><td>4</td><td>From Date</td><td>2024-01-01</td></tr>
                                    <tr><td>5</td><td>Expiry Date</td><td>2025-01-01</td></tr>
                                    <tr><td>6</td><td>Amount</td><td>50000</td></tr>
                                    <tr><td>7</td><td>Remarks</td><td>(optional)</td></tr>
                                </tbody>
                            </table>
                            <div class="mt-2 small text-muted">
                                <strong>Valid Institutes:</strong>
                                <?php foreach ($institutes as $inst): ?>
                                    <span class="badge bg-secondary me-1"><?php echo htmlspecialchars($inst['description']); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Results Panel -->
                <div class="col-lg-7">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $e): ?>
                                <div><?php echo htmlspecialchars($e); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($results)): ?>
                        <div class="d-flex gap-3 mb-3">
                            <div class="card border-0 bg-success text-white text-center px-4 py-3 flex-fill">
                                <div class="fs-2 fw-bold"><?php echo $imported; ?></div>
                                <div class="small">Imported</div>
                            </div>
                            <div class="card border-0 bg-danger text-white text-center px-4 py-3 flex-fill">
                                <div class="fs-2 fw-bold"><?php echo $skipped; ?></div>
                                <div class="small">Skipped / Errors</div>
                            </div>
                            <div class="card border-0 bg-primary text-white text-center px-4 py-3 flex-fill">
                                <div class="fs-2 fw-bold"><?php echo count($results); ?></div>
                                <div class="small">Total Rows</div>
                            </div>
                        </div>

                        <div class="border rounded" style="max-height:420px; overflow-y:auto;">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-dark sticky-top">
                                    <tr><th>Row</th><th>Reg No</th><th>Status</th><th>Message</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $r): ?>
                                        <tr class="<?php echo $r['status']==='ok' ? 'table-success' : 'table-danger'; ?>">
                                            <td><?php echo $r['row']; ?></td>
                                            <td><?php echo htmlspecialchars($r['regno']); ?></td>
                                            <td>
                                                <?php if ($r['status']==='ok'): ?>
                                                    <span class="badge bg-success">OK</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Error</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($r['msg']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif (empty($errors)): ?>
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted" style="min-height:200px;">
                            <div class="text-center">
                                <i class="fas fa-file-csv fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">Upload a CSV file to import insurance records.<br>
                                Download the template to get the correct format.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>

</div>
</main>

<script>
function previewFile(input) {
    var info = document.getElementById('fileInfo');
    if (input.files && input.files[0]) {
        var f = input.files[0];
        var size = (f.size / 1024).toFixed(1);
        info.style.display = 'block';
        info.innerHTML = '<strong>' + f.name + '</strong> &nbsp;|&nbsp; ' + size + ' KB';
    } else {
        info.style.display = 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
