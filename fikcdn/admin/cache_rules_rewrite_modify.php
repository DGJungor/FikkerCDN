<?php
include_once("./head2.php");
?>
<script type="text/javascript">
function FikCdn_ModifyRewrite(Rid){
	var groupSelect=document.getElementById("groupSelect").value;
	var SourceUrl=document.getElementById("SourceUrl").value;
	var DestinationUrl=document.getElementById("DestinationUrl").value;
	var IcaseOBJ=document.getElementsByName("Icase");
		for(i=0;i<IcaseOBJ.length;i++){
			if(IcaseOBJ[i].checked==true)var Icase=IcaseOBJ[i].value;
		}
	var FlagOBJ=document.getElementsByName("Flag");
		for(i=0;i<FlagOBJ.length;i++){
			if(FlagOBJ[i].checked==true)var Flag=FlagOBJ[i].value;
		}
	var Note=document.getElementById("Note").value;
	if (groupSelect.length==0){ 
		var boxURL="msg.php?1.9&msg=目前没有服务器组，不能修改转向规则。";
		showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');	
	  	return false;
	}
	
	if (SourceUrl.length==0){ 
		var boxURL="msg.php?1.9&msg=请输入访问地址 URL。";
		showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');	
		document.getElementById("SourceUrl").focus();
	  	return false;
	}

	var postURL="./ajax_cache_rules.php?mod=rewrite&action=modify";
	var postStr="rid="+Rid+"&gid="+UrlEncode(groupSelect)+"&SourceUrl=" + UrlEncode(SourceUrl) + "&DestinationUrl=" + UrlEncode(DestinationUrl) +"&Icase="+Icase+"&Flag=" +Flag +"&Note=" + UrlEncode(Note);
	AjaxBasePost("rewrite","modify","POST",postURL,postStr);
}

