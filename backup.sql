
-- Дамп структуры базы данных guest_book
DROP DATABASE IF EXISTS `guest_book`;
CREATE DATABASE IF NOT EXISTS `guest_book` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `guest_book`;

-- Дамп структуры для таблица guest_book.registereds Пользователи, которые зарегистрировались
DROP TABLE IF EXISTS `registereds`;
CREATE TABLE IF NOT EXISTS `registereds` (
 `id` SERIAL PRIMARY KEY COMMENT 'уникальный id для каждого, кто зарегистрировался',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'имя каждого, кто зарегистрировался',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci UNIQUE NOT NULL COMMENT 'email каждого, кто зарегистрировался',
  `login` varchar(255) UNIQUE COLLATE utf8mb4_unicode_ci NOT NULL  COMMENT 'логин для входа в систему',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL   
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп структуры для таблицы guest_book.users Пользователи, которые оставляют сообщения
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` SERIAL PRIMARY KEY COMMENT 'уникальный id для каждого, кто оставляет сообщение',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'имя каждого, кто оставляет сообщение',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci UNIQUE NOT NULL COMMENT 'email каждого, кто оставляет сообщение',
  `homepage`  varchar(255) COLLATE utf8mb4_unicode_ci default NULL COMMENT 'homepage каждого, кто оставляет сообщение',
  `text` longtext  NOT NULL COLLATE utf8mb4_unicode_ci default NULL COMMENT 'текст сообщения каждого',
  `date` datetime NOT NULL default current_timestamp,
  `ip` int(10) unsigned NOT NULL,
  `browse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int default null COMMENT 'id зарегистрированного пользователя',
  FOREIGN KEY(`user_id`) REFERENCES registereds(`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Дамп структуры для таблица guest_book.images Картинки, которые пользователи добавляют к сообщению
DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` SERIAL PRIMARY KEY COMMENT 'уникальный id картинки',
  `post_id` bigint unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'id пользователя из таблицы users, который добавляет фото',
  FOREIGN KEY(`user_id`) REFERENCES users(`id`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

