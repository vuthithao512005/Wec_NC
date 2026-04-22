# 📄 SRS_ADMIN_COURSE.md

## Chức năng: Quản lý Khóa học & Danh mục (Admin Course Management)

**Mã chức năng:** ADM-01  
**Trạng thái:** Đang phát triển  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng Quản lý khóa học cho phép Quản trị viên (Admin) thực hiện các thao tác Thêm, Xem, Sửa, Xóa (CRUD) danh sách khóa học và danh mục trên hệ thống. 
Admin có thể định giá khóa học (Miễn phí hoặc Trả phí) và tải lên hình ảnh minh họa để hiển thị ra trang chủ của người dùng.

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động hệ thống / Admin             | Phản hồi hệ thống                                      |
|------|----------------------------------------|--------------------------------------------------------|
| 1    | Truy cập `/admin/courses`              | Kiểm tra quyền Admin, hiển thị danh sách khóa học      |
| 2    | Nhấn nút "Thêm khóa học mới"           | Hiển thị Form nhập liệu (Tên, mô tả, giá, ảnh...)      |
| 3    | Nhập thông tin & Upload ảnh            | Validate định dạng file ảnh và dữ liệu trống           |
| 4    | Nhấn "Lưu lại"                         | Gửi request (POST) tới Controller                      |
| 5    | Server xử lý                           | Move file ảnh vào thư mục `Uploads`, Insert vào Database |
| 6    | Thành công                             | Ghi log, tạo Session Toast "Thêm thành công", reload trang |
| 7    | Xóa/Sửa khóa học                       | Cập nhật hoặc Xóa record tương ứng trong Database      |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Dữ liệu đầu vào (Input)

| Trường      | Kiểu   | Bắt buộc | Mô tả                  |
|-------------|--------|----------|------------------------|
| title       | string | ✔        | Tên khóa học           |
| description | text   | ✔        | Giới thiệu khóa học    |
| price       | int    | ✔        | Nhập 0 nếu là Free     |
| image       | file   | ✔        | File ảnh (jpg, png)    |
| category_id | int    | ✔        | Chọn từ Select Box     |

---

### 3.2 Dữ liệu lưu trữ (Database - bảng `courses`, `categories`)

| Trường      | Kiểu   | Mô tả                  |
|-------------|--------|------------------------|
| id          | int    | Khóa chính (Auto_Inc)  |
| title       | string | Tiêu đề khóa học       |
| price       | int    | Giá tiền               |
| image       | string | Đường dẫn lưu ảnh      |
| category_id | int    | Khóa ngoại tới categories |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

### 4.1 Phân quyền
- Middleware kiểm tra `$_SESSION['user']['role'] === 'admin'` trước khi load trang.

### 4.2 Xử lý Upload File
- Ràng buộc đuôi mở rộng: `['jpg', 'jpeg', 'png', 'webp']`.
- Giới hạn dung lượng file ảnh < 2MB.
- Đổi tên file ngẫu nhiên trước khi lưu để tránh trùng lặp.

### 4.3 Bảo mật
- Sử dụng PDO Prepared Statements để chống SQL Injection khi Thêm/Sửa.

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp        | Xử lý                           |
|-------------------|---------------------------------|
| Upload file không phải ảnh | Báo lỗi, từ chối thêm mới |
| Xóa khóa học đã có người mua | Chặn xóa cứng, hoặc cảnh báo rủi ro mất dữ liệu tiến độ của học viên |
| Bỏ trống trường bắt buộc | Validate HTML5 + Báo lỗi từ server |

---

## 6. Giao diện (UI/UX)

- Bảng danh sách khóa học: Hiển thị dạng Table có phân trang, Thumbnail ảnh nhỏ.
- Nút Action: Sửa (Vàng), Xóa (Đỏ - có confirm).
- Form Thêm mới: Dùng Modal hoặc Trang riêng, có preview ảnh tải lên.

---

## 7. Điều kiện thành công

- Thông tin khóa học được lưu chính xác vào CSDL.
- Ảnh được lưu đúng vào thư mục vật lý.
- Khóa học mới tự động hiển thị ra trang chủ của User.
