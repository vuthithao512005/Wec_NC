# 📄 SRS_LOGIN.md

## Chức năng: Đăng nhập hệ thống (User Authentication - Login)

**Mã chức năng:** AUTH-01  
**Trạng thái:** Hoàn thành  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng đăng nhập cho phép người dùng (Học viên hoặc Quản trị viên) truy cập vào hệ thống E-Learning bằng tài khoản đã đăng ký trước đó.

Hệ thống đảm bảo:
- Xác thực thông tin chính xác
- Bảo mật mật khẩu người dùng
- Phân quyền theo vai trò (admin / user)

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động người dùng   | Phản hồi hệ thống                                      |
|------|------------------------|--------------------------------------------------------|
| 1    | Truy cập `/login`      | Hiển thị form đăng nhập                                |
| 2    | Nhập Email, Password   | Validate dữ liệu                                       |
| 3    | Nhấn "Đăng nhập"       | Gửi request tới server                                 |
| 4    | Server xử lý           | Kiểm tra email + password (password_verify)            |
| 5    | Thành công             | Tạo session, chuyển hướng trang phù hợp                |
| 6    | Thất bại               | Hiển thị lỗi                                           |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Dữ liệu đầu vào (Input)

| Trường   | Kiểu   | Bắt buộc | Mô tả                  |
|----------|--------|----------|------------------------|
| email    | string | ✔        | Email hợp lệ           |
| password | string | ✔        | >= 6 ký tự             |

---

### 3.2 Dữ liệu lưu trữ (Database - bảng `users`)

| Trường   | Kiểu   | Mô tả            |
|----------|--------|------------------|
| id       | int    | ID user          |
| name     | string | Tên              |
| email    | string | Unique           |
| password | string | Hash bcrypt      |
| role     | string | admin / user     |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

### 4.1 Xác thực
- Sử dụng `password_hash()` và `password_verify()`

### 4.2 Session
- Lưu thông tin user vào `$_SESSION['user']`
- Regenerate session sau login

### 4.3 Bảo mật
- Không lưu password dạng plaintext
- Validate input cả client + server

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp        | Xử lý                      |
|------------------|---------------------------|
| Email sai        | Báo lỗi                   |
| Password sai     | Báo lỗi                   |
| User không tồn tại | Báo lỗi                  |
| Chưa nhập dữ liệu | Không submit              |

---

## 6. Giao diện (UI/UX)

- Form gồm:
  - Email
  - Password
- Nút đăng nhập
- Hiển thị lỗi dưới input
- Responsive

---

## 7. Điều kiện thành công

- Đăng nhập đúng → chuyển trang:
  - Admin → `/admin`
  - User → `/courses`
- Sai → hiển thị lỗi
