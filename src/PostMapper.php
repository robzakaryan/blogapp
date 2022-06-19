<?php

namespace Blog;

use Exception;
use PDO;

class PostMapper
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    /**
     * @param string $urlKey
     * @return array|null
     */
    public function getByUrlKey(string $urlKey): ?array
    {
        $stmt = $this->connection->prepare('select * from post where url_key = :url_key');
        $stmt->execute([
            'url_key' => $urlKey
        ]);
        $result = $stmt->fetchAll();
        return array_shift($result);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param string $direction
     * @return array|null
     * @throws Exception
     */
    public function getList(int $page = 1, int $limit = 2, string $direction = 'DESC'): ?array
    {
        if(!in_array($direction, ['ASC', 'DESC'])){
            throw new Exception('The direction is wrong');
        }
        $start = ($page - 1) * $limit;
        $stmt = $this->connection->prepare('select * from post order by published_date ' . $direction . ' limit '.$start .', '.$limit);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCount(): int
    {
        $stmt = $this->connection->prepare('select count(post_id) as total from post');
        $stmt->execute();
        return (int)($stmt->fetchColumn()??0);
    }
}