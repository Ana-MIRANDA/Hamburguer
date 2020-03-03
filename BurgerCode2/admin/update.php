<!--Esta pagina é extremamnte similar à pagina de view.php por isso em caso de necessidade de explicaçoes ir ver a esse documento.
Nesta vou incluir as explicaçoes de elementos ue nao fazem parte da pagina view.php-->


<?php
/* Para se conectar à database/ base de dados */
require 'database.php';

/*Neste documento usar-se-à tb o Get e nao apenas Post porque neste caso necessitamos do id dos elementos.Fazemos este if.  */

$nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = " ";

if (!empty($_GET['id'])) {
    $id = checkInput($_GET['id']);
    /*Para que eauqndo se carregue no botao modificar a 1a vez, que se va buscar os dados a db: nome, etc tal como eles estao para serem mudados enqto os clientes os vêem tal como 
    estao na db */
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM items WHERE id = ?");
    /* Pedimos para apanhar todos os parametros desta linha.  */
    $statement->execute(array($id));
    /* aqui pede-se para executar sobre a variavel array e dentro dele mete-se a variavel id criada no inicio do documento */
    /* Estes dois statements permitem recuperar todos os valores para que os vejamos quando formos altera-los, ou seja 
    para que eles apareçam quando se carrega no botao modifier na tabela.  */
    $item = $statement->fetch();
    $name =         $item['name'];
    $description =  $item['description'];
    $price =        $item['price'];
    $category =     $item['category'];
    $image =        $item['image'];
    Database::disconnect();
}

/* este codigo (igual ao do view.php) sera para a segunda vez que se passa na pagina update.php verificar se o method post
esta envolvido. Se ele esta envolvido ssignifica que se clicou no botao modifier para modificar os valores  */
if (!empty($_POST)) {
    $name                      = checkInput($_POST['name']);
    $description               = checkInput($_POST['description']);
    $price                     = checkInput($_POST['price']);
    $category                  = checkInput($_POST['category']);
    $image                     = checkInput($_FILES['image']['name']);
    $imagePath                 = '../images' . basename($image);
    $imageExtension            = pathinfo($imagePath, PATHINFO_EXTENSION);
    $isSuccess                 = true;
    /* Daqui retirou-se o $isUploadSuccess = false que se usa no view.php porque  ; */


    if (empty($name)) {
        $nameError = 'Ce champ ne peut pas être vide.';
        $isSuccess = false;
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
        $categoryError = 'Ce champ ne peut pas être vide.';
        $isSuccess = false;
    }
        /* Se a imagem esta vazia, ou seja, que nao foi selecionada uma imagem, pode nao ser um erro 
        pk este campo pode estar vazio no caso de eu nao querer upload uma imagem. Podemos querer mudar o preço mas nao uma imagem.
        entao retirou-se o if (empty($image)) {
                    $imageError = 'Ce champ ne peut pas être vide.';
                     $isSuccess = false;
    que se usou no view.php. Mas mudou-se para o seguinte :  */
    if (empty($image)) {
        $isImageUpdated = false; /* pergunta se a imagem foi modificada, updated. se nao é pk a pessoa quer 
        deixar a mma imagem que ja la esta.   */
    } else { /* aqu ja se manifesta o interesse em mudar a imagem  */
        $isImageUpdated = true;
        $isUploadSuccess = true;

        if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $immageExtension != "gif") {
            $imageError = "Les fichiers autorisés sont: .jpg, .jpeg, .png, .gif";
            $isUploadSuccess = false;
        }

        if (file_exists($imagePath)) {
            $imageError = "Le fichier existe deja";
            $isUploadSuccess = false;
        }

        if ($_FILES["image"]["size"] > 500000) {
            $imageError = "Le fichier ne doit pas dépasser 500KB";
            $isUploadSuccess = false;
        }

        if ($isUploadSuccess) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                $imageError = "Il y a un erreur lors de l'upload";
                $isUploadSuccess = false;
            }
        }
    }
    /* Neste caso estas questoes, que estavam no view.php, if ($isSuccess && $isUploadSuccess) {
                                                            $db = Database::connect();
                                                            $statement = $db->prepare("INSERT INTO items (name, description, price, category, image) values(?, ?, ?, ?, ?");
                                                            $statement->execute(array($name, $description, $price, $category, $image));
                                                            Database::disconnect();
                                                            header("location: index.php");
     so fazem sentido no caso de a pessoa ter mudado de image.Temos, entao, de mudar para o seguinte:
     Se todos os paramentros estao bem é entao = a true; 
    quanto a imagem pergunta-se se a imagem foi updated? se sim, o isuploadsucess deve ser igual a true,
    mas se nao se mudou a imagem, basta-nos o issuccess pk o update nao se aplica nesse caso:
     $isSuccess && $isImageUpdated && $isUploadSucccess para quando a imagem e dados sao modificados
     $isSuccess && !$isImageUpdated Para quando a imagem nao foi updated e verifica-se, entao, so o sucesso de entrada de valores:  */

    if (($isSuccess && $isImageUpdated && $isUploadSucccess) || ($isSuccess && !$isImageUpdated)) {

        $db = Database::connect();
        /* para o caso de sucesso, true:  */
        if ($isImageUpdated) {
            /* Também aqui se retira-se a palavra INSERT INTO : $statement = $db->prepare("UPDATE ...) 
     o que estava no view.php pk n s ker insert, quer-se modificar/update + set os items para nos dar o novo valor de cada item e com image = ? 
     porque estamos no if de qd se muda tb a imagem: */
            $statement = $db->prepare("UPDATE  items set name = ?, description = ?, price = ?, category = ?, image = ? WHERE id ?");
            $statement->execute(array($name, $description, $price, $category, $image, $id));/* Acrescentou-se aqui o id */
            /* Portanto aqui ja recebemos os novos valores dados pelo cliente, estamos entao na segunda passagem do documento */
        } else {
            /* Aqui image = ?  e $image sao retirados pk este campo destina-se apenas para quando o cliente muda valores de
      tudo/alguns items mas q n tem upload de image, mantem a q ja ecistia. */

            $statement = $db->prepare("UPDATE items set name = ?, description = ?, price = ?, category = ?  WHERE id = ?");
            $statement->execute(array($name, $description, $price, $category, $id));
        }
        Database::disconnect();
        header("location: index.php");
        /* o header quer dizer muda a morada e manda-me para index.php  */
    } else if ($isImageUpdated && !$isUploadSuccess) {
        $db = Database::connect();
        $statement = $db->prepare("SELECT image FROM items WHERE id = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();
        $image = $item['image'];
        Database::disconnect();
    }

    /* Para o caso de insucesso, false, p exemplo k a pagina nao foi preenchida c os valores a mudar, faz-se este else if:   */ 
    else {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM items WHERE id = ?");
        /* Pedimos para apanhar todos os parametros desta linha.  */
        $statement->execute(array($id));
        /* aqui pede-se para executar sobre a variavel array e dentro dele mete-se a variavel id criada no inicio do documento */
        /* Estes dois statements permitem recuperar todos os valores para que os vejamos quando formos altera-los, ou seja 
        para que eles apareçam quando se carrega no botao modifier na tabela.  */
        $item = $statement->fetch();
        $name =         $item['name'];
        $description =  $item['description'];
        $price =        $item['price'];
        $category =     $item['category'];
        $image =        $item['image'];
        Database::disconnect();
    }
}

