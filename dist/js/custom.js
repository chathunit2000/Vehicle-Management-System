//Jquery taken from the template
$( document ).ready(function() {
    

        // Optional: Auto-highlight based on current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-link[href]');
            
            sidebarLinks.forEach(link => {
                if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
                    const parentItem = link.closest('.sidebar-item');
                    if (parentItem) {
                        parentItem.classList.add('selected');
                    }
                }
            });
        });

 let table;
 let table2;
    
    var fullPath = window.location.pathname; // Gets the path like /myfolder/mypage.php
    var fileName = fullPath.substring(fullPath.lastIndexOf('/') + 1); // Extracts mypage.php
    
//load user requests
if(fileName == "assistance.php")
{
    //load Assistance details to table
    loadAssistanceDetails(0,'full');
}
    
if(fileName == "approve.php")
{
    //load Assistance details to table
    loadAssistanceDetailsApproval(0,'full');
}
    
function loadAssistanceDetails($id,$type)
{
     $.ajax({
              type: "POST",
              url: 'Controller/assistanceController.php',           
              dataType: "json",
              data: { "assistanceId": $id},
              success: function(data) 
                { 
                   if($id ==0)
                    {
                            //Clear table 
                            $('#tbluserAssistanceBody').empty();  
                            var len = data.length; 
                            for( var i = 0; i<len; i++){
                       
                            var paxAssistanceId = data[i].paxAssistanceId;
                            var movementType = data[i].movementType;
                            var movementDateTime = data[i].movementDateTime;
                            var flightNo = data[i].flightNo;
                            var passengerName = data[i].passengerName;
                            var status = data[i].status;
                            var movementDate = '';

                            if(movementDateTime.date != undefined)
                            {
                                movementDate =movementDateTime.date;
                            }


                            tr = $('<tr/>');
                            tr.append("<td>" + paxAssistanceId +"</td>");
                            tr.append("<td>" + movementType +"</td>");
                            tr.append("<td>" + movementDate + "</td>");
                            tr.append("<td>" + flightNo + "</td>");
                            tr.append("<td>" + passengerName +"</td>");
                            tr.append("<td>" + status +"</td>");
                                
                            if(status =="pending")
                            {
                                  tr.append("<td><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary view-btn'><i class='bi bi-eye'></i></button><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary edit-btn'><i class='bi bi-pencil'></i></button><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary delete-btn'><i class='bi bi-trash'></i></button></td>");
                            }
                            else
                            {
                                  tr.append("<td><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary view-btn'><i class='bi bi-eye'></i></button><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary edit-btn'><i class='bi bi-pencil' disabled></i></button><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary delete-btn'><i class='bi bi-trash'></i></button></td>");
                            }
                          
                            $('#tbluserAssistanceBody').append(tr);    

                        }  

                        if (!$.fn.DataTable.isDataTable('#tbluserAssistance')) {
                            $('#tbluserAssistance').DataTable({                           
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    pageLength: 10,
                                    deferRender: true,
                                    processing: true,
                                    "order": [[0, 'desc']],
                                    language: {
                                      emptyTable: 'No records found',
                                      loadingRecords: 'Loading records…',
                                      processing: 'Loading…'
                                    }
                            });
                        }
                    }
                    else
                    {
                            //check status 
                             $("#txtstatus").val($type);
                        
                            // uncheck checked areas
                            $("input[type=checkbox]").each(function() {
                                $(this).prop('checked', false);
                            });
                        
                            if(data.area != undefined)
                            {
                                let areaArray = data.area.map(a => a.Area);
                                
                                                                    // Check araea
                                $("input[type=checkbox]").each(function() {
                                   $areaName =  $(this).next("label").text().trim();                              
                                   if (areaArray.includes($areaName)) {

                                         $(this).prop('checked', true);
                                    }                                
                                });
                                
                            }
                          
                           

                            var myButton = document.getElementById('btnSubmit');
                            const header = document.getElementById('cardheader');
                            header.classList.remove('bg-success'); 
                            header.classList.remove('bg-warning'); 
                            header.classList.remove('bg-danger'); 
                        
                            if($type == 'view')
                            {
                                header.innerHTML = "Assistance Request - View";
                                myButton.disabled = true;
                                header.classList.add('bg-success');  
                                $("#txtAssistanceId").val("0");
                            }
                            else if($type == 'edit')
                            {
                                
                                 $("#txtAssistanceId").val($id);                                
                                 header.innerHTML = "Assistance Request - Edit";
                                 myButton.disabled = false;
                                 header.classList.add('bg-warning');       
                            }
                            else if($type == 'delete')
                            {
                                 $("#txtAssistanceId").val($id);
                                 header.innerHTML = "Assistance Request - delete";
                                 myButton.disabled = false;
                                 header.classList.add('bg-danger');       
                            }
                            else
                            {
                                header.innerHTML = "Assistance Request - New";
                                myButton.disabled = false;
                                header.classList.remove('bg-success'); 
                                header.classList.remove('bg-warning'); 
                                $("#txtAssistanceId").val("0");
                            }
                            var paxAssistanceId2 = data.header[0].paxAssistanceId;
                            var movementType2 = data.header[0].movementType;
                            var movementDateTime2 = data.header[0].movementDateTime;
                            var flightNo2 = data.header[0].flightNo;
                            var passengerName2 = data.header[0].passengerName;
                            var relationship = data.header[0].relationship;
                            var status2 = data.header[0].status;
                            var movementDate2 = '';
                            var remark = data.header[0].remarks;
                            var assistDatetime = '';
                            var service_no = data.header[0].service_no;
                            var employeeName = data.header[0].employeeName;
                            var designation = data.header[0].designation;
                            if(data.header[0].movementType != undefined)
                            {
                                assistDatetime = movementDateTime2.date.replace(" ", "T").slice(0, 16);                               
                            }
                            
                        
                            $("#cmbMovementType").val(movementType2);
                            $("#dtpFlightDate").val(assistDatetime);
                            $("#txtFlightNo").val(flightNo2);
                            $("#txtPaxName").val(passengerName2);
                            $("#cmbRelationship").val(relationship);
                            $("#txtRemark").val(remark);
                            $("#txtServiceNo").val(service_no);
                            $("#txtEmpName").val(employeeName);
                            $("#txtDesignation").val(designation);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                   // alert("Error when loading!");
                     
                } 

                    
              });
    
}
    
function loadAssistanceDetailsApproval($id,$type)
{
     $.ajax({
              type: "POST",
              url: 'Controller/assistanceController.php',           
              dataType: "json",
              data: { "approvalList": $id},
              success: function(data) 
                { 
                   if($id ==0)
                    {
                            //Clear table 
                            $('#tblapprovalBody').empty();  
                            var len = data.length; 
                            for( var i = 0; i<len; i++){
                       
                            var paxAssistanceId = data[i].paxAssistanceId;
                            var movementType = data[i].movementType;
                            var movementDateTime = data[i].movementDateTime;
                            var employeeName = data[i].employeeName;
                            var designation = data[i].designation;
                            var status = data[i].status;
                            var movementDate = '';

                            if(movementDateTime.date != undefined)
                            {
                                movementDate =movementDateTime.date;
                            }


                            tr = $('<tr/>');
                            tr.append("<td>" + paxAssistanceId +"</td>");
                            tr.append("<td>" + movementType +"</td>");
                            tr.append("<td>" + movementDate + "</td>");
                            tr.append("<td>" + employeeName + "</td>");
                            tr.append("<td>" + designation +"</td>");
                            tr.append("<td>" + status +"</td>");
                            tr.append("<td><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary view-approve-btn'><i class='bi bi-eye'></i></button><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary approve-btn'><i class='bi bi-check-lg'></i></button><button id=" +paxAssistanceId+ " class='btn btn-sm btn-outline-primary reject-btn'><i class='bi bi-x-circle'></i></button></td>");
                            $('#tblapprovalBody').append(tr);    

                        }  

                        if (!$.fn.DataTable.isDataTable('#tblapproval')) {
                            $('#tblapproval').DataTable({                           
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    pageLength: 10,
                                    deferRender: true,
                                    processing: true,
                                    "order": [[0, 'desc']],
                                    language: {
                                      emptyTable: 'No records found',
                                      loadingRecords: 'Loading records…',
                                      processing: 'Loading…'
                                    }
                            });
                        }
                    }
                    else
                    {
                            //check status 
                             $("#txtstatus").val($type);
                        
                            // uncheck checked areas
                            $("input[type=checkbox]").each(function() {
                                $(this).prop('checked', false);
                            });
                        
                            if(data.area != undefined)
                            {
                                let areaArray = data.area.map(a => a.Area);
                                
                                                                    // Check araea
                                $("input[type=checkbox]").each(function() {
                                   $areaName =  $(this).next("label").text().trim();                              
                                   if (areaArray.includes($areaName)) {

                                         $(this).prop('checked', true);
                                    }                                
                                });
                                
                            }
                          
                           

                          
                            const header = document.getElementById('cardheader');
                            header.classList.remove('bg-success'); 
                            header.classList.remove('bg-warning'); 
                            header.classList.remove('bg-danger'); 
                        
                            if($type == 'view')
                            {
                                header.innerHTML = "Assistance Request - View";
                              
                                header.classList.add('bg-success');  
                                $("#txtAssistanceId").val("0");
                            }
                            else if($type == 'edit')
                            {
                                
                                 $("#txtAssistanceId").val($id);                                
                                 header.innerHTML = "Assistance Request - Edit";
                               
                                 header.classList.add('bg-warning');       
                            }
                            else if($type == 'delete')
                            {
                                 $("#txtAssistanceId").val($id);
                                 header.innerHTML = "Assistance Request - delete";
                               
                                 header.classList.add('bg-danger');       
                            }
                            else
                            {
                                header.innerHTML = "Assistance Request - New";                             
                                header.classList.remove('bg-success'); 
                                header.classList.remove('bg-warning'); 
                                $("#txtAssistanceId").val("0");
                            }
                            var paxAssistanceId2 = data.header[0].paxAssistanceId;
                            var movementType2 = data.header[0].movementType;
                            var movementDateTime2 = data.header[0].movementDateTime;
                            var flightNo2 = data.header[0].flightNo;
                            var passengerName2 = data.header[0].passengerName;
                            var relationship = data.header[0].relationship;
                            var status2 = data.header[0].status;
                            var movementDate2 = '';
                            var remark = data.header[0].remarks;
                            var assistDatetime = '';
                            if(data.header[0].movementType != undefined)
                            {
                                assistDatetime = movementDateTime2.date.replace(" ", "T").slice(0, 16);                               
                            }
                        
                            $("#cmbMovementType").val(movementType2);
                            $("#dtpFlightDate").val(assistDatetime);
                            $("#txtFlightNo").val(flightNo2);
                            $("#txtPaxName").val(passengerName2);
                            $("#cmbRelationship").val(relationship);
                            $("#txtRemark").val(remark);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                   // alert("Error when loading!");
                     
                } 

                    
              });
    
}
     
 $("#btnSubmit").click(function() {
    // Collect form data
    var status=  $("#txtstatus").val();
     
    if(status =="delete")
    {
          var result = confirm("Are you sure you want to delete this request?");
          if (result == false) {
            return;
          }
    }
     
     
    let data = {
      status: status,
      txtRequestId :$("#txtAssistanceId").val(),
      cmbMovementType: $("#cmbMovementType").val(),
      dtpFlightDate: $("#dtpFlightDate").val(),
      txtFlightNo: $("#txtFlightNo").val(),
      txtPaxName: $("#txtPaxName").val(),
      cmbRelationship: $("#cmbRelationship").val(),
      txtRemark: $("#txtRemark").val(),
      serviceNo: $("#txtServiceNo").val(),
      employeeName: $("#txtEmpName").val(),
      designation: $("#txtDesignation").val(),     
      areas: []
    };
     

    // Collect checked areas
    $("input[type=checkbox]:checked").each(function() {
      data.areas.push($(this).next("label").text().trim());
    });

    // Send via AJAX
    $.ajax({
      url: "Controller/assistanceController.php",   // your PHP backend file
      type: "POST",
      data: data,
      dataType: "json",
      success: function(response) {
        if (response.id >0) {

            if(status =="new")
            {
                 alert("Assistance Request Inserted!");
            }
            else if(status =="edit")
            {
                 alert("Assistance Request Updated!");
            }
            else if(status =="delete")
            {
                 alert("Assistance Request deleted!");
            }
         
          ClearInputs();
          loadAssistanceDetails(0);            
        } else {
          alert("Error: " + response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("Something went wrong. Please try again.");
      }
    });
  });
    
   // Handle click on edit button  
  $('body').on('click', '.edit-btn', function () {
       loadAssistanceDetails(this.id,'edit');
  });
    
    
 // Handle click on View button
  $('#tbluserAssistance tbody').on('click', '.view-btn', function () {
       loadAssistanceDetails(this.id,'view');
  });    

 // Handle click on delete button
  $('#tbluserAssistance tbody').on('click', '.delete-btn', function () {
       loadAssistanceDetails(this.id,'delete');
  });
    
 // Handle click on View button approval
  $('#tblapproval tbody').on('click', '.view-approve-btn', function () {
       loadAssistanceDetailsApproval(this.id,'view');
  });
    
 // Handle click on button approval
  $('#tblapproval tbody').on('click', '.approve-btn', function () {
     
        // Send via AJAX
        $.ajax({
          url: "Controller/assistanceController.php",   // your PHP backend file
          type: "POST",
          data: {approveRequestId:this.id},
          dataType: "json",
          success: function(response) {
            if (response.id >0) {
                alert("Request Approved!");

              ClearInputs();
                loadAssistanceDetailsApproval(0,'full'); 
            } else {
              alert("Error: " + response.message);
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("Something went wrong. Please try again.");
          }
        });
  });
    
 // Handle click on View button approval
  $('#tblapproval tbody').on('click', '.reject-btn', function () {
     
         // Send via AJAX
        $.ajax({
          url: "Controller/assistanceController.php",   // your PHP backend file
          type: "POST",
           data: {rejectrequestId:this.id},
          dataType: "json",
          success: function(response) {
            if (response.id >0) {

              alert("Request Rejected!");
                
              ClearInputs();
              loadAssistanceDetailsApproval(0,'full');   
            } else {
              alert("Error: " + response.message);
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("Something went wrong. Please try again.");
          }
        });
  });
    
    
function ClearInputs()
{   
       var myButton = document.getElementById('btnSubmit');
       const header = document.getElementById('cardheader');
     $("#txtServiceNo").val("");
     $("#txtEmpName").val("");
     $("#txtDesignation").val("");
     $("#txtAssistanceId").val("");
     $("#cmbMovementType").val("");
     $("#txtFlightNo").val("");
     $("#txtPaxName").val("");
     $("#cmbRelationship").val("");
     $("#txtRemark").val("");
     $("#txtstatus").val("new");
    // uncheck checked areas
    $("input[type=checkbox]:checked").each(function() {
        $(this).prop('checked', false);
    });
    header.innerHTML = "Assistance Request - New";
    
    if(myButton != undefined)
    {
            myButton.disabled = false;
    }

    header.classList.remove('bg-success'); 
    header.classList.remove('bg-warning'); 
    header.classList.remove('bg-danger'); 
    $("#txtAssistanceId").val("0");
}
    
$("#btnLogOut").click(function() {
    // Show confirmation modal
    var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
    logoutModal.show();
});
    
$("#btnLogOutConfirm").click(function() {

    sessionStorage.clear();
    localStorage.clear();

    // Show loading indicator
    document.body.style.cursor = 'wait';

    // Perform logout
    window.location.href = 'index.php';
});

function highlightSection(element) {
    // Remove previous selections
    const allSidebarItems = document.querySelectorAll('.sidebar-item');
    allSidebarItems.forEach(item => {
        item.classList.remove('selected');
    });

    // Add selection to clicked item
    const parentItem = element.closest('.sidebar-item');
    if (parentItem) {
        parentItem.classList.add('selected');
    }
}

    
});