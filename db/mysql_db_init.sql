-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: 2018 年 6 月 20 日 18:30
-- サーバのバージョン： 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `StoreTaskManager`
--
CREATE DATABASE IF NOT EXISTS `StoreTaskManager` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `StoreTaskManager`;

-- --------------------------------------------------------

--
-- テーブルの構造 `dailytasks`
--

CREATE TABLE `dailytasks` (
  `id` int(11) NOT NULL,
  `department` text,
  `date` date NOT NULL,
  `date_time` text NOT NULL,
  `caption` text NOT NULL,
  `detail` text,
  `work_time_h` int(3) DEFAULT NULL,
  `work_time_m` int(3) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `dep_index` int(11) NOT NULL,
  `is_sub` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `department` int(11) DEFAULT NULL,
  `detail` text,
  `date_time` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `password` text,
  `name` text,
  `area` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `stores`
--

INSERT INTO `stores` (`id`, `password`, `name`, `area`) VALUES
(1, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '会社名', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `caption` text NOT NULL,
  `detail` text,
  `situation` text,
  `update_date` date DEFAULT NULL,
  `update_date_time` text,
  `update_user` text,
  `department` text,
  `created_date` date DEFAULT NULL,
  `created_date_time` text,
  `due_date` date DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `worker` text NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `completed_user` int(11) NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `user_id` text,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` int(11) DEFAULT NULL,
  `require_password` int(11) NOT NULL DEFAULT '0',
  `department` text,
  `name` text,
  `password` text,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `memo` text NOT NULL,
  `store` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dailytasks`
--
ALTER TABLE `dailytasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dailytasks`
--
ALTER TABLE `dailytasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
