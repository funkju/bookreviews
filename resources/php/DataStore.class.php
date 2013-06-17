<?php

    class DataStore {

        private $data;

        function __construct(){
            $this->data = array();
        }

   
        /** __set
         *
         * Adds an item to the data store
         * using php magic set
         *
         * i.e. $datastore->key = val;
         * @param $k string  key 
         * @param $v mixed
         */
        function __set($k, $v) {
            $this->data[$k] = $v;
        }

        /** __get
         *
         * Get's an item from cache by key
         *
         * @param $k string Key to retrieve
         * @return mixed
         * @throws Exception when Key is not in cache
         */
        function __get($k) {
            if(!$this->exists($k)){
                throw new Exception("Key not found in cache.");
            }
        
            return $this->data[$k];
        }

        /** clear
         * 
         * removes a given key from the cache
         *
         * @param string|array $k
         * @return bool
         */
        function clear($ks){
            if(!is_array($ks)) $ks = array($ks);
            foreach($ks as $k){
                unset($this->data[$k]);
            }
        }

        /** clearAll
         *
         * completely clears the cache
         *
         * @return void
         */
         function clearAll(){
            $this->data = array();
            
            return;
         }


        /** exists
         * 
         * is a given key in the cache
         *
         * @param  string  $k
         * @return boolean
         */
        function exists($k){
            return isset($this->data[$k]);
        }

        /** getCacheKeys
         *
         * returns the keys of all the cached values
         *
         * @return array
         */
        function getCacheKeys(){
            return array_keys($this->data);
        }
       




    }






?>
