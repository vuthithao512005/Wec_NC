# 📄 SRS_AUTH_REGISTER.md

## Chức năng: Đăng ký tài khoản (User Authentication - Register)

**Mã chức năng:** AUTH-02  
**Trạng thái:** Đã hoàn thành
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng đăng ký cho phép người dùng tạo tài khoản mới để sử dụng hệ thống E-Learning.

Người dùng cần nhập:
- Tên
- Email
- Mật khẩu

Hệ thống sẽ kiểm tra dữ liệu và lưu vào database nếu hợp lệ.

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động người dùng     | Phản hồi hệ thống                          |
|------|--------------------------|--------------------------------------------|
| 1    | Truy cập `/register`     | Hiển thị form đăng ký                      |
| 2    | Nhập thông tin           | Validate dữ liệu                           |
| 3    | Nhấn "Đăng ký"           | Gửi request POST                           |
| 4    | Hệ thống xử lý           | Kiểm tra email tồn tại                     |
| 5    | Đăng ký thành công       | Lưu DB + chuyển trang login                |
| 6    | Đăng ký thất bại         | Hiển thị lỗi                               |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1. Dữ liệu đầu vào (Input Fields)

| Trường            | Kiểu dữ liệu | Bắt buộc | Mô tả                    |
|-------------------|--------------|----------|--------------------------|
| name              | string       | ✔        | Tên người dùng           |
| email             | string       | ✔        | Email hợp lệ             |
| password          | string       | ✔        | ≥ 6 ký tự                |
| confirm_password  | string       | ✔        | Phải trùng password      |

---

### 3.2. Dữ liệu lưu trữ (Database - bảng `users`)

| Trường     | Kiểu     | Mô tả              |
|------------|----------|--------------------|
| id         | int      | ID người dùng      |
| name       | string   | Tên                |
| email      | string   | Duy nhất           |
| password   | string   | Mã hóa             |
| role       | string   | user/admin         |
| created_at | datetime | Ngày tạo           |

---

## 4. Ràng buộc kỹ thuật & Bảo mật (Technical Constraints & Security)

### 4.1 Xác thực dữ liệu
- Không để trống field
- Email đúng format
- Password ≥ 6 ký tự

### 4.2 Bảo mật mật khẩu
- Sử dụng `password_hash()`
- Không lưu plaintext

### 4.3 Kiểm tra trùng
- Email phải unique

---

## 5. Trường hợp ngoại lệ & Xử lý lỗi (Edge Cases)

| Trường hợp            | Xử lý                         |
|----------------------|-------------------------------|
| Thiếu dữ liệu        | Báo lỗi                       |
| Email sai định dạng  | Báo lỗi                       |
| Email đã tồn tại     | Báo "Email đã tồn tại"        |
| Password không khớp  | Báo lỗi                       |
| Server lỗi           | Báo lỗi hệ thống              |

---

## 6. Giao diện (UI/UX)

- Form gồm:
  - Name
  - Email
  - Password
  - Confirm Password  
- Nút "Đăng ký"
- Hiển thị lỗi dưới input
- Responsive

---

## 7. Điều kiện thành công (Acceptance Criteria)

- Đăng ký thành công → chuyển login  
- Không tạo user trùng email  
- Password được mã hóa  
- Validate hoạt động đúng  

---
