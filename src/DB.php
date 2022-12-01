<?php

class DB {
  protected PDO $_pdo;
  protected bool $_pdoExist = false;

  public function __construct(array $conf) {
    try {
      $this->_pdo = new PDO("mysql:host={$conf['host']};dbname={$conf['dbname']}", $conf['user'], $conf['pass']);
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->_pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
      $this->_pdoExist = true;
    } catch (PDOException $e) {
      $this->_pdoExist = false;
      throw $e;
    }
  }

  protected function _execQuery(string $query, array $params = []): PDOStatement {
    if ($this->_pdoExist === true) {
      $query = $this->_pdo->prepare($query);
      $query->execute($params);
      return $query;
    } else {
      throw new PDOException('DB does not connected');
    }
  }

  public function fetchOne(string $query, array $params = []): Object|bool {
    return $this->_execQuery($query, $params)->fetch();
  }

  public function fetch(string $query, array $params = []): Array|bool {
    return $this->_execQuery($query, $params)->fetchAll();
  }

  public function exec(string $query, array $params = []): void {
    $this->_execQuery($query, $params);
  }
}