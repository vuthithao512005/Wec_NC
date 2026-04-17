 WEBSITE QUẢN LÝ HỌC TẬP (E-LEARNING)

1. Giới thiệu
Dự án xây dựng hệ thống học tập trực tuyến cho phép người dùng đăng ký khóa học, học bài, làm bài kiểm tra và theo dõi tiến độ học tập.

2. Danh sách tài liệu SRS

2.1 🔐 Authentication
- [SRS - Đăng nhập](./SRS/AUTH_LOGIN.md)
- [SRS - Đăng ký](./SRS/AUTH_REGISTER.md)

2.2 📚 Course
- [SRS - Quản lý khóa học](./SRS/COURSE_MANAGEMENT.md)
- [SRS - Xem khóa học](./SRS/COURSE_VIEW.md)

2.3 🎓 Learning
- [SRS - Học bài](./SRS/LEARNING.md)

2.4 📝 Testing
- [SRS - Làm bài kiểm tra](./SRS/TEST.md)
2.5 📊 Progress
- [SRS - Theo dõi tiến độ](./SRS/PROGRESS.md)

Công nghệ sử dụng
- HTML, CSS, JavaScript
- PHP 
- MySQL
///////////////

# 🎓 Website E-Learning Thông Minh

Dự án môn học xây dựng một website học trực tuyến (E-Learning) fullstack với PHP thuần theo mô hình MVC, triển khai trên XAMPP. Hệ thống cung cấp đầy đủ chức năng cho **người học** (xem khóa học, học bài, làm quiz, theo dõi tiến độ, mua khóa học) và **quản trị viên** (quản lý khóa học, bài học, người dùng, nội dung đào tạo).

Dự án tập trung vào trải nghiệm học tập hiện đại, giao diện thân thiện, phân loại khóa học miễn phí và trả phí, cùng với logic kiểm soát truy cập nội dung chặt chẽ.

---

## 👥 Thành viên

* **Lớp**: D18CNPM2

| STT | Họ và tên       | Mã sinh viên |
| --- | --------------- | ------------ |
| 1   | Nguyễn Văn Kiên | 23810310138  |
| 2   | Đỗ Quang Hà     | 23810310132  |
| 3   | Nguyễn Bá Nhân  | 23810310144  |

---

## 📝 Phân công công việc

### 🔒 Hệ thống Quản trị (Admin) — Hà

* Dashboard
* Quản lý khóa học
* Quản lý bài học
* Quản lý người dùng

### 👨‍🎓 Giao diện học tập (User) — Kiên

* Trang chủ (Home UI)
* Danh sách khóa học
* Trang bài học (Lesson)
* Quiz & Kết quả

### ⚙️ Chức năng hỗ trợ — Nhân

* Đăng ký / Đăng nhập
* Theo dõi tiến độ học tập
* Lịch sử kết quả
* Giỏ hàng & mua khóa học (mô phỏng)

---

## 📋 Mục lục

* [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
* [Cấu trúc thư mục](#-cấu-trúc-thư-mục)
* [Cơ sở dữ liệu](#-cơ-sở-dữ-liệu)
* [Chức năng nổi bật](#-chức-năng-nổi-bật)
* [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)

---

## 🛠 Công nghệ sử dụng

| Thành phần     | Công nghệ            | Lý do lựa chọn                                |
| -------------- | -------------------- | --------------------------------------------- |
| **Ngôn ngữ**   | PHP (MVC thuần)      | Hiểu rõ luồng xử lý backend, session, routing |
| **Database**   | MySQL / MariaDB      | Dễ dùng, tích hợp sẵn XAMPP                   |
| **Web Server** | Apache (XAMPP)       | Chạy local nhanh                              |
| **Frontend**   | HTML, CSS, Bootstrap | Tạo UI nhanh, responsive                      |
| **JS**         | Vanilla JS           | Nhẹ, dễ kiểm soát                             |
| **Icon**       | FontAwesome          | UI đẹp hơn                                    |

---

## 📁 Cấu trúc thư mục

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

## 🗄 Cơ sở dữ liệu

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

## ⚡ Chức năng nổi bật

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

---

### 🔒 Quản trị viên (Admin)

* **Dashboard**
* **Quản lý khóa học:**

  * Thêm / sửa / xóa
* **Quản lý bài học**
* **Quản lý người dùng**

---

## 🚀 Hướng dẫn cài đặt

1. Copy project vào:

```
C:\xampp\htdocs\E-Learning
```

2. Mở XAMPP:

* Start Apache
* Start MySQL

3. Import database:

* Vào phpMyAdmin
* Import file `.sql`

4. Chạy project:

* Trang user:

```
http://localhost/E-Learning/
```

* Trang admin:

```
http://localhost/E-Learning/index.php?page=admin
```

---

## 💡 Ghi chú

* Dự án áp dụng mô hình **MVC cơ bản**
* Có phân quyền:

  * Admin
  * User
* Có thể mở rộng:

  * Thanh toán online (VNPAY)
  * Giỏ hàng
  * Video learning

---

*Dự án E-Learning hướng đến trải nghiệm học tập trực tuyến đơn giản, dễ dùng, phù hợp cho sinh viên nghiên cứu và phát triển thêm.*

