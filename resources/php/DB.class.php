<?php



class DB {

    //Database Handle
    private $dbh;
    //Table being read
    private $table;
    //Primary Key field
    public  $pk;
    //To hold errors
    private $error;


    /* DATABASE CONFIGURATION */
    private $host     = "localhost"; 
    private $database = "bookreviews";
    private $user     = "bookreviews";    ///CHANGE THIS
    private $password = "zuWb35UnRWMBUwSs";  ///CHANGE THIS

    function __construct($table = null) {
        //Throw an exception if no table is specified
        if ($table === null) throw new Exception("Table not defined.");

        //Set table
        $this->table = $table;

        try {
            //Open new DB connection
            $this->dbh = new PDO("mysql:dbname={$this->database};host={$this->host}", 
                                 $this->user, $this->password);
        } catch (PDOException $e) {
            //Set error on exception
            $this->error = $e->getMessage();
            return false;
        }
        //Set PK Field
        $this->pk = $this->getPrimaryKey();
    }

    /** __sleep
     * 
     * When the DB connection is sleeping
     * turn off the handle
     * 
     * And serialize table, pk, and error
     * @return array
     */
    function __sleep() {
        $this->dbh = null;

        return array('table','pk','error');
    }
    /** __wakeup
     *
     * reconstruct the DB
     */
    function __wakeup() {
        $this->__construct($this->table);
    }
    
    /**
     * create
     *
     * creates a new record in the specified table
     *
     * @param array $vars  An associative array containing the keys and values
     * @return int Returns the new ID
     */
    function create($vars){
        // If they didn't pass us an array, throw exception
        if(!is_array($vars)) {
            throw new Exception("Parameter is not an array.");
        }


        // Create doesn't use the buildSQLString method
        // because there is no "WHERE"
        $fields = "";
        $bindString = "";
        $whereString = "";
        $binds = array();
        foreach($vars as $key=>$val) {
            if ($fields != "") $fields .= ", ";
            $fields .= $key;

            if ($bindString != "") $bindString .= ", ";
            $bindString .= ":$key";

            if ($whereString != "") $whereString .= " AND ";
            $whereString .= "$key = :$key";

            $binds[":$key"] = utf8_encode($val);
        }

        $sql = "INSERT INTO {$this->table} ($fields) VALUES($bindString)";
        $insertStmt = $this->dbh->prepare($sql);
        
        if(!$insertStmt) {
            $this->error = $this->dbh->errorInfo();
            return false;
        }

        //Execute the prepared statement with the binds array
        if($insertStmt->execute($binds)){
            //clean up
            $insertStmt = null;

            // Find the ID of the inserted record
            $sql = "SELECT MAX({$this->pk}) as {$this->pk} FROM {$this->table}";
            $sql .= ($whereString == "") ? "" : " WHERE $whereString";

            $lookupStmt = $this->dbh->prepare($sql);
            
            if(!$lookupStmt) {
                print $sql;
               throw new Exception("Added record but could not find id (".print_r($this->dbh->errorInfo(),true)."').");
            }
            $lookupStmt->execute($binds);
            $row = $lookupStmt->fetch(PDO::FETCH_ASSOC);
            
            return $row[$this->pk];
        }
        else {
            $this->error = $insertStmt->errorInfo();
            print_r($this->error);
            return false;
        }
    }

