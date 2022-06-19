<?php

namespace Blog;

use PDO;

class LatestPosts
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $limit
     * @return array|null
     */
    public function get(int $limit): ?array
    {
        $stmt = $this->connection->prepare('Select * from post order by published_date DESC limit ' . $limit);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}