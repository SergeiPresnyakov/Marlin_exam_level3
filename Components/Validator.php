<?php

class Validator
{
    private $passed = false, $errors = [], $db = null;

    public function __construct($pdo)
    {
        // для проверки записи на уникальность нужен доступ к БД
        $this->db = $pdo;
    }

    /**
     * Проверка введенных данных в форме на соответствие правилам
     * required - поле должно быть заполнено
     * min - введённые данные не должны быть короче чем это значение
     * max - не должно быть длиннее чем это значение
     * matches - данные должны совпадать с данными в указанном поле
     * unique - эта запись должна быть уникальна в БД
     * email - данные должны соответствовать формату email
     * 
     * Arguments:
     *      source - Array
     *      items - Array
     * 
     * Returns: Validator object
     * 
     * Example:
     *      $validation = new Validator;
     *      $validation->check($_POST, [
     *          'email' => [
     *              'required' => true,
     *              'email' => true,
     *              'unique' => true
     *          ],
     *          'password' => [
     *              'required' => true,
     *              'min' => 5
     *          ],
     *          'password_confirm' => [
     *              'matches' => 'password'
     *          ]
     * ]);
     */
    public function check($source, $items = [])
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = $source[$item];

                /* Если поле не заполнено и обязательно к заполнению,
                добавляем соответствующую ошибку и дальше его не проверяем */
                if ($rule == 'required' && empty($value)) {
                    $this->addError("{$item} is required");


                /* проверка непустых полей */
                } else if (!empty($value)) {
                    switch ($rule) {

                        case 'min':
                            if (mb_strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                        break;

                        case 'max':
                            if (mb_strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }
                        break;

                        case 'matches':
                            if ($value !== $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}.");
                            }
                        break;

                        case 'unique':
                            $check = $this->db->get($rule_value, [$item, '=', $value]);
                            if ($check) {
                                $this->addError("{$item} already exists.");
                            }
                        break;

                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$item} is not an email.");
                            }
                        break;
                    }
                }
            }
        }

        /* 
        Если массив ошибок пуст,
        значит валидация пройдена 
        */
        if (empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    /**
     * Возвращает список ошибок валидации
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Возвращает ошибки валидации в виде
     * маркированного списка (HTML код)
     * 
     * Returns:
     *      String (HTML code)
     */
    public function errors_ul_html()
    {
        $errors_list = '';

        foreach ($this->errors as $error) {
            $errors_list .= '<li>' . $error . '</li>';
        }

        return '<ul>' . $errors_list . '</ul>';
    }

    /**
     * Пройдена ли валидация
     * 
     * Returns: bool
     */
    public function passed()
    {
        return $this->passed;
    }

    private function addError($error)
    {
        $this->errors[] = $error;
    }
}