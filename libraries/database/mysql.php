<?php
    /**
     * PHP Mysql Base Function (2021-06-27)
     * Github: https://github.com/awanz/php-mysqli-base
     * By: Awan
     */
    class MySQLBase {
        
        protected $connection;
        public $hostinfo;

        public function __construct($hostname = "localhost", $database = "", $username = "", $password = null) {
            $this->connection = new mysqli($hostname, $username, $password, $database);
            if ($this->connection->connect_errno) {
                echo "Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error;
            }
            $this->hostinfo = $this->connection->host_info;
        }

        function __destruct() {
            if ($this->connection) {
                $this->connection->close();
            }
        }

        public function getConnection(){
            return $this->connection;
        }

        public function getHostInfo(){
            return $this->hostinfo;
        }

        public function escape($text) {
            return $this->connection->real_escape_string($text);
        }
        
        public function query($query) {
            /*
                YOUR QUERY
            */
            $result = $this->connection->query($query);
            return $result;
        }

        public function getAll($tableName, $orderBy = null) {
            /*
                SELECT * FROM $tableName
            */
            $query = "SELECT * FROM " . $tableName;
            if ($orderBy) {
                $query = $query . " ORDER BY " . $orderBy;
            }
            // print_r($query);
            // die();
            $result = $this->connection->query($query);
            
            return $result;
        }

        public function getAllClean($tableName, $clean = true) {
            /*
                SELECT * FROM $tableName
            */
            $query = "SELECT * FROM " . $tableName;
            if ($clean) {
                $query = $query . " WHERE deleted_at is null";
            }
            // print_r($query);
            // die();
            $result = $this->connection->query($query);
            
            return $result;
        }
        
        public function raw($raw) {
            // die($raw);
            $result = $this->connection->query($raw);
            
            return $result;
        }
        
        public function getBy($tableName, $keyWhere, $valueWhere, $orderBy = null) {
            /*
                SELECT * FROM $tableName
                WHERE $keyWhere = $valueWhere
                ORDER BY $orderBy
            */
            $query = "SELECT * FROM " . $tableName . " WHERE " . $keyWhere . " = '" . $valueWhere . "'";
            if ($orderBy) {
                $query = $query . " ORDER BY " . $orderBy;
            }
            $result = $this->connection->query($query);
            
            return $result;
        }
        
        public function getOneBy($tableName, $keyWhere, $valueWhere, $orderBy = null, $orderMethod = "ASC") {
            /*
                SELECT * FROM $tableName
                WHERE $arrayWhere = $valueWhere
                ORDER BY $orderBy $orderMethod
                LIMIT 1
            */
            $query = "SELECT * FROM " . $tableName . " WHERE " . $keyWhere . " = '" . $valueWhere . "'";
            if ($orderBy) {
                $query = $query . " ORDER BY " . $orderBy . " " . $orderMethod;
            }
            $query = $query . " LIMIT 1";
            $result = $this->connection->query($query);
            
            return $result;
        }
        
        public function getByArray($tableName, $arrayWhere) {
            /*
                SELECT * FROM $tableName
                WHERE $arrayWhere
            */
            $where = null;
            foreach ($arrayWhere as $key => $value) {
                $where .= $key . " = '" . $value . "'";
                $where .= " AND "; 
            }

            $where = substr($where, 0, -5);
            
            $query = "SELECT * FROM " . $tableName . " WHERE " . $where;
            // die($query);
            $result = $this->connection->query($query);
            
            return $result;
        }
        
        public function getLike($tableName, $keyWhere, $valueWhere) {
            /*
                SELECT * FROM $tableName
                WHERE $keyWhere $valueWhere
            */
            $query = "SELECT * FROM " . $tableName . " WHERE " . $keyWhere . " LIKE " . $valueWhere;
            $result = $this->connection->query($query);

            return $result;
        }

        public function insert($tableName, $dataArray) {
            /*
                INSERT INTO table_name
                (column1, column2, column3, ...)
                VALUES
                (values1, values2, values3, ...)
            */
            $entry = null;
            foreach ($dataArray as $key => $value) {
                if (is_numeric($value)) {
                    $entry = $entry . $value . ', ';
                } else {
                    if (preg_match("/\/[a-z]*>/i", $value ) != 0) {
                        $entry = $entry . '"' . addslashes($value) . '", ';  
                    } else {
                        $entry = $entry . '"' . $value . '", ';
                    }
                }
            }

            $entry = rtrim($entry, ", ");
            $arrayKeys = array_keys($dataArray);
            
            $column = null;
            foreach ($arrayKeys as $value) {
                $column = $column . $value . ", ";
            }
            $column = rtrim($column, ", ");
            
            $query = "INSERT INTO " . $tableName . " (" . $column . ") VALUES (" . $entry . ")";
            // print_r($query);

            $result = null;
            try {
                $queryact = $this->connection->query($query);
                if ($this->connection->error) {
                    throw new Exception($this->connection->error);
                }
                $result['status'] = 1;
                $result['message'] = "Add successful!";
                $result['last_id'] = $this->connection->insert_id;
            } catch (\Throwable $th) {
                throw new Exception($this->connection->error);
                // $result['status'] = 0;
                // $result['message'] = "Query failed: (" . $this->connection->errno . ") " . $this->connection->error;
            }
            
            return $result;
        }

        public function update($tableName, $dataArray, $keyWhere, $valueWhere) {
            /*
                UPDATE $tableName
                SET $dataArray
                WHERE $keyWhere = $valueWhere;
            */
            $set = null;
            $arrayKeys = array_keys($dataArray);
            foreach ($arrayKeys as $key) {
                if (is_numeric($dataArray[$key])) {
                    $set = $set . $key . " = " . $dataArray[$key] . ", ";
                }else {
                    if (preg_match("/\/[a-z]*>/i", $dataArray[$key] ) != 0) {
                        $set = $set . $key . " = '" . addslashes($dataArray[$key]) . "', ";
                    } else {
                        $set = $set . $key . " = '" . $dataArray[$key] . "', ";
                    }
                }
            }
            $set = rtrim($set, ", ");
            $query = "UPDATE " . $tableName . " SET " . $set . " WHERE " . $keyWhere . " = " . $valueWhere;
        
            $queryact = $this->connection->query($query);
            
            $result = null;
            
            if (!$this->connection->affected_rows) {
                $result['status'] = 0;
                $result['message'] = "Query failed: (" . $this->connection->errno . ") " . $this->connection->error;
            }else{
                $result['status'] = 1;
                $result['message'] = "Edit successful!";
            }
            
            return $result;
        }

        public function delete($tableName, $keyWhere, $valueWhere) {
            /*
                DELETE FROM $tableName
                WHERE $keyWhere = $valueWhere;
            */
            $query = "DELETE FROM " . $tableName . " WHERE " . $keyWhere . " = " . $valueWhere;
            $queryact = $this->connection->query($query);

            if (!$this->connection->affected_rows) {
                $result['status'] = 0;
                $result['message'] = "Query failed: (" . $this->connection->errno . ") " . $this->connection->error;
            }else{
                $result['status'] = 1;
                $result['message'] = "Delete successful!";
            }
            return $result;
        }
    }