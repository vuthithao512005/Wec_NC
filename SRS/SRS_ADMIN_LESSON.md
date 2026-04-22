# 📄 SRS_ADMIN_LESSON.md

## Chức năng: Quản lý Bài học & Nội dung (Admin Lesson Management)

**Mã chức năng:** ADM-02  
**Trạng thái:** Đang phát triển  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Cho phép Admin thêm mới, sắp xếp và biên tập nội dung bài giảng cho từng khóa học cụ thể. Nội dung có thể bao gồm văn bản, mã nhúng video (YouTube/Drive), và thứ tự sắp xếp bài học trong hệ thống trình phát video của User.

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động Admin                        | Phản hồi hệ thống                                      |
|------|----------------------------------------|--------------------------------------------------------|
| 1    | Chọn khóa học cần thêm bài             | Hiển thị danh sách các bài học hiện có của khóa đó     |
| 2    | Nhấn "Thêm bài học"                    | Mở form soạn thảo bài học                              |
| 3    | Nhập tiêu đề, nội dung, video URL, STT | Validate dữ liệu                                       |
| 4    | Nhấn "Lưu bài học"                     | Gửi dữ liệu qua Controller                             |
| 5    | Server xử lý                           | Thêm record vào bảng `lessons`                         |
| 6    | Cập nhật STT (Position)                | Cập nhật lại thứ tự hiển thị của các bài học           |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Dữ liệu đầu vào (Input)

| Trường      | Kiểu   | Bắt buộc | Mô tả                  |
|-------------|--------|----------|------------------------|
| course_id   | int    | ✔        | Khóa học chứa bài này  |
| title       | string | ✔        | Tên bài học            |
| content     | text   |          | Văn bản mô tả          |
| video_url   | string |          | Link nhúng video       |
| position    | int    | ✔        | Thứ tự bài học (1, 2..)|

---

### 3.2 Dữ liệu lưu trữ (Database - bảng `lessons`)

| Trường      | Kiểu   | Mô tả                  |
|-------------|--------|------------------------|
| id          | int    | Khóa chính             |
| course_id   | int    | Khóa ngoại             |
| title       | string | Tiêu đề bài            |
| video_url   | string | Link video             |
| position    | int    | Sắp xếp tăng dần ASC   |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

### 4.1 Bảo mật nội dung
- Lọc thẻ HTML nguy hiểm (XSS) từ thẻ `content` nếu dùng Text Editor (như CKEditor).

### 4.2 Toàn vẹn dữ liệu
- Khi xóa một Khóa học, tất cả Bài học (Lessons) thuộc về nó cũng phải tự động bị xóa (ON DELETE CASCADE).

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp        | Xử lý                           |
|-------------------|---------------------------------|
| Nhập trùng `position` | Tự động đẩy lùi các bài khác hoặc cảnh báo lỗi |
| Xóa bài học       | Xác nhận kỹ, tự dồn số thứ tự các bài học còn lại |
| Link video sai định dạng| Cảnh báo định dạng link không hỗ trợ |

---

## 6. Giao diện (UI/UX)

- View theo dạng danh sách dạng cây hoặc Accordion (Sổ xuống).
- Tích hợp trình soạn thảo WYSIWYG cho phần `content`.

---

## 7. Điều kiện thành công

- Bài học lưu đúng vào khóa học được chỉ định.
- Giao diện học viên (User) tự động cập nhật danh sách phát (Playlist) theo đúng thứ tự `position`.
