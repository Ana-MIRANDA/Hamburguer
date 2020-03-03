<?php
/* Para se conectar à database/ base de dados */
require 'database.php';

/* Tal como se fez um campo de validaçao de formularios noutro documento, vamos aqui tb criar as variaveis de mensagem de erros 
Assim elas estando vazias, ou seja demos-lhes "" vazias que e uma estrategia para fazer desaparecer os erros da pagina que se 
apresentam agora pk elas nao estavam definidas anteriormente. Temos as mensagne sde erro
 e também as variaveis que nos dao os valores*/
$nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = " ";
/* Para reenviar as variaveis com a method post ao memso documento */
/* faz-se um if para dizer-lhe que se o post nao esta vazio 
/* Para recuperar os valores inseridos usa-se a superglobal POST e se os campos estao vazios (à segunda passagem) da a msg de erro */
if (!empty($_POST)) {
    $name                      = checkInput($_POST['name']);
    $description               = checkInput($_POST['description']);
    $price                     = checkInput($_POST['price']);
    $category                  = checkInput($_POST['category']);
    /* N imagem vai buscar-se o nome do ficheiro dai ter FILES['image']['name'], um array FILES com dois arrays  */
    $image                     = checkInput($_FILES['image']['name']);
    /* N imagem vai buscar-se o caminho/path/chemin que nos leva ao dossier onde ele esta*/
    $imagePath                 = '../images/' . basename($image);
    /* N imagem vai buscar-se a extensao do ficheiro. Para recuperar a extensao faz-se:*/
    $imageExtension            = pathinfo($imagePath, PATHINFO_EXTENSION);/*PATHINFO_EXTENSION esta é uma constante*/ 
    /* Verificar se o upload foi um sucesso */
    $isSuccess                 = true;
    $isUploadSuccess           = false;

    /* Se os campos dass variaveis nao estiverem preenchidos, vazios pk o cliente nao preencheu, aparecem as mensagem de erro */
    if (empty($name)) {
        $nameError = 'Ce champ ne peut pas être vide.';
        $isSuccess = false; /* O  $isSuccess  passa de true definido em cima a false aqui para dar a mensg de erro  */
    }

    if (empty($description)) {
        $descriptionError = 'Ce champ ne peut pas être vide.';
        $isSuccess = false;
    }

    if (empty($price)) {
        $priceError = 'Ce champ ne peut pas être vide.';
        $isSuccess = false;
    }

    if (empty($category)) {
        /* este é particular pk o menu aparece sempre na lista, esta sempre presente, por isso na verdade nao vai 
                nunca estar vazio mas para guardar a logica dos outros fica assim  */
        $categoryError = 'Ce champ ne peut pas être vide.';
        $isSuccess = false;
    }

    if (empty($image)) {
        $imageError = 'Ce champ ne peut pas être vide.';
        $isSuccess = false;
    } /* se a imagem nao esta vazia faz-se o que diz o else e dentro dele todos os campos associados a imagem */
     else {
       $isUploadSuccess = true;

        /* Aqui especifica-se que as imagens têm esta extensao. Logo se o documento upload n tiver nnhma destas dar erro */
        if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $immageExtension != "gif") {
            $imageError = "Les fichiers autorisés sont: .jpg, .jpeg, .png, .gif";
            $isUploadSuccess = false;
        }
        /* verificar se o caminho da imagem ja existe, tem o mmo nome, por exemplo. Se a aimagem é repetida */
        if (file_exists($imagePath)) {
            $imageError = "Le fichier existe deja";
            $isUploadSuccess = false;
        }
        /* Aqui falemos do tamanho do ficheiro  */
        if ($_FILES["image"]["size"] > 500000) {
            $imageError = "Le fichier ne doit pas dépasser 500KB";
            $isUploadSuccess = false;
        }

        /* Se a imagem nao e bem telechargé: a configuraçao n e compativel, tmp name é o nome da imagem temporaria e 
            mete-a no caminho da imagem */
        if ($isUploadSuccess) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                $imageError = "Il y a un erreur lors de l'upload";
                $isUploadSuccess = false;
            }
        }
    }
    /* verificar se nao houve problemas c os valores hors image:  se tudo se passsou bem (isSuccess = true) nos passos anteriores
         e que isUploadSuccess= true entao 1° fazer conexao a db, 2° prepare e execute : insere na tabela items os campos a seguir:
         name, description, price, category, image) values(?, ?, ?, ?, ?") e no fim se tudo OK volta-se para o documento index.php

         */
    if ($isSuccess && $isUploadSuccess) {
        $db = Database::connect();
        $statement = $db->prepare("INSERT INTO items (name, description, price, category, image) values(?, ?, ?, ?, ?)");
        $statement->execute(array($name, $description, $price, $category, $image));
        Database::disconnect();
        header("location: index.php");
        /* o header quer dizer muda a morada e manda-me para index.php  */
    }
}

