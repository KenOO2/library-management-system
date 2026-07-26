<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    //funcion constructora de la clase Database, 
    // se ejecuta automáticamente al crear una instancia de la clase
    public function __construct(){
        // Obtenemos el valor de la variable de entorno DB_HOST,
        //  si no está definida, se asigna 'localhost' como valor predeterminado
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'biblioteca';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
    }
    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;
        // Configuracion de el DSN (Data Source Name) para la conexion con la base de datos MySQL
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
 
        // Encerramos la conexion en un bloque try-catch para manejar posibles errores y que el programa no se detenga
        try{
            //Creamos una nueva instancia de PDO y se la asignamos a la propiedad $conn
            //La instancia de PDO recibe el DSN, el nombre y contraseña de usuario como parámetros para establecer la conexión con la base de datos
            $this->conn =new PDO($dsn, $this->username, $this->password);

            // Configuramos el modo de error de PDO para que lance excepciones en caso de errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }
        catch (PDOException $exception){
            echo "Ha habido un problema al conectarse a la base de datos : ". $exception->getmessage();

        }       
        return $this->conn;
    }
}
