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
            <!--class="btn btn-success btn-lg" botao verde e large para o ajouter elementos-->
            <h1><strong>Liste des Items </strong><a href="insert.php" class="btn btn-success btn-lg"><i class="fas fa-plus"></i> Ajouter</a></h1>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th> Nom </th>
                        <th> Description </th>
                        <th> Prix </th>
                        <th> Catégorie </th>
                        <th> Actions </th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    /* Antes de passar ao que esta na linha 92 fez-se esta explanaçao e organizaçao da tabela e seus elementos, botoes, etc.
 Depois estas linhas foram apagadas e substituidas pelo que ques esta a partir do require sob a forma de php. 
            <tr>
                <td>Item 1</td>
                <td>Description 1 </td>
                <td>Prix 1</td>
                <td>Catégorie 1 </td>
                <td width=500>
        <!--O simbolos tirei do fontaewsome. neste olho tirei a class i e pus spane isso muda tipo de letra de "voir"-->
        <!-- Somos enviados para diferentes paginas com estes botoes e usamos ?id=1 para ser em get com o id que se quer abrir-->
        <!--O id 1 no href fica assim no GET no URL: http://192.168.64.2/BurgerCode/admin/view.php?id=1--> 
        <!--O id 2 abaixo, no 2° item, no href fica assim no GET no URL: http://192.168.64.2/BurgerCode/admin/view.php?id=2--> 
                <a class="btn btn-info" href="view.php?id=1"><span class="far fa-eye"></span> Voir</a>
                <a class="btn btn-primary" href="update.php?id=1"><span class="fas fa-pencil-alt"></span> Modifier</a>
                <a class="btn btn-danger" href="delete.php?id=1"><span class="far fa-trash-alt"></i></span> Supprimer</a>
                </td>
            </tr>

            <tr>
                <td>Item 2</td>
                <td>Description 2 </td>
                <td>Prix 2</td>
                <td>Catégorie 2 </td>
                <td width=500>
                <a class="btn btn-info" href="view.php?id=2"><span class="far fa-eye"></span> Voir</a>
                <a class="btn btn-primary" href="update.php?id=2"><span class="fas fa-pencil-alt"></span> Modifier</a>
                <a class="btn btn-danger" href="delete.php?id=2"><span class="far fa-trash-alt"></i></span> Supprimer</a>
            </td>
            </tr> */
                    /* require VS include = se o ficheiro nao existe ele para tudo + require permite utilisar o conteudo do documento*/
                    require "database.php";
                    /* Aqui vamos usar a classe publica static criada na pagina database.php retorna-nos a conexao da variavel db */
                    $db = Database::connect();
                    /* Para que recupermos os resultados; no sistema de administraçao se selecionar elementos faz-se um statement, tipo select do sql das bd:  */
                    
                   $statement = $db->query('SELECT items.id, items.name, items.description, items.price, categories.name AS category
                                             from items LEFT JOIN categories ON items.category = categories.id
                                                ORDER BY items.id DESC');

                    /*  Para que elas se apresentem faz-se um while como no sql da bd mas pedidas em php*/
                    
                    
                    while ($item = $statement->fetch()) { /* para que passe uma linha de cada vez, uma a seguir à outra, recupera so uma linha de cada vez  */
                        echo '<tr>';
                        
                        /* Para imrpimir cada coluna da  */
                        echo '<td>' . $item['name']. '</td>';
                        echo '<td>' . $item['description'] . ' </td>';
                        echo '<td>' . number_format((float)$item['price'],2, '.', ''); '</td>';/* para tarnsformar o preço em float, com 2 numeros
                        a seguir à virgula, o . separar a unidades das decimas para fazer num decimal, a ' ' vazia aqui seria para escrever numero com
                        3.000 com mais do que decimas, neste caso deixamos vazio.  */
                      
                    
                        echo '<td>' . $item['category'] . '</td>';
                        echo '<td width=500>';
                        echo '<a class="btn btn-info" href="view.php?id=' . $item['id'] . '"><span class="far fa-eye"></span> Voir</a>';
                        echo ' ';
                        echo '<a class="btn btn-primary" href="update.php?id=' . $item['id'] . '"><span class="fas fa-pencil-alt"></span> Modifier</a>';
                        echo ' ';
                        echo '<a class="btn btn-danger" href="delete.php?id=' . $item['id'] . '"><span class="far fa-trash-alt"></i></span> Supprimer</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    database::disconnect();

                    ?>

                </tbody>


            </table>
        </div>
    </div>

</body>

</html>