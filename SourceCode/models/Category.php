<?php
class Category {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * Lấy tất cả danh mục KÈM THEO số lượng khóa học bên trong
     */
    public function all() {
        $sql = "SELECT categories.*, COUNT(courses.id) as course_count 
                FROM categories 
                LEFT JOIN courses ON categories.id = courses.category_id 
                GROUP BY categories.id 
                ORDER BY categories.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm 1 danh mục theo ID
     */
    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo danh mục mới
     */
    public function create($name, $description = '') {
        $sql = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name, 
            ':description' => $description
        ]);
    }

    /**
     * Cập nhật danh mục
     */
    public function update($id, $name, $description = '') {
        $sql = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name, 
            ':description' => $description, 
            ':id' => $id
        ]);
    }

    /**
     * Xóa danh mục
     */
    public function delete($id) {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}