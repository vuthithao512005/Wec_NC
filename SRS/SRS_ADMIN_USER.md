# 📄 SRS_ADMIN_USER.md

## Chức năng: Quản lý Người dùng & Phân quyền (Admin User Management)

**Mã chức năng:** ADM-03  
**Trạng thái:** Đang phát triển  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng giúp Admin quản lý toàn bộ tài khoản trên hệ thống. Admin có quyền xem danh sách, cập nhật thông tin, thay đổi vai trò (cấp quyền Admin cho user khác) hoặc xóa/khóa tài khoản vi phạm.

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động Admin                        | Phản hồi hệ thống                                      |
|------|----------------------------------------|--------------------------------------------------------|
| 1    | Truy cập `/admin/users`                | Lấy toàn bộ dữ liệu bảng `users` hiển thị ra Table     |
| 2    | Nhập email/tên vào ô Tìm kiếm          | Lọc danh sách theo từ khóa (LIKE)                      |
| 3    | Nhấn "Cấp quyền Admin"                 | Update field `role` thành 'admin'                      |
| 4    | Nhấn "Xóa tài khoản"                   | Cảnh báo an toàn -> Chấp nhận -> Thực thi Xóa          |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Dữ liệu lưu trữ (Database - bảng `users`)

| Trường      | Kiểu   | Mô tả                  |
|-------------|--------|------------------------|
| id          | int    | Khóa chính             |
| name        | string | Tên hiển thị           |
| email       | string | Unique identifier      |
| role        | string | `admin` hoặc `user`    |
| created_at  | date   | Ngày tham gia          |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

### 4.1 Bảo vệ quyền lực tối cao
- **Bắt buộc:** Tránh việc Admin tự xóa chính mình (so sánh `$id` cần xóa với `$_SESSION['user']['id']`).
- Không hiển thị mật khẩu của User ra màn hình Admin (ẩn hoàn toàn hoặc chỉ hiện `***`).

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp        | Xử lý                           |
|-------------------|---------------------------------|
| Tự xóa tài khoản Admin đang đăng nhập | Chặn bằng logic code + Ẩn nút Xóa với record của chính mình |
| Xóa User đã từng mua khóa học | Chặn xóa để bảo toàn dữ liệu đối soát hóa đơn (Nên dùng logic ẩn/khóa tài khoản thay vì xóa cứng). |

---

## 6. Giao diện (UI/UX)

- Bảng danh sách phân biệt rõ Role bằng thẻ Badge màu sắc (Vd: Admin - Đỏ, User - Xanh).
- Chức năng phân trang nếu lượng User lớn.

---

## 7. Điều kiện thành công

- Chỉnh sửa quyền hoặc xóa người dùng phản hồi chính xác vào Database.
- User bị xóa lập tức bị vô hiệu hóa khi cố gắng đăng nhập.
