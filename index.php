<?php 

// Chamando o arquvo responsável pelo upload
require_once './arquivo.php';

if (isset($_FILES['arquivo'])):

    $classe = new Upload();
    $classe->imagem($_FILES['arquivo'], null, 'artur');

    if ($classe->getResult()):
        echo "Arquivo carregado com sucesso ". $classe->getResult();
    else:
        echo $classe->getError();
    endif;

    echo "<br><hr>";

    var_dump($classe);

endif;

 ?>	


<form method="post" enctype="multipart/form-data">
          
    <div class="form-group m-t-20">
        <label for="foto">Escolha a foto</label><br>
        <input type="file" name="arquivo" id="foto" accept="jpeg,png,jpg">
        <input type="submit" value="Carregar">
    </div>
</form>