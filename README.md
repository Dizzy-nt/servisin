# servisin
Proyek UAS Sistem Terdistribusi: aplikasi manajemen servis dengan RESTful API pada Debian dan client PHP di Docker XAMPP. Fitur customer, admin, teknisi, serta demonstrasi keamanan (brute force Hydra dan mitigasi reCAPTCHA)

📌 Distributed Service Management System (RESTful API)

Proyek ini dikembangkan untuk memenuhi Ujian Akhir Semester mata kuliah Praktikum Sistem Terdistribusi dan System Security. Sistem dibangun dengan pendekatan multi-machine environment agar mahasiswa memahami konsep distribusi layanan, komunikasi REST, dan mitigasi keamanan.

🚀 Arsitektur Sistem

Proyek menggunakan dua mesin terpisah:

1. Windows (Host)
Menjalankan Docker XAMPP (Linux) untuk folder /client
Menyediakan web login dan dashboard:
http://<windows-ip>:8082/www/servis/client/login.php

2. Debian VM
Menjalankan REST API pada folder /server/api
Endpoint root:
http://<debian-ip>/servis/api/

Keduanya saling berkomunikasi via HTTP menggunakan protokol RESTful.

📂 Fitur Sistem
🔵 Customer
Registrasi perangkat
Membuat service order
Melihat status order

🔴 Admin
Mengelola user (customer/technician)
Melihat semua order
Meng-assign order ke teknisi

🟢 Technician
Melihat assignment order
Update status: assigned → in_progress → completed

🔗 REST API Endpoint Summary
| Method | Endpoint                    | Description         |
| ------ | --------------------------- | ------------------- |
| POST   | /login                      | User login          |
| GET    | /users                      | List all users      |
| POST   | /users                      | Add a new user      |
| GET    | /devices?user_id=           | Get user devices    |
| POST   | /devices                    | Add user device     |
| GET    | /orders                     | Get all orders      |
| POST   | /orders                     | Create order        |
| POST   | /orders/{id}/assign         | Assign technician   |
| PATCH  | /orders/{id}/status         | Update status       |
| GET    | /assignments?technician_id= | Get assignment list |

🔒 Security Section

Sistem juga digunakan untuk demonstrasi:

1. Brute Force Attack (Hydra)
Dilakukan dari Debian ke login client:
hydra -l admin -P passlist.txt -s 8082 <windows-ip> http-post-form "/www/servis/client/login.php:user_name=^USER^&user_password=^PASS^:Invalid credentials"

2. Mitigasi: Google reCAPTCHA v2
Setelah implementasi CAPTCHA:
Hydra tidak dapat lagi memvalidasi response
Brute force tidak efektif
Ini menunjukkan bagaimana CAPTCHA mencegah automated login attacks.

🛠 Teknologi yang Digunakan

PHP Native
Apache2
MySQL (MariaDB)
Docker (XAMPP Linux)
VirtualBox Debian
cURL (client–server communication)
Hydra (security testing)
Google reCAPTCHA v2

📸 Screenshots
Admin -> Manage Orders
<img width="877" height="599" alt="image" src="https://github.com/user-attachments/assets/6e5ae003-d7da-4e65-a54b-49276d198844" />
Admin -> Manage Users
<img width="553" height="644" alt="image" src="https://github.com/user-attachments/assets/1c90ca62-8ee2-419a-a9a3-c8331129d2e2" />

Customer -> Devices
<img width="611" height="525" alt="image" src="https://github.com/user-attachments/assets/f0d96953-1b59-4c4e-b9b7-99de53dc90f7" />
Customer -> Orders
<img width="802" height="641" alt="image" src="https://github.com/user-attachments/assets/de254c6d-2019-42fa-8018-7a8286211c07" />

Technician -> Assignment
<img width="725" height="491" alt="image" src="https://github.com/user-attachments/assets/423a40d3-e87e-4e3e-b69c-68fe711103f5" />

Login sebelum menggunakan reCAPTHCA
<img width="488" height="406" alt="image" src="https://github.com/user-attachments/assets/3e1f7332-f2cb-4f42-adde-eef138c09549" />
<img width="532" height="447" alt="image" src="https://github.com/user-attachments/assets/e612b334-041b-418a-bc4b-a6e5faef04fd" />

passlist.txt
<img width="661" height="418" alt="image" src="https://github.com/user-attachments/assets/ceb1ebdf-2858-48b9-b419-ac5a66c1e431" />
Percobaan brute force
<img width="661" height="418" alt="image" src="https://github.com/user-attachments/assets/beba51c3-8005-4572-9eb8-900b6c2cda0f" />

Login setelah menggunakan reCAPTCHA v2
<img width="518" height="461" alt="image" src="https://github.com/user-attachments/assets/a88c1afd-2f87-404b-a2da-80a93298a34e" />

Percobaan brute force setelah ada CAPTCHA
<img width="661" height="338" alt="image" src="https://github.com/user-attachments/assets/8556a1fb-39b4-47b8-9178-0a02b5eb2637" />

📜 License
Free to use for educational purposes.
