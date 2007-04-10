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



-- --------------------------------------------------------

-- 
-- Table structure for table `players`
-- 

ALTER TABLE `players` ADD  `dodge` float NOT NULL default '0',
ADD  `parry` float NOT NULL default '0',
ADD  `block` float NOT NULL default '0',
ADD  `mitigation` float NOT NULL default '0',
ADD  `crit` float NOT NULL default '0';
