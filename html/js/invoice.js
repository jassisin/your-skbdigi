 function BtnAdd()
{
    
    var v = $("#TRow").clone().appendTo("#TBody") ;
    $(v).find("input").val('');
    $(v).removeClass("d-none");
    $(v).find("th").first().html($('#TBody tr').length);
}
function GetPrint()
{
    /*For Print*/
    window.print();
}



function BtnDel(v)
{
    /*Delete Button*/
       $(v).parent().parent().remove(); 
       GetTotal();

        $("#TBody").find("tr").each(
        function(index)
        {
           $(this).find("th").first().html(index);
        }

       );
}

function Disval(v){
    var index = $(v).parent().parent().index();
    var vendor = document.getElementById("vendor").value;
    var itemname = document.getElementsByClassName("itemname")[index].value;
    
    $.ajax({
        url:"fetchdata.php",
        method: "POST",
        data : {
           x : vendor,
           y : itemname,
            
        },
        dataType: "JSON",
        success: function(data){
           
            document.getElementsByClassName("landing")[index].value = data.landing;
            document.getElementsByClassName("gst")[index].value = data.gst;
            document.getElementsByClassName("hsncode")[index].value = data.hsncode;

        },
        // error: function (jqXHR, textStatus, errorThrown) {
        //     alert("Error: " + textStatus + " - " + errorThrown);
        //     // Handle the error here
        // }
    });
}

function Gst(v){
    var index = $(v).parent().parent().index();
    var vendor = document.getElementById("vendor").value;
    
    
    $.ajax({
        url:"fetchgst.php",
        method: "POST",
        data : {
           ven : vendor,
           
            
        },
        dataType: "JSON",
        success: function(data){
           
            document.getElementsByClassName("gstno")[index].value = data.gstno;

        },
        // error: function (jqXHR, textStatus, errorThrown) {
        //     alert("Error: " + textStatus + " - " + errorThrown);
        //     // Handle the error here
        // }
    });
}


function Calc(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var qty = document.getElementsByClassName("qty")[index].value;
    var costwithgst = document.getElementsByClassName("withgstprice")[index].value;

    var amt = qty * costwithgst;
    document.getElementsByClassName("amt")[index].value = amt;

    GetTotal();
}

function Withgst(v){
    var index = $(v).parent().parent().index();
    var landing = document.getElementsByClassName("landing")[index].value;
    var gst = document.getElementsByClassName("gst")[index].value;
    var gstorg=gst / 100;
    var costgst = +(landing * gstorg)+ +landing;
    document.getElementsByClassName("withgstprice")[index].value = costgst;
}


function GetTotal()
{
    /*Footer Calculation*/   

    var sum=0;
    var amts =  document.getElementsByClassName("amt");

    for (let index = 0; index < amts.length; index++)
    {
        var amt = amts[index].value;
        sum = +(sum) +  +(amt) ; 
    }

    document.getElementById("FTotal").value = sum;

    // var gst =  document.getElementById("FGST").value;
    // if(sum==gst){
    //     var net = +(sum) + +(gst);
    // }
    // else{
    //     var net =0;
    // }
    // document.getElementById("FNet").value = net;

}


function Master(v)
{

    var index = $(v).parent().parent().index();
 
    var barcode = document.getElementsByClassName("barcode")[index].value;
    
    $.ajax({
        url:"master.php",
        method: "POST",
        data : {
           x : barcode,
          
            
        },
        dataType: "JSON",
        success: function(data){
           
            document.getElementsByClassName("pr_name")[index].value = data.prname;
            document.getElementsByClassName("hsncode")[index].value = data.hsncode;
            document.getElementsByClassName("landing")[index].value = data.landing;
            document.getElementsByClassName("withoutgst")[index].value = data.withoutgst;
            document.getElementsByClassName("dp")[index].value = data.dp;
            document.getElementsByClassName("mrp")[index].value = data.mrp;

            document.getElementsByClassName("gst")[index].value = data.gst;
            document.getElementsByClassName("tid")[index].value = data.tid;
            document.getElementsByClassName("rcp")[index].value = data.rcp;

        },
        // error: function (jqXHR, textStatus, errorThrown) {
        //     alert("Error: " + textStatus + " - " + errorThrown);
        //     // Handle the error here
        // }
    });
}

