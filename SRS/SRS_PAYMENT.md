# 📄 SRS_PAYMENT.md

## Chức năng: Mua khóa học (Payment Simulation)

**Mã chức năng:** PAY-01  
**Trạng thái:** Completed  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả
Cho phép user mua khóa học trả phí.

---

## 2. Workflow

| Bước | Hành động |
|------|----------|
| 1 | Click mua |
| 2 | Thêm giỏ |
| 3 | Thanh toán |
| 4 | Unlock |

---

## 3. Database

| Field | Mô tả |
|------|------|
| user_id | User |
| course_id | Course |

---

## 4. Logic

- Đã mua → học được

---

## 5. Acceptance

- Unlock đúng khóa học
