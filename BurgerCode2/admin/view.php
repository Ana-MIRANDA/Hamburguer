<!-- esta pagina vai permitir ver cada  um dos items, cujo id e transmitido pelo URL -->
<?php
require 'database.php';/* para nos conectar a database */

/* para recuperar o id, que é trabsmitido no URL*/
if (!empty($_GET['id'])) {
    $id = checkInput($_GET['id']); /* Quando variaveis exteriores nos sao enviadas é melhor verifica-las para nao
    dar aos hackers porta de entrada esta funçao esta definida abaixo em function checkInput($data)*/
}
/* Vamos connectar a dbase e vamos stocker essa ligaçao na variavel db  */
$db = Database::connect();
/* para saber o id dos elementos de cada linha e para que ele selecione todos os elementos(um de cd vez)
 que pretendemos afixar na pagina. Mas como nos nao sabemos de cor os id's usa-se o GET para o vermos na morada URL*/
$statement = $db->prepare('SELECT items.id, items.name, items.description, items.price, items.image, categories.name AS category
                           from items LEFT JOIN categories ON items.category = categories.id
                           WHERE items.id = ?'); /* Como so queremos uma linha especificamos c este  where que linha queremos */
/* Para executar a requete acima apresentada faz-se ste conjunto de statements */
$statement->execute(array($id));
$item = $statement->fetch(); /* Como se trata de uma so linha nao e necessario fazer boucles. */
Database::disconnect(); /* Ao acabar faz-se disconnect */

/* funçao para verificar multiplas coisas(intençoes de malfeitores para criarem problemas nos sites, erros, etc. ) e leva um
 parametro  */
function checkInput($data) 
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<!--Neste documento vamso fazer com o sistema de administraçao tenha, entao, o aspeto da nossa pagina web-->

<!-- esta parte do sistema pode ser acedido em http://192.168.64.2/BurgerCode/admin/index.php-->

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
        <div class="row">
            <div class="col-sm-6">
                <h1><strong>Voir un item </strong></h1>
                <br>
                <form>
                    <div class="form-group">
                        <label> Nom: </label><?php echo ' ' . $item['name']; ?>
                    </div>

                    <div class="form-group">
                        <label> Description: </label><?php echo ' ' . $item['description']; ?>
                    </div>

                    <div class="form-group">
                        <label> Prix: </label><?php echo ' ' . number_format((float) $item['price'], 2, '.', '') . ' € ' . ' '; ?>
                    </div>

                    <div class="form-group">
                        <label> Catégorie: </label><?php echo ' ' . $item['category']; ?>
                    </div>

                    <div class="form-group">
                        <label> Nom: </label><?php echo ' ' . $item['image']; ?>
                    </div>
                </form>

                <div class="form-actions">
                    <a class="btn btn-primary" href="index.php"><span class="fas fa-arrow-left"></span> Retour </a>
                </div>

            </div>
            <!-- Para ir buscar a imagem, o preço de cada elemento que se pretende: -->
            <div class="col-sm-6 site">
                <!-- Site é o nome da classe referente ao tipo de letra a utilizar-->
                <div class="img-thumbnail">
                <!--o echo '../images/' como ja estamos dentro do ssier admin com este indicamos que e um outro dossier com nome images
            $item['image'] vai buscar a imagemm k corresponde ao item selecionado-->
                    <img src="<?php echo '../images/' . $item['image']; ?> " class="img-thumbnail" alt=" ...">
                    <div class="price"> <?php echo number_format((float) $item['price'], 2, '.', '') . ' € ' ; ?></div>
                    <div class="caption">
                        <h4><?php echo $item['name']; ?></h4>
                        <p><?php echo $item['description']; ?></p>
                    </div>
                    <a href="#" class="btn btn-warning" role="button"> <i class="fas fa-shopping-basket"></i> Commander</a>

                </div>

            </div>

        </div>
    </div>

</body>

</html>