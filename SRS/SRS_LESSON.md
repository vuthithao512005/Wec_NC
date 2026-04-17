# 📄 SRS_LESSON.md

## Chức năng: Quản lý bài học (Lesson Management)

**Mã chức năng:** LESSON-01  
**Trạng thái:** Hoàn thành  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Quản lý bài học trong khóa học.

Hệ thống đảm bảo:
- Gắn bài học với khóa học
- Sắp xếp theo thứ tự

---

## 2. Luồng nghiệp vụ

| Bước | Hành động | Phản hồi |
|------|----------|----------|
| 1 | Admin thêm bài | Lưu DB |
| 2 | User chọn bài | Hiển thị nội dung |

---

## 3. Dữ liệu

### Input

| Field | Type |
|------|------|
| title | string |
| content | text |
| position | int |

---

### Database (`lessons`)

| Field | Mô tả |
|------|------|
| id | ID |
| course_id | khóa học |
| title | tên |
| content | nội dung |
| position | thứ tự |

---

## 4. Ràng buộc

- position phải tăng dần
- phải thuộc course

---

## 5. Edge Cases

| Case | Xử lý |
|------|------|
| Không có bài | Hiển thị rỗng |

---

## 6. UI

- Danh sách bài học
- Điều hướng next/prev

---

## 7. Acceptance

- Hiển thị đúng thứ tự
