<div id="postage__recipient" style="float: left; width: 260px; height: 260px;">
    <h3 style="margin-bottom: 5px;">Recipient</h3>
    <label style="width: 75px;">Full Name:</label><select id="postage_to__assoc_editor_id"></select><br>
    <input type="hidden" value="postage_to__FullName"></input>
    <label style="width: 75px;">Institution:</label><input id="postage_to__Company" value="Iowa State University"  style="width: 160px;"></input><br>
    <label style="width: 75px;">Department:</label><input id="postage_to__Department" value="Department of Statistics" style="width: 160px;"></input><br>
    <label style="width: 75px;">Address 1:</label><input id="postage_to__Address1" value="1718 Wilson Ave" style="width: 160px;"></input><br>
    <label style="width: 75px;">Address 2:</label><input id="postage_to__Address2" value="" style="width: 160px;"></input><br>
    <label style="width: 75px;">City:</label><input id="postage_to__City" value="Ames" style="width: 160px;"></input><br>
    <label style="width: 75px;">State:</label><input id="postage_to__State" value="IA" style="width: 160px;"></input><br>
    <label style="width: 75px;">ZIP Code:</label><input id="postage_to__ZIPCode" value="50010" style="width: 160px;"></input><br>
    <label style="width: 75px;">Country:</label><input id="postage_to__Country" value=""  style="width: 160px;"></input><br>
    <a onClick="do_cleanseAddress()" href="Javascript:;">Cleanse Address</a>
    <br>
    <div id="to_address_match" style="display: none;"><img src="resources/images/icons/accept.png">Address Matched!</div>
    <div id="to_csz_match" style="display: none;"><img src="resources/images/icons/accept.png">City/State/Zip OK!</div>

</div>
<div id="postage__package_info" style="float: left; width: 225px; display: none;">
    <h3 style="margin-bottom: 5px;">Details</h3>
    <label style="width: 95px">Weight (OZ)</label><input id="postage_details__WeightOz" value="9.3" style="width: 40px"></input><br>
    <label style="width: 95px">Package Type</label><select id="postage_details__PackageType">
                                 <option value="Package" selected>Package
                                 <option value="Thick Envelope">Thick Envelope
                               </select><br>
    <label style="width: 95px">Ship Date</label><input id="postage_details__ShipDate" value="2010-11-01" style="width: 100px"></input><br><br>
    <a href="Javascript:;" onClick="getRates()">Get Rates</a>
</div>
<div id="postage__rates" style="float: left; width: 135px; display: none;">
    <h3 style="margin-bottom: 5px;">Rates</h3>
    <input style="width: 10px; margin-top: 4px; margin-right:2px;" type="radio" name="postage_rates" value=""> <b>Media Mail</b> <br>
        <span style="padding-left: 20px">$3.32</span><br>
        <span style="padding-left: 20px">4-5 Days</span><br>
    <input style="width: 10px; margin-top: 4px; margin-right:2px;" type="radio" name="postage_rates" value=""> <b>First-Class Mail</b><br>
        <span style="padding-left: 20px">$1.45</span><br>
        <span style="padding-left: 20px">2-4 Days</span><br>
    <input style="width: 10px; margin-top: 4px; margin-right:2px;" type="radio" name="postage_rates" value=""> <b>Priority Mail</b><br>
        <span style="padding-left: 20px">$2.32</span><br>
        <span style="padding-left: 20px">1-2 Days</span><br>
</div>
<h2 style="clear: left;width:100%; text-align: center;">
    <a id="purchase_postage" onclick="createIndicium()" href="Javascript:;" style="display: none;">Purchase Postage</a>
    <a id="view_postage" target="_blank" href="" style="display: none;">View Postage</a>
</h2>
<div id="status" style="border: 1px solid gray; height: 140px; margin-top: 20px; overflow-y: auto;">
<ul style="margin-left: 20px; list-style-type: square;">

</ul>
</div>


{literal}
<script type="text/javascript">
    $("#postage_to__assoc_editor_id").bind("change",function(){
        var assoc_editor_id = $(this).val();
       
        addPostageLog("Looking Up Recipient Address...");
        $.ajax({
            url: uri+"/svc/read",
            type:   'GET',
            dataType: 'json',
            data: {
                cls: 'address',
                query: [['person_id','=',assoc_editor_id]]
            },
            async: true,
            success: function(ret){
                if(ret != -1){
                    var address = {};
                    for(var i in ret[0]){
                        if($("#postage_to__"+i)){ 
                            $("#postage_to__"+i).val(ret[0][i]);
                            address[i] = ret[0][i];
                        }
                    }
                    addPostageLog("Found Recipient Address...");
                    cleanseAddress(address, "#postage__recipient");
                    
                } else {
                    showStatus("Error getting Recipient's Address");
                }
            
            }

        });


    });


    $("#getrates").bind("showrates",function(){
        $(this).show();
    });
    $("#getrates").bind("hiderates",function(){
        $(this).hide();
    });

    $("#getrates").bind("click", function(){
        showStatus("Getting Rates...",false,function(){
            var rate = {
                FromZIPCode:  $("#postage_from_zip").val(),
                ToZIPCode:    $("#postage_to_ZIPCode").val(),
                WeightOz:     $("#getrates_WeightOz").val(),
                PackageType:  $("#getrates_PackageType").val(),
                ShipDate:     $("#getrates_ShipDate").val(),
            };

            getRates(rate, "#rates");
        });
    });
    

    $("#postage_details__ShipDate").datepicker({
        dateFormat: 'yy-mm-dd'   
    });

    loadRecipientSelect();


</script>
{/literal}