    /**
     * read 
     *
     * returns a record set that matches the query
     *
     * @param  array  $vars     An associative array containing the search keys and values
     * @param  array  $select   An array of fields to return
     * @param  array  $options  Other options to put on the sql
     * @return array 
     */
     function read($vars = null, $select = "*", $options = null){
        // If they didn't pass us an array, throw exception
        if(!is_array($vars) && $vars !== null) {
            throw new Exception("Parameter is not an array.");
        }



        // Foreach key=>value pair in the $vars parameter
        // build the strings needed for the SQL statements
        // and the $binds array
        $build = $this->buildSqlString($vars);
        $whereString = $build['sql'];
        $binds = $build['binds'];

        $selectString = "";
        if(isset($options['DISTINCT'])) $selectString = "DISTINCT ";

        if(isset($options['COUNT']) && $options['COUNT']){
            $selectString = "COUNT(*)";
        } else {
            if(is_array($select)){
                for($i = 0; $i < count($select); $i++) {
                    $s = $select[$i];
                    if ($i != 0) $selectString  .= ", ";
       
                     //TODO: Verify $s is a field in $this->table
                     $selectString .= $s;
                }
            } else {
                $selectString = "*";
            }
        }
        
    
        $sql = "SELECT $selectString FROM {$this->table}";
        if($whereString) $sql .= " WHERE $whereString";
        

        if(isset($options['GROUP'])){
            $sql .= " GROUP BY ".$options['GROUP'];
        }

        if(isset($options['ORDER'])){

            $sql .= " ORDER BY ";
            $first = 1;

            if(!is_array($options['ORDER'])) $options['ORDER'] = array($options['ORDER']);
            foreach($options['ORDER'] as $opt){
                if(!$first) $sql .= ", ";
                $sql .= $opt;
                $first = 0;
            }
        }

        if(isset($options['LIMIT'])){
            $sql .= " LIMIT ".$options['LIMIT'];    
        }
        if(isset($options['OFFSET'])){
            $sql .= " OFFSET ".$options['OFFSET']; 
        }

        $selectStmt = $this->dbh->prepare($sql);
        if(!$selectStmt) {
            $this->error = $this->dbh->errorInfo();
            return false;
        }


        //Execute the prepared statement with the binds array
        if($selectStmt->execute($binds)){
            $rows = $selectStmt->fetchAll(PDO::FETCH_ASSOC);
            
            //clean up
            $selectStmt = null;

            return $rows;
        } else {
            print_r($sql);
            $this->error = $selectStmt->errorInfo();
            return false;
        }
    }

    /**
     * update
     *
     * updates a record that matches the query
     * and returns the id of the updated record
     *
     * @param  array  $vars     An associative array containing the search keys and values
     * @param  array  $update   An associateive array of new key=>value pairs
     * @return boolean
     */
     function update($set, $vars){


        // If they didn't pass us an array, throw exception
        if(!is_array($vars) || !is_array($set)) {
            throw new Exception("Parameter is not an array.");
        }


        // Foreach key=>value pair in the $vars parameter
        // build the strings needed for the SQL statements
        // and the $binds array
        $build = $this->buildSqlString($vars,'w');
        $binds = $build['binds'];
        $whereString = $build['sql'];

        $build = $this->buildSqlString($set,'s',',');
        $setString = $build['sql'];
        $binds = array_merge($binds,$build['binds']);

        $sql = "UPDATE {$this->table} SET $setString WHERE $whereString";
        
        $updateStmt = $this->dbh->prepare($sql);
        if(!$updateStmt) {
            $this->error = $this->dbh->errorInfo();
            return false;
        }

        //Execute the prepared statement with the binds array
        if($updateStmt->execute($binds)){
            //clean up
            $updateStmt = null;

            return true;

        }
        else {
            $this->error = $updateStmt->errorInfo();
            return false;
        }
    }


    /**
     * delete
     *
     * deletes a record that matches the query
     * and returns true or false
     *
     * @param  array  $vars     An associative array containing the search keys and values
     * @return boolean
     */
    function delete($vars){
        // If they didn't pass us an array, assume they passed us an ID
        if(!is_array($vars)) {
            $vars = array(array($this->getPrimaryKey(),$vars));
        }


        // Foreach key=>value pair in the $vars parameter
        // build the strings needed for the SQL statements
        // and the $binds array
        $build = $this->buildSqlString($vars);
        $binds = $build['binds'];
        $whereString = $build['sql'];

        $sql = "DELETE FROM {$this->table} WHERE $whereString";
        $deleteStmt = $this->dbh->prepare($sql);

        if(!$deleteStmt) {
            $this->error = $this->dbh->errorInfo();
            return false;
        }

        //Execute the prepared statement with the binds array
        if($deleteStmt->execute($binds)){
            //clean up
            $deleteStmt = null;

            return true;

        }
        else {
            $this->error = $deleteStmt->errorInfo();
            return false;
        }
    }