function FikCdn_ModifyRewriteResult(sResponse){
	var json = eval("("+sResponse+")");
	if(json["Return"]=="True"){
		var boxURL="msg.php?7.2";
		showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');
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

function FikCdn_ToTaskList(){
	parent.parent.window.leftFrame.window.OnSelectNav("span_task_list");
	parent.window.location.href="task_list.php";
}

</script>
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="normal">
  <tr>
    <td width="130" height="25" class="objTitle" title="将此页面缓存规则添加到组内所有服务器中" >服务器组：</td>
    <td width="220">
		<select id="groupSelect" style="width:165px" title="将此页面缓存规则添加到此组内所有服务器中" disabled="disabled">
 <?php	
 	$node_id 	= isset($_GET['node_id'])?$_GET['node_id']:'';
	$rid 		= isset($_GET['rid'])?$_GET['rid']:'';	
	 
 	$db_link = FikCDNDB_Connect();
	if($db_link)
	{
		$node_id = mysql_real_escape_string($node_id); 
		$rid 	 = mysql_real_escape_string($rid);
		
		$sql = "SELECT * FROM cache_rule_rewrite WHERE id=$rid;";
		$result = mysql_query($sql,$db_link);
		if(!$result || mysql_num_rows($result)<=0)
		{
			//exit();
		}
		
		$this_node_id = mysql_result($result,0,"node_id");	 
		$group_id = mysql_result($result,0,"group_id");	 
		$NO = mysql_result($result,0,"NO");	 		
		$RewriteID = mysql_result($result,0,"RewriteID");	 		
		$SourceUrl = mysql_result($result,0,"SourceUrl");
		$DestinationUrl = mysql_result($result,0,"DestinationUrl");	 		 		
		$Icase = mysql_result($result,0,"Icase");				
		$Flag = mysql_result($result,0,"Flag");											
		$Note = mysql_result($result,0,"Note");
		
		$SourceUrl = urldecode($SourceUrl);
		$DestinationUrl = urldecode($DestinationUrl);	
		$Note = urldecode($Note);		
			
		do
		{
			$sql = "SELECT * FROM fikcdn_group;";
			$result = mysql_query($sql,$db_link);
			if(!$result)
			{
				break;
			}
			
			$row_count=mysql_num_rows($result);
			if(!$row_count)
			{
				break;
			}
			
			for($i=0;$i<$row_count;$i++)
			{
				$this_group_id  = mysql_result($result,$i,"id");	
				$group_name  	= mysql_result($result,$i,"name");				
				
				if($group_id == $this_group_id)
				{
					echo '<option value="'.$this_group_id.'" selected="selected">'.$group_name."</option>";
				}else{
					echo '<option value="'.$this_group_id.'">'.$group_name."</option>";
				}
			}
		}while(0);
		
		mysql_close($db_link);
	}			
 ?>
				</select> 
	</td>
  </tr>  
    
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td width="120" height="25" class="objTitle" title="输入用户的访问地址URL，匹配成功后，用户的“访问地址URL” 将变成“转向地址URL”，并向源站提交“转向地址URL”而不是用户的“访问地址URL”，正则表达式匹配规则，类似 rewrite 规则，详情参看【相关使用帮助】。" >访问地址URL：</td>
    <td width="330"><input name="SourceUrl" type="text" class="inputText" id="SourceUrl" style="width:330px;height:16px" onfocus="check_onfocus1();" value="<?php echo $SourceUrl;?>" title="输入用户的访问地址URL，匹配成功后，用户的“访问地址URL” 将变成“转向地址URL”，并向源站提交“转向地址URL”而不是用户的“访问地址URL”，正则表达式匹配规则，类似 rewrite 规则，详情参看【相关使用帮助】。"  /></td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="将匹配成功的“访问地址URL”转换成“转向地址URL”，并且向源站提交最终的“转向地址URL”。" >转向地址URL：</td>
    <td><input name="DestinationUrl" type="text" class="inputText" id="DestinationUrl" style="width:330px;height:16px" onfocus="check_onfocus2();" value="<?php echo $DestinationUrl;?>" title="将匹配成功的“访问地址URL”转换成“转向地址URL”，并且向源站提交最终的“转向地址URL”。" /></td>
  </tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="匹配“访问地址URL”时是否忽略大小写" >忽略大小写：</td>
    <td height="25"><input name="Icase" type="radio" class="radio" id="Icase" value="1" <?php if($Icase==1) echo 'checked="checked"'; ?> />
      忽略　
      <input name="Icase" type="radio" class="radio" id="Icase" value="0" <?php if($Icase==0) echo 'checked="checked"'; ?>/>
      不忽略 </td>
  </tr>
    
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  <tr>
    <td height="25" class="objTitle" title="指定“访问地址URL”匹配成功后的转向逻辑，详情参看【相关使用帮助】。" >转向逻辑：</td>
    <td height="25"><input name="Flag" type="radio" class="radio" id="Flag" value="0" <?php if($Flag==0) echo 'checked="checked"'; ?> />
      Last　
      <input name="Flag" type="radio" class="radio" id="Flag" value="1" <?php if($Flag==1) echo 'checked="checked"'; ?> />
      Return
      <input name="Flag" type="radio" class="radio" id="Flag" value="2" <?php if($Flag==2) echo 'checked="checked"'; ?>/>
      Round
      <input name="Flag" type="radio" class="radio" id="Flag" value="3" <?php if($Flag==3) echo 'checked="checked"'; ?>/>
      Continue</td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
  
  <tr>
    <td height="25" class="objTitle" title="" >备注：</td>
    <td>
		<textarea name="Note" class="inputText" id="Note" style="width:330px;height:66px;font-size:14px;border:1px solid #94C7E7;overflow:auto;"><?php echo $Note;?></textarea>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">
	    <center><input name="btnAddRewrite"  type="submit" style="width:105px;height:28px" id="btnAddRewrite" value="修改转向规则" style="cursor:pointer;" onclick="FikCdn_ModifyRewrite(<?php echo $rid;?>);" /></center></td>
  </tr>
</table>

<?php

include_once("./tail.php");
?>
