<?php
session_start();
include 'includes/header.php';
include 'includes/leftnav.php';
?>

<main class="content" style="padding-top: 80px;">
  <div class="container-fluid">
            <div class="header mb-3">
            <h1 class="header-title">Assistance Management</h1>
        </div>
          
      <div class="card mb-4">
  <div class="card-header" id="cardheader">
    <h3 class="card-title">Assistance Request - Details</h3>
  </div>
  <div class="card-body">
    <form id=frmAssistance>
       <input id="txtAssistanceId" name="txtAssistanceId" type="hidden" class="form-control form-control-sm">
       <input id="txtstatus" name="txtstatus" type="hidden" value="new" class="form-control form-control-sm">
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Movement Type</label>
          <select id="cmbMovementType" name="cmbMovementType"  class="form-select form-select-sm">
            <option value="Arrival">Arrival</option>
            <option value="Departure">Departure</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Flight Date/Time</label>
          <input id="dtpFlightDate" name="dtpFlightDate" type="datetime-local" class="form-control form-control-sm" step="1">
        </div>

        <div class="col-md-4">
          <label class="form-label">Flight No</label>
          <input id="txtFlightNo" name="txtFlightNo" type="text" class="form-control form-control-sm">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Passenger Name</label>
          <input id="txtPaxName" name="txtPaxName" type="text" class="form-control form-control-sm">
        </div>

        <div class="col-md-4">
          <label class="form-label">Relationship</label>
          <select id="cmbRelationship" name="cmbRelationship" class="form-select form-select-sm">
            <option value="Father">Father</option>
            <option value="Mother">Mother</option>
            <option value="Spouse">Spouse</option>
            <option value="Sibling">Sibling</option>
            <option value="Children">Children</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Remarks</label>
          <input  id="txtRemark" name="txtRemark" type="text" class="form-control form-control-sm">
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <label class="form-label d-block mb-2">Area Available in the Pass:</label>
          <div class="d-flex flex-wrap gap-3">
            <div class="form-check form-check-inline" style="min-width: 40px;">
              <input  id="cbA" name="cbA" class="form-check-input" type="checkbox" id="areaA">
              <label class="form-check-label" for="areaA">A</label>
            </div>
            <div class="form-check form-check-inline"><input id="cbB" name="cbB" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaB">B</label></div>
            <div class="form-check form-check-inline"><input id="cbC" name="cbC" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaC">C</label></div>
            <div class="form-check form-check-inline"><input id="cbF" name="cbF" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaF">F</label></div>
            <div class="form-check form-check-inline"><input id="cbG" name="cbG" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaG">G</label></div>
            <div class="form-check form-check-inline"><input id="cbK" name="cbK" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaK">K</label></div>
            <div class="form-check form-check-inline"><input id="cbM" name="cbM" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaM">M</label></div>
            <div class="form-check form-check-inline"><input id="cbN" name="cbN" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaN">N</label></div>
            <div class="form-check form-check-inline"><input id="cbO" name="cbO" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaO">O</label></div>
            <div class="form-check form-check-inline"><input id="cbS" name="cbS" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaS">S</label></div>
            <div class="form-check form-check-inline"><input id="cbT" name="cbT" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaT">T</label></div>
            <div class="form-check form-check-inline"><input id="cbV" name="cbV" class="form-check-input" type="checkbox"><label class="form-check-label" for="areaV">V</label></div>
          </div>
        </div>
      
                  
      </div>
    </form>
  </div>
</div>
<div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Pending Approval Assistance</h3>
            </div>
            <div class="card-body">
            <table id="tblapproval" class="table table-striped">
                    <thead>
                       <tr>
                            <th>Request Id</th>
                            <th>Arrival/ Departure</th>
                            <th>Movement Date</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tblapprovalBody">                  
                       </tbody>
                        <tfoot>
                       
                      </tfoot>
                    </table>
            </div>
</div>
<div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Approved /Grant permision Assistance</h3>
            </div>
            <div class="card-body">
            <table id="tblApproved" class="table table-striped">
                    <thead>
                       <tr>
                            <th>Request Id</th>
                            <th>Arrival/ Departure</th>
                            <th>Movement Date</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tblApprovedBody">                  
                       </tbody>
                        <tfoot>
                       
                      </tfoot>
                    </table>
            </div>
</div>
<!-- Modal -->
<div class="modal fade" id="loadingModel" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered d-flex justify-content-center" role="document">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>
      
<!-- Modal -->
<div class="modal fade" id="missingInvoiceModel" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invoice Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Invoice:</strong> <span id="modalInvoice"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Amount:</strong> <span id="modalAmount"></span></p>
        <p><strong>Currency:</strong> <span id="modalCurrency"></span></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>

</main>
<script>
</script>

<?php include 'includes/footer.php'; ?>