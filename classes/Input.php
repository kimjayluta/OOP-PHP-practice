<?php
class Input {
    // Tig re-return kung anu method ang gamit pag submit
    public static function exists($type = 'post'){
        switch ($type){
            case 'post':
                return (!empty($_POST)) ? true: false;
                break;
            case 'get':
                return (!empty($_GET)) ? true: false;
                break;
            default:
                return false;
                break;
        }
    }
    // To get the submitted data's
    public static function get($items){
        if (isset($_POST[$items])){
            return $_POST[$items];
        } else if (isset($_GET[$items])){
            return $_GET[$items];
        }
        return '';
    }
}