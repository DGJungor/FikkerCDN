<?php
include_once("head2.php");
?>
<script language="javascript" src="../js/calendar.js"></script>
<script type="text/javascript">
var __nBuyId;
function FikCdn_BuyModifyBox(buy_id){
	var txtDomainNum = document.getElementById("txtDomainNum").value;
	var txtDataFlow = document.getElementById("txtDataFlow").value;
	var txtPrice = document.getElementById("txtPrice").value;
	var txtBackup = document.getElementById("txtBackup").value;
	var txtEndTime = document.getElementById("txtEndTime").value;
	var txtCName     =document.getElementById("txtCName").value;
	
	__nBuyId = buy_id;
	if (!isNumberFormat(txtDomainNum) || txtDomainNum<=0 || txtDomainNum.length==0){
		var boxURL="msg.php?1.9&msg=请输入可加速域名个数。";
		showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');			
		document.getElementById("txtDomainNum").focus();
	  	return false;
	}
	
	if (!isNumberFormat(txtDataFlow) || txtDataFlow.length==0 || txtDataFlow<=0){
		var boxURL="msg.php?1.9&msg=请输入月度流量数量。";
		showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');			
		document.getElementById("txtDataFlow").focus();
	  	return false;
	}
	
	if (isNaN(txtPrice) || txtPrice<=0 || txtPrice.length==0){
		var boxURL="msg.php?1.9&msg=请输入套餐价格。";
		showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');				
		document.getElementById("txtPrice").focus();
	  	return false;
	}

	var boxURL="msg.php?4.8";
	showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');
}

function FikCdn_BuyModify(){
	var txtDomainNum = document.getElementById("txtDomainNum").value;
	var txtDataFlow = document.getElementById("txtDataFlow").value;
	var txtPrice = document.getElementById("txtPrice").value;
	var txtBackup = document.getElementById("txtBackup").value;
	var txtEndTime = document.getElementById("txtEndTime").value;
	var txtCName     =document.getElementById("txtCName").value;
	
	var AutoStopBuyObj=document.getElementsByName("AutoStopBuy");
		for(i=0;i<AutoStopBuyObj.length;i++){
			if(AutoStopBuyObj[i].checked==true)var AutoStopBuy=AutoStopBuyObj[i].value;
		}

	
	if (!isNumberFormat(txtDomainNum) || txtDomainNum<=0 || txtDomainNum.length==0){	
		document.getElementById("txtDomainNum").focus();
	  	return false;
	}
	
	if (!isNumberFormat(txtDataFlow) || txtDataFlow.length==0 || txtDataFlow<=0){	
		document.getElementById("txtDataFlow").focus();
	  	return false;
	}
	
	if (isNaN(txtPrice) || txtPrice<=0 || txtPrice.length==0){		
		document.getElementById("txtPrice").focus();
	  	return false;
	}
						
	var postURL="./ajax_buy.php?mod=buy&action=modify";
	var postStr="buy_id="+UrlEncode(__nBuyId)+"&domain_num="+UrlEncode(txtDomainNum)+"&data_flow="+UrlEncode(txtDataFlow)+"&auto_stop_buy="+AutoStopBuy+"&price="+UrlEncode(txtPrice)+"&end_time="+UrlEncode(txtEndTime)+"&note="+UrlEncode(txtBackup) + "&cname=" + UrlEncode(txtCName) ;
					
	AjaxBasePost("buy","modify","POST",postURL,postStr);
}

