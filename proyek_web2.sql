CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
);

CREATE TABLE profile (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(100),
    bio TEXT,
    photo VARCHAR(255),
    contact_info VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE experience (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(100),
    company VARCHAR(100),
    description TEXT,
    start_date DATE,
    end_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    skill VARCHAR(50),
    level INT, -- nilai dari 1 hingga 5 untuk menilai tingkat keterampilan
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE hobbies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hobby VARCHAR(50),
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

# ===========================================================================

-- Data dummy untuk tabel profile
INSERT INTO profile (user_id, name, bio, photo, contact_info) VALUES
(1, 'Andika Pratama', 'Seorang pengembang web yang antusias dengan pengalaman lebih dari 5 tahun.', 'profile_andika.jpg', 'andika.pratama@example.com'),
(2, 'Siti Nurhaliza', 'Desainer grafis yang mencintai seni batik dan budaya Indonesia.', 'profile_siti.jpg', 'siti.nurhaliza@example.com');

-- Data dummy untuk tabel experience
INSERT INTO experience (user_id, title, company, description, start_date, end_date) VALUES
(1, 'Pengembang Web', 'PT Nusantara Teknologi', 'Membangun aplikasi web untuk berbagai perusahaan Indonesia.', '2017-01-01', '2019-12-31'),
(1, 'Senior Developer', 'Indonesia Digital Innovation', 'Mengelola tim pengembang dan mengimplementasikan solusi berbasis web.', '2020-01-01', '2023-06-30'),
(2, 'Desainer Grafis', 'Studio Kreatif Batik Nusantara', 'Mendesain konten visual yang menampilkan motif batik Indonesia.', '2018-03-01', '2022-12-31');

-- Data dummy untuk tabel skills
INSERT INTO skills (user_id, skill, level) VALUES
(1, 'PHP', 5),
(1, 'JavaScript', 4),
(1, 'SQL', 4),
(2, 'Photoshop', 5),
(2, 'CorelDRAW', 4),
(2, 'Ilustrasi Batik', 5);

-- Data dummy untuk tabel hobbies
INSERT INTO hobbies (user_id, hobby, description) VALUES
(1, 'Membaca', 'Membaca buku sejarah dan budaya Indonesia.'),
(1, 'Bersepeda', 'Menjelajahi alam sekitar dengan sepeda setiap akhir pekan.'),
(2, 'Melukis', 'Melukis pola batik dan seni tradisional Indonesia.'),
(2, 'Traveling', 'Mengunjungi tempat-tempat budaya dan cagar alam di Indonesia.');

