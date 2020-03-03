<!--Esta pagina é extremamente similar à pagina de insert.php por isso em caso de necessidade de explicaçoes ir ver a esse documento.
Nesta vou incluir as explicaçoes de elementos ue nao fazem parte da pagina delete.php-->


<?php

require 'database.php';

if(!empty($_GET['id'])){
    $id = checkInput($_GET['id']);
}

/* Para a segunda passagem:  */

if(!empty($_POST)){
    $id = checkInput($_POST['id']);
    $db = Database::connect();
    $statement = $db->prepare("DELETE FROM items WHERE id = ?");
    $statement->execute(array($id));
    Database::disconnect();
    header("Location: index.php");
}


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

            <h1><strong>Supprimer un item </strong></h1>
            <br>

            <form class="form" role="form" action="delete.php" method="post">
        <!--O hidden permite esconder a info do utilizador aquando da primeira passagem de delete. -->
                <input type="hidden" name="id" value="<?php echo $id;?>"/>
                <p class="alert alert-warning">Etes-vous sûr de vouloir supprimer?</p>
              
                <div class="form-actions">
           <!--Aqui vamos fazer uma method diferente -->         
                    <button class="btn btn-info" type="submit"> Oui </button>
                    <a class="btn btn-light" href="index.php"></span> Non </a>
                </div>

            </form>

        </div>

        </tbody>

    </div>
    </div>