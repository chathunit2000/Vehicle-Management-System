//Jquery to manage data
$( document ).ready(function() {
 //load relavant columns and datset
 // window.addEventListener('load', getFinancialData(), false);
//get the name of page and load data
    
var path = window.location.pathname;
var page = path.split("/").pop();
    
//Financial data management    
 
if(page =="financial.php")
{
  // getFinancialData();
}
    
$('#datetimepicker-date').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date2').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date3').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date4').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date5').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date6').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date7').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date8').datetimepicker({
    format: 'yyyy-MM-DD'
});
 
$('#datetimepicker-date9').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date10').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date11').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date12').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date13').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date14').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date15').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date16').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date17').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date18').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date19').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date20').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date21').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date22').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date23').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date24').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date25').datetimepicker({
    format: 'yyyy-MM-DD'
});

$('#datetimepicker-date26').datetimepicker({
    format: 'yyyy-MM-DD'
});
    
$('#datetimepicker-date27').datetimepicker({
    format: 'yyyy-MM-DD'
});  
    



    
// Datatables Responsive
$("#datatables-reponsive").DataTable({
    responsive: true
});
    
    
$(".catId").click(function(){
      getFinancialData(this.id);
    }); 
    
  $('.collapse').collapse(); 
 
function getFinancialData($id)
{
        $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "financialList": $id},
          success: function(data) 
            { 
                $("#tbAreoInt").empty();
                var len = data.length;
                
                for( var i = 0; i<len; i++){                    
                    var id = data[i]['recordId'];
                    var datetime = data[i]['datetime']; 
                    var itemvalue = data[i]['itemvalue']; 
                          
                        tr = $('<tr/>');
                        tr.append("<td>" + datetime + "</td>");
                        tr.append("<td>" + itemvalue + "</td>"); 

                       //tr.append("<td><a class='add2' title='Add' data-toggle='tooltip'><i class='material-icons'>&#xE03B;</i></a><a class='edit2' title='Edit' data-toggle='tooltip'><i class='material-icons'>&#xE254;</i></a><a class='delete2' title='Delete' data-toggle='tooltip'><i class='material-icons'>&#xE872;</i></a>"); 
                       tr.append("<td class='table-action'><a href=''#'><i class='align-middle fas fa-fw fa-pen'></i></i></a></td>");
                       $('#tbAreoInt').append(tr);
                }
                
                					

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $("#tbAreoInt").empty();

            } 


          }); 
}
    
function getLevelData1($id)
{
        $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "financialList": $id},
          success: function(data) 
            { 
                
                var len = data.length;
                $("#cat"+$id).append("<div class='tab tab-vertical tab-primary' id='tab-content"+$id+"'><ul class='nav nav-tabs' role='tablist' id='tabList"+$id+"'>");
                
                for( var i = 0; i<len; i++){                    
                    var id = data[i]['id'];
                    var cateogry = data[i]['category'];  
                    
                    $("#tabList"+$id).append("<li class='nav-item'><a class='nav-link' href='#tab"+id+"' data-bs-toggle='tab' role='tab'>"+cateogry+"</a></li>");
                    
                   // $("#cat"+$id).append("<div class='row'><div class='col-12'><div class='card'><div class='card-header'><h5 class='card-title mb-0'>"+cateogry+"</h5></div><div class='card-body'><div class='my-5' id='subcat"+id+"'>&nbsp;</div></div></div></div></div>");
                   //getLevelData2(id);
 
                    
                }
                  $("#tab-content"+$id).append("</ul>");                
                  $("#tab-content"+$id).append("<div class='tab-content' id='cont"+$id+"'>");
                
                  for( var i2 = 0; i2<len; i2++){  
                        var id = data[i2]['id'];
                        var cateogry = data[i2]['category'];  
                        $("#cont"+$id).append("<div class='tab-pane' id='tab"+id+"' role='tabpanel'><h4 class='tab-title'>Colored vertical icon tabs</h4><p>TAB "+ cateogry+"</p></div>");

                  }
                    
                  $("#cat"+$id).append("</div>");                
                  $("#cat"+$id).append("</div>");
                					

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
}
    
