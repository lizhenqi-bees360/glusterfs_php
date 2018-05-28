-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-04-15 19:56:09
-- 服务器版本： 5.6.23-log
-- PHP Version: 5.4.41

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `glusterfs_web`
--

-- --------------------------------------------------------

--
-- 表的结构 `brick_list`
--

CREATE TABLE IF NOT EXISTS `brick_list` (
  `name` varchar(200) NOT NULL,
  `host_uuid` varchar(50) NOT NULL,
  `host_ip` varchar(15) NOT NULL,
  `is_arbiter` int(10) NOT NULL,
  `volume_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `brick_list`
--

INSERT INTO `brick_list` (`name`, `host_uuid`, `host_ip`, `is_arbiter`, `volume_id`) VALUES
('192.168.1.132:/data/brick1/gv0', '1e3af436-3318-4f30-8067-6530cbdca9cb', '192.168.1.132', 0, '3459e6ee-1437-46fc-9902-65f5c5e85611'),
('192.168.1.132:/data/brick1/gv1', '1e3af436-3318-4f30-8067-6530cbdca9cb', '192.168.1.132', 0, 'd0cd77b5-bffa-4c9a-9bb1-717e2b69c776'),
('192.168.1.132:/data/brick1/gv3', '1e3af436-3318-4f30-8067-6530cbdca9cb', '192.168.1.132', 0, '913fa163-3ed2-4ed1-9268-91bc1bb4537a'),
('192.168.1.132:/data/brick2/gv2', '1e3af436-3318-4f30-8067-6530cbdca9cb', '192.168.1.132', 0, '574bc928-9d6d-4876-9e33-4b2f816b3f71'),
('192.168.1.155:/data/brick1/gv0', 'eb203100-4dab-42cd-8e99-43f843c8630d', '192.168.1.155', 0, '3459e6ee-1437-46fc-9902-65f5c5e85611'),
('192.168.1.155:/data/brick1/gv1', 'eb203100-4dab-42cd-8e99-43f843c8630d', '192.168.1.155', 0, 'd0cd77b5-bffa-4c9a-9bb1-717e2b69c776'),
('192.168.1.155:/data/brick1/gv3', 'eb203100-4dab-42cd-8e99-43f843c8630d', '192.168.1.155', 0, '913fa163-3ed2-4ed1-9268-91bc1bb4537a'),
('192.168.1.196:/data/brick2/gv2', '2c07c978-899d-40ad-b9ac-79a1a240a153', '192.168.1.196', 0, '574bc928-9d6d-4876-9e33-4b2f816b3f71');

-- --------------------------------------------------------

--
-- 表的结构 `client_list`
--

CREATE TABLE IF NOT EXISTS `client_list` (
  `ip` varchar(50) NOT NULL,
  `account` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `gluster_list`
--

CREATE TABLE IF NOT EXISTS `gluster_list` (
  `ip` varchar(15) NOT NULL,
  `status` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `gluster_list`
--

INSERT INTO `gluster_list` (`ip`, `status`) VALUES
('192.168.1.132', '3.8.15'),
('192.168.1.155', '3.8.15'),
('192.168.1.196', '3.8.15');

-- --------------------------------------------------------

--
-- 表的结构 `host_list`
--

CREATE TABLE IF NOT EXISTS `host_list` (
  `ip` varchar(15) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `host_list`
--

INSERT INTO `host_list` (`ip`, `status`) VALUES
('192.168.1.132', 'up'),
('192.168.1.155', 'up'),
('192.168.1.196', 'up'),
('192.168.150.182', 'unknown');

-- --------------------------------------------------------

--
-- 表的结构 `node_list`
--

CREATE TABLE IF NOT EXISTS `node_list` (
  `ip` varchar(15) NOT NULL,
  `uuid` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `node_list`
--

INSERT INTO `node_list` (`ip`, `uuid`, `status`) VALUES
('192.168.1.132', NULL, 'active'),
('192.168.1.155', NULL, 'active'),
('192.168.1.196', NULL, 'active');

-- --------------------------------------------------------

--
-- 表的结构 `storage_list`
--

CREATE TABLE IF NOT EXISTS `storage_list` (
  `id` varchar(150) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `place` varchar(128) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `storage_list`
--

INSERT INTO `storage_list` (`id`, `ip`, `place`, `is_used`) VALUES
('192.168.1.132:/data/brick1/gv3', '192.168.1.132', '/data/brick1/gv3', 1),
('192.168.1.132:/data/brick2/gv0', '192.168.1.132', '/data/brick2/gv0', 0),
('192.168.1.132:/data/brick2/gv1', '192.168.1.132', '/data/brick2/gv1', 0),
('192.168.1.132:/data/brick2/gv2', '192.168.1.132', '/data/brick2/gv2', 1),
('192.168.1.155:/data/brick1/gv3', '192.168.1.155', '/data/brick1/gv3', 1),
('192.168.1.155:/data/brick2/gv0', '192.168.1.155', '/data/brick2/gv0', 0),
('192.168.1.155:/data/brick2/gv1', '192.168.1.155', '/data/brick2/gv1', 0),
('192.168.1.196:/data/brick2/gv2', '192.168.1.196', '/data/brick2/gv2', 1);

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(2, 'root', 'hUEJO32RWW.ce352383391fc6b0e5a5a2fe946c7015', '123456@qq.com', 1),
(3, 'andy', 'o7Qilqkv2q.2c3bae993a30b8c712e1fd996a403cf4', '12345622@qq.com', 4),
(5, 'user1', 'K2sesKNr6S.8382021b477e9df7b1cbbb348e911f3e', '1234522@qq.com', 2),
(6, 'user2', '5BpO08e9a2.9450f2a4d6bb09f8f1e52a06d2933adf', '12345600@qq.com', 3);

-- --------------------------------------------------------

--
-- 表的结构 `volume_list`
--

CREATE TABLE IF NOT EXISTS `volume_list` (
  `id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` int(10) DEFAULT NULL,
  `status_str` varchar(50) DEFAULT NULL,
  `snapshot_count` int(10) DEFAULT NULL,
  `brick_count` int(10) DEFAULT NULL,
  `dist_count` int(10) DEFAULT NULL,
  `stripe_count` int(10) DEFAULT NULL,
  `replica_count` int(10) DEFAULT NULL,
  `arbiter_count` int(10) DEFAULT NULL,
  `disperse_count` int(10) DEFAULT NULL,
  `redundancy_count` int(10) DEFAULT NULL,
  `type` int(10) DEFAULT NULL,
  `type_str` varchar(50) DEFAULT NULL,
  `transport` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `volume_list`
--

INSERT INTO `volume_list` (`id`, `name`, `status`, `status_str`, `snapshot_count`, `brick_count`, `dist_count`, `stripe_count`, `replica_count`, `arbiter_count`, `disperse_count`, `redundancy_count`, `type`, `type_str`, `transport`) VALUES
('3459e6ee-1437-46fc-9902-65f5c5e85611', 'gv0', 1, 'Started', 0, 2, 2, 1, 2, 0, 0, 0, 2, 'Replicate', 'tcp'),
('574bc928-9d6d-4876-9e33-4b2f816b3f71', 'gv2', 1, 'Started', 0, 2, 2, 1, 2, 0, 0, 0, 2, 'Replicate', 'rdma'),
('913fa163-3ed2-4ed1-9268-91bc1bb4537a', 'gv3', 1, 'Started', 0, 2, 1, 1, 1, 0, 0, 0, 0, 'Distribute', 'tcp,rdma'),
('d0cd77b5-bffa-4c9a-9bb1-717e2b69c776', 'gv1', 1, 'Started', 0, 2, 2, 1, 2, 0, 0, 0, 2, 'Replicate', 'tcp');

-- --------------------------------------------------------

--
-- 表的结构 `volume_owner`
--

CREATE TABLE IF NOT EXISTS `volume_owner` (
  `id` int(10) NOT NULL,
  `volume_id` varchar(50) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `volume_owner`
--

INSERT INTO `volume_owner` (`id`, `volume_id`, `user_id`) VALUES
(1, '3459e6ee-1437-46fc-9902-65f5c5e85611', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brick_list`
--
ALTER TABLE `brick_list`
  ADD PRIMARY KEY (`name`),
  ADD KEY `volume_id` (`volume_id`),
  ADD KEY `host_ip` (`host_ip`);

--
-- Indexes for table `client_list`
--
ALTER TABLE `client_list`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `gluster_list`
--
ALTER TABLE `gluster_list`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `host_list`
--
ALTER TABLE `host_list`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `node_list`
--
ALTER TABLE `node_list`
  ADD PRIMARY KEY (`ip`),
  ADD KEY `node_uuid` (`uuid`);

--
-- Indexes for table `storage_list`
--
ALTER TABLE `storage_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `volume_list`
--
ALTER TABLE `volume_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `volume_owner`
--
ALTER TABLE `volume_owner`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `volume_owner`
--
ALTER TABLE `volume_owner`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
