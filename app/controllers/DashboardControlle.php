<?php
require_once __DIR__ . '/../models/dashboard.php';
require_once __DIR__ . '/../helpers/auth.php';

class DashboardController {
    private $model;

    public function __construct() {
        requireAdmin();
        $this->model = new Dashboard();
    }

    // Tổng quan
    public function summary() {
        return [
            "success" => true,
            "data" => [
                "users"    => $this->model->countUsers(),
                "products" => $this->model->countProducts(),
                "orders"   => $this->model->countOrders(),
                "revenue"  => $this->model->totalRevenue(),
            ]
        ];
    }

    // Thống kê theo tháng + % tăng/giảm
    public function monthly() {
        $year  = date("Y");
        $month = date("n");

        // Tháng trước
        $prevMonth = $month - 1;
        $prevYear  = $year;

        if ($prevMonth == 0) {
            $prevMonth = 12;
            $prevYear -= 1;
        }

        $m = $this->model;

        $current = [
            "users"    => $m->countUsersByMonth($year, $month),
            "products" => $m->countProductsByMonth($year, $month),
            "orders"   => $m->countOrdersByMonth($year, $month),
            "revenue"  => $m->revenueByMonth($year, $month),
        ];

        $previous = [
            "users"    => $m->countUsersByMonth($prevYear, $prevMonth),
            "products" => $m->countProductsByMonth($prevYear, $prevMonth),
            "orders"   => $m->countOrdersByMonth($prevYear, $prevMonth),
            "revenue"  => $m->revenueByMonth($prevYear, $prevMonth),
        ];

        $change = [
            "users"    => $m->percentChange($current["users"],    $previous["users"]),
            "products" => $m->percentChange($current["products"], $previous["products"]),
            "orders"   => $m->percentChange($current["orders"],   $previous["orders"]),
            "revenue"  => $m->percentChange($current["revenue"],  $previous["revenue"]),
        ];

        return [
            "success"  => true,
            "current"  => $current,
            "previous" => $previous,
            "change"   => $change
        ];
    }
}
