<!--Aqui tornaremos o site dinamico pela utilizacao de php. Ou seja todas as lateroes feitas na base de dados a partir do nosso CRUD afeta direta e imediatamente
 os elementos do site. Exemplo, se apagarmos um artigo, ele desaparece automaticamente do site, fazendo refresh à pagina. -->
 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">



    <!-- ligaçoes externas: JQUERY e BOOTSTRAP (css, js)-->

    <!-- Bootstrap e JQuery-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <!--Fontawesome script para usarmos icones-->
    <script src="https://kit.fontawesome.com/29d0aa5d27.js" crossorigin="anonymous"></script>

    <!-- Google Font : police/tipo de letra: HoltWwod-->
    <link href="https://fonts.googleapis.com/css?family=Holtwood+One+SC&display=swap" rel="stylesheet">

    <!-- Link com css-->
    <link rel="stylesheet" href="css/style.css">

    <title>Burger Menu </title>
</head> 

<body>
    <div class="container site">
        <h1 class="text-logo"> <i class="fas fa-utensils"></i> Burger Menu <i class="fas fa-utensils"></i> </h1>

        <!--Fizemos esta parte como a ultima parte depois de todos os outros documentos da base de dados etc. para tornar o site dinamico. sem ser dinamico temos no documento indexnaodinamico.php-->
        <!--Fazemos na mesma a ligaçao à base de dados -->
        <?php
            require 'admin/database.php'; /*Ligaçao à base de dados*/ 
            echo '<nav>
                  <ul class="nav nav-pills">';
            $db = Database::connect();
            $statement = $db->query('SELECT * FROM categories');
            $categories = $statement->fetchAll();
            foreach($categories as $category){
                if($category['id'] =='1') /*O id de categorias começa em 1 e cd categoria tem o seu 1,2,3 como esta na base de données.
                Perguntamos se o id é 1:
                Se for 1, ele tem a classe ativo pk é o MENU e por defeito é o k ficar logo com cor ao inicio */ 

                    echo '<li role="presentation"><a class="nav-link active" href="#' . "id" . $category['id'] . '"data-toggle="pill">' .$category['name']. '</a></li>'; 
                    /* tivemos de colocar no echo a string "id" pk ja no outro site nao pude incluir numeros 1,2,3,... no a href, e,ta aqui fizemos  "id" . $category['id'] pk
                    ao fazer inspect nao sai um numero sozinho sai um id seguido do id da categoria "id1, id2, id3,..."  */
                    
                else 
                /*Para todos os outros burgers, snacks etc ficam sm a classe ative*/  
                    echo '<li role="presentation" ><a class="nav-link" href="#' . "id" . $category['id'] . '"data-toggle="pill">' .$category['name']. '</a></li>'; 
            }
            echo '</ul>
                </nav>';
            echo '<div class="tab-content">';
            foreach($categories as $category){
                if($category['id'] == '1' )
                     echo '<div class="tab-pane active" id="' . "id" .  $category['id'] . '">'; 
                else 
                     echo '<div class="tab-pane" id="'. "id" .  $category['id'] . '">'; 

                echo '<div class="row">';

                $statement = $db->prepare('SELECT * FROM items WHERE items.category = ?');
                $statement->execute(array($category['id']));

                while($item = $statement->fetch()) {
                    echo '<div class="col-sm-6 col-md-4">
                                 <div class="img-thumbnail">
                                     <img class="img-thumbnail" src="images/' . $item['image'] . '" alt="...">
                                     <div class="price">' . number_format($item['price'], 2, '.', ''). ' €</div>
                                     <div class="caption">
                                         <h4>' . $item['name'] . '</h4>
                                         <p>' . $item['description'] . '</p>
                                         <a href="#" class="btn btn-warning" role="button"> <i class="fas fa-shopping-basket"></i> Commander</a>
                                    </div>
                                 </div>
                            </div>';
                }
                echo  '</div>
                    </div>';

            }
            Database::disconnect();
            echo  '</div>
            </div>';
        ?>
        </div>
    </body>
</html>