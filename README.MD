# 🎓 WEBSITE E-LEARNING (HỆ THỐNG HỌC TRỰC TUYẾN)


Dự án môn học xây dựng một website học trực tuyến (E-Learning) fullstack với PHP thuần theo mô hình MVC, triển khai trên XAMPP. Hệ thống cung cấp đầy đủ chức năng cho **người học** (xem khóa học, học bài, làm quiz, theo dõi tiến độ, mua khóa học) và **quản trị viên** (quản lý khóa học, bài học, người dùng, nội dung đào tạo).

Dự án tập trung vào trải nghiệm học tập hiện đại, giao diện thân thiện, phân loại khóa học miễn phí và trả phí, cùng với logic kiểm soát truy cập nội dung chặt chẽ.

---
## 👥 Thành viên
| STT | Họ và tên | Mã sinh viên |
|---|---|---|
| 1 | Vũ Thị Thảo | 23810310277 |

---
## 📋 Mục lục

- [🎯 Mục tiêu hệ thống](#muc-tieu)
- [⚙️ Chức năng chính](#chuc-nang-chinh)
- [👨‍🎓 Người dùng (User)](#user)
- [🔒 Quản trị viên (Admin)](#admin)
- [🛠 Công nghệ sử dụng](#cong-nghe)
- [📁 Cấu trúc thư mục](#cau-truc)
- [🗄 Cơ sở dữ liệu](#co-so-du-lieu)
- [⚡ Chức năng nổi bật](#chuc-nang-noi-bat)
- [🚀 Hướng dẫn cài đặt](#cai-dat)
- [📄 Danh sách tài liệu SRS](#srs)
- [📊 Bảng tiến độ](#tien-do)

---
## <a id="muc-tieu"></a> 🎯 Mục tiêu hệ thống

- Áp dụng mô hình MVC vào thực tế
- Quản lý người dùng, khóa học và nội dung học
- Phân quyền rõ ràng giữa **Admin** và **User**
- Triển khai logic:
  - Khóa học miễn phí (Free)
  - Khóa học trả phí (Paid)
- Bảo vệ nội dung học (chỉ người đã mua mới được xem)

---

## <a id="chuc-nang-chinh"></a> ⚙️ Chức năng chính

### <a id="user"></a> 👨‍🎓 Người dùng (User)

- Đăng ký / Đăng nhập
- Xem danh sách khóa học
- Phân loại:
  - 🆓 Free → học ngay
  - 💰 Paid → phải mua
- Học bài:
  - Xem nội dung chi tiết
- Làm bài Quiz
- Xem kết quả
- Theo dõi tiến độ học (%)

### <a id="admin"></a> 🔒 Quản trị viên (Admin)

- Dashboard (thống kê)
- Quản lý khóa học (CRUD)
- Quản lý bài học
- Quản lý người dùng

---

## <a id="cong-nghe"></a> 🛠 Công nghệ sử dụng

| Thành phần | Công nghệ / Thư viện | Vai trò trong dự án |
| :--- | :--- | :--- |
| **Backend** | PHP (OOP, MVC thuần) | Xử lý logic nghiệp vụ, routing, session, bảo mật. |
| **Database** | MySQL / MariaDB | Lưu trữ dữ liệu quan hệ (chống SQL Injection qua PDO). |
| **Server** | Apache (XAMPP) | Môi trường chạy server cục bộ (Localhost). |
| **Frontend**| HTML5, CSS3, Bootstrap 5 | Thiết kế giao diện hiện đại, Responsive. |
| **Tương tác**| Vanilla JavaScript, AJAX (Fetch API) | Xử lý sự kiện tìm kiếm Realtime, DOM manipulation. |
| **Icons & Font**| FontAwesome, Google Fonts | Tối ưu hóa UI/UX trực quan. |
---

## <a id="cau-truc"></a> 📁 Cấu trúc thư mục

```
E-Learning/
├── config/
│   └── database.php
│
├── controllers/
│   ├── AuthController.php
│   ├── CourseController.php
│   ├── LessonController.php
│   ├── QuizController.php
│   ├── AdminController.php
│   ├── AdminQuizController.php
│   ├── CategoryController.php
│   ├── ProgressController.php
│   ├── ResultController.php
│   └── CartController.php
│
├── models/
│   ├── User.php
│   ├── Course.php
│   ├── Lesson.php
│   ├── Quiz.php
│   ├── Result.php
│   ├── Cart.php
│   ├── Order.php
│   ├── Category.php
│   └── Progress.php
│
├── Uploads/
|
├── views/
│   ├── layout/
│   │   ├── user_layout.php
│   │   └── admin_layout.php
│   │
│   ├── user/
│   │   ├── home.php
│   │   ├── courses.php
│   │   ├── lesson.php
|   |   ├── lessons.php
│   │   ├── quiz.php
│   │   ├── result.php
|   |   ├── cart.php
|   |   ├── history.php
│   │   └── progress.php
│   │
│   └── admin/
│       ├── dashboard.php
│       ├── courses.php
│       ├── lessons.php
│       ├── categories.php
│       ├── orders.php
│       ├── quiz.php
│       └── users.php
│
├── auth/
│    ├── login.php
│    ├── register.php
|
├── routes.php
├── index.php
└── middleware.php
```

---
## <a id="co-so-du-lieu"></a> 🗄 Cơ sở dữ liệu

**Database**: `elearning` (Sử dụng PDO chuẩn hóa)

Dưới đây là cấu trúc các bảng chi tiết trong cơ sở dữ liệu dựa trên sơ đồ thiết kế thực tế:

| Tên bảng | Chức năng chính |
| :--- | :--- |
| `users` | Lưu trữ thông tin tài khoản người dùng và phân quyền (admin/user). |
| `categories` | Quản lý danh mục phân loại các khóa học. |
| `courses` | Thông tin chi tiết của khóa học (tiêu đề, mô tả, giá cả, hình ảnh, category_id). |
| `lessons` | Danh sách các bài giảng chi tiết thuộc từng khóa học. |
| `cart` | Lưu trữ tạm thời các khóa học mà người dùng đã thêm vào giỏ. |
| `orders` | Lưu thông tin hóa đơn thanh toán tổng quát (tổng tiền, trạng thái, ngày tạo). |
| `order_items` | Chi tiết các khóa học nằm trong từng hóa đơn (dùng để thống kê doanh thu). |
| `user_courses` | **Bảng cấp quyền:** Bảng trung gian nối `user_id` và `course_id` để mở khóa nội dung. |
| `quizzes` | Lưu trữ ngân hàng câu hỏi bài tập trắc nghiệm. |
| `results` | Ghi nhận kết quả và điểm số làm bài quiz của học viên. |
| `progress` | Ghi nhận tiến độ học tập (lưu trạng thái bài học nào đã được hoàn thành). |
| `contacts` | Lưu trữ thông tin liên hệ, tin nhắn phản hồi hoặc hỗ trợ từ người dùng. |
| `posts` | Quản lý các bài viết tin tức, blog chia sẻ kiến thức trên hệ thống. |

---

## <a id="chuc-nang-noi-bat"></a> ⚡ Chức năng nổi bật
- Luồng thanh toán chặt chẽ: Tự động phát hiện nếu học viên đã sở hữu khóa học để ngăn chặn việc mua trùng lặp.
- Cơ chế mở khóa nội dung (Content Protection): Code backend kiểm tra đối chiếu bảng user_courses trước khi render bài giảng, đảm bảo chỉ người đã thanh toán mới có thể xem video/tài liệu.
- Hệ thống thông báo toàn cục (Global Toast UI): Mọi hành động như (Thêm giỏ hàng thành công, Lỗi mua trùng, Thanh toán hoàn tất) đều trả về một popup thông báo đẹp mắt ở góc màn hình, tự động biến mất sau 4 giây.

---

## <a id="cai-dat"></a> 🚀 Hướng dẫn cài đặt

B1. Copy project vào:

```
C:\xampp\htdocs\E-Learning
```

B2. Mở XAMPP:

* Start Apache
* Start MySQL

B3. Import database:

* Vào phpMyAdmin
* Import file `.sql`

B4. Chạy project:

* Trang user:

```
http://localhost/E-Learning/
```

* Trang admin:

```
http://localhost/E-Learning/index.php?page=admin
```

---

## <a id="srs"></a> 📄 Danh sách tài liệu SRS

| STT | Tài liệu | Nội dung |
|:---:|:---|:---|
| 1 | 📄 [SRS_LOGIN.md](./SRS/SRS_LOGIN.md) | Đăng nhập hệ thống |
| 2 | 📄 [SRS_REGISTER.md](./SRS/SRS_REGISTER.md) | Đăng ký tài khoản |
| 3 | 📄 [SRS_HOME.md](./SRS/SRS_HOME.md) | Trang chủ |
| 4 | 📄 [SRS_COURSE.md](./SRS/SRS_COURSE.md) | Danh sách khóa học |
| 5 | 📄 [SRS_LESSON.md](./SRS/SRS_LESSON.md) | Bài học và logic bảo vệ nội dung |
| 6 | 📄 [SRS_QUIZ.md](./SRS/SRS_QUIZ.md) | Hệ thống Quiz (Trắc nghiệm) |
| 7 | 📄 [SRS_RESULT.md](./SRS/SRS_RESULT.md) | Kết quả bài làm |
| 8 | 📄 [SRS_PROGRESS.md](./SRS/SRS_PROGRESS.md) | Theo dõi tiến độ học |
| 9 | 📄 [SRS_PAYMENT.md](./SRS/SRS_PAYMENT.md) | Mua khóa học và Cấp quyền |
| 10 | 📄 [SRS_CART.md](./SRS/SRS_CART.md) | Giỏ hàng |
| 11 | 📄 [SRS_ADMIN_DASHBOARD.md](./SRS/SRS_ADMIN_DASHBOARD.md) | Dashboard thống kê (Admin) |
| 12 | 📄 [SRS_ADMIN_COURSE.md](./SRS/SRS_ADMIN_COURSE.md) | Quản lý Khóa học & Danh mục (Admin) |
| 13 | 📄 [SRS_ADMIN_LESSON.md](./SRS/SRS_ADMIN_LESSON.md) | Quản lý Bài học & Nội dung (Admin) |
| 14 | 📄 [SRS_ADMIN_USER.md](./SRS/SRS_ADMIN_USER.md) | Quản lý Người dùng & Phân quyền (Admin) |
| 15 | 📄 [SRS_ADMIN_ORDER.md](./SRS/SRS_ADMIN_ORDER.md) | Quản lý Đơn hàng & Lịch sử mua (Admin) |

---

## <a id="tien-do"></a> 📊 Bảng tiến độ

| STT | Công việc | Mô tả chi tiết | Người thực hiện | Trạng thái |
|-----|----------|---------------|----------------|-----------|
| 1 | Thiết kế database | Tạo bảng users, courses, lessons, quiz,... | Thảo | ✅ Hoàn thành |
| 2 | Xây dựng Auth | Đăng ký, đăng nhập, session | Thảo | ✅ Hoàn thành |
| 3 | Routing hệ thống | Điều hướng trang bằng index.php | Thảo | ✅ Hoàn thành |
| 4 | Trang chủ | Hiển thị khóa học nổi bật, free và mất phí | Thảo | ✅ Hoàn thành|
| 5 | Danh sách khóa học | Hiển thị tất cả khóa học | Thảo | ✅ Hoàn thành |
| 6 | Phân loại khóa học | Free / Paid | Thảo | ✅ Hoàn thành |
| 7 | Trang bài học | Hiển thị nội dung lesson | Thảo | ✅ Hoàn thành |
| 8 | Bảo vệ nội dung | Chặn khóa học chưa mua | Thảo | ✅ Hoàn thành |
| 9 | Quiz | Làm bài trắc nghiệm | Thảo | ✅ Hoàn thành |
| 10 | Kết quả | Hiển thị điểm | Thảo | ✅ Hoàn thành |
| 11 | Progress | Tính % hoàn thành | Thảo | ✅ Hoàn thành |
| 12 | Admin Dashboard | Thống kê | Thảo | ✅ Hoàn thành |
| 13 | CRUD Course | Thêm / sửa / xóa | Thảo | ✅ Hoàn thành |
| 14 | CRUD Lesson | Quản lý bài học | Thảo | ✅ Hoàn thành |
| 15 | CRUD User | Quản lý user | Thảo | ✅ Hoàn thành |
| 16 | UI/UX | Cải thiện giao diện | Thảo | ✅ Hoàn thành |

- **Người thực hiện:** Vũ Thị Thảo - 23810310277
- **Lớp:** D18CNPM2
- **Môn học:** Lập trình Web nâng cao

---

