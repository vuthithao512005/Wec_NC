# 📄 Lesson.md

## Chức năng: Bài học (Lesson Learning)

**Mã chức năng:** LESSON-01  
**Trạng thái:** Completed  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả
Hiển thị nội dung bài học theo khóa học.

---

## 2. Workflow

| Bước | Hành động | Hệ thống |
|------|----------|----------|
| 1 | Chọn khóa học | Load danh sách bài |
| 2 | Chọn bài | Hiển thị content |

---

## 3. Database

| Field | Mô tả |
|------|------|
| course_id | FK |
| title | Tiêu đề |
| content | Nội dung |
| position | Thứ tự |

---

## 4. Logic

- Sắp xếp theo position
- Next/Prev bài

---

## 5. Bảo mật

- Khóa paid → phải mua

---

## 6. UI

- Nội dung lớn
- Sidebar danh sách bài

---

## 7. Acceptance

- Hiển thị đúng nội dung
- Điều hướng đúng
