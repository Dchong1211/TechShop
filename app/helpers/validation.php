<?php
    class Validation {

        public static function email($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        public static function strongPassword($pw) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $pw);
        }

        public static function required($value) {
            return !empty(trim($value));
        }
    }
?>