<?php header('Content-type: text/html; charset=windows-1251')?>
<link href="/create/st.css" rel="stylesheet" type="text/css">
<?php 
function crdb()
{
	$HOST = htmlspecialchars($_POST['name_server']); 
	$USER = htmlspecialchars($_POST['login']);
	$PASS = htmlspecialchars($_POST['pass']);
	$DB = htmlspecialchars($_POST['name_db']); 
	$CONFIG = "config.php"; 
    if(!empty($HOST) && !empty($USER) && !empty($DB))
	{

		$mysqli = new mysqli($HOST, $USER, $PASS);

		if($mysqli->connect_errno)
		{
			return "<strong>���������� ����������� � �������.</strong><br> <br>
                   <p align=left><b> ��������� �������:</b><br>
					1. �� ��������� ����� ������. (�� ��������� ������ �����������)<br>
                    2. ��� ������� ������� �� �����.<br>
                    3. ����� ������� � ������� ���� ������ MySQL �� ���������������.<br>
					Connect failed: %s\n" . $mysqli->connect_errno . "</p>";
		}
		$queryResult = $mysqli->query("CREATE DATABASE $DB");
		if(!$queryResult)
		{
			return "<strong>���������� ������� ���� ������.</strong><br> <br>
                   <p align=left><b> ��������� �������:</b><br>
					���� ������ ��� ����������, ������� �����.</p>";
		}
?>
<?php
		
		if (!$mysqli->select_db($DB))
		{
			return $mysqli->error();
		}
		$mysqli->query('SET NAMES cp1251;');
		// ������ ���������������� ����		
		$data = '<?php
			$HOST = "' . $HOST . '";
			$USER = "' . $USER . '";
			$PASS = "' . $PASS . '";
			$DB = "' . $DB . '";

			$mysqli = new mysqli($HOST, $USER, $PASS, $DB);

			if($mysqli->connect_errno){
				printf("Connect failed: %s\n", $mysqli->connect_error);
				exit();
			}
		?>';
		$hd = fopen($CONFIG,"w");
		$e = fwrite($hd, $data);
		if($e == -1)
		{
		   return "������. ���������������� ���� �� ������.";	
		}
		return "<span class='green'>���� ������ \"$DB\" ������� �������.</span><br>
                                    <a href='create-tab.php?step=1'>�����</a>";
	}
	else
	{
	   return "�� ��� ���� ���������.";	
	}
}
if($_POST['button'] == "�������")
{
 $err = crdb();
}
?>



<form method="post" action="">
  <div class="centers">
    <br>
     <table align="center" width="483" border="0" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="2" align="center"><strong> ������� ���� ������ </strong></td>
      </tr>
      <tr>
        <td width="224" align="right"><span class='red'>*</span>��� SQL �������:</td>
        <td width="227" align="left"><input name="name_server" type="text" value="localhost" size="30" maxlength="45"></td>
      </tr>
      <tr>
        <td align="right"><span class='red'>*</span>����� :</td>
        <td><input name="login" type="text"  value="root"  size="20" maxlength="25"></td>
      </tr>
      <tr>
        <td align="right">������:</td>
        <td><input name="pass" type="text" size="20" maxlength="20"></td>
      </tr>
      <tr>
        <td align="right"><span class='red'>*</span>��� ��:</td>
        <td><input name="name_db" type="text" value="<?php echo $DB; ?>" size="30" maxlength="30"></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td><label>
          <input type="submit" name="button" id="button" value="�������" class="buts">
        </label></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><span class='red'><?php echo $err; ?></span></td>
      </tr>
    </table>
  </div>
</form>
