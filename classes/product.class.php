<?php

require_once 'database.php';

class Product {
    public $id = '';
    public $code = '';
    public $name = '';
    public $category_id = '';
    public $price = '';

    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function add() {
        $sql = "INSERT INTO product (code, name, category_id, price) VALUES (:code, :name, :category_id, :price);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':code', $this->code);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':category_id', $this->category_id);
        $query->bindParam(':price', $this->price);
        return $query->execute();
    }

    function showAll($keyword='', $category='') {
        $sql = "SELECT i.*, p.*, c.name as category_name, SUM(IF(s.status='in', quantity, 0)) as stock_in, SUM(IF(s.status='out', quantity, 0)) as stock_out FROM product p INNER JOIN category c ON p.category_id = c.id LEFT JOIN product_image i ON p.id = i.product_id AND i.image_role = 'main' LEFT JOIN stocks s ON p.id = s.product_id WHERE (p.code LIKE CONCAT('%', :keyword, '%') OR p.name LIKE CONCAT('%', :keyword, '%')) AND (c.id LIKE CONCAT('%', :category, '%')) GROUP BY p.id ORDER BY p.name ASC;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);
        $query->bindParam(':category', $category);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function edit() {
        $sql = "UPDATE product SET code = :code, name = :name, category_id = :category_id, price = :price WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':code', $this->code);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':category_id', $this->category_id);
        $query->bindParam(':price', $this->price);
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    function fetchRecord($recordID) {
        $sql = "SELECT * FROM product WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function delete($recordID) {
        $sql = "DELETE FROM product WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        return $query->execute();
    }

    function codeExists($code, $excludeID = null) {
        $sql = "SELECT COUNT(*) FROM product WHERE code = :code";
        if ($excludeID) {
            $sql .= " AND id != :excludeID";
        }
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':code', $code);
        if ($excludeID) {
            $query->bindParam(':excludeID', $excludeID);
        }
        $query->execute();
        $count = $query->fetchColumn();
        return $count > 0;
    }

    public function fetchCategory() {
        $sql = "SELECT * FROM category ORDER BY name ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }
}