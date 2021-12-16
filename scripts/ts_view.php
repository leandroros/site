<?php

$cgiDir = $nof_rootDir ."/". $nof_scriptDir . "/";
$componentid = $nof_componentId;
$xml_file  = $nof_scriptInterfaceFile;


if (NOF_fileExists($cgiDir . "ts_lib.php")) {
    include_once($cgiDir."ts_lib.php");
} else {
    exit();
}

$conf = ts_parseXmlFile($cgiDir.$xml_file);

$itemnsnumber = $conf['view.' . $componentid . '.itemnsnumber'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'itemnsnumber');
$addlink = $conf['view.' . $componentid . '.addlink'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'addlink');
$dbPath = $conf['view.' . $componentid . '.dbPath'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'dbPath');
$addlinklabel = $conf['view.' . $componentid . '.addlinklabel'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'addlinklabel');
$email = $conf['view.' . $componentid . '.email'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'email');
$picture = $conf['view.' . $componentid . '.picture'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'picture');

// css from xml
$CSSstyle = $conf['view.' . $componentid . '.style'];//tstXMLGetPropertyByID($cgiDir.$xml_file,$componentid, 'style');

$flag_emptydb=0;

$lines_array=array();
$numberoflines=0;

if (!file_exists($cgiDir.$dbPath)) {
    $flag_emptydb=1;
}else{
    $lines_array=tstgetdb($cgiDir.$dbPath,1);
    $numberoflines = sizeof($lines_array);
    $picture_pos="";
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

$tmp_get = GetGVariable('offset');
if(empty($tmp_get)){
    $offset=1;
}else{
    $offset=GetGVariable('offset');
}


?>
  <div class="nof_<?php echo $CSSstyle?>_content">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $CSSstyle?>_header">
            <tr>
              <td><span class="nof_<?php echo $CSSstyle?>_text"><a href="<?php echo encodeURI($addlink);?>?text=<?php echo urlencode($addlinklabel);?>&amp;link=<?php echo urlencode(GetServerVariable('PHP_SELF'));?>" class="nof_<?php echo $CSSstyle?>_link"><span class="nof_<?php echo $CSSstyle?>_heading"><?php echo $addlinklabel;?></span></a></span></td>
              <td align="right">
                <?php
                if($flag_emptydb==0 and $numberoflines!=0){
                    tstdisplaypages($offset,$numberoflines,$itemnsnumber,5,$CSSstyle);
                }
                ?>
        			</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="nof_<?php echo $CSSstyle?>_columnHeadingRow"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_columnHeading"><?php echo $GLOBALS['nof_resources']->get('ts.View.Column1.Label')?></span></span></td>
        <td class="nof_<?php echo $CSSstyle?>_columnHeadingRow"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_columnHeading"><?php echo $GLOBALS['nof_resources']->get('ts.View.Column2.Label')?></span></span></td>
      </tr>
			<?php
			$start=$offset-1;
			$inc=1;
			while($start>=0 and $start<$numberoflines and $inc<=$itemnsnumber and $lines_array[$start]){
			?>
			<tr>
				<td align="center" class="nof_<?php echo $CSSstyle?>_contentCell" valign="top">
					<?php
						if(trim($picture)=="true" and $picture_pos!="" and $lines_array[$start][$picture_pos]!=""){
								echo tstimgresize($cgiDir."images/",$lines_array[$start][$picture_pos],122,180);
								echo "<br>";
						}
					?>
					<?php
						echo "<div class=\"nof_".$CSSstyle."_text\"><span class=\"nof_".$CSSstyle."_author\">".htmlspecialchars($lines_array[$start][$firstname_pos])."&nbsp;".htmlspecialchars($lines_array[$start][$lastname_pos])."</span></div>";
					?>
				</td>
				<td width="100%" valign="top" class="nof_<?php echo $CSSstyle?>_contentCell"><div class="nof_<?php echo $CSSstyle?>_text">
					<?php
						if ($GLOBALS['nof_locale'] == 'de') {
								$lines_array[$start][$date_pos] = date('d.m.Y H:m:s',$lines_array[$start][$date_pos]);
						} else {
								$lines_array[$start][$date_pos] = date('m/d/Y h:m:s a',$lines_array[$start][$date_pos]);
						}
						echo "<span class=\"nof_".$CSSstyle."_date\">".$lines_array[$start][$date_pos]."</span>";
					?>
					<?php
						echo "<p class=\"nof_".$CSSstyle."_testimonial\">".htmlspecialchars($lines_array[$start][$title_pos])."</p>";
					?>
					<?php
						$desc_array=split("<br>",$lines_array[$start][$description_pos]);
					?>
					<span class="nof_<?php echo $CSSstyle?>_description">
					<?php
					foreach($desc_array as $val){
							echo $val."<br>";
					}
					?>
					</span>
					<?php
						if( trim($email)=="true" and $emailonpage_pos!="" and trim($lines_array[$start][$emailonpage_pos])=="true" and trim($lines_array[$start][$email_pos])!="" ){
							?>
								<span class="nof_<?php echo $CSSstyle?>_email"><a href="mailto:<?php echo $lines_array[$start][$email_pos]?>" class="nof_<?php echo $CSSstyle?>_link"><?php echo $lines_array[$start][$email_pos]?></a></span>
							<?php
						}
					?>
			</div></td>
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
		          <td class="nof_<?php echo $CSSstyle?>_contentCell" colspan="2"><span class="nof_<?php echo $CSSstyle?>_text"><span class="nof_<?php echo $CSSstyle?>_successText"><?php echo $GLOBALS['nof_resources']->get('ts.Text.NoTestimonials')?></span></span></td>
				</tr>
				<?php
			}
			?>
            <tr>
              <td colspan="2">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="nof_<?php echo $CSSstyle?>_footer">
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
              </td>
            </tr>
		</table>
	</div>