-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2021-09-23 13:41:48
-- サーバのバージョン： 10.4.21-MariaDB
-- PHP のバージョン: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `php_todoapp`
--
CREATE DATABASE IF NOT EXISTS `todo_phpapp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `todo_phpapp`;

-- --------------------------------------------------------

--
-- テーブルの構造 `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `detail` text DEFAULT NULL,
  `done` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `detail`, `done`, `user_id`) VALUES
(1, 'PHPを使えるようになる', '・PHPの基礎\r\n・データベース連携\r\n・セキュリティ対策\r\n・フレームワークの使い方', 0, 1),
(2, 'JavaScriptを使えるようになる', '・JavaScriptの基本構文\r\n・DOMの操作\r\n・外部サーバとの通信/データ取得(ajax)\r\n・フレームワークの使い方', 0, 1),
(3, 'サーバー構築ができるようになる', '・Linuxコマンドラインの基礎\r\n・Linuxのファイルシステムを覚える\r\n・OSインストール手順を覚える\r\n・パッケージ管理の仕組みを理解する', 0, 2),
(4, 'サーバー間通信ができるようになる', '・ネットワーク機器の種類と役割を知る\r\n・TCP/IPの通信の仕組みを覚える\r\n・ブリッジネットワークを作ってみる\r\n・ルーティングプロトコルを勉強する\r\n・別のネットワークをつないで、パケット送信を設定できるようになる', 0, 2),
(5, 'CSSを使えるようになる', '・HTMLのタグの種類と役割を知る\r\n・CSSのセレクタの使い方を学ぶ\r\n・CSSのプロパティの使い方を覚える\r\n・CSSでの要素配置の概念を知る\r\n・CSSフレームワークを学ぶ', 0, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'user001', 'user001@example.com', '97f3c717da19b4697ae9884e67aabce6'),
(2, 'user002', 'user002@example.com', '8b63b2922634ffaeab2019e50f13dd20');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
