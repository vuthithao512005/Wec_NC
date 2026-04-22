# 📄 SRS_ADMIN_ORDER.md

## Chức năng: Quản lý Đơn hàng & Lịch sử mua (Admin Order Management)

**Mã chức năng:** ADM-04  
**Trạng thái:** Đang phát triển  
**Người soạn thảo:** Thảo  
**Vai trò:** Developer  

---

## 1. Mô tả tổng quan (Description)

Phân hệ theo dõi dòng tiền và đối soát giao dịch. Cho phép Admin xem danh sách toàn bộ các hóa đơn đã được thanh toán trên hệ thống, kiểm tra học viên nào đã mua khóa học nào với giá trị bao nhiêu.

---

## 2. Luồng nghiệp vụ (User Workflow)

| Bước | Hành động Admin                        | Phản hồi hệ thống                                      |
|------|----------------------------------------|--------------------------------------------------------|
| 1    | Truy cập `/admin/orders`               | Truy vấn DB JOIN bảng `orders` và `users`              |
| 2    | Hiển thị danh sách hóa đơn             | Hiện Mã đơn, Tên người mua, Tổng tiền, Trạng thái      |
| 3    | Nhấn "Xem chi tiết"                    | Truy vấn bảng `order_items` lấy danh sách khóa học     |
| 4    | Hiển thị Popup/Trang chi tiết          | Liệt kê các khóa đã mua trong đơn hàng đó              |

---

## 3. Yêu cầu dữ liệu (Data Requirements)

### 3.1 Dữ liệu lưu trữ (Database - bảng `orders`, `order_items`)

| Bảng `orders`  | Bảng `order_items`  | Mô tả Liên kết                |
|----------------|---------------------|-------------------------------|
| id (Mã HD)     | id                  |                               |
| user_id        | order_id            | Liên kết với Mã HD            |
| total          | course_id           | Mã khóa học được mua          |
| status         | price               | Giá bán thực tế lúc chốt đơn  |
| created_at     |                     | Thời gian thanh toán          |

---

## 4. Ràng buộc kỹ thuật & Bảo mật

### 4.1 Tính toàn vẹn dữ liệu kế toán
- **READ-ONLY:** Tính năng Đơn hàng đối với Admin chủ yếu là Xem (View). Tuyệt đối không xây dựng chức năng Xóa (Delete) hóa đơn để đảm bảo minh bạch tài chính.
- Giá tiền ở `order_items` phải lưu cứng giá trị tại thời điểm mua, không phụ thuộc vào giá hiện tại của bảng `courses` (đề phòng sau này khóa học đổi giá).

---

## 5. Xử lý lỗi (Edge Cases)

| Trường hợp        | Xử lý                           |
|-------------------|---------------------------------|
| Khóa học bị xóa khỏi hệ thống nhưng vẫn nằm trong hóa đơn cũ | Không dùng INNER JOIN ép buộc, phải dùng LEFT JOIN hoặc hiển thị "Khóa học đã bị gỡ" nếu không tìm thấy `course_id`. |

---

## 6. Giao diện (UI/UX)

- Bảng dữ liệu sắp xếp theo ngày mua giảm dần (DESC - Hóa đơn mới nhất lên đầu).
- Format cột tiền tệ hiển thị định dạng chuẩn VNĐ.
- Chi tiết hóa đơn hiển thị dạng Modal (Popup) cho trực quan.

---

## 7. Điều kiện thành công

- Admin nắm được toàn bộ dòng tiền thu về từ người dùng một cách chính xác.
- Truy xuất được rõ ràng user_id nào đã mua course_id nào vào ngày giờ nào.
