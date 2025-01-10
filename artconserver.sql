-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 03. Jan 2025 um 11:05
-- Server-Version: 10.11.8-MariaDB-0ubuntu0.24.04.1-log
-- PHP-Version: 7.4.33-nmm7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `d0136a20`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_content`
--

CREATE TABLE `sk_content` (
  `id` bigint(11) NOT NULL,
  `mid` bigint(11) NOT NULL DEFAULT 0,
  `content_area` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_content`
--

INSERT INTO `sk_content` (`id`, `mid`, `content_area`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 2, 10000),
(4, 3, 1),
(5, 3, 10000),
(6, 4, 1),
(7, 4, 10000),
(8, 5, 1),
(9, 6, 1),
(10, 7, 1),
(11, 7, 10000),
(12, 5, 10000),
(13, 10, 1),
(14, 8, 1),
(15, 8, 10000),
(16, 11, 1),
(17, 11, 10000),
(18, 6, 10000),
(22, 13, 10000),
(21, 13, 1),
(23, 1, 10000),
(24, 14, 1),
(25, 14, 10000),
(26, 15, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_groups`
--

CREATE TABLE `sk_groups` (
  `groupid` int(5) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_groups`
--

INSERT INTO `sk_groups` (`groupid`, `name`, `description`, `type`) VALUES
(1, 'Administrators', 'Administrators of Test-Site', 'Admin'),
(2, 'Usergroup', 'Users mit weniger Rechten', 'User');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_groups_modules_link`
--

CREATE TABLE `sk_groups_modules_link` (
  `id` int(10) UNSIGNED NOT NULL,
  `groupid` int(5) NOT NULL DEFAULT 0,
  `mid` int(5) NOT NULL DEFAULT 0,
  `type` char(1) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_groups_modules_link`
--

INSERT INTO `sk_groups_modules_link` (`id`, `groupid`, `mid`, `type`) VALUES
(1, 1, 1, 'A'),
(2, 1, 2, 'A'),
(3, 1, 3, 'A'),
(4, 1, 4, 'A'),
(5, 1, 5, 'A'),
(6, 1, 6, 'A'),
(7, 1, 7, 'A'),
(8, 1, 9, 'A'),
(9, 1, 10, 'A'),
(10, 1, 11, 'A'),
(16, 2, 11, 'A'),
(15, 2, 4, 'A'),
(14, 2, 3, 'A');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_groups_nav_tree_link`
--

CREATE TABLE `sk_groups_nav_tree_link` (
  `id` int(10) UNSIGNED NOT NULL,
  `groupid` int(5) NOT NULL DEFAULT 0,
  `nat_id` int(5) NOT NULL DEFAULT 0,
  `type` char(2) NOT NULL DEFAULT 'R'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_groups_nav_tree_link`
--

INSERT INTO `sk_groups_nav_tree_link` (`id`, `groupid`, `nat_id`, `type`) VALUES
(1, 1, 1, 'A'),
(2, 1, 2, 'A'),
(3, 1, 3, 'A'),
(4, 1, 4, 'A'),
(5, 1, 5, 'A'),
(6, 1, 6, 'A'),
(7, 1, 7, 'A'),
(8, 2, 1, 'A'),
(9, 2, 2, 'A'),
(10, 2, 3, 'A'),
(11, 2, 4, 'A'),
(12, 2, 5, 'A'),
(13, 2, 6, 'A'),
(14, 2, 7, 'A'),
(15, 1, 8, 'A'),
(16, 2, 8, 'A'),
(20, 2, 10, 'A'),
(19, 1, 10, 'A'),
(21, 1, 11, 'A'),
(22, 2, 11, 'A'),
(26, 2, 13, 'A'),
(25, 1, 13, 'A'),
(27, 1, 14, 'A'),
(28, 2, 14, 'A'),
(29, 1, 15, 'A'),
(30, 2, 15, 'A');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_groups_sites_link`
--

CREATE TABLE `sk_groups_sites_link` (
  `id` int(10) UNSIGNED NOT NULL,
  `groupid` int(5) NOT NULL DEFAULT 0,
  `site_id` int(5) NOT NULL DEFAULT 0,
  `type` char(2) NOT NULL DEFAULT 'R'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_groups_sites_link`
--

INSERT INTO `sk_groups_sites_link` (`id`, `groupid`, `site_id`, `type`) VALUES
(1, 1, 1, 'A'),
(2, 2, 1, 'A');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_groups_users_link`
--

CREATE TABLE `sk_groups_users_link` (
  `id` int(10) UNSIGNED NOT NULL,
  `groupid` int(5) NOT NULL DEFAULT 0,
  `uid` int(5) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_groups_users_link`
--

INSERT INTO `sk_groups_users_link` (`id`, `groupid`, `uid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_modules`
--

CREATE TABLE `sk_modules` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `descr` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'modules',
  `settings` varchar(255) DEFAULT NULL,
  `sort` mediumint(255) DEFAULT 1,
  `icon` varchar(50) DEFAULT 'news',
  `link` varchar(50) DEFAULT NULL,
  `dirname` varchar(150) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Sections that can be administered';

--
-- Daten für Tabelle `sk_modules`
--

INSERT INTO `sk_modules` (`id`, `name`, `descr`, `title`, `type`, `settings`, `sort`, `icon`, `link`, `dirname`) VALUES
(1, 'sk_news', 'News', 'News/Artikel', 'module', '', 10, 'admin/icon', 'admin/sk_news_sections.php', 'sk_news'),
(2, 'sk_users', 'User-Administration', 'Benutzer', 'system', '', 10, 'icon', 'sk_users_list.php', 'sk/admin/sk_users'),
(3, 'sk_cms', 'Content-Management of Site', 'Seiten editieren', 'cms', '', 20, 'icon', '../../../../../edit.php', 'sk/admin/sk_cms'),
(4, 'sk_nav_tree', 'Navigation-Structure', 'Site-Struktur', 'cms', NULL, 25, 'icon', 'sk_nav_tree_list.php', 'sk/admin/sk_nav_tree'),
(5, 'phpmyadmin', 'Datenbank administrieren', 'phpMyAdmin', 'system', NULL, 30, 'icon', 'index.php', 'phpMyAdmin'),
(6, 'sk_groups', 'Gruppen-Administration', 'Gruppen', 'system', NULL, 20, 'icon', 'sk_groups_list.php', 'sk/admin/sk_groups'),
(7, 'sk_sites', 'Web-Site Verwaltung', 'Web-Sites', 'system', NULL, 30, 'icon', 'sk_sites_list.php', 'sk/admin/sk_sites'),
(10, 'output_all', 'Output whole content of site', 'Output All', 'cms', NULL, 40, 'out_all', 'output_all.php', 'sk/admin/sk_cms'),
(9, 'sk_clean', 'Dateien säubern', 'Dateien säubern', 'util', NULL, 30, 'icon', 'sk_clean.php', 'sk/admin/sk_clean'),
(11, 'webanalyse', 'Webanalyse', 'Webanalyse', 'module', NULL, 40, 'sk_admin/webanalyse', 'sk_admin/index.php', 'webanalyse'),
(12, 'phpmnl', 'phpMyNewsletter', 'Newsletter', 'module', NULL, 40, 'sk_admin/phpmnl', 'sk_admin/index.php', 'phpMyNewsletter'),
(13, 'sk_site_copy', 'Web-Site kopieren', 'Web-Site kopieren', 'util', NULL, 30, 'icon', 'sk_site_copy.php', 'sk/admin/sk_sites');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_nav_tree`
--

CREATE TABLE `sk_nav_tree` (
  `id` bigint(11) NOT NULL,
  `p` bigint(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `depth` tinyint(6) NOT NULL DEFAULT 0,
  `group_id` mediumint(9) NOT NULL DEFAULT 0,
  `site_id` mediumint(11) NOT NULL DEFAULT 0,
  `template` varchar(50) NOT NULL DEFAULT 'default.html',
  `path` varchar(255) DEFAULT NULL,
  `filename` varchar(50) NOT NULL DEFAULT 'index.php',
  `linkname` varchar(255) DEFAULT NULL,
  `mview` set('0','1') NOT NULL DEFAULT '1',
  `icon` varchar(50) DEFAULT NULL,
  `xpos` smallint(6) DEFAULT 0,
  `ypos` smallint(6) DEFAULT 0,
  `nolink` tinyint(4) DEFAULT NULL,
  `sort_nr` mediumint(9) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `last_mod` date NOT NULL DEFAULT '0000-00-00',
  `by_user` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_nav_tree`
--

INSERT INTO `sk_nav_tree` (`id`, `p`, `title`, `depth`, `group_id`, `site_id`, `template`, `path`, `filename`, `linkname`, `mview`, `icon`, `xpos`, `ypos`, `nolink`, `sort_nr`, `user_id`, `last_mod`, `by_user`) VALUES
(1, 1, '', 1, 0, 1, 'default.html', NULL, 'index.php', 'Home', '1', NULL, 0, 0, 0, 1, 1, '2002-02-08', 1),
(2, 2, 'Gemälde', 1, 0, 1, 'default.html', NULL, 'index.php', 'Gemälde', '1', NULL, 0, 0, 0, 2, 1, '2002-04-02', 1),
(3, 3, 'Skulpturen', 1, 0, 1, 'default.html', NULL, 'index.php', 'Skulptur', '1', NULL, 0, 0, 0, 3, 1, '2002-03-08', 1),
(4, 4, 'Grafik', 1, 0, 1, 'default.html', NULL, 'index.php', 'Grafik', '1', NULL, 0, 0, 0, 4, 1, '2002-01-08', 1),
(5, 5, 'Kontakt', 1, 0, 1, 'default.html', NULL, 'index.php', 'Kontakt', '1', NULL, 0, 0, 0, 7, 1, '2001-03-01', 3),
(6, 6, 'Person', 1, 0, 1, 'default.html', NULL, 'index.php', 'Person', '1', NULL, 0, 0, 0, 8, 1, '2001-03-01', 3),
(7, 7, 'Referenzen', 1, 0, 1, 'default.html', NULL, 'index.php', 'Referenzen', '0', NULL, 0, 0, 0, 9, 1, '2001-03-01', 3),
(8, 8, 'moderne und zeitgenössische Kunst', 1, 0, 1, 'default.html', NULL, 'index.php', 'Moderne Kunst', '0', NULL, 0, 0, 0, 5, 3, '2000-11-08', 3),
(10, 10, 'Links zu verwandten Seiten', 1, 0, 1, 'default.html', NULL, 'index.php', 'Links', '0', NULL, 0, 0, 0, 12, 3, '2002-05-04', 3),
(11, 11, 'Leistungsprofil', 1, 0, 1, 'default.html', NULL, 'index.php', 'Leistungsprofil', '0', NULL, 0, 0, 0, 10, 3, '2002-02-08', 3),
(13, 13, 'Shop', 1, 0, 1, 'default.html', NULL, 'index.php', 'Shop', '0', NULL, 0, 0, 0, 11, 3, '2002-06-07', 3),
(14, 14, 'Antiquitäten', 1, 0, 1, 'default.html', NULL, 'index.php', 'Antiquitäten', '0', NULL, 0, 0, 0, 6, 3, '2000-11-08', 3),
(15, 15, 'Impressum', 1, 0, 1, 'default.html', NULL, 'index.php', 'Impressum', '1', NULL, 0, 0, 0, 13, 3, '2001-04-06', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_news`
--

CREATE TABLE `sk_news` (
  `id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `lead` varchar(255) DEFAULT NULL,
  `newstext` text NOT NULL,
  `image` varchar(30) DEFAULT NULL,
  `file1` varchar(30) DEFAULT NULL,
  `file2` varchar(30) DEFAULT NULL,
  `ndate` date NOT NULL DEFAULT '0000-00-00',
  `duedate` date NOT NULL DEFAULT '0000-00-00',
  `section_id` bigint(20) NOT NULL DEFAULT 1,
  `uid` int(11) NOT NULL DEFAULT 0,
  `last_mod` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `by_user` int(50) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='sk_news Standard News-Table';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_newssections`
--

CREATE TABLE `sk_newssections` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT 1,
  `anon_com` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Sections for News';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_news_comments`
--

CREATE TABLE `sk_news_comments` (
  `comment_id` int(8) UNSIGNED NOT NULL,
  `pid` int(8) UNSIGNED NOT NULL DEFAULT 0,
  `article_id` int(8) UNSIGNED NOT NULL DEFAULT 0,
  `date` int(10) NOT NULL DEFAULT 0,
  `user_id` int(5) NOT NULL DEFAULT 0,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `subject` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `nohtml` tinyint(1) NOT NULL DEFAULT 0,
  `nosmiley` tinyint(1) NOT NULL DEFAULT 0,
  `noxcode` tinyint(1) NOT NULL DEFAULT 0,
  `icon` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_objects`
--

CREATE TABLE `sk_objects` (
  `id` bigint(11) NOT NULL,
  `content_id` bigint(11) NOT NULL DEFAULT 0,
  `sort_nr` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT '',
  `file` varchar(255) DEFAULT NULL,
  `attributes` varchar(255) DEFAULT NULL,
  `objtext` text DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `last_mod` date NOT NULL DEFAULT '0000-00-00',
  `by_user` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_objects`
--

INSERT INTO `sk_objects` (`id`, `content_id`, `sort_nr`, `type`, `file`, `attributes`, `objtext`, `user_id`, `last_mod`, `by_user`) VALUES
(2, 2, 10, 'text', '', 'align=\"\" margin=\"\"', '<p><strong>Leinwandgem&auml;lde</strong></p><p><br />Die h&auml;ufigsten Sch&auml;den sind Deformationen und Fehlstellen, wie L&ouml;cher und Risse im Bildtr&auml;ger, die zu einem Abplatzen der Malschicht f&uuml;hren. Besonders Gem&auml;lde ab dem 19. Jahrhundert sind auf maschinell hergestellten Geweben gemalt, und die damals &uuml;bliche &Ouml;l-Grundierung springt einfach in Schollen vom Bildtr&auml;ger ab.</p><p>L&ouml;cher und Risse werden heute mit neuen F&auml;den verklebt und eine Doublierung ist selten notwendig.</p><p>Lose und bl&auml;tternde Malschicht wird gefestigt und Fehlstellen mit einer neuen Kittmasse gef&uuml;llt. Danach kann eine Retusche erfolgen, und je nach Bedarf auch eine neue Firnisschicht aufgebracht werden. Wichtig um neue Sch&auml;den vorzubeugen sind das Anbringen eines <strong>R&uuml;ckseitenschutzes</strong> und eine klimastabile H&auml;ngung. </p><p>F&uuml;r besonders empfindliche Holztafeln, oder klimatisch besonders ung&uuml;nstige Situationen, wie eine Au&szlig;enwand eines Altabaus, bieten wir auch die kosteng&uuml;nstige Variante eines <strong>Klimasafes</strong> an. Dieser ist eine Kombination mit dem Zierrahmen und der Verglasung, und wird individuell angefertigt. </p>', 1, '2006-09-25', 3),
(9, 4, 10, 'text', '', 'align=\"\" margin=\"\"', '<ul><li> gefasste Holzskulpturen</li><li> gefasste Zierrahmen</li><li> bemalte M&#246;bel</li></ul><p><u>Pflege und Schutz</u></p><p>Dreidimensionale gefasste Objekte haben meistens einen Bildtr&#228;ger aus Holz und eine oder mehrere Fassungen aus organischen Materialien. Beide sind anf&#228;llig auf Klimaeinfl&#252;sse und Sch&#228;dlinge. Daher ist eine klimatisch stabile Umgebung sehr wichtig, um Sch&#228;den zu vermeiden.</p><p><u>Reinigung</u></p><p>Die Entfernung von Oberfl&#228;chenschmutz, der meistens eine Mischung aus schwefelh&#228;ltigen Ru&#223;partikeln, alkalischem Staub und Schmutz beinhaltet, ist besonders wichtig, da sich im feuchten Klima S&#228;uren und Pilze bilden k&#246;nnen, die die Malschicht angreifen. Die Entscheidung &#252;ber die Empfindlichkeit der Oberfl&#228;chen und die Wahl des Reinigungsmittels kann nur der Farchmann geben! Allzuoft sind Fassungen durch unsachgem&#228;&#223;es Ablaugen und Reinigen zerst&#246;rt worden. </p><p> <u>Untersuchung und Freilegung</u></p><p>Liegen mehrere Fassungen vor, mu&#223; zwischen Original und &#220;berfassung unterschieden werden. Nicht immer ist es ratsam s&#228;mtliche Fassungen zu entfernen, um auf die vermeintlich &#228;lteste Originalschicht zu kommen. Eine Konservierung sollte im Vordergrund stehen, und neuere Fassung als historisch gewachsener Zustand anerkannt werden. </p><p>Eine Freilegung kann jedoch naheliegend sein, wenn sich zum Beispiel nur eine &#220;berfassung findet, die besch&#228;digt ist oder zu weiteren Sch&#228;den f&#252;hren kann. Mithilfe von Freilegungstreppen unter dem Mikroskop kann die darunterliegende Malschicht begutachtet werden, und im Falle eines guten Erhaltungszustandes freigelegt werden.</p><p><u>Retusche und Erg&#228;nzung</u></p><p>Um Fehlstellen wieder zu integrieren gibt es zahlreiche M&#246;glichkeiten. Von der sichtbaren Strukturretusche, wie z.B. der Punktretusche oder dem Tratteggio - das mit sehr feinen Linien aus mehreren Farbt&#246;nen eine Farbmischung ergibt, k&#246;nnen besonders fragmentarisch erhaltene St&#252;cke aus der Romanik bis Sp&#228;tgotik oder Museumsst&#252;cke retuschiert werden.</p><p>Bei besonders gut erhaltenen Fassungen z.B. einer&#160; Rokokoplastik oder einer Skulptur mit liturgischer Funktion in einer Kirche, k&#246;nnen Fehlstellen aber auch mit der Imitation der Originalfassung erg&#228;nzt werden, soda&#223; die Oberfl&#228;che v&#246;llig geschlossen und einheitlich wirkt. </p><p>&#160;</p>', 1, '2009-04-29', 3),
(170, 22, 140, 'image', 'wappen_lilie.gif', 'width=\"198\" height=\"261\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"150\" theight=\"200\"', 'Beispiel eines Wappens auf Papier, 200,-Euro', 3, '2009-05-19', 3),
(3, 2, 20, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"0x181818\" framecolor=\"0xFFFFFF\" textcolor=\"0xFFFFFF\" vcolumns=\"6\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"2\"', '', 1, '2006-09-21', 3),
(4, 3, 130, 'image', 'atelier.jpg', 'width=\"1024\" height=\"644\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"200\" theight=\"124\"', 'Restaurierung von vier Gemaelden der Noerdlichen Galerie im Schloss Nymphenburg', 1, '2009-05-20', 3),
(6, 3, 30, 'image', 'hermann_posthumus_lanndhsut1.gif', 'width=\"425\" height=\"573\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" filesize=\"172492\" twidth=\"147\" theight=\"200\"', 'Residenz Landshut - Altar von Hermann Posthumus um 1540 während der Restaurierung', 1, '2008-08-08', 3),
(7, 3, 40, 'image', 'hermann_posthumus_lanndhsut_det.gif', 'width=\"425\" height=\"319\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"200\" theight=\"149\"', ' Altar von Hermann Posthumus Detail während der Abnahme einse ölhältigen Firnisses  ', 1, '2008-08-08', 3),
(10, 4, 20, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"0x181818\" framecolor=\"0xFFFFFF\" textcolor=\"0xFFFFFF\" vcolumns=\"8\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"2\"', '', 1, '2006-09-21', 3),
(44, 3, 140, 'image', 'nymphenburg_badenburg.gif', 'width=\"283\" height=\"378\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"148\" theight=\"200\"', 'Gemaelde Badenburg nach der Restaurierung im Schloss Nymphenburg in Muenchen', 3, '2009-05-20', 3),
(43, 5, 60, 'image', 'freilegung-skulptur.jpg', 'width=\"591\" height=\"800\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" filesize=\"102250\" twidth=\"146\" theight=\"200\"', 'Freilegungstreppe an einer Madonna der Pfarrkirche St.Josef in Hohnelinden', 3, '2008-04-22', 3),
(15, 6, 10, 'text', '', 'align=\"\" margin=\"\"', '<p><strong>Druckgrafik und Aquarelle auf Papier<br />Chinesische Rollbilder</strong></p><p><br />Papier ist einer der fragilsten Bildtr&auml;ger unserer Kultur. Er ist anf&auml;llig auf Feuchtigkeit, auf Schimmel, sehr lichtempfindlich und zerrei&szlig;t sehr leicht. Dennoch kann mit der richtigen Lagerung und Pflege ein Kunstwerk auf Papier mehrere Jahrhunderte &uuml;berdauern!<br />Wichtig ist eine angemessene Verglasung und trockene Aufh&auml;ngung, mit s&auml;urefreien Kartons und Passepartouts. </p><p>Risse, Vergilbungen und Stockflecken k&ouml;nnen heute ohne neue Sch&auml;den zu verursachen behoben werden. Auch Fehlstellen k&ouml;nnen mit neuen Intarsien ersetzt und die fehlende Darstellung retuschiert werden.</p>', 1, '2006-09-21', 3),
(16, 6, 20, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"0x181818\" framecolor=\"0xFFFFFF\" textcolor=\"0xFFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"2\"', '', 1, '2006-09-21', 3),
(17, 7, 10, 'image', 'parlament1.jpg', 'width=\"718\" height=\"550\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" filesize=\"91748\" twidth=\"200\" theight=\"153\"', 'Vorzustand einer verschmutzten Grafik des 19. Jhdts. mit durchgehendem Riss, zwei großen Wasserrändern und zahlreichen Fehlstellen ', 1, '2006-09-25', 1),
(18, 7, 20, 'image', 'parlament2.gif', 'width=\"413\" height=\"301\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" twidth=\"200\" theight=\"146\"', 'Dieselbe Grafik nach der Oberflächenreinigung, einem Wasserbad und der Verklebung von Rissen mit Japanpapier', 1, '2006-09-25', 3),
(21, 8, 10, 'text', '', 'align=\"\" margin=\"\"', '<p><font color=\"#3333ff\"><strong>Mag.art Ulrike B&#252;ltemeyer</strong><br /></font></p><p><font color=\"#666666\">akademisch diplomierte Restauratorin</font> </p><p><font color=\"#666666\"><strong>mail: info@artconserver.net</strong></font></p><p><font color=\"#666666\"><strong>fon. ++43/676/843 26 88 37</strong></font></p><p>Atelier im Kunstquartier- Wien</p><p>&#214;sterreich <br /></p><p>&#160;</p><p>Termine nur nach telefonischer Vereinbarung!</p><p>&#160;</p><p>Das Atelier wird derzeit nicht regelm&#228;&#223;ig betrieben! <br /></p><p>Bitte senden Sie keine Bewerbungen als Parktikanten oder Mitarbeiter!</p><br /><br />', 1, '2011-04-15', 3),
(22, 9, 30, 'text', '', 'align=\"\" margin=\"\"', '<p><strong>ULRIKE G. E. B&#220;LTEMEYER</strong>                        </p><p><font size=\"2\">geb.1976 in Klagenfurt, K&#228;rnten&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160; &#160;&#160; &#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</font></p><p><font size=\"2\"> Juni 1994               Matura ( Abitur)</font></p><p><br /><strong>1994 - 2002    Hauptuniversit&#228;t Wien</strong><br />           Institut f&#252;r Romanistik ( bis 1996)<br />           Institut f&#252;r Kunstgeschichte ( bis 2002 )</p><p><strong><br />1998- 2003       Akademie der Bildenden K&#252;nste Wien ( Universit&#228;t)</strong><br />           Meisterklasse f&#252;r Konservierung und Restaurierung<br />1999 - 2001    Studienrichtungsvertretung<br />1999 - 2002     Mandatar der Universit&#228;tsvertretung<br />2003                Diplom mit Auszeichnung<br /><br /><strong>2003           </strong>W&#252;rdigungspreis des Bundesministeriums f&#252;r Bildung,Wissenschaft und         Kultur</p><p>&#160;</p><p><strong>REFERENZEN&#160;</strong>&#160;</p><p><strong>seit September 2009 - Liechtensteinmuseum Wien</strong></p><p>Gemaelderestaurierung, Angestelltenverh&#228;ltnis </p><p>&#160;</p><p><strong>2009 - Museum zu Allerheiligen, Schaffhausen, Schweiz</strong></p><p>Restaurierungsprojekt von gefassten Objekten zur Sonderausstellung &quot;Schaffhausen im Fluss&quot; </p><p>&#160;</p><p><strong>2008- Bayerisches Textil- und Industriemuseum Augsburg<br /></strong></p><p>Konservierung von Gem&#228;lden, Zierrahmen und eines dreidimensionalen Stadtmodells f&#252;r die Einrichtung der zuk&#252;nftigen Schausammlung </p><p>&#160;</p><p><strong>2008-&#160; Pfarrhof in Raabs an der Thaya, Bundesdenkmalamt Nieder&#246;sterreich, A<br /></strong></p><p>Konservierung und Restaurierung einer aus Leinwand gespannten bemalten T&#252;r im g&#228;nzlich ausgemalten Festsaal der Sakristei, Projektleitung Arge RESTAURATOR </p><p>&#160;</p><p><strong>2008- Kloster Seligenthal, Landshut</strong></p><p>Konservierung und Entrestaurierung eines barocken Leinwandgem&#228;ldes mit starken Spannungssch&#228;den durch fehlerhafte Doublierung. </p><p>&#160;</p><p><strong>2008 - Stadtresidenz Landshut, Kapelle</strong></p><p><strong>Bayerische Schl&#246;sserverwaltung</strong> </p><p>Restaurierung des Renaissancealtares mit zwei Tafelgem&#228;lden von Hermann Posthumus um 1540 </p><p>&#160;</p><p><strong>2008 - Schlosskapelle Blutenburg, M&#252;nchen Obermenzing</strong></p><p><strong> Bayerische Schl&#246;sserverwaltung</strong></p><p>Konservierung&#160; und Restaurieurng der Gesprenge an den sp&#228;tgotischen </p><p>Seitenalt&#228;ren von Jan Polack um 1492</p><p>&#160;</p><p><strong>2007 - Schlo&#223; Nymphenburg, M&#252;nchen</strong></p><p><strong> Staatliches Bauamt M&#252;nchen I </strong></p><p>Konservierung und Restaurierung Gem&#228;lde der Westseite der n&#246;rdlichen Galerie von Franz Joachim Beich um 1722&#160;</p><p>&#160;</p><p><strong>2007 - Erzbisch&#246;fliche Residenz Bamberg, Mitarbeit bei A.Rothe<br /></strong></p><p>Konservierungs- und Pflegema&#223;nahmen im Gro&#223;en Speisesaal&#160;</p><p>&#160;</p><p><strong>2007 - Burgkirche Wittelsbach, Mitarbeit bei I.St&#252;mmer<br /></strong></p><p>Untersuchung der Fassung und Schadenserfassung zur Konzepterstellung und Kalkulation f&#252;r die Konservierung der Ausstattung in der Burgkirche in Oberwittelsbach.</p><p>&#160;</p><p><strong>2007 - St. Josef zu Hohenlinden, Mitarbeit bei I.St&#252;mmer<br /></strong></p><p>Untersuchung zur Fassung an den drei neugotischen Hochalt&#228;ren. Konzept und Kalkulation zur Freilegung der Fassung an den Hochalt&#228;ren und den Skulpturen.<br /> </p><p>&#160;</p><p><strong>2006 - Atelier Vogt/ Rothe</strong></p><p>Restaurierung eines Fastentuches aus dem Kloster Irsee </p><p>&#160;</p><p><strong>2003 &#8211; 2006</strong> - <strong>BNM Bayerisches Nationalmuseum M&#252;nchen</strong> </p><p>Abteilung f&#252;r Gem&#228;lde und Skulpturen</p><p>Konservierung und Restaurierung der Gem&#228;lde f&#252;r das Zweigmuseum Landshut -Burg Trausnitz </p><p>Konservierung und Resaturierung des Traminer Altares von Hans Klocker </p><p>&#160;</p><p><strong>2002</strong> - <strong>Atelier Prenner / Scheel, Wien</strong></p><p>Voruntersuchung und Schadensbefund der skulpturalen Ausstattung der Wallfahrtskirche Maria Taferl in Nieder&#246;sterreich</p><p>Restaurierungsarbeiten an gro&#223;formatigen barocken Leinwandgem&#228;lden</p><p>&#160;</p><p><strong>2002 </strong>- <strong>MAK Museum f&#252;r Angewandte Kunst in Wien</strong></p><p>Abteilung f&#252;r Papierrestaurierung</p><p>Montage von Teilen der Ornamentstichsammlung</p><p>Konservierung eines chinesischen Rollbildes </p><p>Restaurierung eines Plakates der Wiener Werkst&#228;tte </p><p>Motage und Rahmung von Entw&#252;rfen von Hoffman </p><p><br /><strong>2001 - Bayerisches Landesamt f&#252;r Denkmalpflege</strong></p><p>Restaurierungsarbeiten in der barocken Klosterkirche Rott am Inn </p><p>&#160;</p><p><strong>2000 - Pfanner GmbH f&#252;r Steinmetz und Steinrestaurierung, M&#252;nchen</strong></p><p>Restaurierungsarbeiten an der Terakottafassade des Regierungsgeb&#228;udes von Oberbayern </p><p>&#160;</p><p><strong>1996 &#8211; 1999 - Atelier f&#252;r Gem&#228;lderestaurierung Mag. Manfred Siems, Wien</strong></p><p>Resaturierung und Neurahmung der Gem&#228;lde der Sammlung Leopold </p><p>Resaturierungsma&#223;nahmen an Gem&#228;lden des 19. und 20. Jahrhunderts </p><p>&#160;</p>', 1, '2011-04-11', 3),
(28, 8, 20, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"#181818\" framecolor=\"#FFFFFF\" textcolor=\"#FFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"schatten.html\" align=\"\" columns=\"3\"', '', 1, '2006-09-07', 1),
(23, 10, 10, 'text', '', 'align=\"\" margin=\"\"', '<p><strong>2008 - Schlosskapelle Blutenburg</strong></p><p>&nbsp;Konservierung der sp&auml;tgotischen Seitenalt&auml;re der Kapelle der Blutenburg </p><p>&nbsp;</p><p><strong>2007 - Schlo&szlig; Nymphenburg Gem&auml;lde der N&ouml;rdlichen Galerie</strong> </p><p>Restaurierung von 4 gro&szlig;formatigen Leinwandgem&auml;lden von Farnz Joachim Beich, 1620&nbsp;</p><p>&nbsp;</p><p><strong>2007 - Mitarbeit bei A.Rothe in der Erzbisch&ouml;flichen Residenz Bamberg</strong></p><p>Konservierungs- und Pflegema&szlig;nahmen im Gro&szlig;en Speisesaal&nbsp;</p><p>&nbsp;</p><p><strong>2007 - Mitarbeit bei I.St&uuml;mmer in der Burgkirche Wittelsbach</strong></p><p>Untersuchung der Fassung und Schadenserfassung zur Konzepterstellung und Kalkulation f&uuml;r die Konservierung der Ausstattung in der Burgkirche in Oberwittelsbach.</p><p>&nbsp;</p><p><strong>2007 - Mitarbeit bei I. St&uuml;mmer in der Pfarrkirche St. Josef zu Hohenlinden</strong></p><p>Untersuchung zur Fassung an den drei neugotischen Hochalt&auml;ren. Konzept und Kalkulation zur Freilegung der Fassung an den Hochalt&auml;ren und den Skulpturen.<br /> </p><p>&nbsp;</p><p><strong>2006 - Atelier Vogt/ Rothe</strong></p><p>Restaurierung eines Fastentuches aus dem Kloster Irsee </p><p>&nbsp;</p><p><strong>2003 &ndash; 2006</strong> - <strong>BNM Bayerisches Nationalmuseum M&uuml;nchen</strong> </p><p>Abteilung f&uuml;r Gem&auml;lde und Skulpturen</p><p>Konservierung und Restaurierung der Gem&auml;lde f&uuml;r das Zweigmuseum Landshut -Burg Trausnitz </p><p>Konservierung und Resaturierung des Traminer Altares von Hans Klocker </p><p>&nbsp;</p><p><strong>2002</strong> - <strong>Atelier Prenner / Scheel, Wien</strong></p><p>Voruntersuchung und Schadensbefund der skulpturalen Ausstattung der Wallfahrtskirche Maria Taferl in Nieder&ouml;sterreich</p><p>Restaurierungsarbeiten an gro&szlig;formatigen barocken Leinwandgem&auml;lden</p><p>&nbsp;</p><p><strong>2002 </strong>- <strong>MAK Museum f&uuml;r Angewandte Kunst in Wien</strong></p><p>Abteilung f&uuml;r Papierrestaurierung</p><p>Montage von Teilen der Ornamentstichsammlung</p><p>Konservierung eines chinesischen Rollbildes </p><p>Restaurierung eines Plakates der Wiener Werkst&auml;tte </p><p>Motage und Rahmung von Entw&uuml;rfen von Hoffman </p><p><br /><strong>2001 - Bayerisches Landesamt f&uuml;r Denkmalpflege</strong></p><p>Restaurierungsarbeiten in der barocken Klosterkirche Rott am Inn </p><p>&nbsp;</p><p><strong>2000 - Pfanner GmbH f&uuml;r Steinmetz und Steinrestaurierung, M&uuml;nchen</strong></p><p>Restaurierungsarbeiten an der Terakottafassade des Regierungsgeb&auml;udes von Oberbayern </p><p>&nbsp;</p><p><strong>1996 &ndash; 1999 - Atelier f&uuml;r Gem&auml;lderestaurierung Mag. Manfred Siems, Wien</strong></p><p>Resaturierung und Neurahmung der Gem&auml;lde der Sammlung Leopold </p><p>Resaturierungsma&szlig;nahmen an Gem&auml;lden des 19. und 20. Jahrhunderts </p>', 1, '2008-04-24', 3),
(24, 10, 20, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"0x181818\" framecolor=\"0xFFFFFF\" textcolor=\"0xFFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"2\"', '', 1, '2006-08-27', 1),
(25, 11, 10, 'image', 'oerv.jpg', 'width=\"97\" height=\"68\" align=\"center\" border=\"0\" space=\"0\" noflow=\"0\" link=\"\" target=\"\" viewer=\"0\"', 'Mitglied im Österreichischen Restauratorenverband', 1, '2006-08-27', 1),
(26, 11, 20, 'image', 'museumsrat.jpg', 'width=\"97\" height=\"64\" align=\"center\" border=\"0\" space=\"0\" noflow=\"0\" link=\"\" target=\"\" viewer=\"0\"', 'Mitglied im internationalen Museumsrat', 1, '2006-08-27', 1),
(171, 22, 150, 'image', 'wappen_frau_2.gif', 'width=\"198\" height=\"252\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"156\" theight=\"200\"', 'Beispiel fuer ein handgemaltes Wappen auf Papier, 250,-Euro', 3, '2009-05-19', 3),
(33, 1, 10, 'text_image', 'artconserverblau.jpg', 'width=\"1114\" height=\"145\" align=\"center\" margin=\"\" imgtitle=\"\" border=\"0\" imgspace=\"0\" noflow=\"1\" thumbnail=\"1\" link=\"\" target=\"\" twidth=\"500\" theight=\"64\"', '<div align=\"center\"> </div><div align=\"center\">&#160;</div><div align=\"center\"><font face=\"georgia,palatino\" size=\"5\" color=\"#0000ff\">mit neuem Sitz in Wien</font></div><div align=\"center\"><font color=\"#0000ff\">----------------------------------------------------------------------------------------------- </font><br /></div><div align=\"center\">&#160;</div><div align=\"center\"><font size=\"4\">Mag. Ulrike B&#252;ltemeyer</font></div><div align=\"center\"> </div><div align=\"center\"> </div><div align=\"center\"> </div><div align=\"center\">akademisch diplomierte Restauratorin f&#252;r<br /></div><div align=\"center\"> </div><div align=\"center\"> </div><div align=\"center\"> </div><div align=\"center\"> </div><div align=\"left\"><blockquote><blockquote><blockquote><div align=\"left\"> </div><ul><li><font size=\"3\" color=\"#0000ff\">Gem&#228;lde</font></li><li><font size=\"3\" color=\"#0000ff\">gefasste Holzskulptur</font></li><li><font size=\"3\" color=\"#0000ff\">Grafik und Papier</font><font color=\"#cc0033\"> <br /></font></li></ul><br /></blockquote></blockquote></blockquote></div><div align=\"center\"> </div><div align=\"center\"> </div>', 3, '2010-10-31', 3),
(32, 9, 10, 'image', 'ulli_in_workkl.jpg', 'width=\"345\" height=\"461\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" twidth=\"150\" theight=\"200\"', 'Foto Walter Haberland', 3, '2006-12-30', 3),
(80, 5, 150, 'image', 'rote_fassung.jpg', 'width=\"425\" height=\"313\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"200\" theight=\"146\"', 'Freilegung der originalen hellroten Fassung am Traminer Altar', 3, '2009-04-29', 3),
(41, 5, 140, 'image', 'freilegung_unter_dem_mikroskop.jpg', 'width=\"283\" height=\"213\" align=\"\" border=\"0\" space=\"0\" noflow=\"1\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"200\" theight=\"149\"', 'Freilegung einer feincraquelierten Rotlackfassung unter dem Mikroskop', 3, '2009-04-29', 3),
(36, 13, 30, 'text', '', 'align=\"\" margin=\"\"', '<p><a href=\"http://www.orv.at/\"><u>www.orv.at</u></a>                                 - &Ouml;sterreichischer Restauratorenverband</p><p><a href=\"http://www.icom-deutschland.de\" target=\"_blank\"><u>www.icom-deutschland.de</u></a><a href=\"www.icom-deutschland.de\" target=\"_blank\">            </a>- Internationaler Museumsrat </p><p><a href=\"http://www.romoe.net\" target=\"_blank\"><u>www.romoe.net</u>                            </a>- Das Restauratorenportal</p><p><a href=\"http://www.restaurator.at.tt\" target=\"_blank\">www.restaurator.at.tt</a>                    - Restaurierungsteam f&uuml;r Wandmalerei</p><p><a href=\"http://www.neutron.at\" target=\"_blank\"><u>www.neutron.at</u></a>                         - Web solutions</p><p><a href=\"http://www.restaurierung-lissner.de\" target=\"_blank\"><u>www.restaurierung-lissner.de</u></a> - Metallrestaurierung M&uuml;nchen&nbsp;</p><p><a href=\"http://www.restaurierungsatelier-gredel.de\" target=\"_blank\">www.restaurierungsatelier-gredel.de</a> - M&ouml;belrestaurierung M&uuml;nchen&nbsp;</p><p><a href=\"http://www.muenchen.city-map.de\" target=\"_blank\">www.muenchen.city-map.de</a> - M&uuml;ncheninfo</p><p><a href=\"http://www.munichantiques.com/\">www.munichantiques.com  </a></p><img src=\"file:///F:/DOKUME%7E1/ULLI/LOKALE%7E1/Temp/moz-screenshot.jpg\" alt=\"\" /><p><a href=\"http://www.altertuemliches.at/\" target=\"_blank\"><img src=\"http://www.altertuemliches.at/images/banner_234_18.gif\" border=\"0\" alt=\"Alte Kunst, Antiquit&auml;ten- Verzeichnis: Auktionsh&auml;user, Auktionen, Antiquit&auml;tenh&auml;ndler, Galerien, Restauratoren, Antiquariate, Museen, Ausstellungen, Messen. Kostenlose Kleinanzeigen und Forum. Viel Wissenswertes zum Thema Kunst und Antiquit&auml;ten. \" width=\"234\" height=\"18\" /></a> </p>', 3, '2008-06-13', 3),
(164, 5, 90, 'image', 'leuchter_fehlstellen.gif', 'width=\"269\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"72186\" twidth=\"144\" theight=\"200\"', 'Verluste in der Fassung vor der Restaurierung', 3, '2009-04-29', 3),
(163, 5, 80, 'image', 'vergoldeter_leuchter_klassizismus.gif', 'width=\"262\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"57174\" twidth=\"141\" theight=\"200\"', 'Klassizistischer vergoldeter Leuchter aus Holz und Karton', 3, '2009-04-29', 3),
(39, 3, 50, 'image', 'g165rissverkleb.jpg', 'width=\"298\" height=\"443\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" twidth=\"135\" theight=\"200\"', 'Durch die Verklebung eines Risses mit einzelnen Fäden kann heute auf die Doublierung eines Gemäldes in den meisten Fällen verzichtet werden', 3, '2006-09-25', 3),
(45, 3, 60, 'image', 'intarsie_gemalde.gif', 'width=\"340\" height=\"255\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" filesize=\"72975\" twidth=\"200\" theight=\"150\"', 'Detailaufnahme eines Kardinalportraits des Bayerischen Nationalmuseums mit eingesetzter Gewebeintarsie ', 3, '2006-12-01', 3),
(121, 15, 20, 'image', 'zeitgenossische_kunst.jpg', 'width=\"340\" height=\"256\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"104260\" twidth=\"200\" theight=\"149\"', 'Komplexe Materialkombinationen an zeitgenössischer Skulptur', 3, '2008-03-10', 3),
(120, 21, 20, 'text', '', 'align=\"\" margin=\"\"', '<p>BESTELLUNGEN AN</p><p>FON/ FAX: 089/ 74674050</p><p>info@artconserver&nbsp;</p><p>Oder: </p><p>Ulrike B&uuml;ltemeyer</p><p>Pfeuferstra&szlig;e 42, Rgb</p><p>81373 M&uuml;nchen&nbsp;</p>', 3, '2008-01-19', 3),
(51, 14, 10, 'text', '', 'align=\"\" margin=\"\"', '<p>Der Handel mit zeitgen&ouml;ssischer Kunst boomt wie nie zuvor!</p><p>Kaufen von Kunst als Anlage, Investition oder zum Sammeln liegen voll im Trend! Doch nur bei einer regelm&auml;&szlig;igen Pflege und Wartung bleibt der Wert auch erhalten! Sorgen Sie f&uuml;r eine optimale Lagerung und Aufbewahrung ihrer Kunstwerke!Gerne erarbeiten wir individuelle Konzepte! </p><p>Besondere Probleme entstehen bei der Konservierung von modernen Kunstwerken. da die traditionellen Materialien  verschwunden sind,  und der K&uuml;nstler st&auml;nidg neue Kunststoffe und Farben verwendet, deren Alterungsverhalten nicht bekannt ist.</p><p>Monochrome Gem&auml;lde mit matter pudriger Oberfl&auml;che haben ihren besonderen Reiz - auch f&uuml;r schmutzige Kinderh&auml;nde! Doch solche Mi&szlig;geschicke sind oft leicht zu beheben, wenn sie rechtzeitig behandelt werden, und sie kosten kein Verm&ouml;gen! </p><p>Investieren Sie in die Pflege der modernen Kunst, damit der Wert erhalten bleibt ! </p><p>&nbsp;</p>', 3, '2006-09-21', 3),
(55, 14, 20, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"#181818\" framecolor=\"#FFFFFF\" textcolor=\"#FFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"2\"', '', 3, '2006-09-21', 3),
(56, 15, 10, 'image', 'malschichtschaden_kl.jpg', 'width=\"283\" height=\"212\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" twidth=\"200\" theight=\"150\"', 'Schichtentrennung und Ablösen von weicher Malschicht eines komplexen Gemäldes in pastoser Malweise', 3, '2006-09-26', 1),
(57, 5, 70, 'image', 'blutenburg-altar.jpg', 'width=\"283\" height=\"377\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" filesize=\"36340\" twidth=\"149\" theight=\"200\"', 'Konservierungsmassnahmen an den Seitenaltären der Blutenburgkapelle', 3, '2008-04-22', 3),
(126, 5, 20, 'image', 'nepomuk-vorzustand.jpg', 'width=\"283\" height=\"480\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"116\" theight=\"200\"', 'Rokokofigur des Hl. Nepomuk im Vorzustand mit lockerer Malschicht und stark verschmutzter Fassung', 3, '2009-04-29', 3),
(61, 5, 180, 'image', 'stern_1.jpg', 'width=\"227\" height=\"226\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"200\" theight=\"198\"', 'Ein Strahlenkranz einer Rokokofigur mit erg?nztem Strahlen nach der Grundierung', 3, '2009-04-29', 3),
(62, 5, 190, 'image', 'stern_4.jpg', 'width=\"227\" height=\"212\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"200\" theight=\"185\"', 'Derselbe Strahlenkranz nach der Imitation der oxidierten Silberfassung', 3, '2009-04-29', 3),
(63, 16, 10, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"#181818\" framecolor=\"#FFFFFF\" textcolor=\"#FFFFFF\" vcolumns=\"1\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"1\"', '', 3, '2006-10-23', 3),
(67, 17, 10, 'image', 'folder_gold.jpg', 'width=\"827\" height=\"665\" align=\"center\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"119877\" twidth=\"400\" theight=\"320\"', '', 3, '2008-06-13', 3),
(69, 17, 30, 'text', '', 'align=\"left\" margin=\"\"', '<p align=\"left\"><u><strong>Weitere Leistungen und Service sind:</strong></u></p><p align=\"left\">&#160;</p><p align=\"left\">Restaurierung von Zierrahmen</p><p align=\"left\">Bau von Klimasafes ( Rahmungssystem mit Glas f&#252;r Gem&#228;lde)</p><p align=\"left\">Vergoldungen</p><p align=\"left\">Gem&#228;ldekopie</p><p align=\"left\">Rekonstruktionen von Fassungen&#160;</p><p align=\"left\">Erstellung von Protokollen f&#252;r den Leihverkehr</p><p align=\"left\">Begleitung von Kunsttransporten</p><p align=\"left\">Beratung bei Kunstank&#228;ufen</p><p align=\"left\">Untersuchung mit Ultraviolettem Licht</p><p align=\"left\">mikrochemische Pigmentanalysen</p><p align=\"left\">Depoteinrichtung- und Pflege</p><p align=\"left\">Kostenvoranschl&#228;ge und Schadensberichte f&#252;r Versicherungen&#160;</p><p align=\"left\">Objektuntersuchung und Dokumentation&#160;</p><br />', 3, '2009-08-11', 3),
(169, 22, 130, 'image', 'wappen_kopie.gif', 'width=\"283\" height=\"381\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"65714\" twidth=\"147\" theight=\"200\"', 'Kopie nach einem historischen Wappen auf gealterten Papier, 30 x 20 cm, 200,-?', 3, '2009-05-06', 3),
(168, 22, 80, 'text_image', 'wappen_heraldik1.gif', 'width=\"283\" height=\"333\" align=\"\" margin=\"\" imgtitle=\"\" border=\"0\" imgspace=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" twidth=\"168\" theight=\"200\"', '<p><strong>Wappen</strong></p><p>Anfertigung von handgemalten Wappen mit Gold-/ Silbermalerei oder auch wahlweise mit echtem Blattgold.</p><p>Diverse Formate und Ausf&#252;hrungen sind m&#246;glich.</p><p>Einrahmung mit Passepartout erfolgt auf Wunsch. </p><p>&#160;</p>', 3, '2009-05-06', 3),
(166, 5, 110, 'image', 'leuchter_ende.gif', 'width=\"277\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"81341\" twidth=\"149\" theight=\"200\"', 'Detailaufnahme des Blattwerks mit Goldretusche ', 3, '2009-04-29', 3),
(73, 3, 110, 'image', 'ferdinand_vor.jpg', 'width=\"198\" height=\"241\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" filesize=\"29276\" twidth=\"164\" theight=\"200\"', 'Portrait Erzherzog Ferdinand während der Restaurierung mit Kittungen', 3, '2006-11-29', 3),
(74, 3, 120, 'image', 'gemalderestaurierung.jpg', 'width=\"315\" height=\"380\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" twidth=\"166\" theight=\"200\"', 'Portrait Erzherzog Ferdinand nach der Retusche, neuem Firnisauftrag und Einrahmung', 3, '2006-11-29', 3),
(173, 3, 80, 'image', '09_intarsie.gif', 'width=\"283\" height=\"231\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"63154\" twidth=\"200\" theight=\"162\"', 'Restaurierung des Bildtraegers durch Einsetzen von Intarsien', 3, '2009-05-21', 3),
(172, 3, 70, 'image', '09_ecke_zerfranst.gif', 'width=\"283\" height=\"213\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"55491\" twidth=\"200\" theight=\"149\"', 'Ausgefranste Ecke eines Bildes', 3, '2009-05-21', 3),
(165, 5, 100, 'image', 'leuchter_kittung.gif', 'width=\"276\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"148\" theight=\"200\"', 'Weisse und gelb gefaerbte Kittung der Fehlstellen', 3, '2009-04-29', 3),
(83, 3, 150, 'image', 'poppe_folkerts_det2_vor.gif', 'width=\"283\" height=\"213\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"200\" theight=\"149\"', 'Risse in einem Tafelgemälde von Poppe Folkerts, 1946', 3, '2008-07-10', 3),
(84, 3, 160, 'image', 'poppe_folkerts_det_2nach.gif', 'width=\"312\" height=\"234\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" filesize=\"65305\" twidth=\"200\" theight=\"149\"', 'Risse nach der Restaurierung', 3, '2008-07-10', 3),
(85, 3, 170, 'image', 'gemalderestaurierung_bultemeyer.gif', 'width=\"340\" height=\"454\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" twidth=\"150\" theight=\"200\"', 'Großformatiges Leinwandgemälde des 19. Jahrhunderts mit weißer Kittung der Fehlstellen, während der Strukturretusche', 3, '2006-12-01', 3),
(86, 3, 180, 'image', 'strukturretusche.gif', 'width=\"340\" height=\"426\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" filesize=\"153761\" twidth=\"160\" theight=\"200\"', 'Detailaufnahme der Strukturretusche mit kleinen Punkten zur Imitation der Gemäldeoberfläche', 3, '2006-12-01', 3),
(91, 18, 10, 'image', 'lg1orv.jpg', 'width=\"99\" height=\"36\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" filesize=\"4786\" twidth=\"100\" theight=\"35\"', 'Mitglied im Österreichischen Restauratorenverband', 3, '2008-05-04', 3),
(90, 9, 40, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"#181818\" framecolor=\"#FFFFFF\" textcolor=\"#FFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"2\"', '', 3, '2007-01-07', 3),
(143, 3, 260, 'image', 'rabbs_nachher.gif', 'width=\"198\" height=\"265\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"41209\" twidth=\"148\" theight=\"200\"', 'Tapetentür nach der Restaurierung', 3, '2009-01-15', 3),
(113, 22, 40, 'text_image', 'gotisches-relief-1.gif', 'width=\"283\" height=\"346\" align=\"\" margin=\"\" imgtitle=\"\" border=\"0\" imgspace=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" twidth=\"162\" theight=\"200\"', '<p>Genesis - Erschaffung Evas aus der Rippe Adams, und Verk&uuml;ndigung an Mariae;</p><p>Gipsabgu&szlig; eines sp&auml;tgotischen Reliefs ( ca. 17 x 10 cm)<br /></p><p>&nbsp;15,-&euro;</p><p>&nbsp;</p>', 3, '2008-01-19', 3),
(112, 22, 30, 'text_image', 'artdeco2.gif', 'width=\"283\" height=\"332\" align=\"\" margin=\"\" imgtitle=\"\" border=\"0\" imgspace=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" twidth=\"169\" theight=\"200\"', '<p>Artdeco Engelsanh&auml;nger aus Gips mit Schlaufe zum Aufh&auml;ngen; wahlweise in Silber oder in Altgold gefasst und patiniert! je 5,-&euro;</p><p>in echtem Blattgold oder Blattsilber je 10,-&euro;&nbsp;</p>', 3, '2007-09-09', 3),
(122, 15, 30, 'image', 'gressl--roger.jpg', 'width=\"198\" height=\"346\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"113\" theight=\"200\"', 'Roger Greßl Tryptichon, Gesamtaufnahme', 3, '2008-03-10', 3),
(110, 22, 10, 'text_image', 'engele.jpg', 'width=\"512\" height=\"267\" align=\"\" margin=\"\" imgtitle=\"\" border=\"0\" imgspace=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" twidth=\"200\" theight=\"103\"', '<p>Gipsabgu&szlig; nach einem Original von Ignaz G&uuml;nther Putto; Farbig gefasst mit vergoldeten Federkleid; 30 cm hoch, 17 cm breit, ca.1,5 kg schwer</p><p>Wei&szlig; oder farbig .................................70,-&euro;&nbsp;</p><p>mit Schlagmetallauflage......................... 80,-&euro;</p><p>mit echtem Blattgold............................&nbsp; 90,-&euro;&nbsp;</p><p>Versandkosten 10,-&euro;&nbsp;</p>', 3, '2008-06-13', 3),
(109, 21, 10, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"#181818\" framecolor=\"#FFFFFF\" textcolor=\"#FFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"1\"', '', 3, '2008-01-19', 3),
(157, 7, 90, 'image', 'historisches_wappen_mit_verbraunung.gif', 'width=\"277\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"149\" theight=\"200\"', 'Historischer Wappenbrief mit stark vergilbten Papier und braunem Fleck', 3, '2009-04-29', 3),
(156, 7, 80, 'image', 'grafik_nach_bleichen.gif', 'width=\"425\" height=\"256\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"88072\" twidth=\"200\" theight=\"119\"', 'Kupferstich nach Bleichen der Flecken', 3, '2009-04-29', 3),
(155, 7, 70, 'image', 'grafik_mit_braunen_flecken.gif', 'width=\"425\" height=\"265\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"200\" theight=\"123\"', 'Colorierter Kupferstich mit braunen Flecken', 3, '2009-04-29', 3),
(115, 1, 30, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"#181818\" framecolor=\"#FFFFFF\" textcolor=\"#FFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"invisible.html\" align=\"\" columns=\"2\"', '', 3, '2007-09-09', 3),
(123, 15, 40, 'image', 'gressl-detail1.jpg', 'width=\"369\" height=\"277\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"15830\" twidth=\"200\" theight=\"149\"', 'Dellen durch Transportschaden am Gemälde Tryptichon von R.Greßl', 3, '2008-03-10', 3),
(134, 3, 20, 'image', 'johann_evangelista_holzl_detail.gif', 'width=\"198\" height=\"265\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"148\" theight=\"200\"', 'Detailaufnahme waehrend der Restaurierung und Firnisabnahme', 3, '2009-05-20', 3),
(129, 5, 30, 'image', 'nepomuk-_restauriert.jpg', 'width=\"255\" height=\"476\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"3\" id=\"\" twidth=\"106\" theight=\"200\"', 'Endzustand nach Reinigung, Kittung undRetusche ', 3, '2009-04-29', 3),
(133, 3, 10, 'image', 'johann_evangelista_holzl_rest.gif', 'width=\"198\" height=\"265\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"148\" theight=\"200\"', 'Gemaelde von Johann Evangelista Hoelzl waehrend der Restaurierung', 3, '2009-05-20', 3),
(125, 1, 40, 'text_image', 'folder_gold1.jpg', 'width=\"827\" height=\"665\" align=\"center\" margin=\"\" imgtitle=\"\" border=\"0\" imgspace=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" twidth=\"500\" theight=\"401\"', '<p align=\"center\"><font size=\"5\" color=\"#0000ff\"><strong><font face=\"andale mono,times\"><em>Wahre Kunst bleibt unverg&#228;nglich!</em></font></strong></font></p><p align=\"center\"><em>(Ludwig van Beethoven)&#160;</em></p><p align=\"center\">&#160;</p><p align=\"center\"><font size=\"3\"><strong>Der akademische Restaurator tr&#228;gt zu ihrem Erhalt bei.&#160;</strong></font></p><p align=\"center\">&#160;</p><p align=\"center\">Mag.Ulrike B&#252;ltemeyer</p><p align=\"center\">email: info@artconserver.net</p>', 3, '2010-10-31', 3),
(130, 18, 20, 'image', 'icom-logo_neu.gif', 'width=\"149\" height=\"136\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"4505\" twidth=\"100\" theight=\"90\"', 'Mitglied im Internationalen Museumsverband', 3, '2008-05-04', 3),
(131, 22, 60, 'image', 'alte_dame_portrait.gif', 'width=\"283\" height=\"353\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"159\" theight=\"200\"', 'Portrait Öl auf Leinwand mit vergoldeten Zierrahmen, ca. 1910, Gemäldemaße: 40 x 30 cm, 750,-€', 3, '2008-06-13', 3),
(142, 3, 250, 'image', 'rabbs_vorher.gif', 'width=\"198\" height=\"265\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"44024\" twidth=\"148\" theight=\"200\"', 'Tapetentür im Festsaal des Pfarrhofs in Raabs an der Thaya, Österreich', 3, '2009-01-15', 3),
(138, 5, 40, 'image', 'nepomuk_gesicht_vor.jpg', 'width=\"591\" height=\"794\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"147\" theight=\"200\"', 'Gesicht im Vorzustand mit Verschmutzung und vergilbten Firnis', 3, '2009-04-29', 3),
(139, 5, 50, 'image', 'nepomuk_gesicht_nach.jpg', 'width=\"591\" height=\"799\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"146\" theight=\"200\"', 'Gesicht nach Reinigung der Oberflaeche und Retusche der Fehlstellen', 3, '2009-04-29', 3),
(140, 3, 230, 'image', 'knabe_vorher.gif', 'width=\"591\" height=\"787\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"149\" theight=\"200\"', 'Knabenportrait mit Malschichtverlusten mit weißer Kittung', 3, '2009-01-15', 3),
(141, 3, 240, 'image', 'knabe_nachher.gif', 'width=\"591\" height=\"775\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"296458\" twidth=\"151\" theight=\"200\"', 'Knabenportrait nach der Restaurierung', 3, '2008-12-11', 3),
(144, 24, 10, 'gallery', '', 'ca_nr=\"10000\" title=\"\" type=\"\" width=\"500\" height=\"400\" navPosition=\"bottom\" bgcolor=\"#181818\" framecolor=\"#FFFFFF\" textcolor=\"#FFFFFF\" vcolumns=\"5\" rows=\"2\" template=\"default.html\" align=\"\" columns=\"2\"', '', 3, '2009-02-05', 1),
(145, 25, 10, 'text', '', 'align=\"\" margin=\"\"', '<p align=\"left\">Antiquit&#228;ten und Komplexe Objekte aus dem volkskundlichen oder kirchlichen Umfeld sind meist gekennzeichnet von einer Vielfalt an Materialien. Es ist daher immer schwierig den passenden Fachmann f&#252;r ein Objekt zu finden. Oftmals ist es auch n&#246;tig sich die Materialien und Massnahmen individuell auf jedes Kunstwerk abzustimmen.</p><p align=\"left\">Durch die Zusammenarbeit mit Kollegen ist es m&#246;glich, dass auch Objekte mit Metall, Glas, Textil oder Holz in meinem Atelier restauriert werden k&#246;nnen. </p>', 3, '2009-01-15', 3),
(146, 25, 30, 'image', 'kreuzteile.jpg', 'width=\"728\" height=\"1164\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"124\" theight=\"200\"', 'Holzkreuz mit Perlmuttintarsien vor der Restaurierung', 3, '2009-01-15', 3),
(147, 25, 20, 'image', 'kindl.gif', 'width=\"425\" height=\"355\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"200\" theight=\"166\"', 'Christusfigur mit neu angefertigten Kleid', 3, '2009-01-15', 3),
(148, 25, 40, 'image', 'kreuz_ende.jpg', 'width=\"866\" height=\"1234\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"321964\" twidth=\"139\" theight=\"200\"', 'Kreuz nach der Restaurierung', 3, '2009-01-15', 3),
(149, 25, 50, 'image', 'glas_gebrochen.gif', 'width=\"378\" height=\"283\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"99620\" twidth=\"200\" theight=\"148\"', 'Gebrochene Jugendstil Glasscheibe mit reliefierter Oberfläche', 3, '2009-01-15', 3),
(150, 25, 60, 'image', 'glas_verklebt.gif', 'width=\"378\" height=\"283\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"110334\" twidth=\"200\" theight=\"148\"', 'Glasscheibe nach Verklebung und Ergänzung von Fehlstellen', 3, '2009-01-15', 3),
(151, 25, 70, 'image', 'riss_vorher.gif', 'width=\"378\" height=\"283\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"84315\" twidth=\"200\" theight=\"148\"', 'Riss im Messingrand einer Bleiglaslampe', 3, '2009-01-15', 3),
(152, 25, 80, 'image', 'riss_verklebt_innen.gif', 'width=\"378\" height=\"283\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"81138\" twidth=\"200\" theight=\"148\"', 'Riss nach der Überbrückung mit Messingblech', 3, '2009-01-15', 3),
(153, 22, 110, 'image', 'wappen_auf_marmorpaier.gif', 'width=\"283\" height=\"378\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"148\" theight=\"200\"', 'Handgemaltes Wappen auf Marmorpapier, mit Gold und Silbermalerei, ca 21 x 15 cm, 150,-?', 3, '2009-05-19', 3),
(154, 22, 120, 'image', 'wappen_auf_karton.gif', 'width=\"369\" height=\"475\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"154\" theight=\"200\"', 'Handgemaltes Wappen auf Tonpapier, mit Gold und Silbermalerei, ca 40 x 30 cm, 300,-Euro', 3, '2009-05-19', 3),
(158, 7, 100, 'image', 'historisches_wappen_nach_bleichen.gif', 'width=\"272\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"146\" theight=\"200\"', 'Historischer Wappenbrief nach dem Bleichen des Papieres', 3, '2009-04-29', 3),
(159, 7, 110, 'image', 'broselige_malschicht_in_einem_wappen.gif', 'width=\"369\" height=\"276\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" twidth=\"200\" theight=\"148\"', 'Malschicht des Wappens ist stark beschaedigt und broeselig', 3, '2009-04-29', 3),
(160, 7, 120, 'image', 'festigung_der_malschicht.gif', 'width=\"380\" height=\"271\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"71930\" twidth=\"200\" theight=\"141\"', 'Festigung der Malschicht und Retusche am Saugtisch', 3, '2009-04-29', 3),
(161, 7, 130, 'image', 'wappen_vor_restaurierung.gif', 'width=\"276\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"64640\" twidth=\"148\" theight=\"200\"', 'Historisches Wappen vor der Restaurierung', 3, '2009-04-29', 3),
(162, 7, 140, 'image', 'wappen_nach_restaurierung.gif', 'width=\"276\" height=\"369\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"69984\" twidth=\"148\" theight=\"200\"', 'Historisches wappen nach der Restaurierung und Retusche', 3, '2009-04-29', 3),
(174, 15, 50, 'image', '09_kubistisch_ganz.gif', 'width=\"283\" height=\"340\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"53979\" twidth=\"165\" theight=\"200\"', 'Russisches Gemaelde in kubistischen Stil', 3, '2009-05-21', 3),
(175, 15, 60, 'image', '09_kubism.gif', 'width=\"283\" height=\"378\" align=\"\" border=\"0\" space=\"0\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"0\" id=\"\" filesize=\"61991\" twidth=\"148\" theight=\"200\"', 'Pastose Malerei mit vergilbetn Firnis und Fehlstelle', 3, '2009-05-21', 3),
(178, 26, 10, 'text', '', 'align=\"\" margin=\"\"', '<p>Alle Texte und Bilder soweit nicht anders angegeben sind von</p><p>Ulrike B&#252;ltemeyer</p><br /><p><u>Abbildung von Objekten mit &#246;ffentlichem Eigent&#252;mer</u><br /></p><p>Mit freundlicher Genehmigung der Bayerischen Verwaltung der Schl&#246;sser und Seen </p><p>-&#160; Residenz Landshut, Altar von Herman Posthumus</p><p>-&#160; Schloss Nymphenburg, N&#246;rdliche Galerie, 4 Vedutengem&#228;lde von Franz </p><p>&#160;&#160; Joachim Beich</p><p>-&#160; Schloss Blutenburg, Seitenalt&#228;re der Kapelle </p><p>&#160;</p><p>Mit freundlicher Genehmigung des Bayerischen Nationalmuseum M&#252;nchen </p><p>-&#160; Traminer Altar von Hans Klocker</p><p>-&#160; Portrait Erzherzog Ferdinand<br /></p>', 3, '2009-08-11', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_objecttypes`
--

CREATE TABLE `sk_objecttypes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `mime_type` varchar(150) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `generic` tinyint(4) NOT NULL DEFAULT 0,
  `icon` varchar(40) DEFAULT NULL,
  `admin_vis` tinyint(4) NOT NULL DEFAULT 0,
  `template` varchar(50) DEFAULT NULL,
  `sort_nr` tinyint(4) DEFAULT NULL,
  `objgroup` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_objecttypes`
--

INSERT INTO `sk_objecttypes` (`id`, `name`, `type`, `mime_type`, `description`, `tag`, `generic`, `icon`, `admin_vis`, `template`, `sort_nr`, `objgroup`) VALUES
(1, 'Text', 'text', 'text/html', 'simple HTML-formatted Text-Object', '<DIV class=\"sktext\"></DIV>', 0, 'text.gif', 1, 'text.php', 10, 'cms'),
(2, 'Image', 'image', '', 'Image Upload', '<img src=\"\" width=\"\" height=\"\" border=\"\" vspace=\"\" hspace=\"\" alt=\"\" name=\"\">', 0, 'image.gif', 1, 'image.php', 20, 'cms'),
(3, 'News-Output', 'news', '', 'displays news items FROM database', '', 0, 'news.gif', 1, 'news.php', 60, 'modules'),
(4, 'Text+Image', 'text_image', '', 'Text & 1 Image', '', 0, 'text_image.gif', 1, 'text_image.php', 25, 'cms'),
(5, 'Media-Gallery', 'gallery', '', 'Gallery for different media_types', NULL, 0, 'gallery.gif', 1, 'gallery.php', 50, 'cms'),
(6, 'Sound', 'sound', '', 'Sound-File', NULL, 0, 'sound.gif', 1, 'sound.php', 30, 'cms'),
(7, 'Verknüpfung', 'link', '', 'Link to another object', NULL, 0, 'link.gif', 1, 'link.php', 40, 'cms'),
(8, 'Datei', 'file', '', 'generic File', NULL, 0, 'file.gif', 1, 'file.php', 35, 'cms'),
(9, 'PHP-File', 'phpfile', '', 'includes an PHP-File', NULL, 0, 'phpfile.gif', 1, 'phpfile.php', 99, 'special'),
(10, 'HTTP_Request', 'http', '', 'Opens a Page with HTTP-GET or POST', NULL, 0, 'http.gif', 1, 'http.php', 101, 'special'),
(11, 'Sitemap', 'sitemap', '', 'automatic Sitemap', NULL, 0, 'sitemap.gif', 1, 'sitemap.php', 40, 'modules'),
(12, 'Video&Flash', 'video_flash', '', 'Embed video or flash file', NULL, 0, 'video_flash.gif', 1, 'video_flash.php', 30, 'cms'),
(13, 'Overview', 'overview', '', 'Navigation Overview (Subtree)', NULL, 0, 'sitemap.gif', 1, 'overview.php', 45, 'modules'),
(14, 'Umleitung', 'redirect', '', 'Umleitung zu URL', NULL, 0, 'redirect.gif', 1, 'redirect.php', 50, 'special');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_sites`
--

CREATE TABLE `sk_sites` (
  `site_id` mediumint(9) NOT NULL,
  `name` varchar(150) NOT NULL DEFAULT '',
  `dirname` varchar(100) NOT NULL DEFAULT '',
  `site_url` varchar(127) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `meta` text DEFAULT NULL,
  `last_mod` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) NOT NULL DEFAULT 0,
  `port` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_sites`
--

INSERT INTO `sk_sites` (`site_id`, `name`, `dirname`, `site_url`, `description`, `meta`, `last_mod`, `user_id`, `group_id`, `port`) VALUES
(1, 'Ullis Website', '', 'http://www.artconserver.net/', 'Root von diesem Server', '<meta name=\"keywords\" content=\"Restaurierung, Konservierung, Grafik, Gemälde, Bilder, Skulpturen, Atelier, Kunst, Risse, Fehlstellen, Malerei, Vergolden, Erhalten, Kunstwerke\">\r\n\r\n  <meta name=\"description\" content=\"\">\r\n  <meta name=\"author\" content=\"Ulrike Bueltemeyer\">\r\n  <META name=\"company\" content=\"Atelier für Konservierung und Restaurierung\">\r\n  <meta name=\"robots\" content=\"all\">\r\n  <meta name=\"reply-to\" content=\"info@artconserver.net\">\r\n  <meta name=\"robots\" content=\"index,follow\">\r\n  <meta name=\"revisit-after\" content=\"15 days\">\r\n  <meta name=\"email\" content=\"info@artconserver.net\">\r\n  <meta name=\"city\" content=\"München, Munich\">\r\n  <meta name=\"country\" content=\"Germany\">\r\n\r\n  <meta name=\"GENERATOR\" content=\"SK-CMS v0.9\">\r\n  <meta name=\"abstract\" content=\"Konservierung und Restaurierung von Kunstwerken\">\r\n  <meta HTTP-EQUIV=\"Content-language\" content=\"de, at, ch\">\r\n  ', '2006-09-13 12:16:54', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_users`
--

CREATE TABLE `sk_users` (
  `uid` int(5) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL DEFAULT '',
  `uname` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `user_avatar` varchar(30) DEFAULT NULL,
  `user_regdate` date DEFAULT '0000-00-00',
  `user_icq` varchar(15) DEFAULT NULL,
  `user_from` varchar(100) DEFAULT NULL,
  `user_sig` text DEFAULT NULL,
  `user_viewemail` tinyint(1) NOT NULL DEFAULT 0,
  `actkey` varchar(8) DEFAULT NULL,
  `user_aim` varchar(18) DEFAULT NULL,
  `user_yim` varchar(25) DEFAULT NULL,
  `user_msnm` varchar(25) DEFAULT NULL,
  `pass` varchar(32) NOT NULL DEFAULT '',
  `posts` int(8) DEFAULT 0,
  `attachsig` tinyint(1) DEFAULT 0,
  `rank` int(5) NOT NULL DEFAULT 0,
  `level` int(5) NOT NULL DEFAULT 1,
  `theme` varchar(100) NOT NULL DEFAULT '',
  `timezone_offset` float(3,1) NOT NULL DEFAULT 0.0,
  `last_login` int(10) NOT NULL DEFAULT 0,
  `umode` varchar(10) NOT NULL DEFAULT '',
  `uorder` tinyint(1) NOT NULL DEFAULT 0,
  `user_occ` varchar(100) DEFAULT NULL,
  `bio` tinytext NOT NULL,
  `logins` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `sk_users`
--

INSERT INTO `sk_users` (`uid`, `name`, `uname`, `email`, `url`, `user_avatar`, `user_regdate`, `user_icq`, `user_from`, `user_sig`, `user_viewemail`, `actkey`, `user_aim`, `user_yim`, `user_msnm`, `pass`, `posts`, `attachsig`, `rank`, `level`, `theme`, `timezone_offset`, `last_login`, `umode`, `uorder`, `user_occ`, `bio`, `logins`) VALUES
(1, 'Edgar Bueltemeyer', 'edb', 'edgar_b@gmx.net', '', NULL, '0000-00-00', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '3c22d11e0d12c4a35b08708c4f47dabd', 6, 0, 0, 1, '', 0.0, 1340209007, '', 0, NULL, '', 420),
(3, 'ulli', 'ulli', '', '', NULL, '0000-00-00', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '8e43e0a790d098841d4d234b023b353a', 0, 0, 0, 1, '', 0.0, 1692460006, '', 0, NULL, '', 193);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sk_var_store`
--

CREATE TABLE `sk_var_store` (
  `id` int(11) NOT NULL,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `vardata` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Variable_Storage';

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `sk_content`
--
ALTER TABLE `sk_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `sk_groups`
--
ALTER TABLE `sk_groups`
  ADD PRIMARY KEY (`groupid`),
  ADD UNIQUE KEY `groupid` (`groupid`),
  ADD KEY `type` (`type`);

--
-- Indizes für die Tabelle `sk_groups_modules_link`
--
ALTER TABLE `sk_groups_modules_link`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupid_mid_type` (`groupid`,`mid`,`type`),
  ADD KEY `groupid_type_mid` (`groupid`,`type`,`mid`);

--
-- Indizes für die Tabelle `sk_groups_nav_tree_link`
--
ALTER TABLE `sk_groups_nav_tree_link`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`groupid`,`nat_id`,`type`),
  ADD KEY `index` (`groupid`,`nat_id`,`type`);

--
-- Indizes für die Tabelle `sk_groups_sites_link`
--
ALTER TABLE `sk_groups_sites_link`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`groupid`,`site_id`,`type`),
  ADD KEY `index` (`groupid`,`site_id`,`type`);

--
-- Indizes für die Tabelle `sk_groups_users_link`
--
ALTER TABLE `sk_groups_users_link`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_id_uid` (`groupid`,`uid`),
  ADD KEY `groupid_uid` (`groupid`,`uid`);

--
-- Indizes für die Tabelle `sk_modules`
--
ALTER TABLE `sk_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indizes für die Tabelle `sk_nav_tree`
--
ALTER TABLE `sk_nav_tree`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `sk_news`
--
ALTER TABLE `sk_news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indizes für die Tabelle `sk_newssections`
--
ALTER TABLE `sk_newssections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indizes für die Tabelle `sk_news_comments`
--
ALTER TABLE `sk_news_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `item_id` (`article_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject` (`subject`(40)),
  ADD KEY `date` (`date`);

--
-- Indizes für die Tabelle `sk_objects`
--
ALTER TABLE `sk_objects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `sk_objecttypes`
--
ALTER TABLE `sk_objecttypes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sk_sites`
--
ALTER TABLE `sk_sites`
  ADD PRIMARY KEY (`site_id`),
  ADD UNIQUE KEY `site_id` (`site_id`),
  ADD KEY `site_id_2` (`site_id`);

--
-- Indizes für die Tabelle `sk_users`
--
ALTER TABLE `sk_users`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `idxusersuname` (`uname`),
  ADD KEY `idxusersemail` (`email`),
  ADD KEY `idxusersuiduname` (`uid`,`uname`),
  ADD KEY `idxusersunamepass` (`uname`,`pass`);

--
-- Indizes für die Tabelle `sk_var_store`
--
ALTER TABLE `sk_var_store`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `sk_content`
--
ALTER TABLE `sk_content`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT für Tabelle `sk_groups`
--
ALTER TABLE `sk_groups`
  MODIFY `groupid` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `sk_groups_modules_link`
--
ALTER TABLE `sk_groups_modules_link`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT für Tabelle `sk_groups_nav_tree_link`
--
ALTER TABLE `sk_groups_nav_tree_link`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `sk_groups_sites_link`
--
ALTER TABLE `sk_groups_sites_link`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `sk_groups_users_link`
--
ALTER TABLE `sk_groups_users_link`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `sk_modules`
--
ALTER TABLE `sk_modules`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT für Tabelle `sk_nav_tree`
--
ALTER TABLE `sk_nav_tree`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `sk_news`
--
ALTER TABLE `sk_news`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `sk_newssections`
--
ALTER TABLE `sk_newssections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `sk_news_comments`
--
ALTER TABLE `sk_news_comments`
  MODIFY `comment_id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `sk_objects`
--
ALTER TABLE `sk_objects`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT für Tabelle `sk_objecttypes`
--
ALTER TABLE `sk_objecttypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `sk_sites`
--
ALTER TABLE `sk_sites`
  MODIFY `site_id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `sk_users`
--
ALTER TABLE `sk_users`
  MODIFY `uid` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `sk_var_store`
--
ALTER TABLE `sk_var_store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
