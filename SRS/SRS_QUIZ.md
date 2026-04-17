# 📄 SRS_QUIZ.md

## Chức năng: Làm bài Quiz

**Mã chức năng:** QUIZ-01  
**Trạng thái:** Hoàn thành  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả

Cho phép user làm bài trắc nghiệm.

---

## 2. Workflow

| Bước | Action | Response |
|------|--------|----------|
| 1 | Vào quiz | Hiển thị câu hỏi |
| 2 | Chọn đáp án | Lưu tạm |
| 3 | Submit | Tính điểm |

---

## 3. Data

### Input

| Field | Type |
|------|------|
| question_id | int |
| answer | string |

---

### DB (`quiz`)

| Field | Mô tả |
|------|------|
| id | ID |
| question | câu hỏi |
| correct | đáp án |

---

## 4. Ràng buộc

- Phải chọn đáp án

---

## 5. Edge Cases

| Case | Xử lý |
|------|------|
| Không chọn | Không submit |

---

## 6. UI

- Radio button
- Nút submit

---

## 7. Acceptance

- Tính điểm đúng
