# SRS - Đăng ký tài khoản
**Mã:** AUTH-02

### 1. Mô tả
Cho phép người dùng tạo tài khoản mới.

### 2. Luồng
1. Nhập email, password
2. Validate
3. Lưu DB
4. Thành công → login

### 3. Dữ liệu
- Email (unique)
- Password
- Confirm password

### 4. Lỗi
- Email đã tồn tại
- Password không khớp
