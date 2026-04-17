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

| Thành phần     | Công nghệ             | Lý do lựa chọn                                |
| -------------- | --------------------- | --------------------------------------------- |
| **Ngôn ngữ**   | PHP (MVC thuần)       | Hiểu rõ luồng xử lý backend, session, routing |
| **Database**   | MySQL / MariaDB(XAMPP)      | Dễ dùng, tích hợp sẵn XAMPP                   |
| **Web Server** | Apache (XAMPP)        | Chạy local nhanh                              |
| **Frontend**   | HTML, CSS, Bootstrap  | Tạo UI nhanh, responsive                      |
| **JS**         | Vanilla JS            | Nhẹ, dễ kiểm soát                             |
| **Icon**       | FontAwesome           | UI đẹp hơn                                    |
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
│   └── AdminController.php
│
├── models/
│   ├── User.php
│   ├── Course.php
│   ├── Lesson.php
│   ├── Quiz.php
│   ├── Result.php
│   └── Progress.php
│
├── views/
│   ├── layout/
│   │   ├── user_layout.php
│   │   └── admin_layout.php
│   │
│   ├── user/
│   │   ├── home.php
│   │   ├── courses.php
│   │   ├── lesson.php
│   │   ├── quiz.php
│   │   ├── result.php
│   │   └── progress.php
│   │
│   └── admin/
│       ├── dashboard.php
│       ├── courses.php
│       ├── lessons.php
│       └── users.php
│
├── routes.php
├── index.php
└── middleware.php
```

---
## <a id="co-so-du-lieu"></a> 🗄 Cơ sở dữ liệu

**Database**: `elearning`

| Bảng       | Chức năng                                      |
| ---------- | ---------------------------------------------- |
| `users`    | Lưu thông tin người dùng (admin/user)          |
| `courses`  | Thông tin khóa học (title, desc, price, image) |
| `lessons`  | Nội dung bài học (content, position)           |
| `quiz`     | Câu hỏi trắc nghiệm                            |
| `results`  | Kết quả làm quiz                               |
| `progress` | Tiến độ học tập                                |

---

## <a id="chuc-nang-noi-bat"></a> ⚡ Chức năng nổi bật

### 👨‍🎓 Người học (User)

* **Trang chủ hiện đại:** hiển thị khóa học mới, free, paid
* **Phân loại khóa học:**

  * 🆓 Free → học ngay
  * 💰 Paid → phải mua
* **Học bài:**

  * Hiển thị nội dung chi tiết (content)
  * Điều hướng bài học theo thứ tự (position)
* **Quiz:**

  * Làm bài trắc nghiệm
  * Xem kết quả
* **Tiến độ học:**

  * % hoàn thành khóa học
* **Bảo vệ nội dung:**

  * Khóa paid không được học nếu chưa mua

### 🔒 Quản trị viên (Admin)

* **Dashboard**
* **Quản lý khóa học:**

  * Thêm / sửa / xóa
* **Quản lý bài học**
* **Quản lý người dùng**

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

## 📄 Danh sách tài liệu SRS

| STT | Tài liệu | Nội dung |
|-----|------------|---------|
| 1 | 📄 [SRS_LOGIN.md](./SRS/SRS_LOGIN.md) | Đăng nhập hệ thống |
| 2 | 📄 [SRS_REGISTER.md](./SRS/SRS_REGISTER.md) | Đăng ký tài khoản |
| 3 | 📄 [SRS_HOME.md](./SRS/SRS_HOME.md) | Trang chủ |
| 4 | 📄 [SRS_COURSE.md](./SRS/SRS_COURSE.md) | Danh sách khóa học |
| 5 | 📄 [SRS_LESSON.md](./SRS/SRS_LESSON.md) | Bài học |
| 6 | 📄 [SRS_QUIZ.md](./SRS/SRS_QUIZ.md) | Hệ thống quiz |
| 7 | 📄 [SRS_RESULT.md](./SRS/SRS_RESULT.md) | Kết quả bài làm |
| 8 | 📄 [SRS_PROGRESS.md](./SRS/SRS_PROGRESS.md) | Tiến độ học |
| 9 | 📄 [SRS_PAYMENT.md](./SRS/SRS_PAYMENT.md) | Mua khóa học |
| 10 | 📄 [SRS_CART.md](./SRS/SRS_CART.md) | Giỏ hàng |
| 11 | 📄 [SRS_ADMIN_DASHBOARD.md](./SRS/SRS_ADMIN_DASHBOARD.md) | Dashboard admin |

---

## <a id="tien-do"></a> 📊 Bảng tiến độ

| STT | Công việc | Mô tả chi tiết | Người thực hiện | Trạng thái |
|-----|----------|---------------|----------------|-----------|
| 1 | Thiết kế database | Tạo bảng users, courses, lessons, quiz | Thảo | ✅ Hoàn thành |
| 2 | Xây dựng Auth | Đăng ký, đăng nhập, session | Thảo | ✅ Hoàn thành |
| 3 | Routing hệ thống | Điều hướng trang bằng index.php | Thảo | ✅ Hoàn thành |
| 4 | Trang chủ | Hiển thị khóa học nổi bật, free và mất phí | Thảo | 🔄 Đang phát triển|
| 5 | Danh sách khóa học | Hiển thị tất cả khóa học | Thảo | ✅ Hoàn thành |
| 6 | Phân loại khóa học | Free / Paid | Thảo | ✅ Hoàn thành |
| 7 | Trang bài học | Hiển thị nội dung lesson | Thảo | 🔄 Đang phát triển |
| 8 | Bảo vệ nội dung | Chặn khóa học chưa mua | Thảo | ✅ Hoàn thành |
| 9 | Quiz | Làm bài trắc nghiệm | Thảo | ✅ Hoàn thành |
| 10 | Kết quả | Hiển thị điểm | Thảo | ✅ Hoàn thành |
| 11 | Progress | Tính % hoàn thành | Thảo | ✅ Hoàn thành |
| 12 | Admin Dashboard | Thống kê | Thảo | 🔄 Đang phát triển |
| 13 | CRUD Course | Thêm / sửa / xóa | Thảo | ✅ Hoàn thành |
| 14 | CRUD Lesson | Quản lý bài học | Thảo | 🔄 Đang phát triển |
| 15 | CRUD User | Quản lý user | Thảo | 🔄 Đang phát triển |
| 16 | UI/UX | Cải thiện giao diện | Thảo | 🔄 Đang phát triển |

- **Người thực hiện:** Vũ Thị Thảo - 23810310277
- **Lớp:** D18CNPM2
- **Môn học:** Lập trình Web nâng cao

---

