<?php
class Course {
    private $conn;

    public function __construct($db){
        $this->conn = $db; // $db là instance của PDO
    }

    // ============================================================
    // TRUY VẤN DỮ LIỆU (READ)
    // ============================================================

    public function all($keyword = null) {
        $sql = "SELECT courses.*, categories.name as category_name 
                FROM courses 
                LEFT JOIN categories ON courses.category_id = categories.id";
        
        if ($keyword) {
            $sql .= " WHERE courses.title LIKE :keyword OR courses.description LIKE :keyword";
            $sql .= " ORDER BY courses.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => "%$keyword%"]);
        } else {
            $sql .= " ORDER BY courses.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithCategory($category_id = null){
        if($category_id){
            $stmt = $this->conn->prepare("
                SELECT courses.*, categories.name as category_name
                FROM courses
                LEFT JOIN categories ON courses.category_id = categories.id
                WHERE courses.category_id = ?
                ORDER BY courses.id DESC
            ");
            $stmt->execute([$category_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $this->all();
    }

    public function find($id){
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get($id){
        return $this->find($id);
    }

    // ============================================================
    // THAO TÁC DỮ LIỆU (CREATE - UPDATE - DELETE)
    // ============================================================

    /**
     * TẠO KHÓA HỌC: Đã thêm category_id và price
     */
    public function create($title, $category_id, $price, $desc, $image){
        $stmt = $this->conn->prepare("
            INSERT INTO courses(title, category_id, price, description, image)
            VALUES(:title, :category_id, :price, :desc, :image)
        ");
        return $stmt->execute([
            ':title'       => $title,
            ':category_id' => $category_id,
            ':price'       => $price,
            ':desc'        => $desc,
            ':image'       => $image
        ]);
    }

    /**
     * CẬP NHẬT: Đã thêm category_id và price
     */
    public function update($id, $title, $category_id, $price, $desc, $image = null){
        if($image){
            $sql = "UPDATE courses SET title = :title, category_id = :category_id, price = :price, description = :desc, image = :image WHERE id = :id";
            $params = [
                ':title'       => $title,
                ':category_id' => $category_id,
                ':price'       => $price,
                ':desc'        => $desc,
                ':image'       => $image,
                ':id'          => $id
            ];
        } else {
            $sql = "UPDATE courses SET title = :title, category_id = :category_id, price = :price, description = :desc WHERE id = :id";
            $params = [
                ':title'       => $title,
                ':category_id' => $category_id,
                ':price'       => $price,
                ':desc'        => $desc,
                ':id'          => $id
            ];
        }
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Số lần mua khóa học
    public function getTopSellingCourses($limit = 5) {
        // 1. Ép kiểu về số nguyên để đảm bảo an toàn
        $limit = (int)$limit; 

        // 2. Truyền thẳng biến vào chuỗi SQL (Lưu ý: Không có dấu ? ở đây)
        $sql = "SELECT c.title, COUNT(oi.id) as total_sales 
                FROM courses c
                LEFT JOIN order_items oi ON c.id = oi.course_id
                GROUP BY c.id
                ORDER BY total_sales DESC
                LIMIT $limit"; 
                
        $stmt = $this->conn->prepare($sql);

        // 3. QUAN TRỌNG: Để trống execute() vì không có dấu ? nào trong SQL
        $stmt->execute(); 
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // TIỆN ÍCH BỔ SUNG (EXTRAS)
    // ============================================================

    public function getCategories(){
        return $this->conn->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLessons($course_id){
        $stmt = $this->conn->prepare("SELECT * FROM lessons WHERE course_id = :course_id ORDER BY id ASC");
        $stmt->execute([':course_id' => $course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(){
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM courses");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Tìm kiếm
    public function searchCourses($keyword) {
        // Lấy các khóa học có tiêu đề chứa từ khóa
        $sql = "SELECT id, title, price, image FROM courses WHERE title LIKE ?";
        $stmt = $this->db->prepare($sql);
        
        $searchTerm = "%" . $keyword . "%";
        $stmt->execute([$searchTerm]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}