function Customer(v) {
    // Get values directly from the modal inputs
    var fullname = document.querySelector(".fullname").value;
    var mobno = document.querySelector(".mobno").value;
    var pin = document.querySelector(".pin").value;
    var address = document.querySelector(".address").value;
    var email = document.querySelector(".email").value;

    $.ajax({
        url: "customer.php",
        method: "POST",
        data: {
            x: fullname,
            y: mobno,
            z: pin,
            a: address,
            e: email,
        },
        dataType: "JSON",
        success: function(response) {
            if(response.status === "success") {
                // Close the modal
                $('#basicModal').modal('hide');

                // Redirect to another PHP file after a short delay
                setTimeout(function() {
                    window.location.href = "invoice.php"; // Replace with your desired PHP file
                }, 500); // Adjust the delay as needed
            } else {
                alert("Error: " + response.message);
            }
        },
        // error: function(jqXHR, textStatus, errorThrown) {
        //     alert("Error: " + textStatus + " - " + errorThrown);
        // }
    });
}

function Vendor(v)
{

   
    var fullname = document.getElementsByClassName("fullname").value;
    var mobno = document.getElementsByClassName("mobno").value;
    var category = document.getElementsByClassName("category").value;
    var address = document.getElementsByClassName("address").value;
    var gst = document.getElementsByClassName("gst").value;

    
    $.ajax({
        url:"vendoradd.php",
        method: "POST",
        data : {
           x : fullname,
           y : mobno,
           z : category,
           a : address,
           e : gst,
          
            
        },
        dataType: "JSON",
        success: function(){
            location.reload();
        },
        // error: function (jqXHR, textStatus, errorThrown) {
        //     alert("Error: " + textStatus + " - " + errorThrown);
        //     // Handle the error here
        // }
    });
}
function CalcTo(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var qty = document.getElementsByClassName("qty")[index].value;
    var dp = document.getElementsByClassName("net")[index].value;

    var amt = qty * dp;
    document.getElementsByClassName("total")[index].value = amt;

    GetTotalt();
}
function CalcTwo(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var qty = document.getElementsByClassName("qty")[index].value;
    var dp = document.getElementsByClassName("dp")[index].value;

    var amt = qty * dp;
    document.getElementsByClassName("total")[index].value = amt;

    GetTotalt();
}

function MasterT(v)
{

    var index = $(v).parent().parent().index();
 
    var barcode = document.getElementsByClassName("barcode")[index].value;
        

    $.ajax({
        url:"master2.php",
        method: "POST",
        data : {
           x : barcode,
          
            
        },
        dataType: "JSON",
        success: function(data){
           
            document.getElementsByClassName("pr_name")[index].value = data.prname;
            document.getElementsByClassName("dp")[index].value = data.dp;
            document.getElementsByClassName("gst")[index].value = data.gst;
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error: " + textStatus + " - " + errorThrown);
            // Handle the error here
        }
    });
}

