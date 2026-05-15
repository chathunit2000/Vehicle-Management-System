<?php
include 'includes/header.php';
include 'includes/leftnav.php';
include 'database/db.php';
include 'Model/VehicleModel.php';
include 'Model/StatusModel.php';
include 'Model/FuelTypeModel.php';
include 'Model/MakeModel.php';
include 'Model/ModelModel.php';
include 'Model/CountryModel.php';
include 'Model/ColourModel.php';
include 'Model/ProvincialCouncilModel.php';

$database = new Database();
$db = $database->connect();

// Vehicle classes
$vehicleModel   = new VehicleModel($db);
$vehicleClasses = $vehicleModel->getVehicles();

// Vehicle statuses
$statusModel    = new StatusModel($db);
$vehicleStatuses = $statusModel->getStatuses();

// Taxation classes
$taxStmt = $db->prepare("SELECT id, description FROM TAXATION_CLASS ORDER BY description ASC");
$taxStmt->execute();
$taxClasses = $taxStmt->fetchAll(PDO::FETCH_ASSOC);

// Fuel types
$fuelModel  = new FuelTypeModel($db);
$fuelTypes  = $fuelModel->getFuelTypes()->fetchAll(PDO::FETCH_ASSOC);

// Makes
$makeModel  = new MakeModel($db);
$makes      = $makeModel->getMakes()->fetchAll(PDO::FETCH_ASSOC);

// Countries
$countryModel = new CountryModel($db);
$countries    = $countryModel->getCountries()->fetchAll(PDO::FETCH_ASSOC);

// Colours
$colourModel  = new ColourModel($db);
$colours      = $colourModel->getColours()->fetchAll(PDO::FETCH_ASSOC);

// Provincial councils
$councilModel   = new ProvincialCouncilModel($db);
$councils       = $councilModel->getProvincialCouncils()->fetchAll(PDO::FETCH_ASSOC);

// All models (for client-side filtering by make)
$allModelsStmt = $db->prepare("SELECT id, makeid, description FROM MODEL ORDER BY description ASC");
$allModelsStmt->execute();
$allModelsJson = json_encode($allModelsStmt->fetchAll(PDO::FETCH_ASSOC));
?>

