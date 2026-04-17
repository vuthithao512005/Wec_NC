# 📄 SRS_ADMIN_DASHBOARD.md

## Chức năng: Dashboard quản trị (Admin Dashboard)

**Mã chức năng:** ADMIN-01  
**Trạng thái:** Chưa hoàn thành
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng Dashboard cho phép quản trị viên (Admin) theo dõi tổng quan hoạt động của hệ thống E-Learning.

Dashboard cung cấp các thông tin quan trọng:
- Số lượng người dùng
- Số lượng khóa học
- Số lượng bài học
- Biểu đồ thống kê dữ liệu

Hệ thống giúp admin:
- Nắm bắt tình trạng hệ thống nhanh chóng
- Theo dõi sự phát triển của nền tảng
- Ra quyết định quản lý hiệu quả

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động admin        | Phản hồi hệ thống                         |
|------|------------------------|-------------------------------------------|
| 1    | Đăng nhập hệ thống     | Xác thực thành công                       |
| 2    | Truy cập `/admin`      | Hiển thị Dashboard                        |
| 3    | Xem số liệu tổng       | Hiển thị KPI (Users, Courses, Lessons)    |
| 4    | Xem biểu đồ thống kê   | Render chart dữ liệu                      |
| 5    | Thao tác quản lý       | Điều hướng sang các chức năng khác        |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Dữ liệu đầu vào (Input)

| Trường | Kiểu | Bắt buộc | Mô tả |
|-------|------|----------|------|
| Không | -    | -        | Dashboard không cần input trực tiếp |

---

### 3.2 Dữ liệu xử lý & hiển thị (Database)

#### Bảng `users`

| Trường | Kiểu | Mô tả |
|--------|------|------|
| id     | int  | ID user |

---

#### Bảng `courses`

| Trường | Kiểu | Mô tả |
|--------|------|------|
| id     | int  | ID khóa học |

---

#### Bảng `lessons`

| Trường | Kiểu | Mô tả |
|--------|------|------|
| id     | int  | ID bài học |

---

### 3.3 Dữ liệu tổng hợp

| Dữ liệu | Mô tả |
|--------|------|
| totalUsers   | Tổng số user |
| totalCourses | Tổng số khóa học |
| totalLessons | Tổng số bài học |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

### 4.1 Phân quyền
- Chỉ tài khoản có role = `admin` mới được truy cập

### 4.2 Session
- Kiểm tra `$_SESSION['user']`
- Kiểm tra role trước khi hiển thị

### 4.3 Hiệu năng
- Sử dụng query COUNT() để tối ưu
- Không load dữ liệu quá lớn

### 4.4 Bảo mật
- Không cho truy cập trực tiếp URL `/admin` nếu chưa login
- Redirect nếu không đủ quyền

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp | Xử lý |
|-----------|------|
| Chưa đăng nhập | Redirect về `/login` |
| Không phải admin | Redirect về `/courses` |
| Không có dữ liệu | Hiển thị 0 |
| Lỗi query DB | Hiển thị lỗi hệ thống |
| Chart không load | Hiển thị fallback text |

---

## 6. Giao diện (UI/UX)

Dashboard gồm 3 phần chính:

### 6.1 KPI (thống kê nhanh)
- 👤 Tổng user
- 📚 Tổng khóa học
- 📖 Tổng bài học

### 6.2 Biểu đồ (Chart)
- Hiển thị dữ liệu trực quan
- Có thể dùng Chart.js

### 6.3 Layout
- Responsive (desktop + mobile)
- Card UI rõ ràng
- Màu sắc dễ nhìn

---

## 7. Điều kiện thành công (Acceptance Criteria)

- Admin truy cập được Dashboard
- Hiển thị đúng:
  - Tổng user
  - Tổng khóa học
  - Tổng bài học
- Biểu đồ hiển thị chính xác
- Không bị lỗi trắng chart
- Phân quyền hoạt động đúng

---
