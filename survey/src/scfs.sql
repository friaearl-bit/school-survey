-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2026 at 09:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scfs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','superadmin') NOT NULL DEFAULT 'admin',
  `remember_token` char(64) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `role`, `remember_token`, `first_name`, `last_name`) VALUES
(1, 'superadmin', '$2y$10$L9ICBKrEYIkoksoiuVr/A.bKQxq8ai3CHQ1S5vg1o6BdVSoJDJshy', '2026-05-22 22:44:14', 'superadmin', NULL, 'SuperA', 'Me'),
(2, 'admin', '$2y$10$oRNRJF2RVJIopYwkZgLrDOkfmhP5RX/p1JJfgZRQCV1RaQHfOJwxy', '2026-05-22 22:44:24', 'admin', NULL, 'Admin', 'One'),
(3, 'admin2', '$2y$10$/77wzfTpJ4af8GPS0bMfjelqE9KECkQY/0ft9UZWXRcGZU/Ryi9fq', '2026-05-23 00:06:04', 'admin', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answer_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answer_id`, `response_id`, `question_id`, `value`, `deleted_at`) VALUES
(1, 1, 16, 5, NULL),
(2, 1, 17, 5, NULL),
(3, 1, 18, 5, NULL),
(4, 1, 19, 4, NULL),
(5, 1, 20, 5, NULL),
(6, 1, 6, 5, NULL),
(7, 1, 7, 5, NULL),
(8, 1, 8, 4, NULL),
(9, 1, 9, 4, NULL),
(10, 1, 10, 5, NULL),
(11, 1, 21, 4, NULL),
(12, 1, 22, 5, NULL),
(13, 1, 23, 5, NULL),
(14, 1, 24, 4, NULL),
(15, 1, 25, 5, NULL),
(16, 1, 11, 5, NULL),
(17, 1, 12, 5, NULL),
(18, 1, 13, 4, NULL),
(19, 1, 14, 5, NULL),
(20, 1, 15, 5, NULL),
(21, 1, 1, 4, NULL),
(22, 1, 2, 4, NULL),
(23, 1, 3, 5, NULL),
(24, 1, 4, 4, NULL),
(25, 1, 5, 5, NULL),
(26, 2, 16, 4, NULL),
(27, 2, 17, 4, NULL),
(28, 2, 18, 4, NULL),
(29, 2, 19, 4, NULL),
(30, 2, 20, 5, NULL),
(31, 2, 6, 4, NULL),
(32, 2, 7, 5, NULL),
(33, 2, 8, 5, NULL),
(34, 2, 9, 4, NULL),
(35, 2, 10, 4, NULL),
(36, 2, 21, 4, NULL),
(37, 2, 22, 5, NULL),
(38, 2, 23, 4, NULL),
(39, 2, 24, 5, NULL),
(40, 2, 25, 5, NULL),
(41, 2, 11, 4, NULL),
(42, 2, 12, 5, NULL),
(43, 2, 13, 5, NULL),
(44, 2, 14, 5, NULL),
(45, 2, 15, 4, NULL),
(46, 2, 1, 5, NULL),
(47, 2, 2, 4, NULL),
(48, 2, 3, 4, NULL),
(49, 2, 4, 3, NULL),
(50, 2, 5, 5, NULL),
(51, 3, 16, 4, NULL),
(52, 3, 17, 4, NULL),
(53, 3, 18, 5, NULL),
(54, 3, 19, 4, NULL),
(55, 3, 20, 4, NULL),
(56, 3, 6, 4, NULL),
(57, 3, 7, 5, NULL),
(58, 3, 8, 4, NULL),
(59, 3, 9, 4, NULL),
(60, 3, 10, 5, NULL),
(61, 3, 21, 4, NULL),
(62, 3, 22, 4, NULL),
(63, 3, 23, 5, NULL),
(64, 3, 24, 4, NULL),
(65, 3, 25, 4, NULL),
(66, 3, 11, 4, NULL),
(67, 3, 12, 4, NULL),
(68, 3, 13, 4, NULL),
(69, 3, 14, 5, NULL),
(70, 3, 15, 4, NULL),
(71, 3, 1, 4, NULL),
(72, 3, 2, 5, NULL),
(73, 3, 3, 3, NULL),
(74, 3, 4, 4, NULL),
(75, 3, 5, 4, NULL),
(76, 4, 16, 4, NULL),
(77, 4, 17, 5, NULL),
(78, 4, 18, 3, NULL),
(79, 4, 19, 3, NULL),
(80, 4, 20, 5, NULL),
(81, 4, 6, 5, NULL),
(82, 4, 7, 5, NULL),
(83, 4, 8, 3, NULL),
(84, 4, 9, 5, NULL),
(85, 4, 10, 3, NULL),
(86, 4, 21, 3, NULL),
(87, 4, 22, 4, NULL),
(88, 4, 23, 5, NULL),
(89, 4, 24, 3, NULL),
(90, 4, 25, 4, NULL),
(91, 4, 11, 3, NULL),
(92, 4, 12, 4, NULL),
(93, 4, 13, 5, NULL),
(94, 4, 14, 5, NULL),
(95, 4, 15, 4, NULL),
(96, 4, 1, 5, NULL),
(97, 4, 2, 5, NULL),
(98, 4, 3, 5, NULL),
(99, 4, 4, 5, NULL),
(100, 4, 5, 3, NULL),
(101, 5, 16, 3, NULL),
(102, 5, 17, 5, NULL),
(103, 5, 18, 3, NULL),
(104, 5, 19, 3, NULL),
(105, 5, 20, 5, NULL),
(106, 5, 6, 5, NULL),
(107, 5, 7, 3, NULL),
(108, 5, 8, 4, NULL),
(109, 5, 9, 5, NULL),
(110, 5, 10, 5, NULL),
(111, 5, 21, 4, NULL),
(112, 5, 22, 5, NULL),
(113, 5, 23, 5, NULL),
(114, 5, 24, 4, NULL),
(115, 5, 25, 4, NULL),
(116, 5, 11, 4, NULL),
(117, 5, 12, 5, NULL),
(118, 5, 13, 5, NULL),
(119, 5, 14, 4, NULL),
(120, 5, 15, 4, NULL),
(121, 5, 1, 4, NULL),
(122, 5, 2, 5, NULL),
(123, 5, 3, 3, NULL),
(124, 5, 4, 4, NULL),
(125, 5, 5, 5, NULL),
(126, 6, 16, 4, NULL),
(127, 6, 17, 3, NULL),
(128, 6, 18, 3, NULL),
(129, 6, 19, 4, NULL),
(130, 6, 20, 3, NULL),
(131, 6, 6, 3, NULL),
(132, 6, 7, 3, NULL),
(133, 6, 8, 5, NULL),
(134, 6, 9, 4, NULL),
(135, 6, 10, 3, NULL),
(136, 6, 21, 4, NULL),
(137, 6, 22, 4, NULL),
(138, 6, 23, 4, NULL),
(139, 6, 24, 5, NULL),
(140, 6, 25, 4, NULL),
(141, 6, 11, 4, NULL),
(142, 6, 12, 4, NULL),
(143, 6, 13, 4, NULL),
(144, 6, 14, 4, NULL),
(145, 6, 15, 5, NULL),
(146, 6, 1, 4, NULL),
(147, 6, 2, 4, NULL),
(148, 6, 3, 3, NULL),
(149, 6, 4, 4, NULL),
(150, 6, 5, 4, NULL),
(151, 7, 16, 4, NULL),
(152, 7, 17, 5, NULL),
(153, 7, 18, 3, NULL),
(154, 7, 19, 3, NULL),
(155, 7, 20, 4, NULL),
(156, 7, 6, 5, NULL),
(157, 7, 7, 5, NULL),
(158, 7, 8, 4, NULL),
(159, 7, 9, 3, NULL),
(160, 7, 10, 4, NULL),
(161, 7, 21, 5, NULL),
(162, 7, 22, 3, NULL),
(163, 7, 23, 3, NULL),
(164, 7, 24, 3, NULL),
(165, 7, 25, 4, NULL),
(166, 7, 11, 3, NULL),
(167, 7, 12, 4, NULL),
(168, 7, 13, 3, NULL),
(169, 7, 14, 3, NULL),
(170, 7, 15, 3, NULL),
(171, 7, 1, 5, NULL),
(172, 7, 2, 3, NULL),
(173, 7, 3, 4, NULL),
(174, 7, 4, 3, NULL),
(175, 7, 5, 5, NULL),
(176, 8, 16, 5, NULL),
(177, 8, 17, 5, NULL),
(178, 8, 18, 4, NULL),
(179, 8, 19, 4, NULL),
(180, 8, 20, 5, NULL),
(181, 8, 6, 5, NULL),
(182, 8, 7, 4, NULL),
(183, 8, 8, 5, NULL),
(184, 8, 9, 5, NULL),
(185, 8, 10, 5, NULL),
(186, 8, 21, 5, NULL),
(187, 8, 22, 5, NULL),
(188, 8, 23, 5, NULL),
(189, 8, 24, 3, NULL),
(190, 8, 25, 4, NULL),
(191, 8, 11, 5, NULL),
(192, 8, 12, 5, NULL),
(193, 8, 13, 4, NULL),
(194, 8, 14, 5, NULL),
(195, 8, 15, 5, NULL),
(196, 8, 1, 4, NULL),
(197, 8, 2, 4, NULL),
(198, 8, 3, 5, NULL),
(199, 8, 4, 4, NULL),
(200, 8, 5, 5, NULL),
(201, 9, 16, 5, NULL),
(202, 9, 17, 4, NULL),
(203, 9, 18, 4, NULL),
(204, 9, 19, 4, NULL),
(205, 9, 20, 4, NULL),
(206, 9, 6, 5, NULL),
(207, 9, 7, 5, NULL),
(208, 9, 8, 5, NULL),
(209, 9, 9, 3, NULL),
(210, 9, 10, 3, NULL),
(211, 9, 21, 4, NULL),
(212, 9, 22, 4, NULL),
(213, 9, 23, 3, NULL),
(214, 9, 24, 4, NULL),
(215, 9, 25, 5, NULL),
(216, 9, 11, 4, NULL),
(217, 9, 12, 2, NULL),
(218, 9, 13, 4, NULL),
(219, 9, 14, 4, NULL),
(220, 9, 15, 4, NULL),
(221, 9, 1, 5, NULL),
(222, 9, 2, 2, NULL),
(223, 9, 3, 5, NULL),
(224, 9, 4, 5, NULL),
(225, 9, 5, 5, NULL),
(226, 10, 16, 4, NULL),
(227, 10, 17, 5, NULL),
(228, 10, 18, 5, NULL),
(229, 10, 19, 4, NULL),
(230, 10, 20, 4, NULL),
(231, 10, 6, 4, NULL),
(232, 10, 7, 5, NULL),
(233, 10, 8, 5, NULL),
(234, 10, 9, 4, NULL),
(235, 10, 10, 4, NULL),
(236, 10, 21, 4, NULL),
(237, 10, 22, 5, NULL),
(238, 10, 23, 4, NULL),
(239, 10, 24, 4, NULL),
(240, 10, 25, 5, NULL),
(241, 10, 11, 5, NULL),
(242, 10, 12, 4, NULL),
(243, 10, 13, 4, NULL),
(244, 10, 14, 4, NULL),
(245, 10, 15, 4, NULL),
(246, 10, 1, 5, NULL),
(247, 10, 2, 4, NULL),
(248, 10, 3, 4, NULL),
(249, 10, 4, 4, NULL),
(250, 10, 5, 4, NULL),
(251, 11, 16, 4, NULL),
(252, 11, 17, 5, NULL),
(253, 11, 18, 4, NULL),
(254, 11, 19, 5, NULL),
(255, 11, 20, 5, NULL),
(256, 11, 6, 4, NULL),
(257, 11, 7, 4, NULL),
(258, 11, 8, 4, NULL),
(259, 11, 9, 5, NULL),
(260, 11, 10, 5, NULL),
(261, 11, 21, 4, NULL),
(262, 11, 22, 5, NULL),
(263, 11, 23, 4, NULL),
(264, 11, 24, 5, NULL),
(265, 11, 25, 5, NULL),
(266, 11, 11, 5, NULL),
(267, 11, 12, 4, NULL),
(268, 11, 13, 5, NULL),
(269, 11, 14, 4, NULL),
(270, 11, 15, 5, NULL),
(271, 11, 1, 4, NULL),
(272, 11, 2, 4, NULL),
(273, 11, 3, 4, NULL),
(274, 11, 4, 4, NULL),
(275, 11, 5, 4, NULL),
(276, 12, 16, 5, NULL),
(277, 12, 17, 4, NULL),
(278, 12, 18, 5, NULL),
(279, 12, 19, 5, NULL),
(280, 12, 20, 5, NULL),
(281, 12, 6, 5, NULL),
(282, 12, 7, 3, NULL),
(283, 12, 8, 3, NULL),
(284, 12, 9, 3, NULL),
(285, 12, 10, 4, NULL),
(286, 12, 21, 3, NULL),
(287, 12, 22, 3, NULL),
(288, 12, 23, 4, NULL),
(289, 12, 24, 3, NULL),
(290, 12, 25, 4, NULL),
(291, 12, 11, 5, NULL),
(292, 12, 12, 4, NULL),
(293, 12, 13, 4, NULL),
(294, 12, 14, 5, NULL),
(295, 12, 15, 4, NULL),
(296, 12, 1, 3, NULL),
(297, 12, 2, 4, NULL),
(298, 12, 3, 4, NULL),
(299, 12, 4, 5, NULL),
(300, 12, 5, 4, NULL),
(301, 13, 16, 4, NULL),
(302, 13, 17, 4, NULL),
(303, 13, 18, 5, NULL),
(304, 13, 19, 5, NULL),
(305, 13, 20, 3, NULL),
(306, 13, 6, 5, NULL),
(307, 13, 7, 5, NULL),
(308, 13, 8, 4, NULL),
(309, 13, 9, 3, NULL),
(310, 13, 10, 5, NULL),
(311, 13, 21, 4, NULL),
(312, 13, 22, 4, NULL),
(313, 13, 23, 4, NULL),
(314, 13, 24, 5, NULL),
(315, 13, 25, 5, NULL),
(316, 13, 11, 4, NULL),
(317, 13, 12, 3, NULL),
(318, 13, 13, 3, NULL),
(319, 13, 14, 5, NULL),
(320, 13, 15, 4, NULL),
(321, 13, 1, 3, NULL),
(322, 13, 2, 4, NULL),
(323, 13, 3, 4, NULL),
(324, 13, 4, 5, NULL),
(325, 13, 5, 5, NULL),
(326, 14, 16, 5, NULL),
(327, 14, 17, 5, NULL),
(328, 14, 18, 4, NULL),
(329, 14, 19, 3, NULL),
(330, 14, 20, 4, NULL),
(331, 14, 6, 4, NULL),
(332, 14, 7, 4, NULL),
(333, 14, 8, 4, NULL),
(334, 14, 9, 5, NULL),
(335, 14, 10, 5, NULL),
(336, 14, 21, 5, NULL),
(337, 14, 22, 5, NULL),
(338, 14, 23, 4, NULL),
(339, 14, 24, 4, NULL),
(340, 14, 25, 5, NULL),
(341, 14, 11, 5, NULL),
(342, 14, 12, 5, NULL),
(343, 14, 13, 3, NULL),
(344, 14, 14, 3, NULL),
(345, 14, 15, 3, NULL),
(346, 14, 1, 5, NULL),
(347, 14, 2, 4, NULL),
(348, 14, 3, 4, NULL),
(349, 14, 4, 5, NULL),
(350, 14, 5, 4, NULL),
(351, 15, 16, 4, NULL),
(352, 15, 17, 5, NULL),
(353, 15, 18, 5, NULL),
(354, 15, 19, 4, NULL),
(355, 15, 20, 5, NULL),
(356, 15, 6, 5, NULL),
(357, 15, 7, 4, NULL),
(358, 15, 8, 5, NULL),
(359, 15, 9, 4, NULL),
(360, 15, 10, 5, NULL),
(361, 15, 21, 4, NULL),
(362, 15, 22, 5, NULL),
(363, 15, 23, 3, NULL),
(364, 15, 24, 5, NULL),
(365, 15, 25, 5, NULL),
(366, 15, 11, 5, NULL),
(367, 15, 12, 5, NULL),
(368, 15, 13, 5, NULL),
(369, 15, 14, 4, NULL),
(370, 15, 15, 4, NULL),
(371, 15, 1, 5, NULL),
(372, 15, 2, 5, NULL),
(373, 15, 3, 3, NULL),
(374, 15, 4, 3, NULL),
(375, 15, 5, 3, NULL),
(376, 16, 16, 5, NULL),
(377, 16, 17, 4, NULL),
(378, 16, 18, 3, NULL),
(379, 16, 19, 3, NULL),
(380, 16, 20, 3, NULL),
(381, 16, 6, 5, NULL),
(382, 16, 7, 4, NULL),
(383, 16, 8, 5, NULL),
(384, 16, 9, 5, NULL),
(385, 16, 10, 4, NULL),
(386, 16, 21, 4, NULL),
(387, 16, 22, 4, NULL),
(388, 16, 23, 4, NULL),
(389, 16, 24, 5, NULL),
(390, 16, 25, 4, NULL),
(391, 16, 11, 5, NULL),
(392, 16, 12, 5, NULL),
(393, 16, 13, 3, NULL),
(394, 16, 14, 4, NULL),
(395, 16, 15, 5, NULL),
(396, 16, 1, 4, NULL),
(397, 16, 2, 4, NULL),
(398, 16, 3, 3, NULL),
(399, 16, 4, 5, NULL),
(400, 16, 5, 5, NULL),
(401, 17, 16, 4, NULL),
(402, 17, 17, 4, NULL),
(403, 17, 18, 5, NULL),
(404, 17, 19, 4, NULL),
(405, 17, 20, 5, NULL),
(406, 17, 6, 4, NULL),
(407, 17, 7, 5, NULL),
(408, 17, 8, 4, NULL),
(409, 17, 9, 5, NULL),
(410, 17, 10, 5, NULL),
(411, 17, 21, 4, NULL),
(412, 17, 22, 4, NULL),
(413, 17, 23, 4, NULL),
(414, 17, 24, 3, NULL),
(415, 17, 25, 5, NULL),
(416, 17, 11, 4, NULL),
(417, 17, 12, 4, NULL),
(418, 17, 13, 3, NULL),
(419, 17, 14, 5, NULL),
(420, 17, 15, 4, NULL),
(421, 17, 1, 5, NULL),
(422, 17, 2, 4, NULL),
(423, 17, 3, 4, NULL),
(424, 17, 4, 5, NULL),
(425, 17, 5, 4, NULL),
(426, 18, 16, 4, NULL),
(427, 18, 17, 5, NULL),
(428, 18, 18, 3, NULL),
(429, 18, 19, 5, NULL),
(430, 18, 20, 5, NULL),
(431, 18, 6, 4, NULL),
(432, 18, 7, 5, NULL),
(433, 18, 8, 5, NULL),
(434, 18, 9, 5, NULL),
(435, 18, 10, 4, NULL),
(436, 18, 21, 4, NULL),
(437, 18, 22, 5, NULL),
(438, 18, 23, 3, NULL),
(439, 18, 24, 5, NULL),
(440, 18, 25, 3, NULL),
(441, 18, 11, 3, NULL),
(442, 18, 12, 5, NULL),
(443, 18, 13, 4, NULL),
(444, 18, 14, 4, NULL),
(445, 18, 15, 4, NULL),
(446, 18, 1, 5, NULL),
(447, 18, 2, 4, NULL),
(448, 18, 3, 3, NULL),
(449, 18, 4, 5, NULL),
(450, 18, 5, 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `text` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `response_id`, `text`, `deleted_at`) VALUES
(1, 1, 'Puts reasonable amount of time at teaching and was able to let us have fun at the end of semester. Good teacher.', NULL),
(2, 2, 'Makes subject fun and enjoyable. The subject is hard but manageable and I was able to grasp hard concepts.', NULL),
(3, 3, 'Hard and too many lessons for my brain to handle. Spent a good amount of time studying but was worth it till the end. Awesome', NULL),
(4, 4, 'Overall good but needs to improve explanation on lessons. Its hard learning another language I\'m not used at.', NULL),
(5, 5, 'The teacher provides a supportive environment where mistakes are treated as learning opportunities, encouraging risk-taking and deeper inquiry. Lessons incorporate multimedia and hands-on activities that cater to visual and kinesthetic learners. I value the individualized feedback on major projects, which pinpoints strengths and improvement areas. Parent communication is regular and informative, though more frequent updates on small assessments would help track progress. Consider adjusting pacing for units that feel rushed to allow for deeper mastery.', NULL),
(6, 6, 'Could benefit more with few breaks in between lessons', NULL),
(7, 7, 'Not my area of expertise but teacher was able to teach most of my problems in this subject. Overall good.', NULL),
(8, 8, 'Magaling magturo at madaling maintindihan ang mga paliwanag.', NULL),
(9, 9, 'Talks too fast for me to understand.', NULL),
(10, 10, 'Very good teacher. I was immersed throughout the whole lesson. Good explanation and makes the subject manageable.', NULL),
(11, 11, 'I was able to understand most of the part but pacing is too fast. Still shows mastery at subject matter.', NULL),
(12, 12, 'Overall good. no more comments needed.', NULL),
(13, 13, 'Impressive teaching method. Would like to spend another year with same teacher and subject.', NULL),
(14, 14, 'The teacher plans lessons that build logically from basic concepts to more advanced topics, and uses formative checks to ensure understanding before moving on. Assessment rubrics are generally helpful, but sometimes assignment instructions lack detail which causes confusion about expectations. Class discussions are moderated well to include diverse viewpoints, and the teacher often connects topics to current events to increase relevance. I appreciate the regular review sessions before tests, which reduce anxiety and improve performance. More differentiated support for students who are behind would be beneficial.', NULL),
(15, 15, 'Maybe needs more improvement at explaining stuff.', NULL),
(16, 16, 'Wow.', NULL),
(17, 17, 'Unbelievable.', NULL),
(18, 18, 'The teacher provides a supportive environment where mistakes are treated as learning opportunities, encouraging risk-taking and deeper inquiry. Lessons incorporate multimedia and hands-on activities that cater to visual and kinesthetic learners. I value the individualized feedback on major projects, which pinpoints strengths and improvement areas. Parent communication is regular and informative, though more frequent updates on small assessments would help track progress. Consider adjusting pacing for units that feel rushed to allow for deeper mastery.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `category_ph` varchar(100) DEFAULT NULL,
  `text` text NOT NULL,
  `text_ph` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `category`, `category_ph`, `text`, `text_ph`, `updated_at`, `updated_by`) VALUES
(1, 'Teaching Skills', 'Kasanayan sa Pagtuturo', 'The teacher explains lessons through clear and understandable teaching methods.', 'Ipinaliwanag ng guro ang mga aralin sa pamamagitan ng malinaw at maintindihang pamamaraan ng pagtuturo.', NULL, NULL),
(2, 'Teaching Skills', 'Kasanayan sa Pagtuturo', 'The teacher demonstrates real-life situations through relevant examples.', 'Ipinapakita ng guro ang mga sitwasyon sa totoong buhay gamit ang mga kaugnay na halimbawa.', NULL, NULL),
(3, 'Teaching Skills', 'Kasanayan sa Pagtuturo', 'The teacher uses visual aids and technology and activities to assist in his explanations.', 'Gumagamit ang guro ng mga biswal na pantulong, teknolohiya, at mga gawain upang tumulong sa kanyang mga paliwanag.', NULL, NULL),
(4, 'Teaching Skills', 'Kasanayan sa Pagtuturo', 'The teacher assesses student comprehension before proceeding to the subsequent lesson.', 'Sinusuri ng guro ang pagkaunawa ng mga estudyante bago magpatuloy sa susunod na aralin.', NULL, NULL),
(5, 'Teaching Skills', 'Kasanayan sa Pagtuturo', 'The teacher provides answers to student inquiries with both patience and complete details.', 'Nagbibigay ang guro ng mga sagot sa mga tanong ng estudyante nang may pasensya at buong detalye.', NULL, NULL),
(6, 'Classroom Management & Behavior', 'Pamamahala ng Silid-Aralan at Pag-uugali', 'The teacher starts and ends class on time.', 'Nagsisimula at nagtatapos ang klase ng guro nang oras.', NULL, NULL),
(7, 'Classroom Management & Behavior', 'Pamamahala ng Silid-Aralan at Pag-uugali', 'The teacher maintains order and discipline in the classroom.', 'Pinananatili ng guro ang kaayusan at disiplina sa silid-aralan.', NULL, NULL),
(8, 'Classroom Management & Behavior', 'Pamamahala ng Silid-Aralan at Pag-uugali', 'The teacher controls noise levels to create an environment where all students can concentrate.', 'Kinokontrol ng guro ang antas ng ingay upang makalikha ng kapaligiran kung saan makakapagtuon ang lahat ng estudyante.', NULL, NULL),
(9, 'Classroom Management & Behavior', 'Pamamahala ng Silid-Aralan at Pag-uugali', 'The teacher handles student behavior and conflict situations through fair methods.', 'Hinahandle ng guro ang pag-uugali ng estudyante at mga sitwasyon ng tunggalian sa makatarungang paraan.', NULL, NULL),
(10, 'Classroom Management & Behavior', 'Pamamahala ng Silid-Aralan at Pag-uugali', 'The teacher is approachable and easy to talk to.', 'Madaling lapakin at kausapin ang guro.', NULL, NULL),
(11, 'Student Engagement & Encouragement', 'Pagsali at Paghihikayat ng Estudyante', 'The teacher encourages students to share their ideas and opinions.', 'Hinihikayat ng guro ang mga estudyante na ibahagi ang kanilang mga ideya at opinyon.', NULL, NULL),
(12, 'Student Engagement & Encouragement', 'Pagsali at Paghihikayat ng Estudyante', 'The teacher makes the lessons interesting and fun.', 'Ginagawang kawili-wili at masaya ng guro ang mga aralin.', NULL, NULL),
(13, 'Student Engagement & Encouragement', 'Pagsali at Paghihikayat ng Estudyante', 'The teacher pushes students to reach their highest potential.', 'Hinihikayat ng guro ang mga estudyante na maabot ang kanilang pinakamataas na potensyal.', NULL, NULL),
(14, 'Student Engagement & Encouragement', 'Pagsali at Paghihikayat ng Estudyante', 'The teacher treats all students equally regardless of gender or background.', 'Itinuturing ng guro ang lahat ng estudyante nang pantay kahit ano pa man ang kasarian o pinagmulan.', NULL, NULL),
(15, 'Student Engagement & Encouragement', 'Pagsali at Paghihikayat ng Estudyante', 'The teacher recognizes students who participate and do well.', 'Kinikilala ng guro ang mga estudyanteng lumalahok at mahusay.', NULL, NULL),
(16, 'Assignments & Feedback', 'Mga Takdang-Aralin at Katugunan', 'The assignments and projects help me understand the lesson better.', 'Nakakatulong ang mga takdang-aralin at proyekto upang mas maintindihan ko ang aralin.', NULL, NULL),
(17, 'Assignments & Feedback', 'Mga Takdang-Aralin at Katugunan', 'The homework requirements for this course establish a reasonable workload that students can manage.', 'Ang mga kinakailangan sa takdang-aralin para sa kursong ito ay nagtatakda ng makatwirang dami ng gawain na kayang pamahalaan ng mga estudyante.', NULL, NULL),
(18, 'Assignments & Feedback', 'Mga Takdang-Aralin at Katugunan', 'The teacher returns graded assignments on time.', 'Ibinabalik ng guro ang mga na-markang takdang-aralin nang nasa oras.', NULL, NULL),
(19, 'Assignments & Feedback', 'Mga Takdang-Aralin at Katugunan', 'The teacher provides useful feedback through comments about my work.', 'Nagbibigay ang guro ng kapaki-pakinabang na puna sa pamamagitan ng mga komento tungkol sa aking gawain.', NULL, NULL),
(20, 'Assignments & Feedback', 'Mga Takdang-Aralin at Katugunan', 'The quizzes and tests assess students fairly based on the material that teachers delivered.', 'Tinutasa ng mga pagsusulit at test ang mga estudyante nang patas batay sa mga materyal na ibinigay ng guro.', NULL, NULL),
(21, 'Professionalism & Attitude', 'Propesyonalismo at Ugali', 'The teacher is respectful to all students.', 'Magalang ang guro sa lahat ng estudyante.', NULL, NULL),
(22, 'Professionalism & Attitude', 'Propesyonalismo at Ugali', 'The teacher confesses his errors and proceeds to fix his mistakes.', 'Inamin ng guro ang kanyang mga pagkakamali at nagsusumikap na itama ang mga ito.', NULL, NULL),
(23, 'Professionalism & Attitude', 'Propesyonalismo at Ugali', 'The teacher shows patience to students who face difficulties with their work.', 'Ipinapakita ng guro ang pasensya sa mga estudyanteng nahihirapan sa kanilang gawain.', NULL, NULL),
(24, 'Professionalism & Attitude', 'Propesyonalismo at Ugali', 'The teacher dresses in a manner that matches professional standards for his teaching role.', 'Nagmumukhang propesyonal ang pananamit ng guro na angkop sa kanyang tungkulin sa pagtuturo.', NULL, NULL),
(25, 'Professionalism & Attitude', 'Propesyonalismo at Ugali', 'The teacher arrives prepared with lesson plans and materials.', 'Dumarating ang guro nang handa na may mga plano sa aralin at mga materyales.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE `response` (
  `response_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `survey_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `response`
--

INSERT INTO `response` (`response_id`, `student_id`, `survey_id`, `target_id`, `section_id`, `created_at`, `is_anonymous`, `deleted_at`) VALUES
(1, 1, 1, 1, 1, '2026-05-02 09:50:51', 0, NULL),
(2, 1, 1, 3, 1, '2026-05-02 09:53:23', 0, NULL),
(3, 2, 1, 4, 2, '2026-06-03 13:16:11', 0, NULL),
(4, 2, 1, 5, 2, '2026-05-03 13:19:29', 0, NULL),
(5, NULL, 1, 2, 2, '2026-05-04 10:02:12', 1, NULL),
(6, NULL, 1, 1, 2, '2026-05-04 10:02:50', 1, NULL),
(7, NULL, 1, 4, 2, '2026-05-04 10:03:59', 1, NULL),
(8, 3, 1, 5, 3, '2026-05-06 12:43:35', 0, NULL),
(9, 3, 1, 1, 3, '2026-06-07 12:46:19', 0, NULL),
(10, 8, 1, 3, 3, '2026-05-08 16:38:39', 0, NULL),
(11, 8, 1, 4, 3, '2026-05-08 16:41:06', 0, NULL),
(12, 4, 1, 1, 1, '2026-05-10 11:09:36', 0, NULL),
(13, 4, 1, 2, 1, '2026-05-11 11:12:17', 0, NULL),
(14, NULL, 1, 5, 2, '2026-05-13 22:53:55', 0, NULL),
(15, NULL, 1, 1, 2, '2026-05-13 22:55:56', 0, NULL),
(16, 5, 1, 1, 3, '2026-05-18 12:41:15', 0, NULL),
(17, NULL, 1, 2, 3, '2026-05-20 12:00:18', 0, NULL),
(18, NULL, 1, 1, 3, '2026-06-01 13:14:38', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `section_count` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `name`, `section_count`, `created_at`, `updated_by`, `deleted_by`, `updated_at`, `deleted_at`) VALUES
(1, 'STEM A', 30, '2026-05-30 11:26:05', NULL, NULL, '2026-05-30 12:06:50', NULL),
(2, 'STEM B', 25, '2026-05-30 11:26:05', NULL, NULL, '2026-05-30 12:07:29', NULL),
(3, 'STEM C', 20, '2026-05-30 11:26:05', NULL, NULL, '2026-05-30 12:07:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `given_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `student_number` varchar(100) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `surname`, `middle_name`, `given_name`, `email`, `student_number`, `section_id`, `deleted_at`, `updated_at`, `updated_by`) VALUES
(1, 'Alvarez', NULL, 'Maria', 'maria.alvarez@example.com', '2024-56178-MN-0', 1, NULL, NULL, NULL),
(2, 'Ibarra', 'S.', 'Noel', 'noel.ibarra@example.com', '2024-80987-MN-0', 2, NULL, '2026-06-01 12:49:30', NULL),
(3, 'Doe', NULL, 'Jane', 'janedoe@example.com', '2024-97560-MN-0', 3, NULL, '2026-06-01 12:52:50', NULL),
(4, 'Dela Cruz', 'Soria', 'Juan', 'email@school.edu', '2023-08807-MN-0', 3, NULL, '2026-06-01 12:55:17', NULL),
(5, 'Torres', NULL, 'Yuri', 'yuri.torres@example.com', '2024-09879-MN-0', 3, NULL, '2026-06-01 13:02:06', NULL),
(6, 'Nieves', NULL, 'Sara', 'sara.nieves@example.com', '2024-08097-MN-0', 1, NULL, '2026-06-01 15:53:59', NULL),
(7, 'Ferrer', 'Ling', 'Iris', 'iris.ferrer@example.com', '2024-09600-MN-0', 2, NULL, '2026-06-01 14:54:28', 1),
(8, 'Ibarra', 'S.', 'Joel', 'joel.ibarra@example.com', '2024-80988-MN-0', 2, NULL, '2026-06-01 15:54:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `survey`
--

CREATE TABLE `survey` (
  `survey_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey`
--

INSERT INTO `survey` (`survey_id`, `title`, `description`, `updated_at`, `updated_by`) VALUES
(1, 'Teacher Effectiveness and Classroom Experience Survey', 'This survey gathers student feedback on key aspects of teaching and classroom environment. It covers five categories: Teaching Skills, Classroom Management & Behavior, Student Engagement & Encouragement, Assignments & Feedback, and Professionalism & Attitude. Your responses will help identify strengths and areas for improvement for a more effective and supportive learning experience.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `target`
--

CREATE TABLE `target` (
  `target_id` int(11) NOT NULL,
  `instructor` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `target`
--

INSERT INTO `target` (`target_id`, `instructor`, `subject`, `updated_at`, `updated_by`) VALUES
(1, 'Alexandria Reyes', 'Effective Communication', NULL, NULL),
(2, 'Maverick Evander', 'Life and Career Skills', NULL, NULL),
(3, 'Ronan Slade Veniel', 'General Mathematics', NULL, NULL),
(4, 'Bianca Montevend', 'General Science', NULL, NULL),
(5, 'Nabia Gatchalian', 'Pag-aaral ng Kasaysayan at Lipunang Pilipino', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_remember_token` (`remember_token`);

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD UNIQUE KEY `uq_feedback_response` (`response_id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `idx_updated_at` (`updated_at`);

--
-- Indexes for table `response`
--
ALTER TABLE `response`
  ADD PRIMARY KEY (`response_id`),
  ADD UNIQUE KEY `uq_response_student_survey_target` (`student_id`,`survey_id`,`target_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `survey_id` (`survey_id`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `idx_deleted_at` (`deleted_at`),
  ADD KEY `idx_updated_at` (`updated_at`);

--
-- Indexes for table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`survey_id`),
  ADD KEY `idx_updated_at` (`updated_at`);

--
-- Indexes for table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`target_id`),
  ADD KEY `idx_updated_at` (`updated_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=451;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `response`
--
ALTER TABLE `response`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `target_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_answer_response` FOREIGN KEY (`response_id`) REFERENCES `response` (`response_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_response` FOREIGN KEY (`response_id`) REFERENCES `response` (`response_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `response`
--
ALTER TABLE `response`
  ADD CONSTRAINT `fk_response_section` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_response_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_response_survey` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`survey_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_response_target` FOREIGN KEY (`target_id`) REFERENCES `target` (`target_id`) ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_section` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
