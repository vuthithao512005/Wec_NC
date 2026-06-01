# 📄 SRS_ADMIN_USER.md

## Chức năng: Quản lý Người dùng & Giám sát Hành vi Học tập

**Mã chức năng:** ADM-03  
**Trạng thái:** Hoàn thành  
**Người soạn thảo:** Vũ Thị Thảo  
**Vai trò:** Developer

---

# 1. Mô tả tổng quan (Description)

Chức năng cho phép Quản trị viên (Admin) quản lý toàn bộ tài khoản trên hệ thống E-Learning. Admin có thể thêm mới, cập nhật thông tin, phân quyền, khóa hoặc xóa tài khoản người dùng.

Ngoài chức năng quản lý tài khoản, hệ thống còn tích hợp mô hình AI phát hiện hành vi học tập bất thường nhằm hỗ trợ Admin giám sát hoạt động của học viên. Mô hình sử dụng phương pháp Hybrid kết hợp giữa Rule-Based Detection và K-Means Clustering để phát hiện các trường hợp có dấu hiệu chia sẻ tài khoản hoặc sử dụng hệ thống không đúng quy định.

---

# 2. Luồng nghiệp vụ (Workflow)

| Bước | Hành động Admin | Phản hồi hệ thống |
|------|----------------|-------------------|
| 1 | Truy cập trang Quản lý Người dùng | Hệ thống tải danh sách tài khoản từ cơ sở dữ liệu |
| 2 | Chọn bộ lọc ngày hoặc vai trò | Hệ thống lọc dữ liệu theo điều kiện |
| 3 | Nhấn Thêm mới | Hiển thị form tạo tài khoản |
| 4 | Nhập thông tin và lưu | Hệ thống kiểm tra dữ liệu và thêm bản ghi mới |
| 5 | Nhấn Sửa | Hiển thị thông tin hiện tại để cập nhật |
| 6 | Cập nhật thông tin | Dữ liệu được lưu vào cơ sở dữ liệu |
| 7 | Khóa hoặc kích hoạt tài khoản | Cập nhật trạng thái hoạt động |
| 8 | Xóa tài khoản | Hiển thị hộp thoại xác nhận trước khi thực hiện |
| 9 | Xem trạng thái AI | Hiển thị kết quả đánh giá hành vi học tập |
| 10 | Mở biểu đồ AI | Hiển thị biểu đồ phân tích hành vi học viên |

---

# 3. Yêu cầu dữ liệu (Data Requirements)

## 3.1 Bảng users

| Trường | Kiểu dữ liệu | Mô tả |
|----------|------------|--------|
| id | INT | Khóa chính |
| name | VARCHAR | Họ và tên |
| email | VARCHAR | Email đăng nhập |
| password | VARCHAR | Mật khẩu đã mã hóa |
| role | ENUM | admin hoặc user |
| status | TINYINT | Trạng thái hoạt động |
| device_count | INT | Số thiết bị đăng nhập |
| watch_time | INT | Tổng thời gian học (phút) |
| created_at | DATETIME | Ngày tạo tài khoản |

---

# 4. Chức năng chính

## 4.1 Quản lý tài khoản

- Thêm tài khoản mới.
- Chỉnh sửa thông tin người dùng.
- Phân quyền Admin hoặc User.
- Khóa hoặc kích hoạt tài khoản.
- Xóa tài khoản khỏi hệ thống.

## 4.2 Tìm kiếm và lọc dữ liệu

- Lọc theo khoảng thời gian đăng ký.
- Lọc theo vai trò người dùng.
- Hiển thị dữ liệu theo thời gian thực.

## 4.3 Phân tích hành vi học tập bằng AI

### Tầng 1: Rule-Based Detection

Hệ thống đánh dấu vi phạm khi:

- `device_count > 3`
- `watch_time > 800`

Kết quả:

- 🔴 Vi phạm quy định (Luật cứng)

### Tầng 2: Protection Layer

Loại trừ các trường hợp:

- Chưa tương tác học tập (`watch_time = 0`)
- Người dùng mới học (`watch_time < 15 phút`)

Nhằm hạn chế cảnh báo sai.

### Tầng 3: K-Means Clustering

Hệ thống thực hiện:

1. Chuẩn hóa dữ liệu bằng StandardScaler.
2. Phân cụm dữ liệu bằng thuật toán K-Means.
3. Xác định cụm đại diện cho nhóm học viên bình thường.
4. Tính khoảng cách từ từng học viên tới tâm cụm bình thường.

Kết quả đánh giá:

- 🟢 Bình thường
- 🟠 Nghi ngờ (K-Means phát hiện)
- 🔴 Vi phạm quy định
- ⚪ Chưa tương tác

---

# 5. API sử dụng

## Endpoint

```http
GET /api/check-fraud
```

## Chức năng

API được xây dựng bằng Python Flask để phân tích dữ liệu học viên và trả về kết quả đánh giá hành vi.

## Dữ liệu trả về

```json
{
  "status": "success",
  "total_analyzed": 20,
  "data": [
    {
      "id": 1,
      "name": "Nguyen Van A",
      "device_count": 2,
      "watch_time": 120,
      "status": "Bình thường",
      "color": "green"
    }
  ]
}
```

---

# 6. Ràng buộc kỹ thuật và bảo mật

- Chỉ Admin mới được truy cập chức năng.
- Mật khẩu không được hiển thị trên giao diện.
- Email đăng nhập phải là duy nhất.
- Xác nhận trước khi xóa dữ liệu.
- Chống SQL Injection bằng PDO Prepared Statement.
- Kiểm tra phiên đăng nhập bằng Session.
- Dữ liệu AI chỉ mang tính chất hỗ trợ cảnh báo.

---

# 7. Xử lý lỗi (Edge Cases)

| Trường hợp | Cách xử lý |
|------------|------------|
| Không có dữ liệu người dùng | Hiển thị thông báo phù hợp |
| Email đã tồn tại | Không cho phép tạo tài khoản |
| Thiếu dữ liệu bắt buộc | Hiển thị thông báo lỗi |
| Người dùng chưa có dữ liệu học tập | Gắn nhãn Chưa tương tác |
| Chỉ có một học viên trong tập phân tích | Gắn nhãn Bình thường |
| API AI không phản hồi | Hiển thị trạng thái Chưa quét |

---

# 8. Giao diện (UI/UX)

- Hiển thị danh sách người dùng dạng bảng.
- Badge phân biệt quyền Admin và User.
- Hiển thị trạng thái hoạt động của tài khoản.
- Hiển thị kết quả AI bằng màu sắc trực quan:
  - 🟢 Bình thường
  - 🟠 Nghi ngờ
  - 🔴 Vi phạm
  - ⚪ Chưa tương tác
- Hỗ trợ Modal thêm và chỉnh sửa tài khoản.
- Tích hợp Scatter Chart trực quan hóa hành vi học tập của học viên.

---

# 9. Điều kiện thành công

- Admin thực hiện thành công các thao tác CRUD người dùng.
- Dữ liệu được cập nhật chính xác trong cơ sở dữ liệu.
- Hệ thống AI trả về kết quả đúng định dạng JSON.
- Biểu đồ AI hiển thị chính xác dữ liệu học tập.
- Giao diện hoạt động ổn định trên các trình duyệt hiện đại.
