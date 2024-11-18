CREATE DATABASE question_bank;
USE question_bank;

-- Table: login (Student credentials)
CREATE TABLE login (
    username VARCHAR(50) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);

-- Table: mods (Moderator credentials)
CREATE TABLE mods (
    username VARCHAR(50) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);

-- Table: course (Course details)
CREATE TABLE course (
    course_id VARCHAR(10) NOT NULL PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL
);

-- Table: test_type (Test type for each course)
CREATE TABLE test_type (
    course_id VARCHAR(10) NOT NULL,
    test_id CHAR(3) NOT NULL,
    PRIMARY KEY (course_id, test_id)
);

-- Table: document (Documents or question papers)
CREATE TABLE document (
    doc_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id VARCHAR(10) NOT NULL,
    test_id CHAR(3) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    question_paper_image MEDIUMBLOB,
    FOREIGN KEY (course_id, test_id) REFERENCES test_type(course_id, test_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Table: status (To track the status of the document)
CREATE TABLE status (
    doc_id INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    mods_username VARCHAR(50),
    PRIMARY KEY (doc_id),
    FOREIGN KEY (doc_id) REFERENCES document(doc_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (mods_username) REFERENCES mods(username)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);