/* Por segurança 1° verificar se alguém esta a tentar lesar-nos e para isso copiamos a funçao checkinput que esta no 
    doucmento view.php */
function checkInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- ligaçoes externas: JQUERY e BOOTSTRAP (css, js)-->

    <!-- Bootstrap e JQuery-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <!--Fontawesome script para usarmos icones-->
    <script src="https://kit.fontawesome.com/29d0aa5d27.js" crossorigin="anonymous"></script>

    <!-- Google Font : police/tipo de letra: HoltWwod-->
    <link href="https://fonts.googleapis.com/css?family=Holtwood+One+SC&display=swap" rel="stylesheet">

    <!-- Link com css-->
    <link rel="stylesheet" href="../css/style.css">

    <title>Construcao do sistema administrativo (index.php) </title>
</head>

<body>
    <!--Aqui vamos buscar os talheres que estao na pagina web e o titulo-->
    <h1 class="text-logo"> <i class="fas fa-utensils"></i> Burger Menu <i class="fas fa-utensils"></i> </h1>

    <div class="container admin">
        <div>

            <h1><strong> Ajouter un item </strong></h1>
            <br>

            <form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data">
                <!-- O formulario sera um form vivant porque se vao ser aceites valores.
 Para que depois de preenchido o "formulario" c as alteraçoes, sejamos enviados à mma pagina, depois de carregar no botao submit
 mas c os valores stockados = action=ficheiro para o qual serao enviadas - neste caso sera para si mesmo as données pela 
 method:post, a forma como seram enviadas as données. com action definidos entao que a pagina insert é para preencher vamlores
 que quando se carrega em submit ele sera redirigdo à mesma pagina "insert.php" mas com os valores stockés dans la superglobal post 
como vamos fazer upload de uma imagem, acrescenta-se (encription type) enctype="multipart/form-data"  -->
                <div class="form-group">
                    <label for="name"> Nom: </label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom de l'article">
                    <!--value= echo $name;"Ao inicio o value sera vazio mas a segunda vez ao retornar à pagina (action) vira com os dados gravados
    echo $name leva a que na segunda vez que reviermos a pagina vamos criar uma variavel com o nome name e no input integramos os 
    dados inseridos na variavel $name 
e para isso depois vamos utilisar as formas de validaçao de données como ja fizemos antes num formulario por isso vamos usar
 span class="help-inline" para que as mensagens de erro apareçam a vermelho -->
                    <span class="help-inline"><?php echo $nameError; ?></span>
                    <!--Em CSS esta p ficar a vermelho-->
                </div>


                <div class="form-group">
                    <label for="description"> Description: </label>

                    <input type="text" class="form-control" id="description" placeholder="Description" name="description">
                    <span class="help-inline"><?php echo $descriptionError; ?></span>
                </div>

                <div class="form-group">
                    <label for="price"> Prix: (en €)</label>
                    <!--o step="" vai permitir que a pessoa aumente ou diminua os numeros com uma seta sendo que cada 
                clic na seta aumenta o valor de um em um -->
                    <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Prix">
                    <span class="help-inline"><?php echo $priceError; ?></span>
                </div>

                <div class="form-group">
                    <!--Neste caso, categorie, deve ir buscar as categorias a database e nos vamos fazer a paresentaçao numa "lista deroulante" -->
                    <label for="category"> Catégorie: </label>
                    <select class="form-control" id="category" name="category">
                        <?php
                        $db = Database::connect(); /*Para que ele saiba o que é a variavel database tem de fazer-se o require 
                            do documento onde ela esta definida. Ver logo no inicio do documento database.php*/
                        /* Em vez de se fazer um while, faremos um foreach para fazer uma REQUET SQL*/
                        /* Em vez de fetch pedimos em $row  */
                        /* Pedimos depois que faça echo de cada uma das linhas das categorias  */
                        foreach ($db->query('SELECT * FROM categories') as $row) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            /* Quandos e faz um insert o que temos de recuperar é o id, o valor, e ele sera registo em value. */
                        }
                        Database::disconnect(); /* Aqui é a classe database.s */
                        ?>
                    </select>
                    <span class="help-inline"><?php echo $categoryError; ?></span>
                </div>

                <div class="form-group">
                    <label for="image">Séléctionner une image: </label>
                    <input type="file" id="image" name="image">
                    <!--Aqui ele sabe que vai recuperar um ficheiro 
                    input type="file", que nao e numero, que nao é texto, é um ficheiro, se quisermos por exemplo inserir 1 foto nova -->
                    <span class="help-inline"><?php echo $imageError; ?></span>
                </div>

                <br>
                <div class="form-actions">
                    <!--Neste otao ele vai submeter o formulario a ele mesmo, insert.php  -->
                    <button type="submit" class="btn btn-success"><span class="fas fa-pencil-alt"></span> Ajouter </button>
                    <a class="btn btn-primary" href="index.php"><span class="fas fa-arrow-left"></span> Retour </a>
                </div>

            </form>

        </div>

</body>

</div>
</div>