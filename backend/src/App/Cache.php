<?php
    namespace App\Base;

    use App\Base\Database;

    class Cache
    {
        private $db = null;
        public function __construct(Database $db)
        {
            $this->db = new Database();
        }

        public function isCached(string $hash)
        {
            $sql = "SELECT commit_hash FROM cache WHERE commit_hash = :hash";
            
            try {
                $stmt = $this->db->getConnection()->prepare($sql);

                $stmt->bindParam(':hash', $hash);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count > 0)
                    return true;
                
                return false;

            } catch (PDOException $e) {
                echo($e->getMessage());
            }
        }

        public function get(string $hash)
        {
            $sql = "SELECT commit_hash, commit_message FROM cache WHERE commit_hash = :hash";

            try {
                $stmt = $this->db->getConnection()->prepare($sql);

                $stmt->bindParam(':hash', $hash);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo ($e->getMessge());
            }
        }

        public function add(string $hash, string $message)
        {
            $sql = "INSERT INTO cache(commit_hash, commit_message) VALUES (:hash, :message)";

            try {
                $stmt = $this->db->getConnection()->prepare($sql);

                $stmt->bindParam(':hash', $hash);
                $stmt->bindParam(':message', $message);
                $stmt->execute();
            } catch (PDOException $e) {
                echo ($e->getMessage());
            }
        }
    }