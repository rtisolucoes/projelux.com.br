<?php
	include_once 'inc/connect_db.php';
    
    $destaque = $_REQUEST["destaque"];
    $id = $_REQUEST["id"];

    $sqltxt = "UPDATE fotos_textos SET ftp_destaque='".$destaque."' WHERE ftp_id=".$id;
    $sql = mysqli_query($mysqli, $sqltxt);

	$sql2 = "SELECT * FROM fotos_textos WHERE ftp_id=".$id;
    $res2 = mysqli_query($mysqli, $sql2);
    $row2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);

    echo "<script type=text/javascript>window.location.href = 'menu.php?sub=textos&acao=cadastrar_imagens&id=".$row2["ftp_tex_id"]."';</script>";
 ?>