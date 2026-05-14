//Jquery taken from the template
$( document ).ready(function() {

 let table;
 let table2;
    
    var fullPath = window.location.pathname; // Gets the path like /myfolder/mypage.php
    var fileName = fullPath.substring(fullPath.lastIndexOf('/') + 1); // Extracts mypage.php

if(fileName == "sales.php")
{
    //load shop Details to dropdown
    loadShopDetails();
}
    
function loadShopDetails()
{
     $.ajax({
              type: "POST",
              url: 'Controller/shopController.php',           
              dataType: "json",
              data: { "shopList": 0},
              success: function(data) 
                { 
           
                   var len = data.length;
                    
                   for( var i = 0; i<len; i++){
                    var shopId = data[i]['shopId'];
                    var ShopName = data[i]['ShopName'];                   
                        $("#cmbShop").append("<option value='"+shopId+"'>"+ShopName+"</option>");

                    }              

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                   // alert("Error when loading!");
                     
                } 

                    
              });
    
}
    

 $("#btnReport").click(function(){
  var shopId = 0;
  var txtshopId= $("#shopId").val();
     
 if(txtshopId =="")
 {
     var shopId= $("#cmbShop").val();
     
 }
else{
    var shopId= $("#shopId").val();   
}
    
    const modal = new bootstrap.Modal(document.getElementById('loadingModel'));
    modal.show();
   var fromDate = $("#SalesFrom").val();
    var toDate = $("#SalesTo").val();
        
     
    var ShopName ='';
    
    //alert(systemId);
         $.ajax({
              type: "POST",
              url: 'Controller/shopController.php',           
              dataType: "json",
              data: { "shopId": shopId,"fromDate":fromDate,"toDate":toDate},
             beforeSend: function() {
                    // Show the spinner before the AJAX request is sent
                    //$("#loadingSpinner").show();
                  },
              success: function(data) 
                { 
                    $("#shopId").text("");
                    $("#shopName").text("");
                    $("#category").text("");
                    $("#companyName").text("");
                    $("#totalSale").text("");
                    $("#CancelledAmount").text("");
                    $("#invoiceCount").text("");
                    $("#cancelledBill").text("");
                    $("#lastInvoiceDate").text("");
                    $("#lastInvoice").text("");   
                    $("#agreementId").text("");
                    $("#startDate").text("");
                    $("#endDate").text("");
                    $("#propertyName").text("");
                    $("#SQFT").text("");
                    $("#Location").text("");
                    $("#STO").text("");
                    $("#BankDeposit").text("");
                    $("#Status").text("");
                    $('#tblCancelSaleBody').empty();  
                    $('#tblSaleBody').empty();  
                    totalSale =0;
                    cancelledAmount=0;

                    var len = data.shopDetails.length;
                    var len2 = data.agreementDetails.length;
                    var len3 = data.salesDetails.length;
                    var len4 = data.CancelBill.length;
                    var len5 = data.maxBill.length;
                    
                  // Load Shop Details
                   for( var i = 0; i<len; i++){
                    var shopId = data.shopDetails[i]['shopId'];
                    ShopName = data.shopDetails[i]['ShopName'];
                    var categoryName = data.shopDetails[i]['categoryName'];
                    var companyName = data.shopDetails[i]['companyName'];
                       
                    $("#shopId").text(shopId);
                    $("#shopName").text(ShopName);
                    $("#category").text(categoryName);
                    $("#companyName").text(companyName);

                    }
                    
                    // Load Sale Details
                   for( var i = 0; i<len5; i++){
                    var maxDate = data.maxBill[i]['maxDate'];
                    var maxInvoice = data.maxBill[i]['maxInvoice'];
                    var transactionDateMax = '';
                      
                    if(maxDate != null)
                    {
                        if(maxDate.date != undefined)
                        {
                            transactionDateMax =maxDate.date;
                        }
                        else{

                            transactionDateMax =maxDate;
                        }                      
                    }
                   
                    $("#lastInvoiceDate").text(transactionDateMax);
                    $("#lastInvoice").text(maxInvoice);                 

                    }
                    
                     // Load aAgreement Details
                    for( var i = 0; i<len2; i++){
                    var BankDepositAmount = data.agreementDetails[i]['BankDepositAmount'];
                    var PropertyName = data.agreementDetails[i]['PropertyName'];
                    var agreementId = data.agreementDetails[i]['agreementId'];
                    var agreementStatus = data.agreementDetails[i]['agreementStatus'];
                    var confeeRate = data.agreementDetails[i]['confeeRate'];
                    var endDate = data.agreementDetails[i]['endDate'];
                    var locationName = data.agreementDetails[i]['locationName'];
                    var mininumSTOLevel = data.agreementDetails[i]['mininumSTOLevel'];
                    var PropertyName = data.agreementDetails[i]['PropertyName'];
                    var sqft = data.agreementDetails[i]['sqft'];
                    var startDate = data.agreementDetails[i]['startDate'];
                       
                    $("#agreementId").text(agreementId);
                    $("#startDate").text(startDate);
                    $("#endDate").text(endDate);
                    $("#propertyName").text(PropertyName);
                    $("#SQFT").text(shopId);
                    $("#Location").text(locationName);
                    $("#STO").text(mininumSTOLevel);
                    $("#BankDeposit").text(BankDepositAmount);
                    $("#Status").text(agreementStatus);

                    }
                    
                    var totalSale =0;
                    var cancelledAmount =0;
                    var InvoiceCount =len3;
                    
                     // Load Sale Details
                    for( var i = 0; i<len3; i++){
                    var dayId = data.salesDetails[i]['dayId'];
                    var INVOICE_NUMBER = data.salesDetails[i]['INVOICE_NUMBER'];
                    var NET_SALES = data.salesDetails[i]['NET_SALES'];
                    var CURRENCY = data.salesDetails[i]['CURRENCY'];
                    var TRANSACTION_DATE = data.salesDetails[i]['TRANSACTION_DATE'];
                    var transactionDate = '';  
                        
                        
                    if(TRANSACTION_DATE.date != undefined)
                    {
                        transactionDate =TRANSACTION_DATE.date;
                    }
                    else{
                        
                        transactionDate =TRANSACTION_DATE;
                    }
                        
                    tr = $('<tr/>');
                    tr.append("<td>" + INVOICE_NUMBER +"</td>");
                    tr.append("<td>" + transactionDate + "</td>");
                    tr.append("<td>" + NET_SALES + "</td>");
                    tr.append("<td>" + CURRENCY +"</td>");
                    tr.append("<td><button id=" +dayId+ " class='btn btn-sm btn-outline-primary view-btn'><i class='bi bi-eye'></i></button></td>");
                        
                         let val = parseFloat(NET_SALES);      // convert string → number
                          if (!isNaN(val))
                          {
                                  totalSale = totalSale+val;
                          }

                        $('#tblSaleBody').append(tr);   
                    }
                    
                    tr = $('<tr/>');
                    tr.append("<td>Total Sale Amount</td>");                     
                    tr.append("<td></td>");  
                    tr.append("<td>"+totalSale+"</td>");
                    tr.append("<td></td>");  
                    tr.append("<td></td>");  
                    $('#tblSaleBody').append(tr);   
                    
                     var Title ='Shop :' + $("#shopId").text() + ' - ' + $("#companyName").text(); 
                     var Title2 ='Period of :' + fromDate + ' - ' + toDate; 

                    if (!$.fn.DataTable.isDataTable('#tblsale')) {
                        $('#tblsale').DataTable({
                             columns: [
                                  { data: 'INVOICE_NUMBER' },
                                  { data: 'TRANSACTION_DATE' },
                                  { data: 'NET_SALES' },
                                  { data: 'CURRENCY' },
                                  { data: 'View' }
                                ],
                            dom: 'Bfrtip',
                             buttons: [
                            {
                              extend: 'excelHtml5',
                              text: 'Export Excel',
                              title: 'Monthly Sale', // suppress default title
                              messageTop: Title + " " + Title2
                            }
                          ],
                                paging: true,
                                searching: true,
                                ordering: true,
                                pageLength: 10,
                                deferRender: true,
                                processing: true,
                                language: {
                                  emptyTable: 'No records found',
                                  loadingRecords: 'Loading records…',
                                  processing: 'Loading…'
                                }
                        });
                    }
                    
                     // Load  Cancelled Sale Details
                    for( var i = 0; i<len4; i++){
                    var INVOICE_NUMBER = data.CancelBill[i]['INVOICE_NUMBER'];
                    var NET_SALES = data.CancelBill[i]['NET_SALES'];
                    var CURRENCY = data.CancelBill[i]['CURRENCY'];
                    var TRANSACTION_DATE = data.CancelBill[i]['TRANSACTION_DATE'];
                        
                       var transactionDateCancel = '';
                    if(TRANSACTION_DATE.date != undefined)
                    {
                        transactionDateCancel =TRANSACTION_DATE.date;
                    }
                    else{
                        
                        transactionDateCancel =TRANSACTION_DATE;
                    }
                        
                    tr = $('<tr/>');
                    tr.append("<td>" + INVOICE_NUMBER +"</td>");
                    tr.append("<td>" + transactionDateCancel + "</td>");
                    tr.append("<td>" + NET_SALES + "</td>");
                    tr.append("<td>" + CURRENCY +"</td>");
                      tr.append("<td><button id=" +dayId+ " class='btn btn-sm btn-outline-primary view-btn-cancel'><i class='bi bi-eye'></i></button></td>");
                  
                          let val2 = parseFloat(NET_SALES);      // convert string → number
                          if (!isNaN(val2))
                          {
                                  cancelledAmount = cancelledAmount+val2;
                          }                        
                     
                        $('#tblCancelSaleBody').append(tr);   
                    }
                    
                     tr = $('<tr/>');
                    tr.append("<td>Total Cancel Amount</td>");                    
                    tr.append("<td></td>");
                    tr.append("<td>"+cancelledAmount+"</td>");
                     tr.append("<td></td>");  
                    tr.append("<td></td>");   
                    $('#tblCancelSaleBody').append(tr);   
                    
                    if (!$.fn.DataTable.isDataTable('#tblCancellsale')) {
                        table2 = new DataTable('#tblCancellsale', {
                            columns: [
                                { data: 'INVOICE_NUMBER' },
                                { data: 'TRANSACTION_DATE' },
                                { data: 'NET_SALES'},
                                { data: 'CURRENCY' },
                                { data: 'View' }
                               
                            ],
                             dom: 'Bfrtip',  // Buttons on top                            
                            buttons: [
                            {
                              extend: 'excelHtml5',
                              text: 'Export Excel',
                              title: 'Cancelled Bills',
                              messageTop: Title + ' '+ Title2  
                            }
                          ],
                             paging: true,
                            searching: true,
                            ordering: true,
                            pageLength: 10,
                            deferRender: true,
                            processing: true, // shows “Processing…” during ajax
                            language: {
                              emptyTable: 'No records found',       // after load if truly empty
                              loadingRecords: 'Loading records…',   // while drawing
                              processing: 'Loading…'
                            }
                        });
                    }
                    
                    
                    const firstChar = cancelledAmount.toFixed(2).charAt(0);
                    
                    if(firstChar =='-')
                    {
                         totalSale = totalSale + cancelledAmount;
                    }
                    else
                    {
                         totalSale = totalSale - cancelledAmount;
                    }
            
                   
                    $("#totalSale").text(totalSale.toFixed(2));
                    $("#CancelledAmount").text(cancelledAmount.toFixed(2));
                    $("#invoiceCount").text(len3);
                    $("#cancelledBill").text(len4);
                    
                    modal.hide();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    modal.hide();
                    $("#shopId").text("");
                    $("#shopName").text("");
                    $("#category").text("");
                    $("#companyName").text("");
                    $("#totalSale").text("");
                    $("#CancelledAmount").text("");
                    $("#invoiceCount").text("");
                    $("#cancelledBill").text("");
                    $('#tblCancelSaleBody').empty();  
                    $('#tblSaleBody').empty();  
                    totalSale =0;
                    cancelledAmount=0;
                }
             
         });
    
    });
    
    
  $('body').on('click', '.modelMissingInvoice', function () {

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('missingInvoiceModel'));
    modal.show();
  });
    
    
 // Handle click on View button
  $('#tblsale tbody').on('click', '.view-btn', function () {
    var id = this.id;
        $.ajax({
              type: "POST",
              url: 'Controller/shopController.php',           
              dataType: "json",
              data: {"dayId":id},
              success: function(data) 
                { 
                
                
                }
        });

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewModal'));
    modal.show();
  });
    
});

