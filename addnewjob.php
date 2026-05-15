<?php
include 'includes/header.php';
include 'includes/leftnav.php';
include 'database/db.php';

$database = new Database();
$db = $database->connect();

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_job'])) {
    $regno        = trim($_POST['regno']);
    $job_cat_id   = (int)$_POST['job_cat_id'];
    $meter        = trim($_POST['meter_reading']);
    $job_id_ref   = trim($_POST['job_id_ref']);
    $works        = isset($_POST['works'])  ? json_decode($_POST['works'],  true) : [];
    $items        = isset($_POST['items'])  ? json_decode($_POST['items'],  true) : [];

    if (empty($regno) || !$job_cat_id) {
        $error = "Reg. No and Job Category are required.";
    } else {
        try {
            $db->beginTransaction();

            $hStmt = $db->prepare("INSERT INTO JOB_HEADER (regno, job_cat_id, date, meter_reading, job_id, job_status)
                                   VALUES (:regno, :catid, CURDATE(), :meter, :jobid, 1)");
            $hStmt->execute([':regno'=>$regno,':catid'=>$job_cat_id,':meter'=>$meter,':jobid'=>$job_id_ref]);
            $jobId = $db->lastInsertId();

            if (!empty($works)) {
                $wStmt = $db->prepare("INSERT INTO TASK (jobid, taskid, qty, unitprice, price, remarks, subcatid, itemname, tasktype)
                                       VALUES (:jobid,:taskid,:qty,:unitprice,:price,:remarks,:subcatid,:itemname,:tasktype)");
                foreach ($works as $w) {
                    $wStmt->execute([
                        ':jobid'     => $jobId,
                        ':taskid'    => $w['taskid']    ?? 0,
                        ':qty'       => $w['qty']        ?? 1,
                        ':unitprice' => $w['unitprice']  ?? 0,
                        ':price'     => $w['price']      ?? 0,
                        ':remarks'   => $w['remarks']    ?? '',
                        ':subcatid'  => $w['subcatid']   ?? 0,
                        ':itemname'  => $w['itemname']   ?? '',
                        ':tasktype'  => $w['tasktype']   ?? 'I/Labor',
                    ]);
                }
            }

            if (!empty($items)) {
                $iStmt = $db->prepare("INSERT INTO ITEM_DESCRIPTION (jobid, itemcode, itemname, qty, unitprice, price, remarks)
                                       VALUES (:jobid,:itemcode,:itemname,:qty,:unitprice,:price,:remarks)");
                foreach ($items as $it) {
                    $iStmt->execute([
                        ':jobid'     => $jobId,
                        ':itemcode'  => $it['itemcode']  ?? '',
                        ':itemname'  => $it['itemname']  ?? '',
                        ':qty'       => $it['qty']        ?? 1,
                        ':unitprice' => $it['unitprice']  ?? 0,
                        ':price'     => $it['price']      ?? 0,
                        ':remarks'   => $it['remarks']    ?? '',
                    ]);
                }
            }

            $db->commit();
            $success = "Job added successfully (Job ID: $jobId).";
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Failed to save job: " . $e->getMessage();
        }
    }
}

// Data for dropdowns
$jobGroups    = $db->query("SELECT id, description FROM JOB_GROUP ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$subGroups    = $db->query("SELECT id, description, type_id FROM JOB_SUB_GROUP ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$works        = $db->query("SELECT id, description, unitprice FROM WORK ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
$labors       = $db->query("SELECT id, description, rate FROM LABOR ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$items        = $db->query("SELECT id, description, unitprice FROM ITEM ORDER BY description ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content" style="padding-top: 80px;">
<div class="container-fluid">

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert"></button><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert"></button><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add New Job</h4>
    </div>
    <div class="card-body">
    <form method="POST" id="jobForm">
        <input type="hidden" name="submit_job" value="1">
        <input type="hidden" name="works"  id="worksJson">
        <input type="hidden" name="items"  id="itemsJson">

        <!-- TOP HEADER ROW -->
        <div class="row g-3 mb-4 pb-3 border-bottom">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Reg. No <span class="text-danger">*</span></label>
                <input type="text" name="regno" class="form-control form-control-sm" placeholder="e.g. WPKY-5249" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Job Category <span class="text-danger">*</span></label>
                <select name="job_cat_id" id="jobCatSelect" class="form-select form-select-sm" onchange="filterSubGroups(this.value)" required>
                    <option value="">--Select--</option>
                    <?php foreach ($jobGroups as $g): ?>
                        <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['description']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Meter Reading</label>
                <input type="text" name="meter_reading" class="form-control form-control-sm" placeholder="km">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Job ID Ref</label>
                <input type="text" name="job_id_ref" class="form-control form-control-sm">
            </div>
        </div>

        <!-- TWO-COLUMN BODY -->
        <div class="row g-4">

            <!-- ====== LEFT: WORK ENTRY ====== -->
            <div class="col-lg-6">
                <div class="card border h-100">
                    <div class="card-header bg-light fw-semibold py-2">Work Entry</div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sub Category</label>
                            <select id="subCatSelect" class="form-select form-select-sm" onchange="filterTasks(this.value)">
                                <option value="">--Select Job Category first--</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Task</label>
                            <select id="taskSelect" class="form-select form-select-sm" onchange="setWorkUnitPrice(this)">
                                <option value="">--Select Sub Category first--</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tasktype" id="ilabor" value="I/Labor" checked onchange="toggleItemField()">
                                    <label class="form-check-label" for="ilabor">I/Labor</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tasktype" id="olabor" value="O/Labor" onchange="toggleItemField()">
                                    <label class="form-check-label" for="olabor">O/Labor</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tasktype" id="oitem" value="O/Item" onchange="toggleItemField()">
                                    <label class="form-check-label" for="oitem">O/Item</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="itemNameRow" style="display:none;">
                            <label class="form-label fw-semibold">Item</label>
                            <input type="text" id="workItemName" class="form-control form-control-sm" placeholder="Item name">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="form-label fw-semibold">QTY</label>
                                <input type="number" id="workQty" class="form-control form-control-sm" value="1" min="1" oninput="calcWorkPrice()">
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold">Unit Price (Rs)</label>
                                <input type="number" id="workUnitPrice" class="form-control form-control-sm" value="0.00" step="0.01" oninput="calcWorkPrice()">
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold">Price (Rs)</label>
                                <div class="fw-bold text-primary fs-5 pt-1" id="workPrice">0.00</div>
                            </div>
                        </div>

                        <div class="mb-3 d-flex align-items-end gap-2">
                            <div class="flex-grow-1">
                                <label class="form-label fw-semibold">Labor Cost (Hours)</label>
                                <input type="number" id="laborHours" class="form-control form-control-sm" value="0" min="0" step="0.5" placeholder="Total hours" readonly>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm mb-1" onclick="openLaborModal()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea id="workRemarks" rows="2" class="form-control form-control-sm"></textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="clearWorkForm()">Clear</button>
                            <button type="button" class="btn btn-danger btn-sm"    onclick="deleteWork()">Delete</button>
                            <button type="button" class="btn btn-primary btn-sm"   onclick="addWork()">Add</button>
                        </div>

                        <!-- Work List -->
                        <div class="fw-semibold text-muted small mb-1">Work List</div>
                        <div class="border rounded" style="min-height:120px; max-height:200px; overflow-y:auto; background:#f8f9fa;">
                            <table class="table table-sm table-hover mb-0 small" id="workTable">
                                <thead class="table-dark">
                                    <tr><th>#</th><th>Task</th><th>Type</th><th>Qty</th><th>Price</th></tr>
                                </thead>
                                <tbody id="workBody">
                                    <tr><td colspan="5" class="text-center text-muted py-3">No work added yet.</td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== RIGHT: ITEM ENTRY ====== -->
            <div class="col-lg-6">
                <div class="card border h-100">
                    <div class="card-header bg-light fw-semibold py-2">Item Entry</div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Item Description</label>
                            <select id="itemSelect" class="form-select form-select-sm" onchange="setItemPrice(this)">
                                <option value="">--Select Item--</option>
                                <?php foreach ($items as $it): ?>
                                    <option value="<?php echo $it['id']; ?>"
                                            data-price="<?php echo $it['unitprice']; ?>"
                                            data-name="<?php echo htmlspecialchars($it['description']); ?>">
                                        <?php echo htmlspecialchars($it['description']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="form-label fw-semibold">Unit Price (Rs)</label>
                                <div class="fw-bold text-primary fs-5 pt-1" id="itemUnitPriceDisplay">0.00</div>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold">QTY</label>
                                <input type="number" id="itemQty" class="form-control form-control-sm" value="1" min="1" oninput="calcItemPrice()">
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold">Price (Rs)</label>
                                <div class="fw-bold text-primary fs-5 pt-1" id="itemPrice">0.00</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea id="itemRemarks" rows="2" class="form-control form-control-sm"></textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="clearItemForm()">Clear</button>
                            <button type="button" class="btn btn-danger btn-sm"    onclick="deleteItem()">Delete</button>
                            <button type="button" class="btn btn-primary btn-sm"   onclick="addItem()">Add</button>
                        </div>

                        <!-- Item List -->
                        <div class="fw-semibold text-muted small mb-1">Item List</div>
                        <div class="border rounded" style="min-height:120px; max-height:200px; overflow-y:auto; background:#f8f9fa;">
                            <table class="table table-sm table-hover mb-0 small" id="itemTable">
                                <thead class="table-dark">
                                    <tr><th>#</th><th>Item</th><th>Qty</th><th>Unit Price</th><th>Price</th></tr>
                                </thead>
                                <tbody id="itemBody">
                                    <tr><td colspan="5" class="text-center text-muted py-3">No items added yet.</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary" onclick="clearAll()">Clear All</button>
                            <button type="submit" class="btn btn-primary" onclick="prepareSubmit()">Submit Job</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>
    </div>
</div>
</div>
</main>

<!-- Labor Modal -->
<div class="modal fade" id="laborModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Labor Cost (Hours)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <thead class="table-dark">
                        <tr><th>Labor Type</th><th>Rate (Rs/hr)</th><th>Hours</th><th>Count</th><th>Cost (Rs)</th></tr>
                    </thead>
                    <tbody id="laborModalBody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="fw-bold text-end">Total Hours:</td>
                            <td colspan="3"><span id="laborTotalHours" class="fw-bold text-primary">0.00</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="fw-bold text-end">Total Cost:</td>
                            <td colspan="3"><span id="laborTotalCost" class="fw-bold text-primary">0.00</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveLaborHours()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
var ALL_SUB_GROUPS = <?php echo json_encode($subGroups); ?>;
var ALL_WORKS      = <?php echo json_encode($works); ?>;
var ALL_LABORS     = <?php echo json_encode($labors); ?>;

var workList  = [];
var itemList  = [];
var selectedWork = -1;
var selectedItem = -1;
var currentLaborData = {};
var itemUnitPrice = 0;

// ─── SUB GROUP / TASK FILTERING ───────────────────────────────────────────────
function filterSubGroups(catId) {
    var sel = document.getElementById('subCatSelect');
    sel.innerHTML = '<option value="">--Select--</option>';
    ALL_SUB_GROUPS.forEach(function(sg) {
        var opt = document.createElement('option');
        opt.value = sg.id;
        opt.text  = sg.description;
        sel.add(opt);
    });
    document.getElementById('taskSelect').innerHTML = '<option value="">--Select Sub Category first--</option>';
}

function filterTasks(subCatId) {
    var sel = document.getElementById('taskSelect');
    sel.innerHTML = '<option value="">--Select--</option>';
    ALL_WORKS.forEach(function(w) {
        var opt = document.createElement('option');
        opt.value       = w.id;
        opt.text        = w.description;
        opt.dataset.price = w.unitprice;
        sel.add(opt);
    });
}

function setWorkUnitPrice(sel) {
    var price = sel.options[sel.selectedIndex] ? (sel.options[sel.selectedIndex].dataset.price || 0) : 0;
    document.getElementById('workUnitPrice').value = parseFloat(price).toFixed(2);
    calcWorkPrice();
}

// ─── WORK PRICE CALC ──────────────────────────────────────────────────────────
function calcWorkPrice() {
    var qty   = parseFloat(document.getElementById('workQty').value)       || 0;
    var price = parseFloat(document.getElementById('workUnitPrice').value) || 0;
    document.getElementById('workPrice').textContent = (qty * price).toFixed(2);
}

function toggleItemField() {
    var type = document.querySelector('input[name="tasktype"]:checked').value;
    document.getElementById('itemNameRow').style.display = (type === 'O/Item') ? 'block' : 'none';
}

// ─── LABOR MODAL ──────────────────────────────────────────────────────────────
function openLaborModal() {
    var body = document.getElementById('laborModalBody');
    body.innerHTML = '';
    ALL_LABORS.forEach(function(l) {
        var saved = currentLaborData[l.id] || { hours: 0, count: 1 };
        var row = '<tr>' +
            '<td>' + l.description + '</td>' +
            '<td class="text-end">' + parseFloat(l.rate).toFixed(2) + '</td>' +
            '<td><input type="number" class="form-control form-control-sm labor-hours" data-id="' + l.id + '" data-rate="' + l.rate + '" value="' + saved.hours + '" step="0.5" min="0" oninput="updateLaborTotals()"></td>' +
            '<td><input type="number" class="form-control form-control-sm labor-count" data-id="' + l.id + '" value="' + saved.count + '" min="1" oninput="updateLaborTotals()"></td>' +
            '<td class="text-end labor-cost" id="lcost_' + l.id + '">0.00</td>' +
            '</tr>';
        body.insertAdjacentHTML('beforeend', row);
    });
    updateLaborTotals();
    new bootstrap.Modal(document.getElementById('laborModal')).show();
}

function updateLaborTotals() {
    var totalHours = 0, totalCost = 0;
    document.querySelectorAll('.labor-hours').forEach(function(inp) {
        var id    = inp.dataset.id;
        var rate  = parseFloat(inp.dataset.rate) || 0;
        var hours = parseFloat(inp.value) || 0;
        var count = parseFloat(document.querySelector('.labor-count[data-id="' + id + '"]').value) || 1;
        var cost  = rate * hours * count;
        document.getElementById('lcost_' + id).textContent = cost.toFixed(2);
        totalHours += hours;
        totalCost  += cost;
    });
    document.getElementById('laborTotalHours').textContent = totalHours.toFixed(2);
    document.getElementById('laborTotalCost').textContent  = totalCost.toFixed(2);
}

function saveLaborHours() {
    currentLaborData = {};
    var totalHours = 0;
    document.querySelectorAll('.labor-hours').forEach(function(inp) {
        var id    = inp.dataset.id;
        var hours = parseFloat(inp.value) || 0;
        var count = parseFloat(document.querySelector('.labor-count[data-id="' + id + '"]').value) || 1;
        currentLaborData[id] = { hours: hours, count: count };
        totalHours += hours;
    });
    document.getElementById('laborHours').value = totalHours.toFixed(2);
    bootstrap.Modal.getInstance(document.getElementById('laborModal')).hide();
}

// ─── WORK LIST ────────────────────────────────────────────────────────────────
function addWork() {
    var taskSel  = document.getElementById('taskSelect');
    var taskId   = taskSel.value;
    var taskName = taskSel.options[taskSel.selectedIndex] ? taskSel.options[taskSel.selectedIndex].text : '';
    var subSel   = document.getElementById('subCatSelect');
    var subcatId = subSel.value;
    var type     = document.querySelector('input[name="tasktype"]:checked').value;
    var itemName = document.getElementById('workItemName').value.trim();
    var qty      = parseFloat(document.getElementById('workQty').value)       || 1;
    var unitPrice= parseFloat(document.getElementById('workUnitPrice').value) || 0;
    var price    = qty * unitPrice;
    var remarks  = document.getElementById('workRemarks').value.trim();

    if (!taskId && type !== 'O/Item') { alert('Please select a Task.'); return; }
    if (type === 'O/Item' && !itemName) { alert('Please enter an Item name.'); return; }

    workList.push({
        taskid:    taskId,
        taskname:  type === 'O/Item' ? itemName : taskName,
        subcatid:  subcatId,
        tasktype:  type,
        itemname:  type === 'O/Item' ? itemName : taskName,
        qty:       qty,
        unitprice: unitPrice,
        price:     price,
        remarks:   remarks,
    });
    renderWorkList();
    clearWorkForm();
}

function deleteWork() {
    if (selectedWork >= 0) {
        workList.splice(selectedWork, 1);
        selectedWork = -1;
        renderWorkList();
    }
}

function renderWorkList() {
    var body = document.getElementById('workBody');
    if (workList.length === 0) {
        body.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No work added yet.</td></tr>';
        return;
    }
    body.innerHTML = '';
    workList.forEach(function(w, i) {
        var tr = document.createElement('tr');
        tr.style.cursor = 'pointer';
        tr.onclick = function() { selectWork(i); };
        tr.innerHTML = '<td>' + (i+1) + '</td><td>' + w.taskname + '</td><td><span class="badge bg-secondary">' + w.tasktype + '</span></td><td>' + w.qty + '</td><td>' + w.price.toFixed(2) + '</td>';
        body.appendChild(tr);
    });
}

function selectWork(idx) {
    selectedWork = idx;
    document.querySelectorAll('#workBody tr').forEach(function(r,i){ r.classList.toggle('table-active', i===idx); });
}

function clearWorkForm() {
    document.getElementById('subCatSelect').value  = '';
    document.getElementById('taskSelect').value    = '';
    document.getElementById('workItemName').value  = '';
    document.getElementById('workQty').value       = 1;
    document.getElementById('workUnitPrice').value = '0.00';
    document.getElementById('workPrice').textContent = '0.00';
    document.getElementById('laborHours').value    = 0;
    document.getElementById('workRemarks').value   = '';
    document.getElementById('ilabor').checked      = true;
    document.getElementById('itemNameRow').style.display = 'none';
    currentLaborData = {};
    selectedWork = -1;
}

// ─── ITEM LIST ────────────────────────────────────────────────────────────────
function setItemPrice(sel) {
    var opt = sel.options[sel.selectedIndex];
    itemUnitPrice = opt ? (parseFloat(opt.dataset.price) || 0) : 0;
    document.getElementById('itemUnitPriceDisplay').textContent = itemUnitPrice.toFixed(2);
    calcItemPrice();
}

function calcItemPrice() {
    var qty = parseFloat(document.getElementById('itemQty').value) || 0;
    document.getElementById('itemPrice').textContent = (qty * itemUnitPrice).toFixed(2);
}

function addItem() {
    var sel      = document.getElementById('itemSelect');
    var itemCode = sel.value;
    var itemName = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].dataset.name : '';
    var qty      = parseFloat(document.getElementById('itemQty').value) || 1;
    var price    = qty * itemUnitPrice;
    var remarks  = document.getElementById('itemRemarks').value.trim();

    if (!itemCode) { alert('Please select an Item.'); return; }

    itemList.push({ itemcode: itemCode, itemname: itemName, qty: qty, unitprice: itemUnitPrice, price: price, remarks: remarks });
    renderItemList();
    clearItemForm();
}

function deleteItem() {
    if (selectedItem >= 0) {
        itemList.splice(selectedItem, 1);
        selectedItem = -1;
        renderItemList();
    }
}

function renderItemList() {
    var body = document.getElementById('itemBody');
    if (itemList.length === 0) {
        body.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No items added yet.</td></tr>';
        return;
    }
    body.innerHTML = '';
    itemList.forEach(function(it, i) {
        var tr = document.createElement('tr');
        tr.style.cursor = 'pointer';
        tr.onclick = function() { selectItem(i); };
        tr.innerHTML = '<td>' + (i+1) + '</td><td>' + it.itemname + '</td><td>' + it.qty + '</td><td>' + it.unitprice.toFixed(2) + '</td><td>' + it.price.toFixed(2) + '</td>';
        body.appendChild(tr);
    });
}

function selectItem(idx) {
    selectedItem = idx;
    document.querySelectorAll('#itemBody tr').forEach(function(r,i){ r.classList.toggle('table-active', i===idx); });
}

function clearItemForm() {
    document.getElementById('itemSelect').value = '';
    document.getElementById('itemQty').value    = 1;
    document.getElementById('itemUnitPriceDisplay').textContent = '0.00';
    document.getElementById('itemPrice').textContent = '0.00';
    document.getElementById('itemRemarks').value = '';
    itemUnitPrice = 0;
    selectedItem = -1;
}

function clearAll() {
    workList = []; itemList = [];
    renderWorkList(); renderItemList();
    clearWorkForm(); clearItemForm();
}

function prepareSubmit() {
    document.getElementById('worksJson').value = JSON.stringify(workList);
    document.getElementById('itemsJson').value = JSON.stringify(itemList);
}
</script>

<?php include 'includes/footer.php'; ?>
