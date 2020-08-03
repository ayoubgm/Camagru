<?php
    class User extends DB {

        public function     getAll()
        {
            $data = $this->query("SELECT * FROM `users` ORDER BY `createdat` DESC", []);
            return json_encode($data);
        }

    }
?>