function FikCdn_ModifyBuyResult(sResponse)
{
	var json = eval("("+sResponse+")");
	if(json["Return"]=="True"){
		var nBuyId = json["buy_id"];
		parent.window.location.href = "./buy_list.php?buy_id="+__nBuyId; 
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

function PriceValueChange(){
	var txtPrice = document.getElementById("txtPrice").value;
	var txtMonth = document.getElementById("txtMonth").value;
	
	if (isNaN(txtPrice)||txtPrice.length==0|| txtPrice<0){
	  	document.getElementById("tipsPrice").innerHTML="请输入套餐价格";
		document.getElementById("txtPrice").focus();
	  	return false;
	}
	else{
		document.getElementById("tipsPrice").innerHTML="";
	}
	
	if (!isNumberFormat(txtMonth) ||txtMonth.length==0 || txtMonth<=0){
	  	document.getElementById("tipsMonth").innerHTML="请输入购买月份数";
		document.getElementById("txtMonth").focus();
	  	return false;
	}
	else{
		document.getElementById("tipsMonth").innerHTML="";
	}
	
	var total_money = parseInt(txtPrice)*(parseInt(txtMonth));
	document.getElementById("tipsTotalMoney").innerHTML=total_money;
}

</script>
<?php
	$buy_id = isset($_GET['buy_id'])?$_GET['buy_id']:'';
 	$admin_username 	=$_SESSION['fikcdn_admin_username'];
	
 	$db_link = FikCDNDB_Connect();
	if($db_link)
	{
		$buy_id = mysql_real_escape_string($buy_id);
		$admin_username = mysql_real_escape_string($admin_username);
		
		$sql = "SELECT * FROM fikcdn_buy WHERE id='$buy_id' ;"; 
		$result = mysql_query($sql,$db_link);
		if(!$result || mysql_num_rows($result)<=0)
		{
			mysql_close($db_link);
			exit();
		}
	
		$username = mysql_result($result,0,"username");
		$product_id = mysql_result($result,0,"product_id");
		$begin_time = mysql_result($result,0,"begin_time");
		$end_time = mysql_result($result,0,"end_time");
		$status = mysql_result($result,0,"status");
		$auto_renew = mysql_result($result,0,"auto_renew");
		$price 		= mysql_result($result,0,"price");
		$has_data_flow 	= mysql_result($result,0,"has_data_flow");
		$data_flow	= mysql_result($result,0,"data_flow");	
		$domain_num = mysql_result($result,0,"domain_num");
		$note	 	= mysql_result($result,0,"note");
		$auto_stop 	= mysql_result($result,0,"auto_stop");
		$dns_cname 		= mysql_result($result,$i,"dns_cname");	
		
		$total_money = $price*$month;
		
		$sql = "SELECT * FROM fikcdn_product WHERE id='$product_id' ;"; 
		$result = mysql_query($sql,$db_link);
		if(!$result || mysql_num_rows($result)<=0)
		{
			mysql_close($db_link);		
			exit();
		}
		
		$product_name = mysql_result($result,0,"name");
		$product_note= mysql_result($result,0,"note");	
		
		$product_name = $product_name.' ('.$username.')';
		
		$sql = "SELECT * FROM fikcdn_client WHERE username='$username' ;"; 
		$result = mysql_query($sql,$db_link);
		if(!$result || mysql_num_rows($result)<=0)
		{
			mysql_close($db_link);		
			exit();
		}
		$client_money = mysql_result($result,0,"money");
		$enable_login = mysql_result($result,0,"enable_login");
		
		if($type==$PubDefine_BuyTypeRenew)
		{
			$sql = "SELECT * FROM fikcdn_buy WHERE id='$buy_id' ;"; 
			$result = mysql_query($sql,$db_link);
			if(!$result || mysql_num_rows($result)<=0)
			{
				mysql_close($db_link);		
				exit();
			}
			$buy_begin_time = mysql_result($result,0,"begin_time");
			$buy_end_time = mysql_result($result,0,"end_time");
		}	
		
		mysql_close($db_link);						
	}			
 ?>    
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="normal">
  <tr>
  <input id="txtId" name="txtId" type="hidden"  value="<?php echo $uid; ?>" /> 
    <td width="120" height="25" class="objTitle" title="" >套餐名称：</td>
    <td width="220">
		<label><?php echo $product_name; ?></label>
	</td>
  </tr>
 
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >可加速的域名个数：</td>
    <td>
		<input id="txtDomainNum" type="text" size="16" maxlength="10" value="<?php echo $domain_num; ?>" />
	</td>
  </tr>
  
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >月度总流量(MB)：</td>
    <td>
		<input id="txtDataFlow" type="text" size="16" maxlength="16" value="<?php echo $data_flow; ?>" />
	</td>
  </tr>
    
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >套餐价格：</td>
    <td>
		<input id="txtPrice" type="text" size="16" maxlength="10" value="<?php echo $price; ?>" />
	</td>
  </tr>
  	
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >CNAME：</td>
    <td>
		<input id="txtCName" type="text" size="36" maxlength="64" value="<?php echo $dns_cname; ?>" />
	</td>
  </tr>
  	
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >套餐说明：</td>
    <td>
		<label><?php echo $product_note; ?></label>
	</td>
  </tr>
  
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >客户账户余额：</td>
    <td>
		<label><?php echo $client_money; ?></label>
	</td>
  </tr>
     
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >开始日期：</td>
    <td>	  
		<span id="tipsTotalMoney"><?php echo date("Y-m-d H:i:s",$begin_time); ?></span>
	</td>
  </tr>
  	 
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >到期日期：</td>
    <td>	  
		<input id="txtEndTime" type="text" size="16" maxlength="21" value="<?php echo date("Y-m-d H:i:s",$end_time);  ?>" />
	</td>
  </tr>
  
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="当购买的套餐到期或者月度流量用完，系统自动停止套餐内的域名加速" >是否自动停止加速：</td>
    <td>
	  <input name="AutoStopBuy" type="radio" class="radio" id="AutoStopBuy" value="0" <?php if($auto_stop==0) echo 'checked="checked"'; ?>title="当购买的套餐到期或者月流量用完，系统自动停止套餐内的域名加速"/>不停止&nbsp;&nbsp;
	  <input name="AutoStopBuy" type="radio" class="radio" id="AutoStopBuy" value="1" <?php if($auto_stop==1) echo 'checked="checked"'; ?> title="当购买的套餐到期或者月流量用完，系统自动停止套餐内的域名加速"/>停止
	</td>
  </tr>
    
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="" >备注：</td>
    <td>
		<textarea id="txtBackup" name="txtBackup" maxlength="128" style="width:320px;height:46px;font-size:14px;border:1px solid #94C7E7;overflow:auto;" ><?php echo $note; ?></textarea>
	</td>
  </tr>
  <tr>
    <td height="15" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">
	    <center><input name="btnModifyBuy"  type="submit" style="width:95px;height:28px" id="btnModifyBuy" value="保存" style="cursor:pointer;" onClick="FikCdn_BuyModifyBox(<?php echo $buy_id; ?>);" /></center></td>
  </tr>
</table>

<script type="text/javascript">
//firefox下检测状态改变只能用oninput,且需要用addEventListener来注册事件。
if(/msie/i.test(navigator.userAgent))    //ie浏览器 
{
	document.getElementById('txtPrice').onpropertychange=PriceValueChange;
	document.getElementById('txtMonth').onpropertychange=PriceValueChange;
} 
else 
{
	//非ie浏览器，比如Firefox 
	document.getElementById('txtPrice').addEventListener("input",PriceValueChange,false);
	document.getElementById('txtMonth').addEventListener("input",PriceValueChange,false); 
} 
</script>

<?php

include_once("./tail.php");
?>