<main class="content" style="padding-top: 80px;">
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Vehicle Registration</h4>
        </div>

        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <div class="row">

                    <!-- LEFT SIDE -->
                    <div class="col-lg-6">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Reg. No</label>
                                <input type="text" name="regno" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Chassis No</label>
                                <input type="text" name="chassisno" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Owner</label>
                            <textarea name="currentowner" rows="2" class="form-control form-control-sm"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Special Notes</label>
                            <textarea name="specialnotes" rows="2" class="form-control form-control-sm"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Absolute Owner</label>
                            <textarea name="absoluteowner" rows="2" class="form-control form-control-sm"></textarea>
                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Engine No</label>
                                <input type="text" name="engineno" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cylinder Capacity (cc)</label>
                                <input type="text" name="cc" class="form-control form-control-sm">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Class of Vehicle</label>
                                <select class="form-select form-select-sm" name="vehicleclass">
                                    <option value="">--Select--</option>
                                    <?php
                                    if($vehicleClasses && $vehicleClasses->rowCount() > 0) {
                                        while($row = $vehicleClasses->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['description']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Taxation Class</label>
                                <select class="form-select form-select-sm" name="taxclass">
                                    <option value="">--Select--</option>
                                    <?php foreach($taxClasses as $row): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Vehicle Status</label>
                                <select class="form-select form-select-sm" name="vehiclestatus">
                                    <option value="">--Select--</option>
                                    <?php
                                    if($vehicleStatuses && $vehicleStatuses->rowCount() > 0) {
                                        while($row = $vehicleStatuses->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['description']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fuel Type</label>
                                <select class="form-select form-select-sm" name="fueltype">
                                    <option value="">--Select--</option>
                                    <?php foreach($fuelTypes as $row): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Make</label>
                                <select class="form-select form-select-sm" name="make" id="makeSelect" onchange="loadModels(this.value)">
                                    <option value="">--Select--</option>
                                    <?php foreach($makes as $row): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <select class="form-select form-select-sm" name="country">
                                    <option value="">--Select--</option>
                                    <?php foreach($countries as $row): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Model</label>
                                <select class="form-select form-select-sm" name="model" id="modelSelect">
                                    <option value="">--Select Make first--</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Manufacture Description</label>
                                <input type="text" name="manufdescription" class="form-control form-control-sm">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Wheel Base</label>
                                <input type="text" name="wheelbase" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Over Hang</label>
                                <input type="text" name="overhang" class="form-control form-control-sm">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Type of Body</label>
                                <input type="text" name="typeofbody" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Year of Manufacture</label>
                                <input type="text" name="yearofmanuf" class="form-control form-control-sm">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Previous Owners</label>
                            <textarea name="previousowners" rows="3" class="form-control form-control-sm"></textarea>
                        </div>

                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="col-lg-6">

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Color</label>
                                <select class="form-select form-select-sm" name="colour">
                                    <option value="">--Select--</option>
                                    <?php foreach($colours as $row): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Seating Capacity</label>
                                <input type="text" name="seatingcapacity" class="form-control form-control-sm">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Weight (Kg)</label>
                                <div class="d-flex gap-2">
                                    <input type="text" placeholder="Unladen" class="form-control form-control-sm">
                                    <input type="text" placeholder="Gross" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tyre Size (cm)</label>
                                <div class="d-flex gap-2">
                                    <input type="text" placeholder="Front" class="form-control form-control-sm">
                                    <input type="text" placeholder="Rear" class="form-control form-control-sm">
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">L / W / H (cm)</label>

                                <div class="d-flex gap-2">
                                    <input type="text" placeholder="Length" class="form-control form-control-sm">
                                    <input type="text" placeholder="Width" class="form-control form-control-sm">
                                    <input type="text" placeholder="Height" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date of First Registration</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Provincial Council</label>
                                <select class="form-select form-select-sm" name="provincialcouncil">
                                    <option value="">--Select--</option>
                                    <?php foreach($councils as $row): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['description']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Value of Vehicle</label>
                                <input type="text" class="form-control form-control-sm">
                            </div>

                        </div>

                        <!-- IMAGE SECTION -->
                        <div class="row">

                            <div class="col-md-6">

                                <label class="form-label">Certificate of Registration</label>

                                <input type="file"
                                       name="certificateimg"
                                       class="form-control form-control-sm mb-2"
                                       accept="image/*"
                                       onchange="previewCertificate(event)">

                                <div class="border rounded p-2 text-center">

                                    <img id="certificatePreview"
                                         src="https://via.placeholder.com/250x250?text=Certificate"
                                         class="img-fluid"
                                         style="height:250px; object-fit:cover;">

                                </div>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">Vehicle Image</label>

                                <input type="file"
                                       name="vehicleimg"
                                       class="form-control form-control-sm mb-2"
                                       accept="image/*"
                                       onchange="previewVehicle(event)">

                                <div class="border rounded p-2 text-center">

                                    <img id="vehiclePreview"
                                         src="https://via.placeholder.com/250x250?text=Vehicle+Image"
                                         class="img-fluid"
                                         style="height:250px; object-fit:cover;">

                                </div>

                            </div>

                        </div>

                        <!-- BUTTONS -->
                        <div class="mt-4 text-end">

                            <button type="reset" class="btn btn-secondary">
                                Clear
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Add Vehicle
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

</div>
</main>

<script>
var ALL_MODELS = <?php echo $allModelsJson; ?>;

function loadModels(makeId) {
    var modelSelect = document.getElementById('modelSelect');
    modelSelect.innerHTML = '<option value="">--Select--</option>';
    if (!makeId) return;
    ALL_MODELS.forEach(function (m) {
        if (String(m.makeid) === String(makeId)) {
            var opt = document.createElement('option');
            opt.value = m.id;
            opt.text  = m.description;
            modelSelect.add(opt);
        }
    });
}

function previewCertificate(event)
{
    const reader = new FileReader();

    reader.onload = function()
    {
        document.getElementById('certificatePreview').src = reader.result;
    }

    reader.readAsDataURL(event.target.files[0]);
}

function previewVehicle(event)
{
    const reader = new FileReader();

    reader.onload = function()
    {
        document.getElementById('vehiclePreview').src = reader.result;
    }

    reader.readAsDataURL(event.target.files[0]);
}

</script>

<?php include 'includes/footer.php'; ?>