/* Por segurança 1° verificar se alguém esta a tentar lesar-nos e para isso copiamos a funçao checkinput que esta no 
    doucmento view.php */
function checkInput($data){
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

    <h1 class="text-logo"> <i class="fas fa-utensils"></i> Burger Menu <i class="fas fa-utensils"></i> </h1>

    <div class="container admin">
        <div class="row">
            <div class="col-sm-6">
                <h1><strong>Modifier un item </strong></h1>
                <br>
                <!--<Para que depois de carregar em submit ele nos redirecione para si mesmo, ao update.php. Para que ele nos 
     dê o id de cada elemento, a primeira vez k s abre o documento faz-se get mas depois quando se quer submeter as novas informaçoes
     faz-se assim, por exemplo, na action:-->
                <form class="form" role="form" action=<?php echo 'update.php?id=' . $id; ?> method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name"> Nom: </label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                        <span class="help-inline"><?php echo $nameError; ?></span>
                    </div>


                    <div class="form-group">
                        <label for="description"> Description: </label>

                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description; ?>">
                        <span class="help-inline"><?php echo $descriptionError; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="price"> Prix: (en €)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Prix" value="<?php echo $price; ?>">
                        <span class="help-inline"><?php echo $priceError; ?></span>
                    </div>

                    <div class="form-group">

                        <label for="category"> Catégorie: </label>
                        <select class="form-control" id="category" name="category">
                            <!--Selecionar a categoria que se pretende na lista rolante.
         Como as categorias se abrem naquela lista rolante, onde menus é o valor por defeito. 
         Mas para precissar qual é a categoria pretendida fazemos um if dentro do foreach, onde a $categoria 
         é o id da cetgoria, nao é o nome. e o selected ="selected". No caso de nao estarmos na categoria do item ele lê o else-->
                            <?php
                            $db = Database::connect();
                            foreach ($db->query('SELECT * FROM categories') as $row) {
                                if ($row['id'] == $category)
                                    echo '<option selected="selected" value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                else
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php echo $categoryError; ?></span>
                    </div>

                    <!--Para que antes de mudar de foto se possa saber quel o valor da imagem atual,faz-se um label  -->

                    <div class="form-group">
                        <label>Image:</label>
                        <p><?php echo $image; ?></p>
                        <label for="image">Sélectionner une image: </label>
                        <input type="file" id="image" name="image">

                        <span class="help-inline"><?php echo $imageError; ?></span>
                    </div>

                    <br>
                    <div class="form-actions">

                        <button type="submit" class="btn btn-success"><span class="fas fa-pencil-alt"></span> Modifier </button>
                        <a class="btn btn-primary" href="index.php"><span class="fas fa-arrow-left"></span> Retour </a>
                    </div>
                </form>
            </div>
            <!--Para a parte direita da pagina onde se apresenta aimagem do item-->
            <div class="col-sm-6 site">
                <div class="img-thumbnail">
                    <img src="<?php echo '../images/' . $image ; ?> " class="img-thumbnail" alt=" ...">
                    <div class="price"><?php echo number_format((float)$price, 2, '.', '') . ' €'; ?></div>
                    <div class="caption">
                        <h4><?php echo $name; ?></h4>
                        <p><?php echo $description; ?></p>
                    </div>
                    <a href="#" class="btn btn-warning" role="button"> <i class="fas fa-shopping-basket"></i> Commander</a>

                </div>

            </div>

        </div>



    </div>

    </tbody>

    </div>
    </div>

    </div>



</body>

</html>