<?php
$cgiDir = $nof_rootDir ."/". $nof_scriptDir . "/";
$componentid = $nof_componentId;
$xml_file  = $nof_scriptInterfaceFile;

// predefine variable values
$myVars['tstact']      = '';
$myVars['tstpassword'] = '';
$myVars['offset']      = '';
$myVars['id']          = '';


$lines_array = array();
$numberoflines = 0;

  // working variables initialisation
function t_adm_InitParameterVariables($array, &$target) {
    if (!is_array($array)) {
        return FALSE;
    }
    $is_magic_quotes = get_magic_quotes_gpc();
    foreach ($array AS $key => $value) {
        if (is_array($value)) {
            unset($target[$key]);
            t_adm_InitParameterVariables($value, $target[$key]);
        } else if ($is_magic_quotes) {
            $target[$key] = stripslashes($value);
        } else {
            $target[$key] = $value;
        }
    }
    return TRUE;
}

t_adm_InitParameterVariables(GetGVariable(''), $myVars);
t_adm_InitParameterVariables(GetPostVariable(''), $myVars);

if($myVars['tstact']=="logout"){
    SetSessionVariable('tstpassword', '');
}
if($myVars['tstact']=="adminlogin"){
    SetSessionVariable('tstpassword', $myVars['tstpassword']);
}

$sessionPassword = "";

$a= GetSessionVariable('tstpassword');
if ( $a!='' ) {
    $sessionPassword = GetSessionVariable('tstpassword');
}


 if (NOF_fileExists($cgiDir . "ts_lib.php")) {
        include_once($cgiDir."ts_lib.php");
    } else {
        exit();
    }

$conf = ts_parseXmlFile($cgiDir.$xml_file);
    
// read style from xml
$CSSstyle = $conf['add.' . $componentid . '.style'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'style');
if ($CSSstyle=="") $CSSstyle = "tsStyle";

