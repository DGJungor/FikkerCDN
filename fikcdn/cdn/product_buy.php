<?php
include_once("head2.php");
?>
<script language="javascript" src="../js/calendar.js"></script>

<script type="text/javascript">
var ___nProductId;
function FikCdn_BuyProductBox(product_id){
	___nProductId =	product_id;
	var txtMonth  = document.getElementById("txtMonth").value;
	var txtBackup = document.getElementById("txtBackup").value;
	if (txtMonth.length==0 || !checkIsNumber(txtMonth) ){
		var boxURL="msg.php?1.9&msg=请输入正确的月份数。";
		showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');			
		document.getElementById("txtMonth").focus();
	  	return false;
	}
	
	var boxURL="msg.php?1.3";
	showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');
}

function FikCdn_BuyProduct(){
	var txtMonth  = document.getElementById("txtMonth").value;
	var txtBackup = document.getElementById("txtBackup").value;
	if (txtMonth.length==0 || !checkIsNumber(txtMonth) ){
		document.getElementById("txtMonth").focus();
	  	return false;
	}
		
	var postURL="./ajax_buy.php?mod=order&action=add";
	var postStr="product_id="+UrlEncode(___nProductId) + "&month=" + UrlEncode(txtMonth)+"&note="+UrlEncode(txtBackup);
					 
	AjaxClientBasePost("order","add","POST",postURL,postStr);	
}

function FikCdn_ClientAddOrderResult(sResponse)
{
	var json = eval("("+sResponse+")");
	if(json["Return"]=="True"){
		var nOrderID = json["order_id"];
		//parent.closeMSGBOX();
		
		var boxURL="product_pay.php?order_id="+nOrderID;
		parent.showMSGBOX('',500,410,BT,BL,120,boxURL,'订单支付:');
		//window.location.href = "./product_pay.php?order_id="+nOrderID; 
	}else{
		var nErrorNo = json["ErrorNo"];
		var strErr = json["ErrorMsg"];	
	
		if(nErrorNo==30000){
			parent.location.href = "./login.php"; 
		}else{
			var boxURL="msg.php?1.9&msg="+strErr;
			showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');
		}
	}		
}

function checkIsNumber(str)
{
     var re = /^[1-9]+[0-9]*]*$/;   //判断字符串是否为数字 /^[0-9]+.?[0-9]*$/     //判断正整数 /^[1-9]+[0-9]*]*$/    
     if (!re.test(str))
    {
        return false;
     }
	 return true;
}

</script>


<?php
	$product_id = isset($_GET['id'])?$_GET['id']:'';
 	$client_username 	=$_SESSION['fikcdn_client_username'];
	
 	$db_link = FikCDNDB_Connect();
	if($db_link)
	{
		$product_id = mysql_real_escape_string($product_id); 
		$client_username = mysql_real_escape_string($client_username); 
			
		$sql = "SELECT * FROM fikcdn_product WHERE id='$product_id' ;"; 
		$result = mysql_query($sql,$db_link);
		if(!$result || mysql_num_rows($result)<=0)
		{
			exit();
		}
	
		$product_name = mysql_result($result,0,"name");
		$price 		= mysql_result($result,0,"price");
		$data_flow	= mysql_result($result,0,"data_flow");	
		$domain_num = mysql_result($result,0,"domain_num");
		$is_online 	= mysql_result($result,0,"is_online");
		$begin_time = mysql_result($result,0,"begin_time");
		$note	 	= mysql_result($result,0,"note");
		
		$sql = "SELECT * FROM fikcdn_client WHERE username='$client_username' ;"; 
		$result = mysql_query($sql,$db_link);
		if(!$result || mysql_num_rows($result)<=0)
		{
			exit();
		}
		$client_money = mysql_result($result,0,"money");
		$enable_login = mysql_result($result,0,"enable_login");
	}			
 ?>    

 <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="normal">
   <tr>
    <td width="130" height="25" class="objTitle" title="" >套餐名称：</td>
    <td width="220">
		<label><?php echo $product_name; ?></label>
	</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  
   <tr>
    <td height="25" class="objTitle" title="" >允许加速的域名个数：</td>
    <td width="220">
		<label><?php echo $domain_num; ?></label>
	</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
    
   <tr>
    <td height="25" class="objTitle" title="" >月度总流量数：</td>
    <td width="220">
		<label><?php echo PubFunc_MBToString($data_flow); ?></label>
	</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  	
   <tr>
    <td height="25" class="objTitle" title="" >套餐价格：</td>
    <td width="220">
		<label><?php echo $price; ?> 元/月</label>
	</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  	
   <tr>
    <td height="25" class="objTitle" title="" >套餐说明：</td>
    <td width="220">
		<label><?php echo $note; ?></label>
	</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  	
   <tr>
    <td height="25" class="objTitle" title="" >我的账户余额：</td>
    <td width="220">
		<label><?php echo $client_money; ?> 元</label>
	</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  	
   <tr>
    <td height="25" class="objTitle" title="" >购买月份数：</td>
    <td width="220">
		<input id="txtMonth" type="text" size="5" maxlength="5" value='<?php echo '6'; ?>' />个月
	</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  	
   <tr>
    <td height="25" class="objTitle" title="" >备注：</td>
    <td width="220">
		<textarea id="txtBackup" name="txtBackup" maxlength="128" style="width:320px;height:46px;font-size:14px;border:1px solid #94C7E7;overflow:auto;" ></textarea>
	</td>
  </tr>  	
  <tr>
    <td height="15" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">
	    <center><input name="btnBuyProduct"  type="submit" style="width:95px;height:28px" id="btnBuyProduct" value="购买" style="cursor:pointer;" onClick="FikCdn_BuyProductBox(<?php echo $product_id; ?>);" /></center></td>
  </tr>
  	
 </table>

<?php

include_once("./tail.php");
?>