    /**
     * exec
     *
     * I don't like it, but this certainly is helpful
     * in some cases
     *
     * @param string $sql
     * @return array
     */
    function exec($sql, $binds = null){
        $stmt = $this->dbh->prepare($sql);
    
        if(!$stmt){
            $this->error = $this->dbh->errorInfo();
            return false;
        }
        $stmt->execute($binds);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * getPrimaryKey
     *
     * returns the primary key of 
     * a the current talbe
     * 
     * Instead of a slow query, use this
     * because of naming conventions
     *
     * @return string
     */
     public function getPrimaryKey() {
        
        
        return strtolower($this->table)."_id";
       
       /*
        $sql = "SELECT k.column_name 
                FROM information_schema.table_constraints t 
                JOIN information_schema.key_column_usage k 
                USING(constraint_name,table_schema,table_name) 
                WHERE t.constraint_type='PRIMARY KEY'
                    AND t.table_schema='{$this->database}'
                    AND t.table_name='{$this->table}'";

        $pkStmt = $this->dbh->query($sql);
        $row = $pkStmt->fetch(PDO::FETCH_ASSOC);

        return $row['column_name'];
        */
    }


    /**
     * buildSqlString
     *
     * returns an SQL Where string
     * given an array of terms
     *
     * @param  array  $where
     * @param  string $bindPfx  prefix to prepend to the bind param name
     * @param  string $delimiter  force $delimiter between each 
     * @return string
     */
     private function buildSqlString($where, $bindPfx = "", $delimiter = null) {
        //if where is malformed, return blank
        if(!is_array($where)) return array('sql'=>'','binds'=>array());

        $string = "";
        $binds = array();
        
        //foreach element in the array
        foreach($where as $w) {
            if($delimiter != null && $string != "") $string .= " $delimiter ";

            //if $w is just a string, add it to the query
            if(!is_array($w)) {
                $string .= " $w ";
            
            // if it is a two element array with strings
            } else if (count($w) == 2 && !is_array($w[0]) && !is_array($w[1])){
                $w[0] = addslashes($w[0]);


                //If it's a number assigned as null
                if((strpos($this->getFieldType($w[0]),"int") === 0 ||
                    strpos($this->getFieldType($w[0]),"float") === 0) 
                    && $w[1] == "") {
                    
                    if($delimiter !== null)  $string .= $w[0] . " = NULL";
                    else $string .= $w[0] . " is NULL";
                
                } else {
                    $t=rand();
                    $string .= $w[0] ." = :$bindPfx{$w[0]}$t";
                    $binds[":$bindPfx{$w[0]}$t"] = $w[1];
                }

            // if it is a three element array with strings
            } else if (count($w) == 3 && !is_array($w[0]) && !is_array($w[1]) && !is_array($w[2])){
                $w[0] = addslashes($w[0]);
                $w[1] = addslashes($w[1]);

                //If it's a number assigned as null
                if((strpos($this->getFieldType($w[0]),"int") === 0 ||
                    strpos($this->getFieldType($w[0]),"float") === 0)
                    && $w[2] === "") {
                    
                    if($delimiter !== null)  $string .= $w[0] . " = NULL";
                    else $string .= $w[0] . " is NULL";

                } else {
                    $t=rand(); 

                    if(is_null($w[2])){
                        $string .= $w[0]." ".$w[1]." NULL";
                    } else {
                        $string .= $w[0] ." " .$w[1] ." :$bindPfx{$w[0]}$t";
                        $binds[":$bindPfx{$w[0]}$t"] = $w[2];
                    }
                }
            } else {
                $build = $this->buildSqlString($w);
                $binds = array_merge($binds,$build['binds']);
                $string .= " (" . $build['sql']. ") ";
            }
        }
        
        return array('binds'=>$binds, 'sql'=>$string);

    }



    /**
     * getFieldType
     *
     * returns the type of the field given
     *
     * @param string $key
     * @return string
     */
    function getFieldType($key){
        $sql = "SHOW COLUMNS FROM {$this->table} WHERE Field = :field";
        $binds = array("field"=>$key);

        $ftSt = $this->dbh->prepare($sql);
        if($ftSt){
            $ftSt->execute($binds);
            $row = $ftSt->fetch(PDO::FETCH_ASSOC);
            return $row['Type'];
        } else {
            throw new Exception("Could not find type for $key (".$this->dbh->errorInfo().")");
        }
    }

    /**
     * isValid
     *
     * Says wether the given value is a valid
     * value for the given field
     *
     * TODO: this.
     * @param  string $key
     * @param  mixed  $value
     * @return boolean
     */
    function isValid($key, $value) {
        return true;
    }


    /**
     * getEror
     * 
     * returns the private member error
     *
     * @return string
     */
    function getError() {
        return $this->error;
    }


}



?>
