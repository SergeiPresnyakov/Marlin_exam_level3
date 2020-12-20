<?php
class QueryBuilder
{
    private $pdo, $query, $queryStatus = false;

    /* 
    * Для работы нужно подключение к БД
    * в виде объекта PDO 
    *
    * $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'root', '');
    * $query = new QueryBuilder($pdo);
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* Получает все данные из таблицы
    * В качестве аргумента принимает имя таблицы
    *
    * Arguments:
    *    table = String
    *
    * Returns: Array
    *
    * Example:
    * $query = new QueryBuilder($pdo);
    * $result = $query->getAll('users'); 
    * получить все записи из таблицы users
    */
    public function getAll($table)
    {
        $sql = "SELECT * FROM $table";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /* Получить одну запись из таблицы по id
    * Arguments: 
    *    table = String,
    *    id = INT
    *
    * Returns: Array
    *
    * Example:
    * $query = new QueryBuilder($pdo);
    * $user = $query->getById('users', 1);
    * получить одного пользователя c id = 1 из таблицы users 
    */
    public function getById($table, $id)
    {
        return $this->action('SELECT *', $table, ['id', '=', $id])[0];
    }

    /* 
    * Получить одну или несколько записей из таблицы по условию
    * Arguments:
    *    table - String,
    *    where - Array
    * 
    * Returns: Array | bool
    *
    * Example:
    * $query = new QueryBuilder($pdo);
    * $active_users = $query->get('users', ['status', '<>', 'banned']); 
    * получить всех незабаненых пользователей 
    */
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

    /* Вставка в таблицу новой записи
    * Arguments:
    *    table - String,
    *    fields - Array
    *
    * Returns: bool
    *
    * Example:
    * $query = new QueryBuilder($pdo);
    * $query->insert('users', ['name' => 'David Smith', 'email' => 'davidsmith@gmail.com']); 
    * вставить в таблицу пользователя c именем David Smith и email davidsmith@gmail.com 
    */
    public function insert($table, $fields = [])
    {
        $values = '';
        foreach ($fields as $field) {
            $values .= '?,';
        }
        $values = rtrim($values, ',');

        $keys = array_keys($fields);
        $sql = "INSERT INTO {$table} (" . '`' . implode("`, `", $keys) . '`' . ") VALUES ({$values})";

        $this->query($sql, $fields);

        return $this->queryStatus;
    }

    /* 
    * Обновление записи в таблице
    * Arguments:
    *     table - String,
    *     id - INT,
    *     fields - Array
    * 
    * Returns: bool
    * 
    * Example:
    * $query = new QueryBuilder($pdo);
    * $query->update('users', 1, ['name' => 'Jane Smith', 'email' => 'janesmith@gmail.com'])
    * Установить пользователю с id = 1 новое имя и email
    */
    public function update($table, $id, $fields)
    {
        $set = '';
        foreach ($fields as $key => $field) {
            $set .= "`{$key}` = ?, ";
        }

        $set = rtrim($set, ', ');
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        $this->query($sql, $fields);

        return $this->queryStatus;
    }

    /*
    * Удаление записи из таблицы по id
    * Arguments:
    *     table - String
    *     id - INT
    *
    * Returns: bool
    *
    * Example:
    *  $query = new QueryBuilder($pdo);
    *  $qeury->delete('users', 1);
    * Удалить из таблицы users запись с id = 1
    */
    public function delete($table, $id)
    {   
        $this->action('DELETE', $table, ['id', '=', $id]);
        return $this->queryStatus;
    }

    private function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = ['=', '<', '>', '<=', '>=', '<>'];
            list ($field, $operator, $value) = $where;

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                $query = $this->query($sql, [$value]);
                
                // Если целью запроса было получение данных, то возвращаются данные, иначе успешность запроса true|false 
                if ($action == 'SELECT *') {
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (!empty($result)) {
                        return $result;
                    } else {
                        return false;
                    }
                }

                return $this->queryStatus;
            }
        }

        return false;
    }

    private function query($sql, $params = [])
    {
        $this->queryStatus = false;
        $this->query = $this->pdo->prepare($sql);

        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }
        $this->queryStatus = $this->query->execute();
        return $this->query;
    }

}