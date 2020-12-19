<?php

class Validator
{
    private $passed = false, $errors = [], $db = null;

    public function __construct()
    {
        // для проверки записи на уникальность нужен доступ к БД
        $this->db = require_once '../dbstart.php';
    }

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

    public function errors()
    {
        return $this->errors;
    }

    public function errors_ul_html()
    {
        $errors_list = '';

        foreach ($this->errors as $error) {
            $errors_list .= '<li>' . $error . '</li>';
        }

        return '<ul>' . $errors_list . '</ul>';
    }

    public function passed()
    {
        return $this->passed;
    }

    private function addError($error)
    {
        $this->errors[] = $error;
    }
}