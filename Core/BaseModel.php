<?php
namespace Core;

abstract class BaseModel
{
    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @var bool
     */
    public $hasError = false;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    private $errors = [];

    public function __construct()
    {
        $this->connection = DbConnection::getConnection();
    }

    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name]['value'];
        } else {
            throw new \Exception("Property '$name' not found in class " . __CLASS__);
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name]['value'] = $value;
        } else {
            throw new \Exception("Property '$name' not found in class " . __CLASS__);
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getLabel($attribute)
    {
        $label = '';

        if (isset($this->attributes[$attribute])) {
            $label = $this->attributes[$attribute]['name'];
        }

        return $label;
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getError($attribute)
    {
        $error = '';

        if (isset($this->errors[$attribute])) {
            $error = $this->errors[$attribute];
        }

        return $error;
    }

    /**
     * @param string $attribute
     * @param string $errorMessage
     */
    public function setError($attribute, $errorMessage)
    {
        $this->errors[$attribute] = $errorMessage;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $fields
     * @return bool
     */
    public function validate($fields = [])
    {
        $nameSpace = 'Core\Validation\\';
        $this->hasError = false;

        if ($fields) {
            $attributes = array_intersect_key($this->attributes, array_flip($fields));
        } else {
            $attributes = $this->attributes;
        }

        foreach ($attributes as $attrName => $attribute) {

            $validationRules = $attribute['rules'];
            $value = $attribute['value'];

            foreach ($validationRules as $validatorName => $options) {
                $className = $nameSpace . ucfirst($validatorName);
                $validator = new $className;

                if (!$validator->validate($value, $options)) {
                    $errorMessage = $this->getLabel($attrName) . ' ' . $validator->getErrorMessage();
                    $this->setError($attrName, $errorMessage);
                    $this->hasError = true;
                    break;
                }
            }
        }

        return !$this->hasError;
    }

    /**
     * @param string $sql
     * @param array $params
     * @param int $fetchMode
     * @return mixed
     */
    protected function find($sql, $params = [], $fetchMode = \PDO::FETCH_ASSOC)
    {
        $sth = $this->connection->prepare($sql);
        $sth->execute($params);

        return $sth->fetch($fetchMode);
    }

    /**
     * @param string $sql
     * @param array $params
     * @param int $fetchMode
     * @return array
     */
    protected function findAll($sql, $params = [], $fetchMode = \PDO::FETCH_ASSOC)
    {
        $sth = $this->connection->prepare($sql);
        $sth->execute($params);

        return $sth->fetchAll($fetchMode);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return bool
     */
    protected function query($sql, $params = [])
    {
        $sth = $this->connection->prepare($sql);
        return $sth->execute($params);
    }

    /**
     * @param arrray $criteria
     * @return string
     */
    protected function createQueryFromCriteria($criteria)
    {
        $select = $criteria['select'] . ' FROM ' . $criteria['from'];

        $where = '';
        if (isset($criteria['where'])) {
            $where = "WHERE " . implode(' ', $criteria['where']);
        }

        $joins = '';
        if (isset($criteria['join'])) {
            $joins = implode(' ', $criteria['join']);
        }

        $order = '';
        if (isset($criteria['order'])) {
            $order = 'ORDER BY ' . $criteria['order'];
        }

        $limit = '';
        if (isset($criteria['limit'])) {
            $limit = $criteria['limit'];
        }

        $offset = '';
        if (isset($criteria['offset'])) {
            $offset = $criteria['offset'];
        } elseif ($limit) {
            $offset = 0;
        }

        $limitStr = 'LIMIT ' . $offset . ', ' . $limit;

        return $select . ' ' . $where . ' ' . $joins . ' ' . $order . ' ' . $limitStr;
    }
}