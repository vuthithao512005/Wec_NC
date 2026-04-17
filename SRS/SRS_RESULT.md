# 📄 SRS_RESULT.md

## Chức năng: Kết quả bài làm

**Mã chức năng:** RESULT-01  
**Trạng thái:** Hoàn thành  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả

Hiển thị điểm quiz.

---

## 2. Workflow

| Bước | Action | Response |
|------|--------|----------|
| 1 | Submit quiz | Tính điểm |
| 2 | Xem kết quả | Hiển thị |

---

## 3. Data

### DB (`results`)

| Field | Mô tả |
|------|------|
| user_id | user |
| score | điểm |

---

## 4. Ràng buộc

- Lưu theo user

---

## 5. Edge Cases

| Case | Xử lý |
|------|------|
| Chưa làm | Không có data |

---

## 6. UI

- Hiển thị điểm

---

## 7. Acceptance

- Hiển thị đúng điểm
