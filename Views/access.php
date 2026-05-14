<?php
session_start();
include 'includes/header.php';
include 'includes/leftnav.php';
?>

<main class="content" style="padding-top: 80px;">
  <div class="container-fluid">
            <div class="header mb-3">
            <h1 class="header-title">Manage Access</h1>
        </div>
          
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Pending Assistance Information</h3>
            </div>
            <div class="card-body">

            </div>
        </div>
        <div class="card mb-4" id="chart1">
            <div class="card-header">
                <h3 class="card-title">Approved Assistance Information</h3>
            </div>
            <div class="card-body">

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