<?php

class Pagination {

    public static function query(mysqli $conn, string $sql, int $page = 1, int $limit = 10)
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $count_sql = "SELECT COUNT(*) AS total FROM ($sql) AS t";
        $count_res = $conn->query($count_sql)->fetch_assoc();
        $total = (int)$count_res['total'];

        $data_sql = $sql . " LIMIT $limit OFFSET $offset";
        $data = $conn->query($data_sql)->fetch_all(MYSQLI_ASSOC);

        return [
            "meta" => [
                "page"        => $page,
                "limit"       => $limit,
                "total"       => $total,
                "totalPages"  => ceil($total / $limit),
                "hasNext"     => $page < ceil($total / $limit),
                "hasPrev"     => $page > 1
            ],
            "data" => $data
        ];
    }
}
