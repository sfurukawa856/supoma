-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2021-04-02 15:45:50
-- サーバのバージョン： 10.4.17-MariaDB
-- PHP のバージョン: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `supoma`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `chat_message` varchar(200) NOT NULL,
  `comment_user_id` int(11) NOT NULL,
  `post_user_id` int(11) NOT NULL,
  `chat_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_insert_date` text DEFAULT NULL,
  `recruitment` varchar(191) DEFAULT NULL,
  `joining` varchar(191) DEFAULT NULL,
  `joining_id` int(11) NOT NULL,
  `application` varchar(191) DEFAULT NULL,
  `approval` varchar(191) DEFAULT NULL,
  `result` varchar(191) DEFAULT NULL,
  `result_no` varchar(191) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `count` int(11) NOT NULL,
  `insert_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `userinfor`
--

CREATE TABLE `userinfor` (
  `user_id` int(11) NOT NULL,
  `nickname` varchar(191) NOT NULL,
  `sports` varchar(191) NOT NULL,
  `sex` varchar(11) NOT NULL,
  `age` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `insert_time` datetime NOT NULL DEFAULT current_timestamp(),
  `update_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `userpost`
--

CREATE TABLE `userpost` (
  `post_id` int(11) NOT NULL,
  `userpost_id` int(11) NOT NULL,
  `title` varchar(191) NOT NULL,
  `category` varchar(191) NOT NULL,
  `member` int(11) NOT NULL,
  `eventDate` datetime NOT NULL,
  `insert_date` datetime NOT NULL DEFAULT current_timestamp(),
  `place` varchar(191) NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL,
  `message` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `update_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `post_user_id` (`post_user_id`);

--
-- テーブルのインデックス `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`);

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- テーブルのインデックス `userinfor`
--
ALTER TABLE `userinfor`
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `file_path` (`file_path`);

--
-- テーブルのインデックス `userpost`
--
ALTER TABLE `userpost`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `file_path` (`file_path`),
  ADD KEY `userpost_id` (`userpost_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- テーブルの AUTO_INCREMENT `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- テーブルの AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- テーブルの AUTO_INCREMENT `userpost`
--
ALTER TABLE `userpost`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_id` FOREIGN KEY (`chat_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `post_user_id` FOREIGN KEY (`post_user_id`) REFERENCES `userpost` (`post_id`);

--
-- テーブルの制約 `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_id` FOREIGN KEY (`news_id`) REFERENCES `user` (`id`);

--
-- テーブルの制約 `userinfor`
--
ALTER TABLE `userinfor`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- テーブルの制約 `userpost`
--
ALTER TABLE `userpost`
  ADD CONSTRAINT `userpost_id` FOREIGN KEY (`userpost_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
