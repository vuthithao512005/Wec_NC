# 📄 SRS_COURSE.md

## Chức năng: Quản lý khóa học (Course Management)

**Mã chức năng:** COURSE-01  
**Trạng thái:** Hoàn thành  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng quản lý khóa học cho phép admin tạo, chỉnh sửa và xóa khóa học.

Hệ thống đảm bảo:
- Hiển thị danh sách khóa học
- Phân loại khóa học (Free / Paid)
- Quản lý thông tin khóa học

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động admin        | Phản hồi hệ thống        |
|------|------------------------|--------------------------|
| 1    | Truy cập `/admin`      | Hiển thị dashboard       |
| 2    | Vào quản lý khóa học   | Hiển thị danh sách       |
| 3    | Thêm / sửa / xóa       | Gửi request              |
| 4    | Hệ thống xử lý         | Cập nhật database        |
| 5    | Thành công             | Hiển thị thông báo       |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Input

| Trường      | Kiểu   | Bắt buộc | Mô tả        |
|-------------|--------|----------|--------------|
| title       | string | ✔        | Tên khóa học |
| description | text   | ✔        | Mô tả        |
| price       | int    | ✔        | Giá          |
| image       | string | ✔        | Hình ảnh     |

---

### 3.2 Database (`courses`)

| Trường      | Kiểu   | Mô tả        |
|-------------|--------|--------------|
| id          | int    | ID           |
| title       | string | Tên khóa học |
| description | text   | Mô tả        |
| price       | int    | Giá          |
| image       | string | Hình ảnh     |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

- Chỉ admin được phép thao tác
- Validate dữ liệu đầu vào
- Không cho nhập giá âm

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp | Xử lý |
|-----------|------|
| Thiếu dữ liệu | Báo lỗi |
| Giá âm | Không cho lưu |
| ID không tồn tại | Báo lỗi |

---

## 6. Giao diện (UI/UX)

- Danh sách khóa học dạng table
- Form thêm / sửa
- Nút CRUD

---

## 7. Điều kiện thành công

- CRUD hoạt động đúng
- Hiển thị chính xác dữ liệu
