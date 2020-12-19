<?php

class Flash
{
    /**
     * Задать Flash-сообщение.
     * Arguments:
     *      theme - String (success, warning, danger)
     *      message - String
     * 
     *  Returns: null
     * 
     *  Example:
     *      Flash::set('success', 'Register successful!');
     */
    public static function set($theme, $message = '')
    {
        $_SESSION[$theme] = $message;
    }

    /**
     * Задано ли Flash-сообщение с соответствующей темой
     * Arguments:
     *      theme - String (success, warning, danger)
     * 
     * Returns: bool
     */
    public static function exists($theme)
    {
        return (isset($_SESSION[$theme]) && $_SESSION[$theme] !== '');
    }

    /**
     * Возвращает Flash-сообщение заданной темы
     * 
     * Arguments:
     *      theme - String (success, warning, danger)
     * 
     *  Example:
     *      Flash::display('success');
     * 
     *  Case using Bootstrap:
     * 
     *     <?php if (Flash::exists('success')):?>
     *          <div class="alert alert-success">
     *              <?php echo Flash::display('success');?>
     *          </div>
     *     <?php endif;?>
     */
    public static function display($theme)
    {
        if (self::exists($theme)) {
            $flash_message = $_SESSION[$theme];
            unset($_SESSION[$theme]);
            return $flash_message;
        }
    }
}