function MasterTQ(v) {
    var index = $(v).closest('tr').index();
    var prname = document.getElementsByClassName("pr_name")[index].value;

    console.log("Selected Product Name:", prname);
    console.log("Row Index:", index);

    $.ajax({
        url: "master3.php",
        method: "POST",
        data: { x: prname },
        dataType: "JSON",
        success: function(data) {
            console.log("AJAX Success Response:", data);
            if (data.dp !== undefined) {
                document.getElementsByClassName("dp")[index].value = data.dp;
                document.getElementsByClassName("barcode")[index].value = data.barcode;
            } else {
                alert("No data found for the selected product.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
            alert("Error: " + textStatus + " - " + errorThrown);
        }
    });
}



function CalcR(v){
    var index = $(v).parent().parent().index();
    var landing = document.getElementsByClassName("landing")[index].value;
    var dp = document.getElementsByClassName("dp")[index].value;
   var pm1 =(dp-landing)/3;
   var pm2 = +(pm1*1) + + landing;
   document.getElementsByClassName("rcp")[index].value = pm2;
}

function CalcT(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var qty = document.getElementsByClassName("qty")[index].value;
    var landing = document.getElementsByClassName("landing")[index].value;

    var amt = qty * landing;
    document.getElementsByClassName("total")[index].value = amt;

    GetTotalt();
}
function GetTotalt()
{
    /*Footer Calculation*/   

    var sum=0;
    var amts =  document.getElementsByClassName("total");

    for (let index = 0; index < amts.length; index++)
    {
        var amt = amts[index].value;
        sum = +(sum) +  +(amt) ; 
    }

    document.getElementById("FTotal").value = sum;

  

}
function Totalcal(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var qty = document.getElementsByClassName("qty")[index].value;
    var dp = document.getElementsByClassName("dp")[index].value;

    var amt = qty * dp;
    document.getElementsByClassName("itemamt")[index].value = amt;

    GetTotalcal();
}

function GetTotalcal()
{
    /*Footer Calculation*/   

    var sum=0;
    var amts =  document.getElementsByClassName("itemamt");

    for (let index = 0; index < amts.length; index++)
    {
        var amt = amts[index].value;
        sum = +(sum) +  +(amt) ; 
    }

    document.getElementById("TTotal").value = sum;

    // var gst =  document.getElementById("FGST").value;
    // if(sum==gst){
    //     var net = +(sum) + +(gst);
    // }
    // else{
    //     var net =0;
    // }
    // document.getElementById("FNet").value = net;

}
function prDel(v){

    var index = $(v).parent().parent().index();
 
    var id = document.getElementsByClassName("closes")[index].value;

    $.ajax({
        url:"prdelete.php",
        method: "POST",
        data : {
           x : id,
          
            
        },
        dataType: "JSON",
        success: function(){
            GetTotalcal();
            location.reload();
        },
       
    });
}


function Offer2(v) {
    /* Detail Calculation Each Row */
    var index = $(v).parent().parent().index();

    var dis = document.getElementsByClassName("dis")[index].value;
    var dp = document.getElementsByClassName("dp")[index].value;
    var gstPercentage = document.getElementsByClassName("gst")[index].value;

    var gst = dp * (gstPercentage / 100);

    // var disamt = 0;
    // if (dis !== 0) {
    //     var discount = dis / 100;
    //     disamt = dp * discount;
    // }

    var totamt =dp-disamt;
    document.getElementsByClassName("net")[index].value = totamt;

    GetTotalt();
}

function Offer(v) {
    /* Detail Calculation Each Row */
    var index = $(v).closest('tr').index();  // Use closest() to find the row
    
    // Parse input values with fallback to 0 if empty
    var dis = parseFloat(document.getElementsByClassName("dis")[index].value) || 0;
    var dp = parseFloat(document.getElementsByClassName("dp")[index].value) || 0;
    var gstPercentage = parseFloat(document.getElementsByClassName("gst")[index].value) || 0;

    // Calculate GST
    var gst = dp * (gstPercentage / 100);

    // Calculate total amount (dp minus discount, then add GST)
    var totamt = (dp - dis) + gst;

    // Set the net amount including GST
    document.getElementsByClassName("net")[index].value = totamt.toFixed(2);  // Round to 2 decimal places

    // Call the function to update the total
    GetTotalt();
}

function Free(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var free = document.getElementsByClassName("free")[index].value;
    // var qty = document.getElementsByClassName("qty")[index].value;

    var dp = document.getElementsByClassName("dp")[index].value;
    var tot = document.getElementsByClassName("total")[index].value;

    var total=free*dp;

    // var discount =dis/100;
    // var disamt = total*discount;
    var totamt=tot-total;
    document.getElementsByClassName("total")[index].value = totamt;

    GetTotalt();
}
function Net(v)
{
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    
    var free = document.getElementsByClassName("free")[index].value;
    // var qty = document.getElementsByClassName("qty")[index].value;

    var dp = document.getElementsByClassName("dp")[index].value;
    var tot = document.getElementsByClassName("total")[index].value;

    var total=free*dp;

    // var discount =dis/100;
    // var disamt = total*discount;
    var totamt=tot-total;
    document.getElementsByClassName("total")[index].value = totamt;

    GetTotalt();
}
