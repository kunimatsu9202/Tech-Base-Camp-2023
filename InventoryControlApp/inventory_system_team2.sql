-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-05-17 10:58:31
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `inventory_system_team2`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL DEFAULT 0,
  `flg_delete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `products`
--

INSERT INTO `products` (`id`, `name`, `stock`, `price`, `flg_delete`) VALUES
(1, 'りんご', 13, 300, 0),
(66, 'yamada', 4, 100000, 0),
(67, 'ななし', 1, 300, 0),
(68, 'みかん', 58, 100, 0),
(69, 'イチゴ', 50, 200, 1),
(70, 'もも', 5, 450, 0),
(71, 'test', 1, 3, 1),
(88, 'ぱいん', 10, 2000, 0),
(89, 'さかな', 11, 1000, 0),
(90, 'にく', 100, 3000, 0),
(92, 'ぱんつ', 10, 3000, 0),
(93, 'メロン', 1, 80, 1),
(94, 'ねこ', 1, 1000, 1),
(95, 'いぬ', 5, 2000, 1),
(96, '魚', 1, 30, 1),
(97, 'pants', 10, 10000, 0),
(98, 'はな', 515, 30000, 0),
(103, 'あ', 0, 1, 1),
(104, 'sasaki', 1, 1, 0),
(105, 'あいうえお', 1, 1, 0),
(106, '冷蔵庫', 10, 10000, 0),
(107, 'かかと', 300, 2000, 0),
(108, 'くにまつ', 1, 20, 0),
(109, 'ほんだ', 2, 300, 0),
(110, 'aa', 1, 1, 1),
(111, 'okuda', 1, 30000, 0),
(112, 'sei', 2, 300000, 0),
(113, 'tada', 50, 1000, 1),
(114, 'kubota', 300, 20, 0),
(115, 'new product', 1000, 100000, 1),
(116, 'import_product', 100, 10000, 0),
(117, 'import_product2', 1000, 10000, 0),
(118, 'import_product3', -100, -10000, 1),
(119, 'import_product4', -1000, -10000, 1),
(120, 'あいうえおa', 1, 1, 0),
(121, 'あいうえおあいうえお', 1, 1, 1),
(122, 'あいふぉん', 20, 3000, 0),
(123, 'アンドロイド', 1, 10, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `DATE` date NOT NULL,
  `note` text DEFAULT NULL,
  `flg_delete_purchase` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `product_id`, `quantity`, `DATE`, `note`, `flg_delete_purchase`) VALUES
(91, 68, 1, '2023-05-16', 'ああああああ', 0),
(92, 66, 4, '2023-05-16', '', 1),
(93, 66, 2, '2023-05-16', '', 1),
(94, 1, 5, '2023-05-16', '', 0),
(95, 68, 4, '2023-05-16', '', 0),
(100, 68, 0, '2023-05-16', '', 0),
(101, 1, 1, '2023-05-16', '', 0),
(102, 1, 100, '2023-05-16', '', 0),
(103, 1, 100, '2023-05-16', '', 0),
(104, 92, 10, '2023-05-16', '', 0),
(105, 1, 4, '2023-05-16', '', 0),
(106, 1, 4, '2023-05-16', '', 0),
(107, 1, 4, '2023-05-16', '', 0),
(109, 1, 5, '2023-05-16', 'あ', 0),
(110, 1, 10000, '2023-05-16', '青森県産のりんごです\r\n', 0),
(120, 1, 1, '2023-05-16', '', 0),
(121, 1, 1, '2023-05-16', '', 0),
(122, 1, 1, '2023-05-16', '', 0),
(123, 1, 1, '2023-05-16', '', 0),
(124, 1, 1, '2023-05-16', '', 0),
(125, 1, 1, '2023-05-16', '', 0),
(126, 1, 1, '2023-05-16', '', 0),
(127, 1, 1, '2023-05-16', '', 0),
(128, 1, 1, '2023-05-16', '', 0),
(129, 1, 1, '2023-05-16', '', 0),
(130, 1, 1, '2023-05-16', '', 0),
(131, 1, 1, '2023-05-16', '', 0),
(132, 1, 1, '2023-05-16', '', 0),
(133, 1, 1, '2023-05-16', '', 0),
(134, 1, 1, '2023-05-16', '', 1),
(135, 1, 1, '2023-05-17', '', 1),
(136, 89, 6, '2023-05-17', '', 0),
(137, 98, 15, '2023-05-17', '', 0),
(138, 98, 5, '2023-05-17', '', 1),
(139, 103, 10, '2023-05-17', '', 1),
(140, 104, 9, '2023-05-17', '', 1),
(141, 1, 1, '2023-05-17', '', 0),
(142, 1, 1, '2023-05-17', '', 0),
(143, 1, 1, '2023-05-17', '', 0),
(144, 1, 1, '2023-05-17', '', 0),
(145, 1, 1, '2023-05-17', '', 0),
(146, 1, 1, '2023-05-17', '', 0),
(147, 115, 200, '2023-05-27', '', 1),
(148, 115, 20, '2023-05-17', '', 1),
(149, 116, 1000, '2023-05-17', '', 1),
(150, 1, 1, '2023-05-17', '', 0),
(151, 1, 1, '2023-05-17', '', 1),
(152, 66, 4, '2023-05-17', '', 1),
(153, 66, 2, '2023-05-17', '', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `password`) VALUES
(1, '佐々木', 'f93284bdcaf41c130c1d25d8a1a38815'),
(2, '國松', 'ca7fa25c2abf54ab56a36a4ab8749e79'),
(3, '山田', '53fec4cda201806226c4852e4678eaa0'),
(4, '本田', '2ab3343875e56dc0a15cbb6a98570cf2'),
(5, '奥田', '054820c93bb26affcc8f3656e1a86eee');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- テーブルの AUTO_INCREMENT `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
