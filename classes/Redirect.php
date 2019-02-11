<?php
class Redirect{
    public static function to($location = null){
        header('Location: ' . $location);
        exit();
    }
}