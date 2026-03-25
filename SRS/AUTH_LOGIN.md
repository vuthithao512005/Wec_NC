# Software Requirement Specification (SRS)
## Chức năng: Đăng nhập hệ thống (User Authentication - Login)

**Mã chức năng:** AUTH-01  
**Trạng thái:** Draft / Review  
**Người soạn thảo:** [Thảo]  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)
- Chức năng đăng nhập cho phép người dùng (Học viên/Quản trị viên) truy cập vào hệ thống E-Learning bằng tài khoản đã đăng ký. 
- Hệ thống đảm bảo xác thực an toàn, bảo vệ dữ liệu cá nhân và quyền truy cập hệ thống.

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động người dùng   | Phản hồi hệ thống                                      |
|------|------------------------|--------------------------------------------------------|
| 1    | Truy cập URL `/login`  | Hiển thị form đăng nhập (Email, Password, Remember Me) |
| 2    | Nhập Email và Password | Kiểm tra dữ liệu đầu vào (client + server)             |
| 3    | Nhấn nút "Login"       | Gửi request đến server                                 |
| 4    | Hệ thống xử lý         | So khớp email và password (đã hash)                    |
| 5    | Đăng nhập thành công   | Tạo session, chuyển hướng dashboard                    |
| 6    | Đăng nhập thất bại     | Hiển thị thông báo lỗi                                 |

---

## 3. Yêu cầu dữ liệu (Data Requirements)
### 3.1. Dữ liệu đầu vào (Input Fields)

| Trường      | Kiểu dữ liệu | Bắt buộc | Mô tả                  |
|-------------|--------------|----------|------------------------|
| Email       | string       | ✔        | Định dạng email hợp lệ |
| Password    | string       | ✔        | Tối thiểu 8 ký tự      |
| Remember Me | boolean      | ✖        | Ghi nhớ đăng nhập      |

---

### 3.2. Dữ liệu lưu trữ (Database - bảng `users`)

| Trường     | Kiểu     | Mô tả              |
|------------|----------|--------------------|
| id         | int      | ID người dùng      |
| email      | string   | Duy nhất           |
| password   | string   | Mã hóa             |
| role       | string   | user/admin         |
| status     | boolean  | active/inactive    |
| last_login | datetime | Lần đăng nhập cuối |
| login_ip   | string   | IP đăng nhập       |

---

## 4. Ràng buộc kỹ thuật & Bảo mật (Technical Constraints & Security)
### 4.1 Giao thức
- Bắt buộc sử dụng **HTTPS**

### 4.2 Xác thực & mã hóa
- Mật khẩu được mã hóa bằng **Bcrypt / Argon2**
- Không lưu password dạng plaintext

### 4.3 Bảo mật form
- Sử dụng **CSRF Token**
- Validate cả client-side và server-side
  
### 4.4 Session
- Sử dụng PHP Session
- Timeout sau 30 phút không hoạt động

### 4.5 Chống tấn công
- Giới hạn login sai: 5 lần / phút
- Khóa tài khoản tạm thời nếu vượt quá

---

## 5. Trường hợp ngoại lệ & Xử lý lỗi (Edge Cases)

| **Trường hợp**      | **Xử lý**                          |
|---------------------|------ -----------------------------|
| Email sai định dạng | Hiển thị lỗi tại field             |
| Password < 8 ký tự  | Không cho submit                   |
| Email không tồn tại | Thông báo chung (tránh lộ info)    |
| Sai password        | Hiển thị "Sai thông tin đăng nhập" |
| Tài khoản bị khóa   | Thông báo liên hệ admin            |
| CSRF hết hạn        | Yêu cầu load lại trang             |
| Server lỗi          | Hiển thị lỗi hệ thống              |

---

## 6. Giao diện (UI/UX)

- Form gồm:
  - Email
  - Password
  - Checkbox Remember Me
- Nút Login có trạng thái loading
- Hỗ trợ Enter để submit
- Hiển thị lỗi rõ ràng dưới input
- Responsive (Mobile + Desktop)

---

## 7. Điều kiện thành công (Acceptance Criteria)

- Người dùng đăng nhập đúng → vào dashboard
- Sai thông tin → hiển thị lỗi
- Không bị lộ password
- Session hoạt động đúng

---

## 8. Phụ thuộc (Dependencies)

- Database (MySQL)
- PHP Backend
- Session / Cookie
