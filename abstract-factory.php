<?php


abstract class DBFactory
{
    abstract public function getDBconnection() : DBconnection;

    abstract public function getDBrecord() : DBrecord;

    abstract public function getDBQueryBuilder() : DBQueryBuilder;


    public function combine()
    {

        $connection = $this->getDBconnection();
        $record = $this->getDBrecord();
        $queryBuilder = $this->getDBQueryBuilder();
        // $sofa->anotherUsefulFunctionB($table);
    }
}

// фабрика для  sql
class MySQL extends DBFactory
{
    public function getDBconnection() : DBconnection
    {
        return new SQLconnection();
    }

    public function getDBrecord() : DBrecord
    {

        return new SQLrecord();
    }

    public function getDBQueryBuilder() : DBQueryBuilder
    {

        return new SQLQueryBuilder();
    }
}

// фабрика для postgres 
class PostGreSQL extends DBFactory
{
    public function getDBconnection() : DBconnection
    {
        return new PGconnection();
    }

    public function getDBrecord() : DBrecord
    {

        return new PGrecord();
    }

    public function getDBQueryBuilder() : DBQueryBuilder
    {

        return new PGQueryBuilder();
    }
}

// фабрика для Oracle

class Oracle extends DBFactory
{
    public function getDBconnection() : DBconnection
    {
        return new Oracleconnection();
    }

    public function getDBrecord() : DBrecord
    {

        return new OracleRecord();
    }

    public function getDBQueryBuilder() : DBQueryBuilder
    {

        return new OracleQueryBuilder();
    }
}




// интерфейс для соединения с базой данных
interface DBconnection
{
    public static function connect() : PDO;
}

// соединение с sql
class SQLConnection implements DBconnection
{

    const DB_HOST ='localhost';
	const DB_NAME ='test_db';
	const DB_USER ='root';
	const DB_PASS ='';

    // устанавливаем связь с базой данных SQL
    public static function connect() : PDO

    { 
        $options = array(
            PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
            PDO:: ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO:: ATTR_EMULATE_PREPARES => TRUE,
        );

        $connectString = 'mysql:host='.self::DB_HOST.';dbname='.self::DB_NAME.';';
        $connect = new PDO($connectString,self::DB_USER,self::DB_PASS, $options);
        return $connect;
        
    }

    // подготавливает запрос
    public static function sql( string $sql, array $args =[]): PDOStatement 
    {
        $connect = self::connect(); 
		$stmt = $connect->prepare($sql);
		$stmt -> execute($args);
		return $stmt;
	}
}

// соединение с postgresql
class PGConnection implements DBconnection 
{
    const DB_HOST ='localhost';
	const DB_NAME ='test_db';
	const DB_USER ='postgres';
	const DB_PASS ='postgres';
    const PORT = 5432;

    // устанавливаем связь с базой данных PG
    public static function connect() : PDO

    { 
        $options = array(
            PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
            PDO:: ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO:: ATTR_EMULATE_PREPARES => TRUE,
        );

        $connectString = 'pgsql:host='.self::DB_HOST.';dbname='.self::DB_NAME.';port=' .self::PORT.';';
        $connect = new PDO($connectString,self::DB_USER,self::DB_PASS, $options);
        return $connect;
        
    }

    // подготавливает запрос
    public static function sql(string $sql, array $args =[]): PDOStatement 
    {
        $connect = self::connect(); 
		$stmt = $connect->prepare($sql);
		$stmt -> execute($args);
		return $stmt;
	}

}

// соединение с  Oracle
class OracleConnection implements DBconnection
{
    $server         = "127.0.0.1";
    $db_username    = "SYSTEM";
    $db_password    = "Oracle_1";
    $service_name   = "ORCL";
    $sid            = "ORCL";
    $port           = 1521;
    $dbtns          = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = $server)(PORT = $port)) (CONNECT_DATA = (SERVICE_NAME = $service_name) (SID = $sid)))";

    // устанавливаем связь с базой данных PG
    public static function connect() : PDO

    { 
        $connect =new PDO("oci:dbname=" . $dbtns . ";charset=utf8", $db_username, $db_password, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
        return $connect;
        
    }

    // подготавливает запрос
    public static function sql(string $sql, array $args =[]): PDOStatement 
    {
        $connect = self::connect(); 
		$stmt = $connect->prepare($sql);
		$stmt -> execute($args);
		return $stmt;
	}

}




// интерфейс записи в базу данных
interface DBrecord
{
    public function record(string $sql, array $args) : int;
}

//  запись в базу данных mysql
class SQLrecord implements DBrecord {
    public function record(string $sql, array $args) : int    
	{	 
		$update =SQLconnection::sql($sql, $args);
        return $update->rowCount();  
    }
    
    
}

// запись в базу данных postgresql
class PGrecord implements DBrecord {

    public function record(string $sql, array $args) : int    
	{	 
		$update =PGconnection::sql($sql, $args);
        return $update->rowCount();  
    }

}
// запись в базу данных Oracle
class  OracleRecord implements DBrecord
{
    public function record(string $sql, array $args) : int    
	{	 
		$update =OracleConnection::sql($sql, $args);
        return $update->rowCount();  
    }

}






// интерфейс запросов к базе данных
interface DBQueryBuilder{
    public function getQuery(string $sql, array $args=[]): void;
    
}

// содержит функционал для осуществления запросов к базе данных sql
class SQLQueryBuilder implements DBQueryBuilder {
    // делает запрос и обрабатывает результаты
    public function getQuery(string $sql, array $args=[]):void
    {
        print_r(SQLconnection::sql($sql,$args)->fetchAll());
    }
}
// содержит функционал для осуществления запросов к базе данных postresql
class PGQueryBuilder implements DBQueryBuilder
{
    // делает запрос и обрабатывает результаты
    public function getQuery(string $sql, array $args=[]):void
    {
        print_r(PGconnection::sql($sql,$args)->fetchAll());
    }
}


// содержит функционал для осуществления запросов к базе данных oracle
class OracleQueryBuilder implements DBQueryBuilder {
    public function getQuery(string $sql, array $args=[]):void
    {
        print_r(OracleConnection::sql($sql,$args)->fetchAll());
    }

}



// клиентский код
function clientCode(DBFactory $factory)
{

    // $stmtUpdate = "INSERT INTO testing_schema.test (name,description,price) VALUES (:test, :test_description, :price )";
    // $paramsUpdate = array('test'=>'sox', 'test_description'=>'sox-desc', 'price'=>12);

    // $record =$factory->getDBrecord();
    // $recordResult = $record->insert($stmtUpdate,$paramsUpdate);

    $stmtQuery = "SELECT * FROM testing_schema.test" ;

    $query = $factory->getDBQueryBuilder();
    $queryResult = $query->getQuery($stmtQuery);

    
    echo $recordResult . "\n";
    echo $queryResult . "\n";


    
}



clientCode(new PostGreSQL());
echo "\n";


