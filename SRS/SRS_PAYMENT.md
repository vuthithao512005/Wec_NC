# 📄 SRS_PAYMENT.md

## Chức năng: Mua khóa học

**Mã chức năng:** PAYMENT-01  
**Trạng thái:** Đang phát triển  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả

Cho phép user mua khóa học.

---

## 2. Workflow

| Bước | Action | Response |
|------|--------|----------|
| 1 | Click mua | Thêm giỏ |
| 2 | Thanh toán | Xử lý |

---

## 3. Data

| Field | Mô tả |
|------|------|
| user_id | user |
| course_id | khóa |

---

## 4. Ràng buộc

- Phải login

---

## 5. Edge Cases

| Case | Xử lý |
|------|------|
| Chưa login | Redirect |

---

## 6. UI

- Nút mua

---

## 7. Acceptance

- Mua thành công
