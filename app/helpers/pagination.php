<?php

class Pagination {

    /**
     * Execute pagination query
     */
    public static function query(mysqli $conn, string $sql, int $page = 1, int $limit = 10)
    {
        $page   = max(1, (int)$page);
        $limit  = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        // Count total rows
        $count_sql = "SELECT COUNT(*) AS total FROM ($sql) AS t";
        $count_res = $conn->query($count_sql);
        $total     = (int)$count_res->fetch_assoc()['total'];

        // Fetch data
        $data_sql  = $sql . " LIMIT $limit OFFSET $offset";
        $data      = $conn->query($data_sql)->fetch_all(MYSQLI_ASSOC);

        $totalPages = max(1, ceil($total / $limit));

        return [
            "meta" => [
                "page"        => $page,
                "limit"       => $limit,
                "total"       => $total,
                "totalPages"  => $totalPages,
                "hasNext"     => $page < $totalPages,
                "hasPrev"     => $page > 1
            ],
            "data" => $data
        ];
    }
}
