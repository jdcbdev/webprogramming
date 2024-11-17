<?php

require_once 'database.php';

class Stocks{
    public $product_id = '';
    public $quantity = '';
    public $status = '';
    public $reason = '';

    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    function add(){
        $sql = "INSERT INTO stocks (product_id, quantity, status, reason) VALUES (:product_id, :quantity, :status, :reason);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':product_id', $this->product_id);
        $query->bindParam(':quantity', $this->quantity);
        $query->bindParam(':status', $this->status);
        $query->bindParam(':reason', $this->reason);

        return $query->execute();
    }

    function getAvailableStocks($id){
        $sql = "SELECT sum(if(status='in', quantity, 0)) - sum(if(status='out', quantity, 0)) as available_stock FROM stocks WHERE product_id=:id GROUP BY product_id;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);

        $data = null;

        if ($query->execute()) {
            $data = $query->fetchColumn();
        }

        return $data;
    }
}