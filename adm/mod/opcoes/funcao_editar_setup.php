<?php
	include_once '../../inc/connect_db.php';
    include "../../js/wideimage/WideImage.php";

	echo "<meta charset='UTF-8'>";
    
    $id=$_REQUEST["id"];
    $nome=$_REQUEST["nome"];
    $lingua=$_REQUEST["lingua"];
    
    if($nome==''){
        echo "<script type=text/javascript>alert('Informe um nome!')</script>";
        echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=editar_setup&id=".$id."';</script>";
    }
    else{
        $imagem=$_FILES["imagem"];
        
        $errorIndex = $_FILES["imagem"]["error"];

        if ($errorIndex > 0) {

        }else{
            // Verifica se já existe uma imagem, se sim deleta.
            $sql_deleta_imagem="SELECT set_logo FROM setup WHERE set_id=".$id." ";
            $result_deleta_imagem=  mysqli_query($mysqli, $sql_deleta_imagem);
            $row_deleta_imagem= mysqli_fetch_array($result_deleta_imagem);

            if($row_deleta_imagem["set_logo"]!=''){
                $filename="../../img/".$row_deleta_imagem["set_logo"];
                unlink($filename);
            }
            // Fim função.
        }

        // Se a foto estiver sido selecionada
        if (!empty($imagem["name"])) {
            $error = array();
            // Largura máxima em pixels
            $largura = 9000;
            // Altura máxima em pixels
            $altura = 9000;
            // Tamanho máximo do arquivo em bytes
            $tamanho = 1000000;

            // Pega as dimensões da imagem
            $dimensoes = getimagesize($imagem["tmp_name"]);

            // Verifica se a largura da imagem é maior que a largura permitida
            if($dimensoes[0] > $largura) {
                    $error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
            }

            // Verifica se a altura da imagem é maior que a altura permitida
            if($dimensoes[1] > $altura) {
                    $error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
            }

            // Verifica se o tamanho da imagem é maior que o tamanho permitido
            if($imagem["size"] > $tamanho) {
                    $error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
            }

            // Se não houver nenhum erro
            if (count($error) == 0) {

                // Pega extensão da imagem
                preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $imagem["name"], $ext);

                // Gera um nome único para a imagem
                $nome_imagem = md5(uniqid(time())) . "." . $ext[1];

                // Caminho de onde ficará a imagem
                $caminho_imagem = "../../img/" . $nome_imagem;

                // Faz o upload da imagem para seu respectivo caminho
                move_uploaded_file($imagem["tmp_name"], $caminho_imagem);
                
                //$image = WideImage::load("../../img/".$nome_imagem);
                //$cropped = $image->crop('center', 'center', 153, 65);
                //$cropped->saveToFile("../../../img/language/".$nome_imagem);
                //$resized = $image->resize(600,85, 'inside', 'any');
                //$resized->saveToFile("../../img/".$nome_imagem);

                // Insere os dados no banco
        		$comando_sql = "UPDATE setup SET set_nome='".$nome."',
        										 set_lang='".$lingua."', 
        										 set_logo='".$nome_imagem."' 
        								     WHERE set_id=".$id." ";
                $sql = mysqli_query($mysqli, $comando_sql);

                // Se os dados forem inseridos com sucesso
                if ($sql){
                	echo "<script type=text/javascript>alert('Cadastro alterado com sucesso!')</script>";
                    echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=index_setup';</script>";
                }else {
                	echo "<script type=text/javascript>alert('Cadastro Não alterado!')</script>";
                    echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=index_setup';</script>";
				}
            }

            // Se houver mensagens de erro, exibe-as
            if (count($error) != 0) {
                if($error[1]){
                    echo "<script type=text/javascript>alert('Isso não é uma imagem.')</script>";
                    echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=editar_setup&id=".$id."';</script>";
                }
                if($error[2]){
                    echo "<script type=text/javascript>alert('A largura da imagem não deve ultrapassar ".$largura." pixels')</script>";
                    echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=editar_setup&id=".$id."';</script>";
                }
                if($error[3]){
                    echo "<script type=text/javascript>alert('Altura da imagem não deve ultrapassar ".$altura." pixels')</script>";
                    echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=editar_setup&id=".$id."';</script>";
                }
                if($error[4]){
                    echo "<script type=text/javascript>alert('A imagem deve ter no máximo ".$tamanho." bytes')</script>";
                    echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=editar_setup&id=".$id."';</script>";
                }
            }
        }
        else{
        	$comando_sql = "UPDATE setup SET set_nome='".$nome."', set_lang='".$lingua."' WHERE set_id=".$id;
            $sql = mysqli_query($mysqli, $comando_sql);

            // Se os dados forem inseridos com sucesso
            if ($sql){
                echo "<script type=text/javascript>alert('Cadastro alterado com sucesso!')</script>";
                echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=index_setup';</script>";
            }else {
                echo "<script type=text/javascript>alert('Cadastro Não alterado!')</script>";
                echo "<script type=text/javascript>window.location.href = '../../menu.php?sub=opcoes&acao=index_setup';</script>";
            }
        }
    }
?>