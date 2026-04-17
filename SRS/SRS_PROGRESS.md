# 📄 SRS_PROGRESS.md

## Chức năng: Theo dõi tiến độ học tập (Learning Progress)

**Mã chức năng:** PROGRESS-01  
**Trạng thái:** Hoàn thành  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng tiến độ học tập cho phép người dùng theo dõi mức độ hoàn thành của một khóa học dựa trên số bài học đã học.

Hệ thống đảm bảo:
- Ghi nhận bài học đã hoàn thành
- Tính toán phần trăm tiến độ
- Hiển thị trực quan giúp người học theo dõi quá trình học

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động người dùng     | Phản hồi hệ thống                                  |
|------|--------------------------|----------------------------------------------------|
| 1    | Truy cập khóa học        | Hiển thị danh sách bài học                        |
| 2    | Chọn bài học             | Hiển thị nội dung bài                             |
| 3    | Hoàn thành bài học       | Lưu tiến độ vào database                          |
| 4    | Hệ thống xử lý           | Đếm số bài đã hoàn thành                          |
| 5    | Hiển thị tiến độ         | Cập nhật % tiến độ học                            |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Dữ liệu đầu vào (Input)

| Trường     | Kiểu   | Bắt buộc | Mô tả                |
|------------|--------|----------|----------------------|
| user_id    | int    | ✔        | ID người dùng        |
| lesson_id  | int    | ✔        | ID bài học           |
| course_id  | int    | ✔        | ID khóa học          |

---

### 3.2 Dữ liệu lưu trữ (Database - bảng `progress`)

| Trường       | Kiểu     | Mô tả                  |
|--------------|----------|------------------------|
| id           | int      | ID                     |
| user_id      | int      | Người học              |
| lesson_id    | int      | Bài học                |
| completed_at | datetime | Thời gian hoàn thành   |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

### 4.1 Logic tính toán
Công thức:
```
progress = (completed / total) * 100
  ```
- completed: số bài đã học  
- total: tổng số bài trong khóa học  

---

### 4.2 Dữ liệu
- Không lưu trùng (user_id + lesson_id)
- Progress chỉ tính trong cùng khóa học

---

### 4.3 Session
- Chỉ user đăng nhập mới có tiến độ
- Lấy user_id từ `$_SESSION['user']`

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp           | Xử lý                      |
|---------------------|---------------------------|
| Chưa học bài nào    | Hiển thị 0%               |
| Không có bài học    | Trả về 0%                 |
| User chưa login     | Không hiển thị tiến độ    |
| Lỗi database        | Hiển thị lỗi hệ thống     |

---

## 6. Giao diện (UI/UX)

- Hiển thị thanh tiến độ (%)
- Ví dụ:
- 25% → đang học
- 100% → hoàn thành
- Cập nhật khi người dùng học bài

---

## 7. Điều kiện thành công

- % tiến độ hiển thị đúng
- Không bị trùng dữ liệu
- Cập nhật ngay khi học xong bài
- Không lỗi khi chưa có dữ liệu