function getLevelData2($id)
{
        $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "financialList": $id},
          success: function(data) 
            { 
                
                var len = data.length;
                
                if(len >0)
                {
                    for( var i = 0; i<len; i++){                    
                    var id = data[i]['id'];
                    var cateogry = data[i]['category'];                
                    $("#subcat"+$id).append("<div class='row'><div class='col-12'><div class='card'><div class='card-header'><h5 class='card-title mb-0'>"+cateogry+"</h5></div><div class='card-body'><div class='my-5' >&nbsp;</div></div></div></div></div>");
                    
                    }   
                }
                else
                {
                    
                    
                }
                
                          
                					

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
}
    
 //Areonatical International Data    
$("#btnAreoInt").click(function(){
 var areoIntDate = $("#areoIntDate").val();
 var areoIntValue = $("#areoIntValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "areoInt": 46,"areoIntDate": areoIntDate,"areoIntValue": areoIntValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(46);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
//Areonatical Domestic Data  
$("#btnAreoDom").click(function(){
 var areoDomDate = $("#areoDomDate").val();
 var areoDomValue = $("#areoDomValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "areoDom": 47,"areoDomDate": areoDomDate,"areoDomValue": areoDomValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                     getFinancialData(47);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
//Areonautical Areo bridge data
$("#btnAerobridge").click(function(){
 var AerobridgetDate = $("#AerobridgetDate").val();
 var AerobridgeValue = $("#AerobridgeValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "areobridgeId": 32,"AerobridgetDate": AerobridgetDate,"AerobridgeValue": AerobridgeValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(32);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
      
//Areonautical over flying data
$("#btnAoverfly").click(function(){
 var overflyIntDate = $("#overflyIntDate").val();
 var overflyValue = $("#overflyValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "areooveflyId": 33,"overflyIntDate": overflyIntDate,"overflyValue": overflyValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(33);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    });
    
//Domestic Ground Handling CIAR / BIA
$("#btndomGroundHandle").click(function(){
 var domGroundHandletDate = $("#domGroundHandletDate").val();
 var adomGroundHandleValue = $("#adomGroundHandleValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "domGroundId": 43,"domGroundHandletDate": domGroundHandletDate,"adomGroundHandleValue": adomGroundHandleValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(43);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
    
//Other Non-Aeronautical Income
$("#btnothernonareo").click(function(){
 var othernonareoDate = $("#othernonareoDate").val();
 var othernonareoValue = $("#othernonareoValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "othernonId": 44,"othernonareoDate": othernonareoDate,"othernonareoValue": othernonareoValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(44);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
//Lounges
$("#btnlounge").click(function(){
 var loungeDate = $("#loungeDate").val();
 var loungeValue = $("#loungeValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "loungeId": 45,"loungeDate": loungeDate,"loungeValue": loungeValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(45);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    });
    
//Parking fee
$("#btnparkingfee").click(function(){
 var parkingfeetDate = $("#parkingfeetDate").val();
 var parkingfeeValue = $("#parkingfeeValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "parkingfeeId": 42,"parkingfeetDate": parkingfeetDate,"parkingfeeValue": parkingfeeValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(42);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    });   
    
//Entry Permit
$("#btnEntryPermit").click(function(){
    
 var entrypermitDate = $("#EntryPermitDate").val();
 var entrypermitValue = $("#EntryPermitValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "entrypermitId": 37,"entrypermitDate": entrypermitDate,"entrypermitValue": entrypermitValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(37);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    });  
    
 //Franchise Fee - SLCS
$("#btnFranSLCS").click(function(){
 var FranSLCStDate = $("#FranSLCStDate").val();
 var FranSLCSValue = $("#FranSLCSValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "fraslcsId": 40,"FranSLCStDate": FranSLCStDate,"FranSLCSValue": FranSLCSValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(40);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    });     
 
 //Franchise Fee on Ground Handling - SLA
$("#btnFranGround").click(function(){
 var FranGroundDate = $("#FranGroundDate").val();
 var FranGroundValue = $("#FranGroundValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "fragroundId": 39,"FranGroundDate": FranGroundDate,"FranGroundValue": FranGroundValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(39);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    });  
    
 //Fuel Throughput Charges
$("#btnFuelCharge").click(function(){
 var FuelChargeDate = $("#FuelChargeDate").val();
 var FuelChargeValue = $("#FuelChargeValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "fuelChargeId": 38,"FuelChargeDate": FuelChargeDate,"FuelChargeValue": FuelChargeValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(38);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 

 //Entry Permits
$("#btnentrypermitpvg").click(function(){
 var EntryPermitDatepvg = $("#entrypermitDatepvg").val();
 var EntryPermitValuepvg = $("#entrypermitValuepvg").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "entrypermitIdpvg": 41,"EntryPermitDatepvg": EntryPermitDatepvg,"EntryPermitValuepvg": EntryPermitValuepvg},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(41);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
 //Rental
$("#btnrental").click(function(){
 var rentalDate = $("#rentalDate").val();
 var rentalValue = $("#rentalValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "rentalId": 36,"rentalDate": rentalDate,"rentalValue": rentalValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(36);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
 //Concession
$("#ConcessionInt").click(function(){
 var ConcessionDate = $("#ConcessionDate").val();
 var ConcessionValue = $("#ConcessionValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "concessionId": 35,"ConcessionDate": ConcessionDate,"ConcessionValue": ConcessionValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(35);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
 //Concession
$("#btnembarkLevy").click(function(){
 var embarkLevyDate = $("#embarkLevyDate").val();
 var embarkLevyValue = $("#embarkLevyValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "embarkId": 34,"embarkLevyDate": embarkLevyDate,"embarkLevyValue": embarkLevyValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(34);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
  
//Revenue per passenger
$("#btnrevperpax").click(function(){
 var revperpaxDate = $("#revperpaxDate").val();
 var revperpaxvalue = $("#revperpaxvalue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "revperpaxId": 7,"revperpaxDate": revperpaxDate,"revperpaxvalue": revperpaxvalue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(7);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
//Operating expenses per passenger
$("#btnopperpax").click(function(){
 var opperpaxDate = $("#opperpaxDate").val();
 var opperpaxValue = $("#opperpaxValue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "opperpaxId": 8,"opperpaxDate": opperpaxDate,"opperpaxValue": opperpaxValue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(7);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
//Return on assets (ROA)
$("#btnaoa").click(function(){
 var roaDate = $("#roaDate").val();
 var roavalue = $("#roavalue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "roaId": 9,"roaDate": roaDate,"roavalue": roavalue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(9);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
//Net cash flow 
$("#btndcr").click(function(){
 var dcrDate = $("#dcrDate").val();
 var dcrvalue = $("#dcrvalue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "dcrId":10,"dcrDate": dcrDate,"dcrvalue": dcrvalue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(10);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
    
//Net cash flow 
$("#btnncf").click(function(){
 var ncfDate = $("#ncfDate").val();
 var ncfvalue = $("#ncfvalue").val();   
          $.ajax({
          type: "POST",
          url: 'Controller/financialController.php',           
          dataType: "json",
          data: { "ncfId":11,"ncfDate": ncfDate,"ncfvalue": ncfvalue},
          success: function(data) 
            { 
                
                if(data >0)
                {
                    alert("Data added successfully!");
                    getFinancialData(11);
                }
	

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 


            } 


          }); 
    }); 
   
//Top 5 airline
$("#btnairlineValue").click(function(){
 var TopAirlineDate = $("#TopAirlineDate").val();
 var airlineValue = $("#airlineValue").val();   
 var airlineValue2 = $("#airlineValue2").val();  
 var airlineValue3 = $("#airlineValue3").val();  
 var airlineValue4 = $("#airlineValue4").val();  
 var airlineValue5 = $("#airlineValue5").val();  
    
    
  $.ajax({
  type: "POST",
  url: 'Controller/financialController.php',           
  dataType: "json",
  data: { "categoryId":49,"TopAirlineDate":TopAirlineDate,"airlineValue": airlineValue,"airlineValue2": airlineValue2,"airlineValue3":airlineValue3,"airlineValue4":airlineValue4,"airlineValue5":airlineValue5},
  success: function(data) 
    { 

        if(data >0)
        {
            $("#TopAirlineDate").val("");
            $("#airlineValue").val("");  
            $("#airlineValue2").val("");
            $("#airlineValue3").val("");
            $("#airlineValue4").val(""); 
            $("#airlineValue5").val("");
            alert("Data added successfully!");
            getFinancialData(11);
        }


    },
    error: function(XMLHttpRequest, textStatus, errorThrown) { 


    } 


  }); 
}); 

    //Top 5 Revenue Categories
$("#btTopRevCat").click(function(){
 var TopRevCatDate = $("#TopRevCatDate").val();
 var TopRevCatValue = $("#TopRevCatValue").val();   
 var TopRevCatValue2 = $("#TopRevCatValue2").val();  
 var TopRevCatValue3 = $("#TopRevCatValue3").val();  
 var TopRevCatValue4 = $("#TopRevCatValue4").val();  
 var TopRevCatValue5 = $("#TopRevCatValue5").val();  
    
    
  $.ajax({
  type: "POST",
  url: 'Controller/financialController.php',           
  dataType: "json",
  data: { "categoryId":50,"TopRevCatDate":TopRevCatDate,"TopRevCatValue": TopRevCatValue,"TopRevCatValue2": TopRevCatValue2,"TopRevCatValue3":TopRevCatValue3,"TopRevCatValue4":TopRevCatValue4,"TopRevCatValue5":TopRevCatValue5},
  success: function(data) 
    { 

        if(data >0)
        {
            $("#TopRevCatDate").val("");
            $("#TopRevCatValue").val("");  
            $("#TopRevCatValue2").val("");
            $("#airlineValue3").val("");
            $("#airlineValue4").val(""); 
            $("#airlineValue5").val("");
            alert("Data added successfully!");
            getFinancialData(11);
        }


    },
    error: function(XMLHttpRequest, textStatus, errorThrown) { 


    } 


  }); 
}); 
    
    //Top 5 TopCustomer
$("#btnTopCustomer").click(function(){
 var TopCustomerDate = $("#TopCustomerDate").val();
 var TopCustomerValue = $("#TopCustomerValue").val();   
 var TopCustomerValue2 = $("#TopCustomerValue2").val();  
 var TopCustomerValue3 = $("#TopCustomerValue3").val();  
 var TopCustomerValue4 = $("#TopCustomerValue4").val();  
 var TopCustomerValue5 = $("#TopCustomerValue5").val();  
    
    
  $.ajax({
  type: "POST",
  url: 'Controller/financialController.php',           
  dataType: "json",
  data: { "categoryId":51,"TopCustomerDate":TopCustomerDate,"TopCustomerValue": TopCustomerValue,"TopCustomerValue2": TopCustomerValue2,"TopCustomerValue3":TopCustomerValue3,"TopCustomerValue4":TopCustomerValue4,"TopCustomerValue5":TopCustomerValue5},
  success: function(data) 
    { 

        if(data >0)
        {
            $("#TopCustomerDate").val("");
            $("#TopCustomerValue").val("");  
            $("#TopCustomerValue2").val("");
            $("#TopCustomerValue3").val("");
            $("#TopCustomerValue4").val(""); 
            $("#TopCustomerValue5").val("");
            alert("Data added successfully!");
            getFinancialData(11);
        }


    },
    error: function(XMLHttpRequest, textStatus, errorThrown) { 


    } 


  }); 
}); 
    
//Top 5 Concessionnaires
$("#btnTopCons").click(function(){
 var TopConsDate = $("#TopConsDate").val();
 var TopConsValue = $("#TopConsValue").val();   
 var TopCons2 = $("#TopCons2").val();  
 var TopConsValue3 = $("#TopConsValue3").val();  
 var TopConsValue4 = $("#TopConsValue4").val();  
 var TopConsValue5 = $("#TopConsValue5").val();  
    
    
  $.ajax({
  type: "POST",
  url: 'Controller/financialController.php',           
  dataType: "json",
  data: { "categoryId":52,"TopConsDate":TopConsDate,"TopConsValue": TopCons2,"TopCons2": TopCons2,"TopConsValue3":TopConsValue3,"TopConsValue4":TopConsValue4,"TopConsValue5":TopConsValue5},
  success: function(data) 
    { 

        if(data >0)
        {
            $("#TopConsDate").val("");
            $("#TopConsValue").val("");  
            $("#TopCons2").val("");
            $("#TopConsValue3").val("");
            $("#TopConsValue4").val(""); 
            $("#TopConsValue5").val("");
            alert("Data added successfully!");
            getFinancialData(52);
        }


    },
    error: function(XMLHttpRequest, textStatus, errorThrown) { 


    } 


  }); 
}); 
    
    //Top 5 Product Categories
$("#btnTopProdCat").click(function(){
 var TopProdCatDate = $("#TopProdCatDate").val();
 var TopProdCatValue = $("#TopProdCatValue").val();   
 var TopProdCatValue2 = $("#TopProdCatValue2").val();  
 var TopProdCatValue3 = $("#TopProdCatValue3").val();  
 var TopProdCatValue4 = $("#TopProdCatValue4").val();  
 var TopProdCatValue5 = $("#TopProdCatValue5").val();  
    
    
  $.ajax({
  type: "POST",
  url: 'Controller/financialController.php',           
  dataType: "json",
  data: { "categoryId":53,"TopProdCatDate":TopProdCatDate,"TopProdCatValue": TopProdCatValue,"TopProdCatValue2": TopProdCatValue2,"TopProdCatValue3":TopProdCatValue3,"TopProdCatValue4":TopProdCatValue4,"TopProdCatValue5":TopProdCatValue5},
  success: function(data) 
    { 

        if(data >0)
        {
            $("#TopProdCatDate").val("");
            $("#TopProdCatValue").val("");  
            $("#TopProdCatValue2").val("");
            $("#TopProdCatValue3").val("");
            $("#TopProdCatValue4").val(""); 
            $("#TopProdCatValue5").val("");
            alert("Data added successfully!");
            getFinancialData(53);
        }


    },
    error: function(XMLHttpRequest, textStatus, errorThrown) { 


    } 


  }); 
}); 
    
    //Top 5 OPEX Categories
$("#btnTopOPEX").click(function(){
 var TopOPEXDate = $("#TopOPEXDate").val(); 
 var TopOPEXValue = $("#TopOPEXValue").val();
 var TopOPEXValue2 = $("#TopOPEXValue2").val();   
 var TopOPEXValue3 = $("#TopOPEXValue3").val();  
 var TopOPEXValue4 = $("#TopOPEXValue4").val();  
 var TopOPEXValue5 = $("#TopOPEXValue5").val();  
 
    
    
  $.ajax({
  type: "POST",
  url: 'Controller/financialController.php',           
  dataType: "json",
  data: { "categoryId":54,"TopOPEXDate":TopOPEXDate,"TopOPEXValue": TopOPEXValue,"TopOPEXValue2": TopOPEXValue2,"TopOPEXValue3":TopOPEXValue3,"TopOPEXValue4":TopOPEXValue4,"TopOPEXValue5":TopOPEXValue5},
  success: function(data) 
    { 

        if(data >0)
        {
            $("#TopOPEXDate").val("");
            $("#TopOPEXValue").val("");  
            $("#TopOPEXValue2").val("");
            $("#TopOPEXValue3").val("");
            $("#TopOPEXValue4").val(""); 
            $("#TopOPEXValue5").val("");
            alert("Data added successfully!");
           // getFinancialData(54);
        }


    },
    error: function(XMLHttpRequest, textStatus, errorThrown) { 


    } 


  }); 
}); 
    
    

  
});