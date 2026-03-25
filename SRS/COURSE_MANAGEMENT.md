# SRS - Quản lý khóa học
**Mã:** COURSE-01
---

## 1. Mô tả
Admin quản lý khóa học.

---

## 2. Workflow

| Bước | Action | System |
|------|--------|--------|
| 1 | Vào admin | Hiển thị list |
| 2 | Thêm | Lưu DB |
| 3 | Sửa | Update |
| 4 | Xóa | Delete |

---

## 3. Data

### Input
| Field | Type |
|------|------|
| Title | string |
| Description | text |
| Image | string |

### Database (courses)
| Field | Type |
|------|------|
| id | int |
| title | string |
| description | text |

---

## 4. Security
- Chỉ admin
- Validate dữ liệu

---

## 5. Edge Cases
- Thiếu dữ liệu
- Không có quyền

---

## 6. UI/UX
- Bảng danh sách
- Form CRUD

---

## 7. Non-functional
- Load nhanh < 2s

---

## 8. Acceptance
- CRUD hoạt động đúng
