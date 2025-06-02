-- MySQL schema for secondhand
CREATE DATABASE IF NOT EXISTS secondhand DEFAULT CHARSET utf8mb4;
USE secondhand;

-- 用户表
CREATE TABLE user (
  uid INT AUTO_INCREMENT PRIMARY KEY,
  nickname VARCHAR(32) NOT NULL,
  email VARCHAR(64) UNIQUE NOT NULL,
  password_hash CHAR(60) NOT NULL,
  role ENUM('buyer','seller','admin') DEFAULT 'buyer',
  reg_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 图书表
CREATE TABLE book (
  bid INT AUTO_INCREMENT PRIMARY KEY,
  uid INT NOT NULL,
  title VARCHAR(128) NOT NULL,
  author VARCHAR(64),
  price DECIMAL(8,2) NOT NULL,
  status ENUM('on_sale','reserved','sold') DEFAULT 'on_sale',
  stock INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (uid) REFERENCES user(uid) ON DELETE CASCADE
);

-- 订单主表
CREATE TABLE `order` (
  oid INT AUTO_INCREMENT PRIMARY KEY,
  buyer_uid INT NOT NULL,
  seller_uid INT NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  state ENUM('pending','paid','shipped','done','cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (buyer_uid) REFERENCES user(uid),
  FOREIGN KEY (seller_uid) REFERENCES user(uid)
);

-- 订单-明细表（多本书）
CREATE TABLE order_item (
  oid INT,
  bid INT,
  quantity INT NOT NULL,
  unit_price DECIMAL(8,2) NOT NULL,
  PRIMARY KEY (oid, bid),
  FOREIGN KEY (oid) REFERENCES `order`(oid) ON DELETE CASCADE,
  FOREIGN KEY (bid) REFERENCES book(bid)
);

-- 站内信 / 留言
CREATE TABLE message (
  mid INT AUTO_INCREMENT PRIMARY KEY,
  from_uid INT NOT NULL,
  to_uid INT NOT NULL,
  content TEXT NOT NULL,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (from_uid) REFERENCES user(uid),
  FOREIGN KEY (to_uid) REFERENCES user(uid)
);
