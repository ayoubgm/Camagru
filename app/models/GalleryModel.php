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
    }
?>