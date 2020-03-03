<!--Fazer ligaçao da BD ao site dos hamburgers-->

<!--Nao esquecer que para este site fez-se uma base de dados no PHPmyAdmin e também um sistema de administraçao.
Porquê?
 porque um sistema de administraçao nao exige conhecimento de rogramaçao, pode ser manipulado por kk pessoa
+
ele pode ser eprsoalizado como nos quisermos, pode ter o loook do site web, por exemplo
+
podemos ver os produtos nosistema tal como eles aparecem no site, por exemplo ver a imagem do sundae como aparece no site
e no PHPmyAdmin n é possivel. isso é + claro visualmentes e mais agradavel para se ver o que se esta passar/ a fazer.
-->


<?php 

   // fazer a ligaçao a base de dados:
   // $connection = new PDO("mysql:host-localhost;dbname-burger_site","root","")

    //na criaçao do PDO ha ters argumentos: 
    //0.1 - criar uma variavel com o nome por exemplo conexion;
    // 1 - mysql:host-localhost - indica que se trata de uma base de dados e onde se encontra;
    // 2 - dbname-burger_site = nome da db, tal como esta na base de dados
    // 3 - root e "" =  username e a password (neste caso vazia "")

    //para por isto em açao, o john fez um try com as seguintes variaveis:


    class Database { //aqui estamos ja a abordar a POO(prog orienté objet)

 //com "static" as variaveis tornam-se estaticas ou seja elas pertencem a classe e nao as instances (new classe) de la classe 
 //com "private"as variaveis ficam definidas como restritas, so acessiveis pela classe database 
 //com "public" as variaveis ficam definidas como acessiveis por toda a gente
  // neste caso n ha instancias da classe. Nos vamos usar diretamente a classe e 
    // nao instances(como na criaçao de objetos em POO). Vamos usar diretamente a conxao da classe
    private static $dbHost = "localhost";//explicaçao em cima de cada uma destas
    private static $dbName = "burger_site";
    private static $dbUser = "root";
    private static $dbUserPassword = "";

    private static $connection = null; //inicializa a variavel a null

    public static function connect(){
        //self:: indica que se trata da propriedade estatica da classe
        try{
            self::$connection = new PDO("mysql:host=" . self::$dbHost .";dbname=". self::$dbName,self::$dbUser,self::$dbUserPassword,[
                PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
       } 
        catch (PDOException $e) //para apanhar erros e exceçoes e PDO excecoes
        {
            die($e->getMessage()); //para a execuçao do codigo e apresenta uma mensagem de erro
        }
        return self::$connection;
    }

   public static function disconnect(){ //permite que quando estamos na pagina web tenhamos acesso a elementos como a 
    // conexao na qual esta presente o "ready", "prepare" e "execute". Pour retourner cette conexion il a fait cette function
    //aqui ela é definida e chamada no return do try e esta anula a conexion (nao percebi mt bem. a rever).
        self::$connection = null;
    }
}
// Ate aqui declaramos a classe agora temos de a usar para que funcione, para que possa ser utilisada:

Database::connect();

//Para ver se esta a funcionar abrir na net a pagina http://192.168.64.2/BurgerCode/admin/database.php:
                                                            //host + nome do dossier + nome d dossier + nome do doc php
// A pagina ficou a branco 
// Agora para criar o sistema criou-se um outro documento index.php para cada um dos elementos: insert, view, 
// update, delete basicamente cada elemento do CRUD quase  etc. 


?>