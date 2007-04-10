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

ALTER TABLE `players` ADD `sessionHK` int(11) NOT NULL default '0',
ADD  `sessionDK` int(11) NOT NULL default '0',
ADD  `yesterdayHK` int(11) NOT NULL default '0',
ADD  `yesterdayDK` int(11) NOT NULL default '0',
ADD  `yesterdayContribution` int(11) NOT NULL default '0',
ADD  `lastweekHK` int(11) NOT NULL default '0',
ADD  `lastweekDK` int(11) NOT NULL default '0',
ADD  `lastweekContribution` int(11) NOT NULL default '0',
ADD  `lastweekRank` int(11) NOT NULL default '0',
ADD  `lifetimeHK` int(11) NOT NULL default '0',
ADD  `lifetimeDK` int(11) NOT NULL default '0',
ADD  `lifetimeRankName` varchar(64) NOT NULL default '0',
ADD  `lifetimeHighestRank` int(11) NOT NULL default '0',
ADD  `RankInfo` varchar(64) NOT NULL default '',
ADD  `RankName` varchar(64) NOT NULL default '',
ADD  `RankIcon` varchar(64) NOT NULL default '';

-- --------------------------------------------------------

