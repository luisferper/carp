<?php

Class DataBase {

        private $pdo;
        private $error;
        
        public function __construct(){

            $config = array(
                'db_host' => HOSTINGDB,
                'db_port' => PORTDATABASE,
                'db_name' => DATABASENAME,
                'db_user' => USERDATABASE,
                'db_pass' => PASSDATABASE
            );

            $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']}";
            try {
                $this->pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
            }
        }
        
        public function add($table, $data) {
            try {
                $columns = implode(', ', array_keys($data));
                $values = implode(', :', array_keys($data));
                $query = "INSERT INTO $table ($columns) VALUES (:$values)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($data);
                return $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }
        
        public function update($table, $where, $data) {
            try {

                $set = '';
                foreach ($data as $key => $value) {
                    $set .= "$key = :$key, ";
                }
                $set = rtrim($set, ', ');

                $whereClause = '';
                foreach ($where as $key => $value) {
                    $whereClause .= "$key = :$key AND ";
                }
                $whereClause = rtrim($whereClause, 'AND ');
        
                $query = "UPDATE $table SET $set WHERE $whereClause";
                $stmt = $this->pdo->prepare($query);

                foreach ($data as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
        
                foreach ($where as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
    
                $stmt->execute();
                return true;
        
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }
    
        public function delete($table, $where, $params = array()) {
            try {

                $whereClause = '';
                foreach ($where as $key => $value) {
                    $whereClause .= "$key = :$key AND ";
                }
                $whereClause = rtrim($whereClause, 'AND ');

                $query = "DELETE FROM $table WHERE $whereClause";
        
                $stmt = $this->pdo->prepare($query);
        
                foreach ($where as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
        
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }
    

        public function select($table, $fields = "*", $conditions = null, $likeConditions = null, $orderBy = null, $limit = null, $params = array()) {
            try {
                // 构建SELECT子句
                if (is_array($fields)) {
                    $fields = implode(', ', $fields);
                } elseif ($fields === "*") {
                    $fields = "*";
                } else {
                    $fields = "";
                }

                $whereClause = '';
                if (!is_null($conditions) && is_array($conditions)) {
                    foreach ($conditions as $key => $value) {
                        $whereClause .= "$key = :$key AND ";
                    }
                    $whereClause = rtrim($whereClause, 'AND ');
                }
        
                if (!is_null($likeConditions) && is_array($likeConditions)) {
                    if (!empty($whereClause)) {
                        $whereClause .= ' AND ';
                    }
                    foreach ($likeConditions as $key => $value) {
                        $whereClause .= "$key LIKE :like_$key AND ";
                        $params[":like_$key"] = $value;
                    }
                    $whereClause = rtrim($whereClause, 'AND ');
                }
        
                $orderByClause = '';
                if (!is_null($orderBy) && is_array($orderBy)) {
                    $orderByClause = "ORDER BY " . implode(', ', $orderBy);
                }
        
                $limitClause = '';
                if (!is_null($limit)) {
                    $limitClause = "LIMIT $limit";
                }
        
                $query = "SELECT $fields FROM $table";
                if (!empty($whereClause)) {
                    $query .= " WHERE $whereClause";
                }
                if (!empty($orderByClause)) {
                    $query .= " $orderByClause";
                }
                if (!empty($limitClause)) {
                    $query .= " $limitClause";
                }

                $stmt = $this->pdo->prepare($query);
        
                if (!is_null($conditions) && is_array($conditions)) {
                    foreach ($conditions as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                }
                
                if (!is_null($likeConditions) && is_array($likeConditions)) {
                    foreach ($likeConditions as $key => $value) {
                        $stmt->bindValue(":like_$key", $value);
                    }
                }
        
                $stmt->execute();
        
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                return $result;
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false; 
            }
        }
    
        public function execQuery($query, $params = array()) {
            try {

                $stmt = $this->pdo->prepare($query);
        
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
    
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }

        public function QueryLong($query){
            try {
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }
    
        public function errorMsg() {
            return $this->error;
        }
    }