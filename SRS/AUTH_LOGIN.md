#Software Requirement Specification (SRS)
##1. Chức năng: Đăng nhập hệ thống
**Mã chức năng:** AUTH-01  

### 1. Mô tả
Cho phép người dùng đăng nhập vào hệ thống bằng email và mật khẩu.

### 2. Luồng nghiệp vụ
| Bước | Hành động       | Hệ thống         |
|------|-----------------|------------------|
| 1    | Truy cập /login | Hiển thị form    |
| 2    | Nhập thông tin  | Validate         |
| 3    | Nhấn login      | Kiểm tra DB      |
| 4    | Thành công      | Chuyển dashboard |
| 5    | Thất bại        | Báo lỗi          |

### 3. Dữ liệu
- Email (bắt buộc)
- Password (>=8 ký tự)

### 4. Bảo mật
- HTTPS
- Hash password (bcrypt)

### 5. Lỗi
- Sai mật khẩu
- Email không tồn tại