if ( $sessionPassword != $passw ){
    ?>

    <div class="nof_<?php echo $CSSstyle?>_content">
    <form action="<?php echo GetServerVariable('PHP_SELF')?>" method="post" name="ts_adminform" target="_self">
      <input type="hidden" name="tstact" value="adminlogin">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="nof_<?php echo $CSSstyle?>_contentCell"><input name="tstpassword" type="password" class="nof_<?php echo $CSSstyle?>_input_text"></td>
                    <td class="nof_<?php echo $CSSstyle?>_contentCell"><input class="nof_<?php echo $CSSstyle?>_input_submit" value="<?php echo $GLOBALS['nof_resources']->get('ts.Admin.Text.Login')?>" type="submit"></td>
                </tr>
            <?php if($myVars['tstpassword'] != ""){?>
                <tr>
                    <td colspan="2" class="nof_<?php echo $CSSstyle?>_contentCell">
                        <ul class="nof_<?php echo $CSSstyle?>_errorText">
                            <li><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_errorText"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Text.WrongPassword')?></span></span></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
            </table>
    </form>
    </div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $gbCSSstyle?>_footer">
    <tr>
      <td>&nbsp;</td>
    </tr>
    </table>
<?php
}else{

    if($myVars['offset'] ==""){
            $offset=1;
      }else{
            $offset=$myVars['offset'];
    }

    if (NOF_fileExists($cgiDir . "ts_lib.php")) {
        include_once($cgiDir."ts_lib.php");
    } else {
        exit();
    }

    $dbPath = $conf['admin.' . $componentid . '.dbPath'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'dbPath');
    $email = $conf['admin.' . $componentid . '.email'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'email');
    $picture = $conf['admin.' . $componentid . '.picture'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'picture');

    if (!file_exists(dirname($cgiDir.$dbPath))) {
        NOF_throwError(540,array("{1}"=>NOF_mapPath(dirname($cgiDir.$dbPath)),"{2}"=>getcwd()));
    }

//    if (!is_writable(dirname($cgiDir.$dbPath))) {
//        NOF_throwError(541,array("{1}"=>NOF_mapPath(dirname($cgiDir.$dbPath)),"{2}"=>getcwd()));
//    }

    if($myVars['tstact']=="delete"){
        $lines_array=tstgetdb($cgiDir.$dbPath,0);
        $arr_delete=array_reverse($lines_array);
        if(!$FILE = @fopen($cgiDir.$dbPath, 'wb')){
                  NOF_throwError(500,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
        }else{
            $strfields=implode(";:;",$fields_array);
            if(!@fputs($FILE, $strfields)){
                        NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
            }
            foreach($arr_delete as $line){
                if($line[0]!=$myVars['id']){
                    $strline=implode(";:;",$line);
                    if(!@fputs($FILE, $strline)){
                                    NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
                    }
                }
            }
            fclose($FILE);
        }
    }

    if($myVars['tstact']=="validation"){
        $lines_array=tstgetdb($cgiDir.$dbPath,0);
        $lines_array=array_reverse($lines_array);
        foreach($fields_array as $key => $value){
            if(trim($value)=="validation"){
                $validation_pos=$key;
            }
        }
        for($i=0;$i<sizeof($lines_array);$i++){
            if($lines_array[$i][0]==$myVars['id']){
                if(trim($lines_array[$i][$validation_pos])=="true"){
                    $lines_array[$i][$validation_pos]="false"."\r\n";
                }else{
                    $lines_array[$i][$validation_pos]="true"."\r\n";
                }
            }
        }
        if(!$FILE = @fopen($cgiDir.$dbPath, 'wb')){
                  NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
        }else{
            $strfields=implode(";:;",$fields_array);
            if(!@fputs($FILE, $strfields)){
                        NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
            }
            foreach($lines_array as $line){
                $strline=implode(";:;",$line);
                if(!@fputs($FILE, $strline)){
                              NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
                }
            }
            fclose($FILE);
        }
    }


    if($myVars['tstact']=="save"){
        $lines_array=tstgetdb($cgiDir.$dbPath,0);
        $lines_array=array_reverse($lines_array);
        foreach($fields_array as $key => $value){
            if(trim($value)=="validation"){
                $validation_pos=$key;
            }
        }
        $saved=array();
        $tmp_post = GetPostVariable('');

        for($i=1;$i<=10;$i++){
                  if(isset($tmp_post['v'.$i])){
                        if(isset($tmp_post['c'.$i]) && $tmp_post['c'.$i]!=""){
                              $saved[$i][0]=$tmp_post['v'.$i];
                              $saved[$i][1]="true";
                        }else{
                              $saved[$i][0]=$tmp_post['v'.$i];
                              $saved[$i][1]="false";
                        }
                  }
        }
        for($i=0;$i<sizeof($lines_array);$i++){
            foreach($saved as $sval){
                if($sval[0]==$lines_array[$i][0]){
                    $lines_array[$i][$validation_pos]=$sval[1]."\r\n";
                }
            }
        }
        if(!$FILE = @fopen($cgiDir.$dbPath, 'wb')){
                  NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
        }else{
            $strfields=implode(";:;",$fields_array);
            if(!@fputs($FILE, $strfields)){
                        NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
            }
            foreach($lines_array as $line){
                $strline=implode(";:;",$line);
                if(!@fputs($FILE, $strline)){
                              NOF_throwError(502,array("{1}"=>NOF_mapPath($cgiDir.$dbPath),"{2}"=>dirname($cgiDir.$dbPath)));
                }
            }
            fclose($FILE);
        }
    }
    //
    $flag_emptydb=0;

    if (!file_exists($cgiDir.$dbPath)) {
        $flag_emptydb=1;
    }else{
        $lines_array=tstgetdb($cgiDir.$dbPath,0);
        $numberoflines = sizeof($lines_array);
        $picture_pos="";
        $email_pos="";

        foreach($fields_array as $key => $value){
            if(trim($value)=="validation"){
                $validation_pos=$key;
            }
            if(trim($value)=="firstname"){
                $firstname_pos=$key;
            }
            if(trim($value)=="lastname"){
                $lastname_pos=$key;
            }
            if(trim($value)=="title"){
                $title_pos=$key;
            }
            if(trim($value)=="description"){
                $description_pos=$key;
            }
            if(trim($value)=="date"){
                $date_pos=$key;
            }
            if(trim($value)=="email"){
                $email_pos=$key;
            }
            if(trim($value)=="picture"){
                $picture_pos=$key;
            }
            if(trim($value)=="emailonpage"){
                $emailonpage_pos=$key;
            }
        }
    }

    if($myVars['tstact']=="view"){
        $viewline=array();
        foreach($lines_array as $line){
            if($line[0]==$myVars['id']){
                $viewline=$line;
                break;
            }
        }
        ?>

				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $CSSstyle?>_header">
					<tr>
						<td>&nbsp;<!--<span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_heading">View Testimonial</span></span>--></td>
					</tr>
				</table>

				<div class="nof_<?php echo $CSSstyle?>_content">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Label.FirstName")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo htmlspecialchars($viewline[$firstname_pos])?></span></td>
							</tr>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Label.LastName")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo htmlspecialchars($viewline[$lastname_pos])?></span></td>
							</tr>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Label.Date")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text">
									<?php
									if ($GLOBALS['nof_locale'] == 'de') {
											$viewline[$date_pos] = date('d.m.Y H:m:s',$viewline[$date_pos]);
									} else {
											$viewline[$date_pos] = date('m/d/Y h:m:s a',$viewline[$date_pos]);
									}
									echo $viewline[$date_pos];
									?>
								</span></td>
							</tr>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Label.Title")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo htmlspecialchars($viewline[$title_pos])?></span></td>
							</tr>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Label.Description")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text">
									<?php
									$desc_array=split("<br>",$viewline[$description_pos]);
									foreach($desc_array as $val){
										echo $val."<br>";
									}
									?>
								</span></td>
							</tr>

							<?php if($picture_pos!="" and trim($picture)=="true"){
							?>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Label.Picture")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text">
								<?php if($viewline[$picture_pos]==""){
								echo "No picture";
								}else{
								echo tstimgresize($cgiDir."images/",$viewline[$picture_pos],250,300);
								}?>
								</span></td>
							</tr>
							<?php } ?>

							<?php if($email_pos!="" and trim($email)=="true"){
							?>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Label.Email")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo $viewline[$email_pos]?><!--email--></span></td>
							</tr>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.EmailOnPage.Label")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><?php if(trim($viewline[$emailonpage_pos])=="true") echo "true"; else echo "false"; ?></span></td>
							</tr>
              <?php } ?>
							<tr>
								<td valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><label class="nof_<?php echo $CSSstyle?>_label"><?php echo $GLOBALS['nof_resources']->get("ts.Admin.Text.Status")?></label></span></td>
								<td width="100%" class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><?php if(trim($viewline[$validation_pos])=="true"){echo $GLOBALS['nof_resources']->get('ts.Admin.Text.ApprovedTestimonial')."!";}else{echo $GLOBALS['nof_resources']->get('ts.Admin.Text.NotApprovedTestimonial')."!";}?></span></td>
							</tr>

							<tr>
								<td class="nof_<?php echo $CSSstyle?>_contentCell" colspan="2" align="center">
								<span class="nof_<?php echo $CSSstyle?>_text">
									<a class="nof_<?php echo $CSSstyle?>_link" href="<?php echo encodeURI(GetServerVariable('PHP_SELF'))?>?offset=<?php echo $offset?>" target="_self"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Link.Back')?></span></a>&nbsp;-&nbsp;
									<a class="nof_<?php echo $CSSstyle?>_link" href="<?php echo encodeURI(GetServerVariable('PHP_SELF'))?>?tstact=delete&amp;id=<?php echo $myVars['id']?>&amp;offset=<?php echo $offset?>" target="_self"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Link.Delete')?></span></a>&nbsp;-&nbsp;
									<?php
									$str="";
									if(trim($viewline[$validation_pos])!="true"){
											$str=$GLOBALS['nof_resources']->get("ts.Admin.Link.Validate");
									}else{
											$str=$GLOBALS['nof_resources']->get("ts.Admin.Link.Invalidate");
									}
									?>
									<a class="nof_<?php echo $CSSstyle?>_link" href="<?php echo encodeURI(GetServerVariable('PHP_SELF'))?>?offset=<?php echo $offset?>&amp;tstact=validation&amp;id=<?php echo $myVars['id']?>" target="_self"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo $str?></span></a>
								</span></td>
							</tr>
            </table></div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $CSSstyle?>_footer">
						<tr>
							<td>&nbsp;</td>
						</tr>
					</table>
	<?php
	}else{
	?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $CSSstyle?>_header">
        <tr>
          <td><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_heading">
          	<?php echo $GLOBALS['nof_resources']->get('ts.Admin.Text.Title')?></span></span></td>
          <td width="33%" align="center"><span class="nof_<?php echo $CSSstyle?>_text"><a class="nof_<?php echo $CSSstyle?>_link"
          	href="<?php echo encodeURI(GetServerVariable('PHP_SELF'))?>?tstact=logout" target="_self" >
          	<span class="nof_<?php echo $CSSstyle?>_navigationLink"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Link.Logout')?></span></a></span></td>
          <td width="33%" align="right">
					<?php
					if($flag_emptydb==0 and $numberoflines!=0){
							tstdisplaypages($offset,$numberoflines,10,5,$CSSstyle);
					}
					?>
					</td>
        </tr>
      </table>

      <div class="nof_<?php echo $CSSstyle?>_content">
				<form action="<?php echo GetServerVariable('PHP_SELF')?>" method="post" name="ts_adminform"  target="_self">
				<input type="hidden" name="offset" value="<?php echo $offset?>">
				<input type="hidden" name="tstact" value="save">
        <table width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="nof_<?php echo $CSSstyle?>_columnHeadingRow"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_columnHeading"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Text.Name')?></span></span></td>
									<td class="nof_<?php echo $CSSstyle?>_columnHeadingRow"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_columnHeading"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Text.Date')?></span></span></td>
									<td class="nof_<?php echo $CSSstyle?>_columnHeadingRow"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_columnHeading"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Text.Title')?></span></span></td>
									<td class="nof_<?php echo $CSSstyle?>_columnHeadingRow">&nbsp;</td>
									<td class="nof_<?php echo $CSSstyle?>_columnHeadingRow"><input name="allbox" type="checkbox" id="allbox" class="nof_<?php echo $CSSstyle?>_input_checkbox" onclick="ts_allCheck();"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_columnHeading"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Text.Validity')?></span></span></td>
								</tr>

                <?php
                $start=$offset-1;
                $inc=1;
                while($start>=0 and $start<$numberoflines and $inc<=10 and $lines_array[$start]){
                ?>
								<tr>
									<td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><?php echo htmlspecialchars($lines_array[$start][$firstname_pos])?>&nbsp;<?php echo htmlspecialchars($lines_array[$start][$lastname_pos])?></span></td>
									<td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text">
										<?php
										if ($GLOBALS['nof_locale'] == 'de') {
												$lines_array[$start][$date_pos] = date('d.m.Y H:m:s',$lines_array[$start][$date_pos]);
										} else {
												$lines_array[$start][$date_pos] = date('m/d/Y h:m:s a',$lines_array[$start][$date_pos]);
										}
										echo $lines_array[$start][$date_pos];
										?>
									</span></td>
									<td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><a href="<?php echo encodeURI(GetServerVariable('PHP_SELF'))?>?tstact=view&amp;id=<?php echo $lines_array[$start][0]?>&amp;offset=<?php echo $offset?>" target="_self" class="nof_<?php echo $CSSstyle?>_link"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Link.View')?></a></span></td>
									<td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text"><a href="<?php echo encodeURI(GetServerVariable('PHP_SELF'))?>?tstact=delete&amp;id=<?php echo $lines_array[$start][0];?>&amp;offset=<?php echo $offset?>" target="_self" class="nof_<?php echo $CSSstyle?>_link"><?php echo $GLOBALS['nof_resources']->get('ts.Admin.Link.Delete')?></a></span></td>
									<td class="nof_<?php echo $CSSstyle?>_contentCell"><span class="nof_<?php echo $CSSstyle?>_text">
                    <?php
                    $str="";

                    if(trim($lines_array[$start][$validation_pos])=="true"){
                        $str="CHECKED";
                    }
                    ?>
                    <input type="checkbox" name="c<?php echo $inc?>" value="<?php echo $lines_array[$start][0]?>" <?php echo $str?> onclick="ts_updateAllBox()" class="nof_<?php echo $CSSstyle?>_input_checkbox">
                    <input type="hidden" name="v<?php echo $inc?>" value="<?php echo $lines_array[$start][0]?>"></span></td>
								</tr>

                <?php
                    $inc++;
                    $start++;
                }
                ?>

                <?php
                if(sizeof($lines_array)==0){
                    ?>
                    <tr>
                        <td colspan="5">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="nof_<?php echo $CSSstyle?>_contentCell">
                                        <span class="nof_<?php echo $CSSstyle?>_text">
                                            <span class="nof_<?php echo $CSSstyle?>_successText"><?php echo $GLOBALS['nof_resources']->get('ts.Text.NoTestimonials')?></span>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
                }
                ?>


          </table>
        </td>
      </tr>
      <tr>
        <td align="center" class="nof_<?php echo $CSSstyle?>_contentCell">
					<?php if(sizeof($lines_array)>0){?>
					<input type="submit" value="<?php echo $GLOBALS['nof_resources']->get('ts.Admin.Button.Save')?>" class="nof_<?php echo $CSSstyle?>_input_submit">
					<?php }?>
				</td>
      </tr>
    </table>
		</form>
  </div>


<?php
    }
}
?>

