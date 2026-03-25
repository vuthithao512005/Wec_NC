# Software Requirement Specification (SRS)
## Chức năng: Đăng ký tài khoản

**Mã chức năng:** AUTH-02  
**Trạng thái:** Draft  
**Người soạn:** [Thảo]

---

## 1. Mô tả tổng quan
Cho phép người dùng tạo tài khoản mới để truy cập hệ thống E-Learning.

---

## 2. Luồng nghiệp vụ

| Bước | Hành động           | Hệ thống       |
|------|---------------------|----------------|
| 1    | Truy cập /register  | Hiển thị form  |
| 2    | Nhập thông tin      | Validate       |
| 3    | Submit              | Kiểm tra email |
| 4    | Thành công          | Lưu DB         |
| 5    | Thất bại            | Hiển thị lỗi   |

---

## 3. Dữ liệu

### Input
| Field | Type | Required | Mô tả |
|------|------|--------|------|
| Email | string | ✔ | Email hợp lệ |
| Password | string | ✔ | >= 8 ký tự |
| Confirm | string | ✔ | Khớp password |

### Database (users)
| Field | Type | Mô tả |
|------|------|------|
| id | int | PK |
| email | string | unique |
| password | string | hashed |
| role | string | user/admin |
| status | boolean | active |

---

## 4. Security
- Hash password (bcrypt)
- CSRF Token
- Validate server-side

---

## 5. Edge Cases
- Email tồn tại
- Password không khớp
- Thiếu dữ liệu

---

## 6. UI/UX
- Form rõ ràng
- Hiển thị lỗi dưới input
- Responsive

---

## 7. Non-functional
- Thời gian xử lý < 2s
- Bảo mật cao

---

## 8. Acceptance Criteria
- Đăng ký thành công → lưu DB
- Lỗi → hiển thị đúng
