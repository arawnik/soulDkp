-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 03.10.2017 klo 16:46
-- Palvelimen versio: 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `soulDKP`
--

DELIMITER $$
--
-- Proseduurit
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_adjustment` (IN `raidId` INT, IN `charId` INT, IN `adjustValue` INT, IN `adjustComment` VARCHAR(250) CHARSET utf8)  NO SQL
INSERT INTO raid_adjustments (adjust_raid, adjust_character, adjust_value, adjust_comment) values (raidId, charId, adjustValue, adjustComment)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_attends_to_raid` (IN `raidId` INT, IN `charId` INT)  NO SQL
INSERT INTO raid_attends (raid_id, character_id) 
values (raidId, charId)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_character` (IN `charName` VARCHAR(250) CHARSET utf8, IN `classId` INT, IN `roleId` INT)  NO SQL
INSERT INTO characters (char_name, char_class, char_role) 
VALUES (charName, classId, roleId)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_point_usage` (IN `raidId` INT, IN `charId` INT, IN `useValue` INT, IN `useDesc` VARCHAR(250) CHARSET utf8)  NO SQL
INSERT INTO points_used (use_raid, use_character, use_amount, use_desc) values (raidId, charId, useValue, useDesc)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `calculate_character_normalization` (IN `normalizationId` INT, IN `charId` INT, IN `percent` INT)  NO SQL
INSERT INTO normalization_points (normalization_id,char_id,normalization_amount)
SELECT normalizationId, c.char_id, (c.current_dkp * (percent / 100)) as normalization_amount
FROM char_with_dkp c
WHERE char_id = charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `char_raid_adjustment` (IN `raidId` INT, IN `charId` INT)  NO SQL
SELECT *
FROM raid_adjustments
WHERE adjust_raid = raidId AND adjust_character = charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_adjustment` (IN `raidId` INT, IN `charId` INT)  NO SQL
DELETE FROM raid_adjustments
WHERE adjust_raid=raidId AND adjust_character=charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_attends_from_raid` (IN `raidId` INT)  NO SQL
DELETE 
FROM raid_attends 
WHERE raid_id = raidId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_character` (IN `charId` INT)  NO SQL
DELETE FROM characters
WHERE char_id=charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_character_normalization` (IN `normalizationId` INT, IN `charId` INT)  NO SQL
DELETE FROM normalization_points
WHERE normalization_id=normalizationId AND char_id=charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_normalization` (IN `normId` INT)  NO SQL
DELETE FROM normalization
WHERE normalization_id=normId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_raid` (IN `raidId` INT)  NO SQL
DELETE FROM raids
WHERE raid_id=raidId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_usage` (IN `usageId` INT)  NO SQL
DELETE FROM points_used
WHERE use_id=usageId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_attendance` ()  NO SQL
SELECT c.char_id, c.name, c.role_name, c.class_name, c.class_color, al.attendance_lifetime, (SELECT count(*) FROM raids) as raids_lifetime, alt.attendance_last_ten
FROM character_joined c
INNER JOIN attendance_lifetime al
on al.char_id = c.char_id
INNER JOIN attendance_last_ten alt
on alt.char_id = c.char_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_characters` ()  NO SQL
SELECT * 
FROM char_with_dkp$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_Characters_not_in_raid` (IN `raidId` INT)  NO SQL
SELECT * 
FROM raid_attends ra 
WHERE ra.raid_id NOT IN (
    SELECT c.char_id, c.name, c.class_name, c.class_Color, c.role_name
    FROM raid_attends ra
    INNER JOIN raids r
    on r.raid_id = ra.raid_id
    INNER JOIN character_joined c
    ON c.char_id =  ra.character_id
    WHERE ra.raid_id = raidId
    GROUP BY c.char_id
    ORDER BY c.name
)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_character_data` (IN `charId` INT)  NO SQL
SELECT ch.char_id, ch.char_name, ch.char_class, ch.char_role, cl.class_name, cl.class_color, ro.role_name
FROM characters ch
INNER JOIN classes cl
on ch.char_class = cl.class_id
INNER JOIN roles ro
on ch.char_role = ro.role_id
WHERE char_id = charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_character_items` (IN `charId` INT)  NO SQL
SELECT pu.use_amount, pu.use_desc, pu.use_raid
FROM points_used pu
WHERE use_character = charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_character_raids` (IN `charId` INT)  NO SQL
SELECT c.char_name, r.raid_id, r.raid_comment, (r.raid_value + ifnull(adj.adjust_value,0)) as raid_value, r.raid_date, DATE(r.raid_date) as formed_raid_date
FROM raid_attends ra
INNER JOIN raids r
on r.raid_id = ra.raid_id
INNER JOIN characters c
ON c.char_id =  ra.character_id
LEFT JOIN raid_adjustments adj
ON c.char_id = adj.adjust_character AND r.raid_id = adj.adjust_raid
WHERE c.char_id = charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_classes` ()  NO SQL
SELECT class_id, class_name, class_color
FROM classes$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_latest_normalization` ()  NO SQL
SELECT n.normalization_id, n.normalization_adder, n.normalization_percent, n.normalization_comment, n.normalization_date
FROM normalization n 
WHERE n.normalization_date = (
	SELECT MAX(nb.normalization_date) 
	FROM normalization nb
)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_normalization` (IN `normalizationId` INT)  NO SQL
SELECT n.normalization_id, n.normalization_adder, n.normalization_percent, n.normalization_comment, n.normalization_date
FROM normalization n 
WHERE n.normalization_id=normalizationId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_normalizations` ()  NO SQL
SELECT n.normalization_id, n.normalization_adder, n.normalization_percent, n.normalization_comment, n.normalization_date, DATE(n.normalization_date) as formed_normalization_date, u.name
FROM normalization n
INNER JOIN users u
ON u.id = n.normalization_adder
ORDER BY n.normalization_date DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_raids` ()  NO SQL
SELECT r.raid_id, r.raid_value, r.raid_comment, COUNT(ra.character_id) as raid_attendees_count, r.raid_date, DATE(raid_date) as formed_raid_date, r.raid_added, DATE(raid_added) as formed_raid_added
FROM raids r
LEFT JOIN raid_attends ra
ON ra.raid_id=r.raid_id
GROUP BY r.raid_id
ORDER BY r.raid_date DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_raid_adjustments` (IN `raidId` INT)  NO SQL
SELECT c.char_id,c.name, c.class_color, ra.adjust_value, ra.adjust_comment
FROM raid_adjustments ra
INNER JOIN character_joined c
on c.char_id = ra.adjust_character
WHERE adjust_raid = raidId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_raid_attends` (IN `raidId` INT)  NO SQL
SELECT c.char_id, c.name, c.class_name, c.class_Color, c.role_name
FROM raid_attends ra
INNER JOIN raids r
on r.raid_id = ra.raid_id
INNER JOIN character_joined c
ON c.char_id =  ra.character_id
WHERE ra.raid_id = raidId
GROUP BY c.char_id
ORDER BY c.name$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_raid_data` (IN `raidId` INT)  NO SQL
SELECT raid_value, raid_comment, raid_date, DATE(raid_date) as formed_date
FROM raids
WHERE raid_id = raidId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_raid_items` (IN `raidId` INT)  NO SQL
SELECT c.char_id,c.name, c.class_color, pu.use_id, pu.use_amount, pu.use_desc
FROM points_used pu
INNER JOIN character_joined c
on c.char_id = pu.use_character
WHERE use_raid = raidId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_roles` ()  NO SQL
SELECT role_id, role_name
FROM roles$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_specific_normalization_points` (IN `normId` INT)  NO SQL
SELECT np.*, c.*
FROM normalization_points np
INNER JOIN character_joined c
ON np.char_id = c.char_id
WHERE normalization_id = normId
ORDER BY c.name$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_character` (IN `charId` INT, IN `charName` VARCHAR(250) CHARSET utf8, IN `charClass` INT, IN `charRole` INT)  NO SQL
UPDATE characters
SET 
char_name=charName,
char_class=charClass,
char_role=charRole
WHERE char_id=charId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_raid_data` (IN `raidId` INT, IN `raidValue` INT, IN `raidComment` VARCHAR(255), IN `raidDate` DATE)  NO SQL
UPDATE raids
SET 
raid_value=raidValue,
raid_comment=raidComment,
raid_date=raidDate
WHERE raid_id=RaidId$$

