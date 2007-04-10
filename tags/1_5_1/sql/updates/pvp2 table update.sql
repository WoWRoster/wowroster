-- phpMyAdmin SQL Dump
-- version 2.6.1-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: 192.168.0.5
-- Generation Time: May 05, 2005 at 11:42 PM
-- Server version: 4.1.10
-- PHP Version: 4.3.10
-- 
-- Database: `test1`
-- 

-- --------------------------------------------------------

ALTER TABLE `pvp2` DROP PRIMARY KEY , 
ADD INDEX ( `member_id` , `index` ) 
ALTER TABLE `pvp2` ADD `column_id` MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY ;

-- --------------------------------------------------------

