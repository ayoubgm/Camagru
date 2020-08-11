<?php
    class       GalleryModel extends DB
    {
        public function         addImage ( $data )
        {
            $query = "INSERT INTO gallery (userid, src) VALUES (?, ?)";
            $stt = $this->connect()->prepare($query);
            return $stt->execute([
                $data['id'],
                $data['src']
            ]);
        }

        public function         userGallery ( $userid )
        {
            $query = 'SELECT * FROM `gallery` WHERE userid = ? ORDER BY createdat DESC';
            $stt = $this->connect()->prepare($query);
            $stt->execute([ $userid ]);
            return $stt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function         getCountImages ()
        {
            $query = 'SELECT count(*) FROM `gallery`';
            $stt = $this->connect()->prepare($query);
            $stt->execute();
            return $stt->rowCount();
        }

        public function         getAllEditedImages ( $depart = 0, $imagePerPage = 6 )
        {
            $query = 'SELECT g.id, g.src, g.userid, u.username, g.createdat FROM `gallery` g INNER JOIN `users` u ON g.userid = u.id ORDER BY createdat DESC LIMIT '.$depart.','.$imagePerPage;
            $stt = $this->connect()->prepare($query);
            $stt->execute();
            return $stt->fetchAll(PDO::FETCH_ASSOC);

        }
    }
?>