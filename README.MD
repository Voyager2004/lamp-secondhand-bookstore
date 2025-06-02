# Secondhand Book Trading Web App

校园二手书交易网站 —— 基于 **LAMP**（Linux + Apache + MySQL + PHP）搭建的课程项目。

> **目标**：演示从需求分析 → 数据库设计 → Web 实现 → 部署上线的完整流程，供《数据库系统原理》实验报告与课堂演示使用。

---

## ✨ 功能概览

| 模块   | 说明                                                     |
| ---- | ------------------------------------------------------ |
| 访客浏览 | 首页展示所有 *在售* 图书，可按关键词（书名/作者）搜索                          |
| 用户认证 | 注册 / 登录 / 退出（Session 维持）                               |
| 发布图书 | 登录后可填写表单发布新书，支持库存数量                                    |
| 个人中心 | 查看自己发布的书目 & 基本资料                                       |
| 数据库层 | `user`, `book`, `order`, `order_item`, `message` 五张核心表 |

> **实验范围**：当前版本侧重 *发布 / 检索* 主线；下单、订单流转、图片上传等保留接口占位。

---

## 🗂️ 目录结构

```text
secondhand/
├─ config/db.php            # PDO 数据库配置
├─ schema.sql               # MySQL 建表脚本
├─ public/                  # Apache DocumentRoot
│  ├─ index.php             # 首页 + 搜索
│  ├─ login.php / register.php
│  ├─ book_create.php       # 发布图书表单
│  ├─ dashboard.php         # 个人中心
│  └─ logout.php
└─ src/                     # 预留业务模块（MVC 可扩展）
```

---

## ⚙️ 部署步骤

> 以下命令以 **Ubuntu 22.04** 为例，默认使用 `root` ssh 登录。

```bash
# 1. 安装 LAMP
apt update && apt install -y apache2 mysql-server php php-mysql php-intl php-xml php-mbstring git unzip

# 2. 克隆/上传项目
cd /var/www/html
git clone <repo> secondhand         # 或 scp secondhand.zip …

# 3. 调整权限
chown -R www-data:www-data secondhand

# 4. 配置虚拟主机（/etc/apache2/sites-available/secondhand.conf）
<VirtualHost *:80>
  DocumentRoot /var/www/html/secondhand/public
  <Directory "/var/www/html/secondhand/public">
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>
a2ensite secondhand.conf && systemctl reload apache2

# 5. 初始化数据库
mysql -u root -p <<'SQL'
CREATE DATABASE IF NOT EXISTS secondhand DEFAULT CHARSET utf8mb4;
CREATE USER IF NOT EXISTS 'lampuser'@'localhost' IDENTIFIED WITH mysql_native_password BY 'strong_pass';
GRANT ALL ON secondhand.* TO 'lampuser'@'localhost';
FLUSH PRIVILEGES;
SQL

mysql -u lampuser -p secondhand < /var/www/html/secondhand/schema.sql
```

> 打开浏览器访问 `http://<server-ip>/` 即可进入首页。

---

## 🚀 快速使用

1. **注册账号** → 登录
2. 点击 **发布图书** 填写信息后提交
3. 回到首页可看到新书；顶部搜索框可按关键词过滤
4. 个人中心查看 *我的发布*，可扩展修改/下架功能

---

## 🔒 安全要点

* 所有 SQL 采用 **PDO 预处理**，避免注入
* 登录密码使用 `password_hash()` / `password_verify()`（bcrypt）加密存储
* 发布/登录表单含 CSRF Token（TODO：待实现）
* 上传文件时应做 MIME 检测 + 生成随机文件名（留空待扩展）

---

## 🗺️ Roadmap

* [ ] 下单 & 订单流转：购物车、支付占位流程
* [ ] 图片上传：书籍封面存储至 `uploads/`
* [ ] 后台管理：管理员审核、报表统计
* [ ] 单元测试 & CI：GitHub Actions 自动部署到云主机
* [ ] Docker Compose：一键启动 LAMP + phpMyAdmin

---

## 📄 License

本示例仅供教学与实验使用，按 MIT 许可自由修改、传播。
