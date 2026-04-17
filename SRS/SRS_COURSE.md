# 📄 SRS_COURSE.md

## Chức năng: Quản lý & hiển thị khóa học (Course Management)

**Mã chức năng:** COURSE-01  
**Trạng thái:** Completed  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan
Chức năng cho phép:
- Người dùng xem danh sách khóa học
- Phân loại khóa học miễn phí và trả phí
- Quản trị viên thực hiện CRUD khóa học

---

## 2. Luồng nghiệp vụ

### 👨‍🎓 User

| Bước | Hành động | Phản hồi hệ thống |
|------|----------|------------------|
| 1 | Truy cập `/courses` | Hiển thị danh sách khóa học |
| 2 | Xem chi tiết | Hiển thị thông tin |
| 3 | Click học | Chuyển lesson |

---

### 🔒 Admin

| Bước | Hành động |
|------|----------|
| 1 | Thêm khóa học |
| 2 | Sửa |
| 3 | Xóa |

---

## 3. Yêu cầu dữ liệu

### Input

| Trường | Kiểu | Bắt buộc |
|--------|------|---------|
| title | string | ✔ |
| description | text | ✔ |
| price | int | ✔ |
| image | string | ✔ |

---

## 4. Database

| Field | Mô tả |
|------|------|
| id | ID |
| title | Tên |
| description | Mô tả |
| price | Giá |
| image | Ảnh |

---

## 5. Logic

- price = 0 → Free
- price > 0 → Paid

---

## 6. UI/UX

- Card khóa học
- Hiển thị ảnh + giá
- Nút học / mua

---

## 7. Acceptance Criteria

- Hiển thị đúng danh sách
- Phân loại đúng Free/Paid
