<?php
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/BookReviews.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class Table {

    protected $table;
    protected $db;
    protected $vals = false;
    protected $key;

    function __construct($id = null) {
        $this->db = new DB($this->table);

        if($this->db->getError()){
            throw new Exception($this->db->getError());
        }

        if($id !== null) {
            $this->key = $id;
            $recs = $this->db->read(array(
                array($this->db->getPrimaryKey(), $id)
            ));
            if(count($recs) == 1) $this->vals = $recs[0];
            else throw new Exception("Record $id not found.");
        }
    }

    function __destruct(){
        //Close instances
        $this->db = null;
    }

    /**
     * Returns a boolean on whether or not the action is
     * allowed. 
     *
     *
     * @param string $action  create, read, update, delete, find
     * @param array  $params  Varys per action, but contains the
     *                        neccesary variables to make the decision
     *
     *                        CREATE, DELETE => null,
     *                        READ => key/s being read
     *                        UPDATE => key=>val pairs being updated
     *                        FIND => search parameters, returning object/record, with keys returning
     * @return boolean
     */
    function can($action, $params = null){
        return false;
    }

    
    function __get($key) {
        if ($this->vals === false) {
            throw new Exception("Key not loaded for __get() method (retreiving $key)");
        }
        

        if(!$this->can('read',$key)){
            throw new Exception("Permission denied reading $key from $this->table");
        }

        $value = null;

        if(array_key_exists($key,$this->vals)){
            $value = $this->vals[$key];
        } else {
            throw new Exception("Key $key not in table {$this->table} array.");
        }

        return $this->formatForDisplay($key,$value);              
    }

    function __set($name, $value) {
        if($name == "db"){
            $this->db = null;
            exit;
        }

        if ($this->vals === false || is_null($this->vals)) {
            throw new Exception("Key not loaded for __set() method");
        }

        if(!$this->can('update',array($name, $value, $this))){
            throw new Exception("Permission denied updating $name=>$value from $this->table");
        }

        $value = $this->formatForStore($name, $value);
        

        if(array_key_exists($name,$this->vals)) {
            if($this->db->isValid($name,$value)){
                try {
                    $where = array(
                        array($this->db->getPrimaryKey(), $this->key)
                    );

                    $set = array(
                        array($name, $value)
                    );

                    if($this->db->update($set,$where)){
                        $this->$name = $value;    
                    } else {
                        print_r( $this->db->getError());
                    }

                } catch (Exception $e) {
                    throw $e;
                    return false;
                }
            } else {
                return false;
            }
        } else {
            throw new Exception("Key $name not in table {$this->table} object.");
        }

        return true;
    }

    /**
     * load
     *
     * Loads a record into memory by primary key
     *
     * @param int $id
     * @return
     */
    function load($id){
       $this->key = $id;

       $recs = $this->db->read(array(
            array($this->db->getPrimaryKey(), $id)
        ));
        if(count($recs) == 1) $this->vals = $recs[0];
        else throw new Exception("Record $id not found.");
    }

    /**
     * find
     *
     * Find object or objects
     *
     * @param  array $params
     * @param  array $options
     * @return array
     */
    function find($params, $options=null, $retObj=true, $select= "*") {
        if(!$this->can('find', array($params, $retObj, $select))){
            throw new Exception("Permission denied while finding on $this->table.");
        }

        if($retObj){
            $recs = $this->db->read($params, $this->db->pk, $options);
            if(!$recs) print_r($this->db->getError());

            if(isset($options['COUNT']) && $options['COUNT']){
                return $recs[0]['COUNT(*)'];
            } else {
                $objs = array();
                $cls = get_class($this);
                foreach($recs as $r){
                    $objs[] = new $cls($r[$this->db->pk]);
                }

                return $objs;
            }

        } else {
           
           if($select != "*"){
               if(!is_array($select)) $select = array($select);
               if(!isset($options['DISTINCT'])) $select = array_merge(array($this->db->pk),$select);
           }
           $recs = $this->db->read($params, $select, $options);
           if(!$recs) print_r($this->db->getError());

           if(is_array($recs)){
                foreach($recs as &$r){
                   foreach($r as $k=>&$v){
                       $v = $this->formatForDisplay($k,$v);
                   }
                }
           }
         
           return $recs;
        }
    }

    /**
     * count
     *
     * Returns the number of records from a given search
     *
     * @param  array $params
     * @return integer
     */
    function getCount($params) {
        $recs = $this->find($params, "COUNT(*) c");

        return $recs[0]['c'];
    }

    /**
     * reload
     *
     * reloads a record
     *
     * @param int $id
     * @return
     */
    function reload(){
       $this->load($this->key);
    }


    /**
     * delete
     *
     * deletes the loaded record
     *
     * @return boolean
     */
    function delete() {
        if ($this->key === false) {
            throw new Exception("Key not loaded for delete() method");
        }
        if(!$this->can('delete')){
            throw new Exception("Permission denied deleting from $this->table");
        }

        $ret = $this->db->delete($this->key);

        if(!$ret){
            print_r($this->db->getError());
        } else {
            return true;
        }
    }

    /**
     * create 
     * 
     * creates a new record
     *
     * @param  array  $vals  Key=>Value pairs for the new record
     * @return integer  New ID
     */
    function create($vals) {
        if(!$this->can('create',$vals)){
            throw new Exception("Permission denied deleting from $this->table");
        }
            

        $n_vals = array();
        foreach($vals as $k=>$v){
            $n_vals[$k] = $this->formatForStore($k,$v);
        }

        $new = $this->db->create($n_vals);

        return $new;
    }

    /**
     * update
     * 
     * A method equivelent to a batch __set()
     * 
     * @param  array   $params  key=>value pairs
     * @param  boolean $ret_arr  Whether to return an array or a boolean
     * @return array
     */ 
    function update($params, $ret_arr = true) {
        if(isset($_SESSION['SR'])){
            $_SESSION['SR']->ds->clear($this->table);
        }
        
        $success = true;

        foreach($params as $k=>$v){
            //set the paramenter (calls the __set method)
            $this->$k = $v;
            //checks the value with what it should be (As a check)
            $success &= ($this->$k == $this->formatForStore($k,$v));
        }



        if($ret_arr) {
            //Build an Array to return
            if($success){
                $ret = array(
                    array(
                        'message'  => 'Changes Saved!',
                        'duration' => 3000,
                        'wait'     => 0
                    )
                );
            } else {
                $ret = array(
                    array(
                        'message'   => 'ERROR: Changes Were Not Saved!',
                        'duration'  => 5000,
                        'color'     => 'black',
                        'fontcolor' => 'white',
                        'wait'      => 0
                    )
                );
            }
        } else {
            //Return TRUE/FALSE
            $ret = $success;
        }

        return $ret;
    }




    /**
     * getRecord
     *
     * returns the entire loaded record
     *
     *
     * @return array
     */
    function getRecord(){

        $vals = ($this->vals) ? $this->vals : array();

        foreach($vals as $k=>&$v){
            $v = $this->formatForDisplay($k,$v);
        }

        return $vals;
    }

    /**
     * getAll
     *
     * returns all the records in a table
     * DANGEROUS FOR BIG TABLES
     * 
     * @return array
     */
    function getAll($options = null) {

        $recs = $this->db->read(null, "*", $options);

        if($recs !== false){
            foreach($recs as &$r){
                foreach($r as $k=>$v){
                    $r[$k] = $this->formatForDisplay($k,$v);
                }
            }
        }
        return $recs;
    }


    /**
     * formatForDisplay
     *
     * takes a key and a value
     * and formats the value to display
     * to the user
     *
     * Needs to be overwritten by child class
     *
     * @param   string  $key
     * @param   mixed   $value
     * @return  mixed
     */
    function formatForDisplay($key, $value) {
        return $value;
    }


    /**
     * formatForStore
     *
     * takes a key and a value
     * and formats the value to store
     * in the database
     *
     * Needs to be overwritten by child class
     *
     * @param   string  $key
     * @param   mixed   $value
     * @return  mixed
     */
    function formatForStore($key, $value) {
        return $value;
    }



}
