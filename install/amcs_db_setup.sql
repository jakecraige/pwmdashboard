-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2013 at 08:28 AM
-- Server version: 5.5.23
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amcs`
--

-- --------------------------------------------------------

--
-- Table structure for table `current_status`
--

CREATE TABLE IF NOT EXISTS `current_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `current_status`
--

INSERT INTO `current_status` (`id`, `status`) VALUES
(1, 'New'),
(7, 'Remote Actions Completed'),
(3, 'On-Site Actions Completed'),
(4, 'Company Tech Dispatched'),
(9, 'Tech On Site'),
(11, 'Call dispatched to PWM');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `event` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=756 ;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `activity` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `sql` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `success` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `unix_timestamp` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `pc_manufacturers`
--

CREATE TABLE IF NOT EXISTS `pc_manufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `pending_queries`
--

CREATE TABLE IF NOT EXISTS `pending_queries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `added_ts` int(11) NOT NULL,
  `execution_ts` int(20) NOT NULL,
  `is_processing` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE IF NOT EXISTS `problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `event` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(20) NOT NULL,
  `linked_events` text COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `status_timestamp` int(20) NOT NULL,
  `notes` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `previous_status` text COLLATE utf8_unicode_ci NOT NULL,
  `resolution_type` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `resolution` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `resolution_timestamp` int(20) NOT NULL,
  `fixed_action` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=172 ;

-- --------------------------------------------------------

--
-- Table structure for table `resolution_type`
--

CREATE TABLE IF NOT EXISTS `resolution_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `resolution_type`
--

INSERT INTO `resolution_type` (`id`, `type`) VALUES
(1, 'Fixed Remotely'),
(2, 'Fixed by Contractor'),
(3, 'Warranty - Fixed by PWM'),
(7, 'Fixed by In House Tech');

-- --------------------------------------------------------

--
-- Table structure for table `ruleset`
--

CREATE TABLE IF NOT EXISTS `ruleset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `actions` text COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(1) NOT NULL,
  `error_to_problem` int(11) NOT NULL,
  `trim` int(1) NOT NULL DEFAULT '0',
  `system_resolved` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `ruleset`
--

INSERT INTO `ruleset` (`id`, `event`, `actions`, `priority`, `error_to_problem`, `trim`, `system_resolved`) VALUES
(1, 'LCP', 'Check for any display issues on the sign.\r\n\r\nVerify if its presents itself on both sides of the sign.\r\n\r\nIf only on one side, you will need a sign company on site with a replacement motherboard and line controller as it could be either of these with a problem.', 3, 60, 0, 240),
(2, 'SCP', 'Check for any sign display issues\r\n\r\nIf they exist, do they exist on both sides of the sign?\r\n\r\nAre you able to update the prices on the sign?\r\n\r\nCall PWM Support at 866-796-7446 for assistance', 2, 120, 0, 60),
(3, 'NOS', 'Check and see if the sign is having any display issues. Ex: Flashing or Brightness\r\n\r\nIf the sign is flashing you will need a new sensor or have a sign technician disconnect the current one and set a static brightness.\r\n\r\nIf the sign is not bright enough call PWM Support at 866-796-7446 and a service technician can walk you through doing this.', 3, 300, 0, 60),
(5, 'OVT', 'Make note if the sign has any display issues.\r\n\r\nPlease turn the sign off at the breaker to prevent damaging any components. \r\n\r\nCall PWM Support at 866-796-7446 after for further instructions. ', 1, 60, 0, 180),
(6, 'NCP', 'Verify the Control Unit and Wireless Radio(if there is one) is powered on. The control unit has a screen that should have a display showing. The radio has led lights next to the antenna.\r\n\r\nVerify all 3 wires are secured into the wireless radio and not loose or broken.\r\n\r\nCall PWM Support at 866-796-7446 after for further instructions if the issue is not resolved.', 2, 300, 0, 60),
(7, 'NCA', 'Check control unit for loose wires and make note if any are found. \r\n\r\nVerify that flashing lights exist inside of the Control Unit.\r\n\r\nCall PWM to troubleshoot this issue.', 2, 60, 1, 60),
(8, 'CPF', 'Verify that the home screen of the Control Unit does not say "POS Unused" on the bottom half. \r\n\r\nCall PWM Support at 866-796-7446 after for further instructions. ', 3, 180, 0, 60),
(10, 'PCE', 'No action necessary.', 3, 60, 0, 300),
(11, 'RST', 'Verify the unit is plugged into a surge protector to protect from power surges.\r\n\r\nIf this issue is recurring you may need to replace the unit.', 3, 0, 0, 0),
(12, 'IVP', 'You must resend the correct price through the POS. \r\n\r\nIf this is not sucessful, send prices manually through the control unit to update the sign.\r\n\r\nIf you are unable to do this, call PWM at 866-796-7446.', 1, 0, 0, 0),
(13, 'CNS', 'Call the store and verify if the sign is displaying the correct price. If it is, have your supervisor close this problem.\r\n\r\nIf the sign displays an incorrect price. Please resend the correct price and recheck step one. \r\n\r\nIf the issue still exists, please contact PWM at 866-796-7446.', 1, 0, 0, 0),
(14, 'UAC', 'None Yet.', 2, 0, 0, 0),
(15, 'KSS-RPS', 'No defined actions.', 2, 15, 0, 15),
(16, 'KSS-SPM', 'No defined actions.', 1, 999, 0, 999);

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `num_signs` int(1) NOT NULL DEFAULT '1',
  `trans_rate` int(5) NOT NULL DEFAULT '5',
  `connection` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `cu_version` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `amcs_version` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `active` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `pos` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `warranty_expires` int(20) NOT NULL,
  `pc_manufacture` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None',
  `sign1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sign2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sign3` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sign4` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `system_config`
--

CREATE TABLE IF NOT EXISTS `system_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refresh_time` int(5) NOT NULL,
  `trans_rate` int(11) NOT NULL,
  `max_price_change` float NOT NULL DEFAULT '0.25',
  `email_list` text COLLATE utf8_unicode_ci NOT NULL,
  `forward_cns_time` int(3) NOT NULL DEFAULT '15',
  `backward_cns_time` int(3) NOT NULL DEFAULT '15',
  `uac_start_time` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `uac_end_time` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `uac_overnight` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `system_config`
--

INSERT INTO `system_config` (`id`, `refresh_time`, `trans_rate`, `max_price_change`, `email_list`, `forward_cns_time`, `backward_cns_time`, `uac_start_time`, `uac_end_time`, `uac_overnight`) VALUES
(1, 15, 30, 0.3, '', 10, 10, '22:00', '07:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(1) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `level`) VALUES
(2, 'jakec@p-w-m.com', '588f5104b178d204b8a599148a561c05', 10),
(3, 'britts@p-w-m.com', '5f4dcc3b5aa765d61d8327deb882cf99', 10),
(4, 'supervisor@p-w-m.com', '09348c20a019be0318387c08df7a783d', 2),
(5, 'user@p-w-m.com', 'ee11cbb19052e40b07aac0ca060c23ee', 1),
(9, 'admin@p-w-m.com', '21232f297a57a5a743894a0e4a801fc3', 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
