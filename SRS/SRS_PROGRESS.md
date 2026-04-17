# 📄 SRS_PROGRESS.md

## Chức năng: Tiến độ học (Learning Progress)

**Mã chức năng:** PROGRESS-01  
**Trạng thái:** Completed  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Chức năng theo dõi tiến độ học tập cho phép người dùng biết được mức độ hoàn thành của một khóa học dựa trên số bài học đã học.

Hệ thống sẽ:
- Ghi nhận bài học đã hoàn thành
- Tính toán % tiến độ
- Hiển thị trực quan trên giao diện

---

## 2. Mục tiêu (Objectives)

- Giúp người học theo dõi quá trình học tập
- Tăng động lực hoàn thành khóa học
- Hỗ trợ hệ thống đánh giá mức độ học

---

## 3. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động người dùng | Phản hồi hệ thống |
|------|---------------------|------------------|
| 1 | Truy cập khóa học | Hiển thị danh sách bài học |
| 2 | Click vào bài học | Load nội dung |
| 3 | Hoàn thành bài học | Lưu vào bảng progress |
| 4 | Hệ thống xử lý | Tính toán số bài đã học |
| 5 | Hiển thị | Cập nhật % tiến độ |

---

## 4. Công thức tính (Calculation)

```text
progress = (completed / total) * 100
```
Trong đó:

- completed: số bài học đã hoàn thành  
- total: tổng số bài học trong khóa học  

Nếu:

- total = 0 → progress = 0%  