--
-- Funktiot
--
CREATE DEFINER=`root`@`localhost` FUNCTION `add_normalization` (`userId` INT, `normPercent` INT, `normComment` VARCHAR(250) CHARSET utf8) RETURNS INT(11) NO SQL
BEGIN
INSERT INTO normalization(normalization_adder, normalization_percent, normalization_comment)
VALUES (userId, normPercent, normComment);

RETURN(LAST_INSERT_ID());
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `add_raid` (`raidValue` INT, `raidComment` VARCHAR(250) CHARSET utf8, `raidDate` DATE) RETURNS INT(11) BEGIN
INSERT INTO raids(raid_value, raid_comment, raid_date)
VALUES (raidValue, raidComment, raidDate);

RETURN(LAST_INSERT_ID());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Näkymän vararakenne `attendance_last_ten`
--
CREATE TABLE `attendance_last_ten` (
`char_id` int(11)
,`attendance_last_ten` bigint(21)
);

-- --------------------------------------------------------

--
-- Näkymän vararakenne `attendance_lifetime`
--
CREATE TABLE `attendance_lifetime` (
`char_id` int(11)
,`attendance_lifetime` bigint(21)
);

-- --------------------------------------------------------

--
-- Rakenne taululle `characters`
--

CREATE TABLE `characters` (
  `char_id` int(11) NOT NULL,
  `char_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `char_class` tinyint(4) NOT NULL,
  `char_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Näkymän vararakenne `character_joined`
--
CREATE TABLE `character_joined` (
`char_id` int(11)
,`name` varchar(250)
,`class_id` tinyint(4)
,`class_name` varchar(250)
,`class_color` varchar(150)
,`role_id` int(11)
,`role_name` varchar(250)
);

-- --------------------------------------------------------

--
-- Näkymän vararakenne `char_with_dkp`
--
CREATE TABLE `char_with_dkp` (
`char_id` int(11)
,`name` varchar(250)
,`class_id` tinyint(4)
,`class_name` varchar(250)
,`class_color` varchar(150)
,`role_id` int(11)
,`role_name` varchar(250)
,`earned` double
,`spent` double
,`normalized` double
,`current_dkp` double
);

-- --------------------------------------------------------

--
-- Rakenne taululle `classes`
--

CREATE TABLE `classes` (
  `class_id` tinyint(11) NOT NULL,
  `class_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `class_color` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vedos taulusta `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `class_color`) VALUES
(1, 'Druid', 'FF7D0A'),
(2, 'Hunter', 'ABD473'),
(3, 'Mage', '69CCF0'),
(4, 'Paladin', 'F58CBA'),
(5, 'Priest', 'FFFFFF'),
(6, 'Rogue', 'FFF569'),
(7, 'Shaman', '0070DE'),
(8, 'Warlock', '9482C9'),
(9, 'Warrior', 'C79C6E');

-- --------------------------------------------------------

--
-- Näkymän vararakenne `dkp_added`
--
CREATE TABLE `dkp_added` (
`char_id` int(11)
,`added` double
);

-- --------------------------------------------------------

--
-- Näkymän vararakenne `dkp_adjusted`
--
CREATE TABLE `dkp_adjusted` (
`char_id` int(11)
,`adjusted` double
);

-- --------------------------------------------------------

--
-- Näkymän vararakenne `dkp_earned`
--
CREATE TABLE `dkp_earned` (
`char_id` int(11)
,`earned` double
);

-- --------------------------------------------------------

--
-- Näkymän vararakenne `dkp_normalized`
--
CREATE TABLE `dkp_normalized` (
`char_id` int(11)
,`normalized` double
);

-- --------------------------------------------------------

--
-- Näkymän vararakenne `dkp_spent`
--
CREATE TABLE `dkp_spent` (
`char_id` int(11)
,`spent` double
);

-- --------------------------------------------------------

--
-- Näkymän vararakenne `last_ten_raids`
--
CREATE TABLE `last_ten_raids` (
`raid_id` int(11)
,`raid_value` double
,`raid_comment` varchar(250)
,`raid_date` datetime
,`raid_added` datetime
);

-- --------------------------------------------------------

--
-- Rakenne taululle `normalization`
--

CREATE TABLE `normalization` (
  `normalization_id` int(11) NOT NULL,
  `normalization_adder` int(11) NOT NULL,
  `normalization_percent` int(11) NOT NULL,
  `normalization_comment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `normalization_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Rakenne taululle `normalization_points`
--

CREATE TABLE `normalization_points` (
  `normalization_id` int(11) NOT NULL,
  `char_id` int(11) NOT NULL,
  `normalization_amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `points_used`
--

CREATE TABLE `points_used` (
  `use_id` int(11) NOT NULL,
  `use_raid` int(11) NOT NULL,
  `use_character` int(11) NOT NULL,
  `use_amount` double NOT NULL,
  `use_desc` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Rakenne taululle `raids`
--

CREATE TABLE `raids` (
  `raid_id` int(11) NOT NULL,
  `raid_value` double NOT NULL,
  `raid_comment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `raid_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `raid_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Rakenne taululle `raid_adjustments`
--

CREATE TABLE `raid_adjustments` (
  `adjust_raid` int(11) NOT NULL,
  `adjust_character` int(11) NOT NULL,
  `adjust_value` double NOT NULL,
  `adjust_comment` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `raid_attends`
--

CREATE TABLE `raid_attends` (
  `raid_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vedos taulusta `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Tank'),
(2, 'Healer'),
(3, 'Dps');

-- --------------------------------------------------------

--
-- Rakenne taululle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `remember_token` varchar(101) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Vedos taulusta `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 'Test', 'test@test.com', '$2a$06$0uQoFpNqSCBRTvSDPhUWgOPIHRC75mcm7WcNyjLWY7fCzdb8cpdV6', '2016-10-06 21:51:21', '2016-10-06 21:51:21', 'bONpEzcjZqN8vyO5vePSXCVflViwb0bN2ImRomYlCSOQCrlGtoEopx6YEJP3');

-- --------------------------------------------------------

--
-- Näkymän rakenne `attendance_last_ten`
--
DROP TABLE IF EXISTS `attendance_last_ten`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `attendance_last_ten`  AS  select `c`.`char_id` AS `char_id`,count(`ra`.`raid_id`) AS `attendance_last_ten` from ((`characters` `c` left join `raid_attends` `ra` on((`ra`.`character_id` = `c`.`char_id`))) join `last_ten_raids` `ltr` on((`ltr`.`raid_id` = `ra`.`raid_id`))) group by `c`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `attendance_lifetime`
--
DROP TABLE IF EXISTS `attendance_lifetime`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `attendance_lifetime`  AS  select `c`.`char_id` AS `char_id`,count(`ra`.`raid_id`) AS `attendance_lifetime` from (`characters` `c` join `raid_attends` `ra` on((`ra`.`character_id` = `c`.`char_id`))) group by `c`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `character_joined`
--
DROP TABLE IF EXISTS `character_joined`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `character_joined`  AS  select `ch`.`char_id` AS `char_id`,`ch`.`char_name` AS `name`,`ch`.`char_class` AS `class_id`,`cl`.`class_name` AS `class_name`,`cl`.`class_color` AS `class_color`,`ch`.`char_role` AS `role_id`,`ro`.`role_name` AS `role_name` from (((`characters` `ch` join `classes` `cl` on((`ch`.`char_class` = `cl`.`class_id`))) join `roles` `ro` on((`ch`.`char_role` = `ro`.`role_id`))) left join `raid_attends` `rads` on((`rads`.`character_id` = `ch`.`char_id`))) group by `ch`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `char_with_dkp`
--
DROP TABLE IF EXISTS `char_with_dkp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `char_with_dkp`  AS  select `c`.`char_id` AS `char_id`,`c`.`name` AS `name`,`c`.`class_id` AS `class_id`,`c`.`class_name` AS `class_name`,`c`.`class_color` AS `class_color`,`c`.`role_id` AS `role_id`,`c`.`role_name` AS `role_name`,`de`.`earned` AS `earned`,`ds`.`spent` AS `spent`,`dd`.`normalized` AS `normalized`,((`de`.`earned` - `ds`.`spent`) - `dd`.`normalized`) AS `current_dkp` from (((`character_joined` `c` left join `dkp_spent` `ds` on((`ds`.`char_id` = `c`.`char_id`))) left join `dkp_earned` `de` on((`de`.`char_id` = `c`.`char_id`))) left join `dkp_normalized` `dd` on((`dd`.`char_id` = `c`.`char_id`))) group by `c`.`char_id` order by `c`.`name` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `dkp_added`
--
DROP TABLE IF EXISTS `dkp_added`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dkp_added`  AS  select `ch`.`char_id` AS `char_id`,sum(ifnull(`rad`.`raid_value`,0)) AS `added` from ((`characters` `ch` left join `raid_attends` `ra` on((`ra`.`character_id` = `ch`.`char_id`))) left join `raids` `rad` on((`rad`.`raid_id` = `ra`.`raid_id`))) group by `ch`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `dkp_adjusted`
--
DROP TABLE IF EXISTS `dkp_adjusted`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dkp_adjusted`  AS  select `ch`.`char_id` AS `char_id`,sum(ifnull(`radju`.`adjust_value`,0)) AS `adjusted` from (`characters` `ch` left join `raid_adjustments` `radju` on((`radju`.`adjust_character` = `ch`.`char_id`))) group by `ch`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `dkp_earned`
--
DROP TABLE IF EXISTS `dkp_earned`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dkp_earned`  AS  select `c`.`char_id` AS `char_id`,(ifnull(`de`.`added`,0) + ifnull(`da`.`adjusted`,0)) AS `earned` from (`dkp_added` `de` left join (`dkp_adjusted` `da` left join `character_joined` `c` on((`da`.`char_id` = `c`.`char_id`))) on((`de`.`char_id` = `c`.`char_id`))) group by `c`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `dkp_normalized`
--
DROP TABLE IF EXISTS `dkp_normalized`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dkp_normalized`  AS  select `c`.`char_id` AS `char_id`,sum(ifnull(`dp`.`normalization_amount`,0)) AS `normalized` from (`characters` `c` left join `normalization_points` `dp` on((`dp`.`char_id` = `c`.`char_id`))) group by `c`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `dkp_spent`
--
DROP TABLE IF EXISTS `dkp_spent`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dkp_spent`  AS  select `ch`.`char_id` AS `char_id`,sum(ifnull(`pu`.`use_amount`,0)) AS `spent` from (`characters` `ch` left join `points_used` `pu` on((`pu`.`use_character` = `ch`.`char_id`))) group by `ch`.`char_id` ;

-- --------------------------------------------------------

--
-- Näkymän rakenne `last_ten_raids`
--
DROP TABLE IF EXISTS `last_ten_raids`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `last_ten_raids`  AS  select `r`.`raid_id` AS `raid_id`,`r`.`raid_value` AS `raid_value`,`r`.`raid_comment` AS `raid_comment`,`r`.`raid_date` AS `raid_date`,`r`.`raid_added` AS `raid_added` from `raids` `r` order by `r`.`raid_date` desc limit 10 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`char_id`),
  ADD KEY `char_role` (`char_role`),
  ADD KEY `char_class` (`char_class`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `normalization`
--
ALTER TABLE `normalization`
  ADD PRIMARY KEY (`normalization_id`);

--
-- Indexes for table `normalization_points`
--
ALTER TABLE `normalization_points`
  ADD PRIMARY KEY (`normalization_id`,`char_id`),
  ADD KEY `decay_char_fk` (`char_id`);

--
-- Indexes for table `points_used`
--
ALTER TABLE `points_used`
  ADD PRIMARY KEY (`use_id`),
  ADD KEY `use_character` (`use_character`),
  ADD KEY `use_raid` (`use_raid`);

--
-- Indexes for table `raids`
--
ALTER TABLE `raids`
  ADD PRIMARY KEY (`raid_id`);

--
-- Indexes for table `raid_adjustments`
--
ALTER TABLE `raid_adjustments`
  ADD PRIMARY KEY (`adjust_raid`,`adjust_character`),
  ADD KEY `adjust_raid` (`adjust_raid`),
  ADD KEY `adjust_character` (`adjust_character`);

--
-- Indexes for table `raid_attends`
--
ALTER TABLE `raid_attends`
  ADD PRIMARY KEY (`raid_id`,`character_id`),
  ADD KEY `attend_character_fk` (`character_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `char_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` tinyint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `normalization`
--
ALTER TABLE `normalization`
  MODIFY `normalization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `points_used`
--
ALTER TABLE `points_used`
  MODIFY `use_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `raids`
--
ALTER TABLE `raids`
  MODIFY `raid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `characters`
--
ALTER TABLE `characters`
  ADD CONSTRAINT `char_class_fk` FOREIGN KEY (`char_class`) REFERENCES `classes` (`class_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `char_role_fk` FOREIGN KEY (`char_role`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE;

--
-- Rajoitteet taululle `normalization_points`
--
ALTER TABLE `normalization_points`
  ADD CONSTRAINT `decay_char_fk` FOREIGN KEY (`char_id`) REFERENCES `characters` (`char_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decay_id_fk` FOREIGN KEY (`normalization_id`) REFERENCES `normalization` (`normalization_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Rajoitteet taululle `points_used`
--
ALTER TABLE `points_used`
  ADD CONSTRAINT `character_use_fk` FOREIGN KEY (`use_character`) REFERENCES `characters` (`char_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raid_use_fk` FOREIGN KEY (`use_raid`) REFERENCES `raids` (`raid_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Rajoitteet taululle `raid_adjustments`
--
ALTER TABLE `raid_adjustments`
  ADD CONSTRAINT `char_adjustment_fk` FOREIGN KEY (`adjust_character`) REFERENCES `characters` (`char_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raid_adjustment_fk` FOREIGN KEY (`adjust_raid`) REFERENCES `raids` (`raid_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Rajoitteet taululle `raid_attends`
--
ALTER TABLE `raid_attends`
  ADD CONSTRAINT `attend_character_fk` FOREIGN KEY (`character_id`) REFERENCES `characters` (`char_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attend_raid_fk` FOREIGN KEY (`raid_id`) REFERENCES `raids` (`raid_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
