-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Client :  srv0.sknz.info
-- Généré le :  Jeu 29 Janvier 2015 à 22:55
-- Version du serveur :  10.0.14-MariaDB-log
-- Version de PHP :  5.6.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `afs`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `text` text,
  `image` varchar(1000) NOT NULL,
  `postdate` datetime DEFAULT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`id`, `user`, `title`, `text`, `image`, `postdate`, `category`) VALUES
(87, 159, 'MegaChat : la messagerie de Kim Dotcom qui doit évincer Skype', 'Fonctionnel en version bêta depuis jeudi, MegaChat est le nouveau service lancé par Kim Dotcom, l''homme qui créa le site de téléchargement Megaupload. Une messagerie instantanée ultra sécurisée qui doit vous permettre d''éviter d''être espionné par la NSA. Et le mégalo d''internet a même annoncé la fin de Skype.\r\nKim Dotcom ne fait rien dans la demi-mesure. L''homme qui créa Megaupload, le service de téléchargement de fichier, et fit fortune grâce à ça, revient sur le devant de la scène avec sa dernière invention. Jeudi, il a lancé la version bêta de MegaChat, son service de messagerie instantanée chiffrée promis en décembre dernier.\r\n\r\nMegaChat propose le chat vidéo via navigateur web afin d''éviter toute installation de logiciel sur votre ordinateur. Pour y accéder, il faudra néanmoins passer par le client web Mega, le service de stockage en ligne lancé par Kim Dotcom fin 2014 qui affiche 15 millions d''inscrits dans le monde. Cela se visualise par l''apparition d''un nouvel onglet sous Drive, Shared, Contacts existants. Il vous faudra créer un compte sur le service Mega pour en profiter.\r\n\r\nComme à son habitude, l''ex-nabab néozélandais du téléchargement a officialisé le lancement sur Twitter par un message retentissant rappelant ses réussites précédentes. Et avec la même modestie, il a d''ores et déjà prédit la mort prochaine de Skype grâce à son alternative sécurisée. Car selon lui, "on ne peut pas faire confiance" au service de Microsoft qui ne fait que "fournir des portes dérobées aux autorités américaines". Les documents publiés par Edward Snowden avaient démontré que les services secrets américains déchiffraient sans problème les communications réalisées par le biais des messageries.\r\n\r\nMais s''il promet que tout sera "entièrement chiffré", il n''explique cependant pas de quelle manière les conversations seront sécurisées. Sa seule annonce concerne ses serveurs qui ne seront pas hébergés aux Etats-Unis pour éviter l''espionnage de la NSA. Sûr de son fait, Kim Dotcom a néanmoins promis une récompense à celui qui parviendra à prendre son outil de communication en défaut.\r\n\r\nPour le moment, les appels vidéo et audio sont disponibles. Des fonctionnalités comme la visio-conférence et le chat écrit le seront progressivement. Kim Dotcom en a également profité pour modifier l''adresse de son site chiffré en mega.nz (au lieu de mega.co.nz), avec toujours la possibilité de l''ajouter à vos navigateurs (Firefox ou Chrome) et de bénéficier d''une offre de stockage gratuite de 50 Go. Avec l''arrivée de MegaChat, il espère atteindre rapidement les 100 millions d''utilisateurs rapidement.\r\n\r\n', 'http://www.gamersblog.fr/wp-content/uploads/2014/12/kim-dotcom.jpg', '2015-01-25 16:14:00', 1),
(88, 1, 'Période de Rush', 'Pour cause de RUSH, ce site sera visualisable uniquement depuis la République démocratique du Congo.\r\nC''EST LA CONGOLEXICOMATISATION DES LOIS DU MARCHÉ', '', '2015-01-22 15:02:25', 1),
(91, 159, '''Charlie Hebdo'' : la vengeance des Anonymous s''étend au Sénégal', 'Le conflit entre hacktivistes islamistes et les Anonymous prend une dimension internationale. Alors que le ministère de l''Intérieur a confirmé que plus de 25.000 sites Web français avaient été vandalisés par les premiers suite aux attaques des seconds, on apprend que les Anonymous ont décidé de s''en prendre à un site officiel sénégalais.\r\n\r\nEn cause, l''interdiction dans le pays du dernier numéro de Charlie Hebdo qui met en "une" une caricature du prophète Mohamed. Le groupe de hackers aurait donc piraté le site de l’Agence de l’informatique de l’Etat (ADIE) et afficher sur sa homepage : "Propriété d’ Anonymous. Eh bien vous avez interdit la caricature de la Une de Charlie Hebdo ? Mauvais choix". Le site a ensuite reconnu avoir "subi une série d’attaques informatiques". \r\n\r\nLes Anonymous expliquent que "la liberté d’expression a été meurtrie. Charlie Hebdo, une figure historique du journalisme satirique, a été pris pour cible par des lâches. Attaquer la liberté d’expression, c’est attaquer Anonymous. Nous le permettons pas(…) Nous vous traquons". \r\n\r\nRappelons que le dernier Charlie Hebdo a provoqué une vague de contestations plusieurs pays musulmans, donnant lieu à des manifestations parfois violentes, notamment contre les intérêts français.', 'http://www.zdnet.fr/i/edit/ne/2013/04/Anonymous-140x105.jpg', '2015-01-25 16:32:14', 1),
(106, 159, 'Une ancienne base de données gouvernementale dérobée par des hackeurs anti-« Charlie Hebdo » ', 'Le groupe AnonGhost, connu pour ses multiples attaques contre des sites français variés depuis le 9 janvier a publié une liste d''une dizaine d''employés des ministères des finances et de l''intérieur, avec nom, prénom, adresse mail et numéros de téléphones – pour la plupart obsolètes d''au moins quatre ans. Joint par Le Monde, l''Agence nationale de la sécurité des systèmes d''information (Anssi) confirme le vol d''une base de données qui daterait, selon leurs estimations, de 2011. Les hackeurs affirment qu''elle comporte au total plus de 10 000 entrées personnelles.\r\n\r\n\r\n« Toutes les informations de ceux qui travaillent pour le gouvernement français obtenues par AnonGhost », se congratule le fondateur du groupe dans un tweet. Contacté par Le Monde.fr, ce Mauritanien de 24 ans confie vouloir « faire quelque chose avec [ces adresses mail] » avant de les diffuser sur Internet. Les possibilités sont toutefois limitées : envoi massif de courriers indésirables, tentatives d''escroquerie ou, plus simplement, diffusion d''un communiqué qui pourrait être à la fois humoristique et menaçant, suggère l''intéressé.\r\n', 'http://www.anonsweden.se/wp-content/uploads/2013/05/700-Sites-Hacked-by-AnonGhost-for-operation-USA.png', '2015-01-25 23:01:31', 1);

-- --------------------------------------------------------

--
-- Structure de la table `article_category`
--

CREATE TABLE IF NOT EXISTS `article_category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `article_category`
--

INSERT INTO `article_category` (`id`, `name`) VALUES
(1, 'NEWS'),
(2, 'NEWS DU SITE');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `message` text,
  `postdate` datetime DEFAULT CURRENT_TIMESTAMP,
  `editdate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `comment`
--

INSERT INTO `comment` (`id`, `user`, `message`, `postdate`, `editdate`) VALUES
(1, 1, 'Woa un message', '2015-01-25 18:10:59', '2015-01-25 22:28:03'),
(2, 1, 'Ok pas trop mal', '2015-01-25 18:13:31', '2015-01-25 22:28:17'),
(3, 1, 'Un article très instructif, je "like"', '2015-01-25 18:29:18', '2015-01-25 18:29:18'),
(6, 1, 'Je marque des choses inutiles', '2015-01-25 18:57:24', '2015-01-25 22:32:18'),
(7, 1, 'First\n\nNope !', '2015-01-25 18:57:39', '2015-01-25 22:28:53'),
(8, 1, 'Thomas MUNOZ, major du PHP.', '2015-01-25 19:07:49', '2015-01-25 19:07:49'),
(11, 1, 'Trop SWAG j''adore', '2015-01-25 19:18:46', '2015-01-25 22:27:06'),
(13, 1, 'J''aime pas trop.', '2015-01-25 19:19:07', '2015-01-25 22:29:08'),
(15, 1, 'Je lis pas les articles je regarde juste les images. Donc Merci pour cet image.', '2015-01-25 19:21:13', '2015-01-25 22:30:01'),
(17, 1, 'Wut', '2015-01-25 19:22:42', '2015-01-25 22:30:28'),
(19, 1, 'Sa passe', '2015-01-25 19:23:08', '2015-01-25 22:30:36'),
(20, 1, 'Bien', '2015-01-25 19:23:08', '2015-01-25 22:32:27'),
(21, 1, 'J''aime moyen', '2015-01-25 19:23:26', '2015-01-25 22:32:40'),
(22, 1, 'Super +2', '2015-01-25 19:23:26', '2015-01-25 22:34:15'),
(23, 1, 'I hate -2', '2015-01-25 19:46:34', '2015-01-25 22:34:28'),
(24, 1, 'SUP ?', '2015-01-25 19:46:34', '2015-01-25 22:35:08'),
(25, 1, 'Nous en parlons souvent entre nous : les hackers prennent le pouvoir sur internet, heureusement il est impossible en France de monter un business plan sur les enfants du baby-boom (et le réchauffement climatique ne va rien améliorer) , si tant est que les Provinciaux n''ont aucune expérience du pouvoir . C''est incohérent, difficile de faire mieux que les provinciaux qui sont les seuls à se réunir et échanger des idées constructives … Et sinon, vous, ça va ? ', '2015-01-25 20:29:22', '2015-01-25 22:35:15'),
(26, 1, 'Le sujet n''est pas politique, sauf que le gaz tellement bon marché qu''il est cadeau, et dans le monde, les inégalités sont frappantes à tous les niveaux, surtout pour   le principe de Peter (en prenant exemple sur le marché Allemand) , les politiques essaient de nous faire croire que les riches sont des cons, c''est tout . Après tout tes propos n''engagent que toi, surtout en regardant de près  les politiques qui veulent seulement voir l''avenir avec une boule de cristal … Avec des propos pareils, tu joues le jeu des politiques. ', '2015-01-25 20:29:22', '2015-01-25 22:35:37'),
(27, 1, 'Mais bien sûr, aucun problème… (ironie) j''espère que tu ne répètes pas sans comprendre… Et en France, il est impossible de se focaliser uniquement sur Big Brother (même si on essaie tous d''y remédier) , et, de ce fait,  les Provinciaux ne sont plus efficaces au travail . C''est juste un effet d''annonce, c''est simplement une histoire de goût, tu n''aimes pas Luc Besson qui ne fait que voir l''avenir avec une boule de cristal . A quand la révolution ? ', '2015-01-25 20:31:01', '2015-01-25 22:35:48'),
(28, 1, 'Mais pas du tout, j''espère que tu ne répètes pas sans comprendre… Et comme d''habitude c''est à cause de la perte totale de confiance des gens envers les vidéos de chat sur Youtube (tu vois de quoi je parle, quand même ?) , je ne vais pas t''apprendre que les gardiens d''immeuble ne peuvent pas se défendre seuls ! Pour moi c''est important, je ne comprends pas les imbéciles qui voient midi à leur porte quand il faut jeter l''opprobe sur les community managers … Salut, bande de réacs. ', '2015-01-25 20:31:02', '2015-01-25 22:35:55'),
(29, 1, 'Nous en parlons souvent entre nous : sans aucune animosité, permets-moi de préciser que tu es simplement très mal placé pour aborder ce sujet, avec au centre de la polémique, bien sûr, les enfants du baby-boom (sans pour autant les déniger) , et tu sais bien que tu nies la réalité : les anarchistes ne peuvent pas se défendre seuls ! Tu n''embobineras personne avec ces propos malveillants, tu dois être finalement assez d''accord avec Luc Besson qui ne fait que jeter bébé avec l''eau du bain ! Tu réfléchiras peut-être plus loin que le bout de ton nez, la prochaine fois. ', '2015-01-25 20:33:44', '2015-01-25 22:36:12'),
(30, 1, 'Tiens tiens comme par hasard les hackers prennent le pouvoir sur internet, heureusement dans le monde, les inégalités sont frappantes à tous les niveaux, surtout pour   les récentes études du M.I.T.  (et sans y mêler mes vues politiques) , tu te fous de tout et les lecteurs de ce post sont rincés ! Tu n''embobineras personne avec ces propos malveillants, tu n''as aucune info concrète sur les gens qui n''ont pas les moyens de jeter un pavé dans la mare ! Avec des propos pareils, tu joues le jeu des politiques. ', '2015-01-25 20:33:45', '2015-01-25 22:36:23'),
(31, 1, 'C''est complètement faux, j''espère que tu ne répètes pas sans comprendre… Et au jour d''aujourd''hui il est impossible d''être d''accord avec les réseaux d''influence (surtout pour les pros du secteur) , je ne vais pas t''apprendre que les bons pères de famille ne sont pas ceux qui consomment le plus ! Ca n''a rien à voir avec NKM, il faut réfléchir 5 minutes pour comprendre les plombiers véreux qui s''entêtent à superviser ces conneries … Voilà ce qui arrive lorque l''on mord la main qui vous nourrit. ', '2015-01-25 22:01:29', '2015-01-25 22:36:29'),
(32, 1, 'Peut-être, mais tu n''as aucune idée de ce que tu avances :D avec le fric que Sarkozy paie pour  les gens qui bossent à mi-temps (vu leur non-pertinence évidente aux yeux de tous…) , j''espère que tu comprends bien que les banlieusards sont quand même plus heureux au soleil . Contacte-moi si tu as besoin de précisions sur le sujet, c''est avant tout pour démolir les gens qui pourraient  jeter l''opprobe sur les community managers . A quand la révolution ? ', '2015-01-25 22:01:29', '2015-01-25 22:36:44'),
(33, 1, 'Normal ; Nicolas Hulot œuvre pour la planète avec son gel douche… Alors que avec le fric que Sarkozy paie pour  les joueurs de foot (c''est foutu d''avance) , c''est bien pour ça que les personnes seules sont fatigués par ce discours d''un autre âge . Les plus médiatiques seront favorisés comme d''habitude, juste par respect pour les gens qui pourraient  dénoncer les inégalités ! Bref, je te laisse méditer là-dessus. ', '2015-01-25 22:07:49', '2015-01-25 22:36:54'),
(34, 1, 'Le sujet n''est pas politique, sauf que quand tu penses que Gérard Houllier a été hospitalisé après un malaise… Ca me dégoûte, tu te fourvoies du début à la fin de ta démonstration, cf. les élites (à moins d''une bonne révolution) , vu notre propension bien euro-centriste :  les Parisiens sont peut-être les seuls réellement concernés . Il est impossible de considérer que tu as raison, il faut apprendre à supporter le rédac chef de Libé qui peut ouvertement jeter l''opprobe sur les community managers … LOL Hypocrisie, quand tu nous tiens. ', '2015-01-25 22:07:49', '2015-01-25 22:37:17'),
(35, 1, 'Bonjour.\r\n\r\nCe commentaire ne sert à rien. J''aime beaucoup.', '2015-01-25 23:03:41', '2015-01-25 23:03:41'),
(36, 1, 'Trop SWAG', '2015-01-26 00:00:41', '2015-01-26 00:00:41'),
(37, 1, 'Trop SWAG', '2015-01-26 00:00:42', '2015-01-26 00:00:42'),
(38, 1, 'Trop SWAG', '2015-01-26 00:00:43', '2015-01-26 00:00:43'),
(39, 1, 'Trop SWAG', '2015-01-26 00:00:43', '2015-01-26 00:00:43'),
(40, 1, 'Trop SWAG', '2015-01-26 00:02:39', '2015-01-26 00:02:39'),
(41, 1, 'Trop SWAG', '2015-01-26 00:02:40', '2015-01-26 00:02:40'),
(42, 1, 'Une soirée qui m''a l''air bien sympathique, j''espère que nous serons nombreux !', '2015-01-26 18:54:37', '2015-01-26 18:54:37'),
(43, 1, 'SEUL DIEU PEUT ME JUGER', '2015-01-26 18:56:45', '2015-01-26 18:56:45'),
(44, 1, 'Kamoulox', '2015-01-27 02:17:18', '2015-01-27 02:17:18'),
(45, 203, 'Trop bien !', '2015-01-27 12:45:56', '2015-01-27 12:45:56');

-- --------------------------------------------------------

--
-- Structure de la table `comment_article`
--

CREATE TABLE IF NOT EXISTS `comment_article` (
  `id` int(11) NOT NULL,
  `article` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `comment_article`
--

INSERT INTO `comment_article` (`id`, `article`) VALUES
(1, 91),
(2, 91),
(3, 87),
(20, 87),
(22, 87),
(24, 87),
(26, 87),
(28, 87),
(30, 87),
(32, 87),
(34, 87),
(35, 106),
(37, 87),
(39, 87),
(41, 87);

-- --------------------------------------------------------

--
-- Structure de la table `comment_event`
--

CREATE TABLE IF NOT EXISTS `comment_event` (
  `id` int(11) NOT NULL,
  `event` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `comment_event`
--

INSERT INTO `comment_event` (`id`, `event`) VALUES
(6, 28),
(7, 28),
(8, 28),
(11, 1),
(13, 1),
(15, 1),
(17, 1),
(19, 1),
(21, 1),
(23, 1),
(25, 1),
(27, 1),
(29, 1),
(31, 1),
(33, 1),
(36, 1),
(38, 1),
(40, 1),
(42, 287),
(43, 1),
(44, 256),
(45, 256);

-- --------------------------------------------------------

--
-- Structure de la table `conversation`
--

CREATE TABLE IF NOT EXISTS `conversation` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
  `lastmessagetime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `lastmessagesnippet` varchar(80) NOT NULL,
  `lastmessageauthor` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `conversation`
--

INSERT INTO `conversation` (`id`, `user`, `title`, `createtime`, `lastmessagetime`, `lastmessagesnippet`, `lastmessageauthor`) VALUES
(81, 1, 'Salut c''est cool', '2015-01-25 02:20:54', '2015-01-25 13:03:53', 'SWAGYOLO', 81),
(82, 1, 'COUCOU', '2015-01-25 03:01:53', '2015-01-25 03:01:54', 'Tu veux voir mon PHP ?', 1),
(83, 1, 'Salut B2O', '2015-01-25 03:25:49', '2015-01-25 03:25:49', 'HELLO B2O !', 1),
(84, -1, 'Coucou tu vas bien ?', '2015-01-25 10:49:12', '2015-01-25 23:22:56', 'blablalol', 81),
(85, 40, 'Ok sa passe', '2015-01-25 12:01:23', '2015-01-25 22:26:17', 'aze', 40),
(86, 160, 'SEVRAN', '2015-01-25 16:06:26', '2015-01-27 02:14:22', 'Salut ca va ? ', 1),
(87, 203, 'dsdsd', '2015-01-27 12:37:35', '2015-01-27 12:51:09', 'Ce n''est pas très mature, Mickaël.', 1),
(88, 203, 'sdqsdfd', '2015-01-27 12:40:54', '2015-01-27 12:40:54', 'LOL', 203);

-- --------------------------------------------------------

--
-- Structure de la table `conversation_user`
--

CREATE TABLE IF NOT EXISTS `conversation_user` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `joindate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastread` datetime NOT NULL,
  `messagecount` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `conversation_user`
--

INSERT INTO `conversation_user` (`id`, `user`, `joindate`, `lastread`, `messagecount`) VALUES
(81, -1, '2015-01-25 03:02:03', '2015-01-25 13:03:56', 1),
(81, 40, '2015-01-25 02:20:54', '2015-01-27 22:13:04', 2),
(81, 42, '2015-01-25 02:20:55', '2015-01-25 18:58:27', 0),
(81, 58, '2015-01-25 02:20:55', '2015-01-25 03:26:03', 0),
(82, 40, '2015-01-25 03:01:54', '2015-01-25 03:02:07', 0),
(83, 1, '2015-01-25 03:25:49', '2015-01-26 18:58:11', 1),
(83, 58, '2015-01-25 03:25:49', '2015-01-25 03:26:06', 0),
(84, -1, '2015-01-25 10:49:12', '2015-01-25 10:49:13', 1),
(85, 1, '2015-01-25 12:01:23', '2015-01-26 00:08:52', 0),
(85, 40, '2015-01-25 12:01:23', '2015-01-27 22:12:49', 1),
(86, 1, '2015-01-25 16:06:26', '2015-01-27 02:14:25', 1),
(86, 160, '2015-01-25 16:06:26', '2015-01-25 16:06:27', 1),
(87, -1, '2015-01-27 12:37:48', '0000-00-00 00:00:00', 0),
(87, 1, '2015-01-27 12:37:48', '2015-01-27 22:20:01', 1),
(87, 40, '2015-01-27 12:37:35', '2015-01-27 22:12:41', 0),
(87, 203, '2015-01-27 12:37:35', '2015-01-27 12:39:43', 3),
(88, -1, '2015-01-27 12:40:54', '0000-00-00 00:00:00', 0),
(88, 201, '2015-01-27 12:40:54', '0000-00-00 00:00:00', 0),
(88, 203, '2015-01-27 12:40:54', '2015-01-27 12:40:55', 1);

-- --------------------------------------------------------

--
-- Structure de la table `cpt_connectes`
--

CREATE TABLE IF NOT EXISTS `cpt_connectes` (
  `ip` varchar(255) DEFAULT NULL,
  `timestamp` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cpt_connectes`
--

INSERT INTO `cpt_connectes` (`ip`, `timestamp`) VALUES
('81.65.159.141', '1422475748'),
('10.10.10.10', '1422466675'),
('10.10.10.10', '1422466675'),
('10.10.10.10', '1422466675'),
('10.10.10.10', '1422466675'),
('10.10.10.1', '1422484023'),
('::1', '1422478709'),
('78.214.178.166', '1422442058'),
('85.171.12.145', '1422398211'),
('92.153.213.70', '1422396439'),
('86.210.90.115', '1422284015'),
('88.177.127.65', '1422359412'),
('88.176.191.241', '1422493886'),
('37.160.78.220', '1422357666'),
('66.249.65.143', '1422373332'),
('66.249.65.139', '1422373205'),
('66.249.65.135', '1422373261'),
('173.208.58.138', '1422377633'),
('192.168.1.102', '1422383581'),
('66.249.64.190', '1422513126'),
('66.249.64.182', '1422513100'),
('66.249.64.186', '1422513074'),
('66.249.67.130', '1422544957'),
('66.249.67.122', '1422544958');

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `user` int(11) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `eventtime` datetime NOT NULL,
  `money` int(11) NOT NULL,
  `personsmax` int(10) unsigned NOT NULL,
  `personsnow` int(11) DEFAULT NULL,
  `image` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=289 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `event`
--

INSERT INTO `event` (`id`, `name`, `user`, `description`, `address`, `createtime`, `eventtime`, `money`, `personsmax`, `personsnow`, `image`) VALUES
(1, 'Évènement #SWAG', 1, 'Un petit event', 'SWAG address nope', '2015-01-23 21:51:28', '2015-01-25 23:16:24', 0, 10, 8, 'http://img1.wikia.nocookie.net/__cb20131120010139/survivor-org/images/6/65/TROLOLOL.jpg'),
(27, 'Mon super event', 1, 'SWAG', 'SWAG', '2015-01-24 14:29:18', '2016-05-13 14:56:30', 4, 4, 3, ''),
(28, 'Trop bien', 1, 'SWAG', 'SWAG', '2015-01-24 14:30:40', '2015-01-22 21:20:30', 4, 4, 4, ''),
(254, 'LAN Party', 159, 'Bonjour,\r\n\r\nj''ai décidé d''organiser une petite soirée, il y aura des jeux et des bières. Vous pouvez amener vos manettes, pc, clavier et souris. Amenez aussi des switch pour avoir internet. ', '48 rue des adresses étroites 13002 Marseille', '2015-01-25 15:17:08', '2015-01-28 15:17:08', 10, 42, 42, ''),
(255, 'Marathon star trek', 159, 'Salut, j''aimerais organiser un marathon sur l''univers star trek. Je planifie de voir toutes les saisons de star trek the next generation (7 saisons), et si possible finir avec la première saison de la série originale. Si c''est pour venir troller les trekies vous pouvez vous abstenir, les fans de star wars ne sont pas les biens venus.  ', '158 rue du colonel moutarde, Aix-en-Provence', '2015-01-25 15:25:14', '2015-02-11 23:34:23', 0, 8, 8, ''),
(256, 'Soirée scrabble', 159, 'Salut à tous, je suis vraiment tout excité en ce moment, la folie atteint son paroxysme : je souhaite organiser une soirée scrabble. Une grande soirée s''annonce. Alors amenez vos dictionnaires et moi je m''occupe de faire de délicieux  Apfelstrudel.', '168 rue du colonel en civil, Éguilles.', '2015-01-25 16:38:31', '2015-02-22 16:38:31', 12, 13, 11, ''),
(281, 'Révision test C++', 1, 'Organise une petite séance de révision de C++ pour le test très facile à venir.', 'IUT', '2015-01-25 20:44:23', '2015-01-26 14:49:16', 0, 12, 12, 'http://image.noelshack.com/fichiers/2014/10/1394321423-laportelol.gif'),
(282, 'LAN Multi-console', 1, 'Bonjour,\r\nnous organisions une petite LAN ouverte à tous la seule condition et de sa console.\r\nLe règlement et l''organisation seront donnés  sur place.', 'Chez quelqu''un', '2015-01-25 20:55:49', '2015-01-31 20:55:49', 0, 20, 20, ''),
(283, 'Brainstorming', 159, 'Bonjour,\r\n\r\nje suis en ce moment en train de créer ma boite, et j''aimerais organiser une petite soirée afin de rencontrer de nouvelles personnes. J''aimerais aussi en profiter pour partager mes nouvelles idées et tenter de faire connaître ma société. ', '142 rue descaartes 13009 Marseille', '2015-01-25 22:40:57', '2015-03-07 22:40:57', 4, 40, 40, 'http://www.quizzle.com/blog/wp-content/uploads/2010/10/group-of-friends-at-bar.jpg'),
(284, 'Exposition artistique', 159, 'Salut à tous les afkiens,\r\n\r\nje suis un artiste et je produis de nombreuses toiles et autres oeuvres. Je me disais que ce serais bien de partager ma passion avec des membres du site. Vous pourrez voir quelques oeuvres que j''ai imaginés et vous pourrez aussi participer à la création de nouvelles oeuvres. ', '3 rue des blaireaux', '2015-01-25 22:44:30', '2015-02-25 22:44:30', 6, 23, 23, 'http://www.scottchristensen.com.au/Images/artist-profile-picture.jpg'),
(285, 'Cuisine entre amisaze', 159, 'Coucou,\r\n\r\nje suis fan de cuisine et j''aimerais partager un petit moments avec vous pour concocter quelque chose tous ensemble.\r\nOn décidera sur place ce que l''on fera. Amenez bonne humeur et huile de coude.', '33 impasse de l''amiral Leclerc ', '2015-01-25 22:48:39', '2015-02-07 23:21:12', 12, 6, 6, 'http://cdn-premiere.ladmedia.fr/var/premiere/storage/images/tele/news-tele/maite-revient-a-la-television-des-le-6-mai-3747952/67860016-1-fre-FR/Maite-revient-a-la-television-des-le-6-mai_portrait_w532.jpg'),
(286, 'Cartes sur tables', 159, 'Salut,\r\n\r\nje possède un paquet de cartes à jouer de type magic et je cherche des gens avec qui jouer parsec j''ai pas beaucoup d''amis. Par contre il faudra amener votre deck, surtout si nous sommes nombreux.', '76 avenue mon bon ami 13001', '2015-01-25 22:52:53', '2015-01-31 22:52:53', 2, 15, 14, 'http://t1.gstatic.com/images?q=tbn:ANd9GcQLNCJ5iA30-4sO3cVB8O9GquxSoI6TFaz4ReAhqb0YUz_nPuS9_JO5D1-sJg'),
(287, 'Jeu de rôles donjon et dragon', 159, 'Hola,\r\n\r\nça fait un moment que je voulais organiser ça, une partie de donjon et dragon à l''ancienne. Je serai le maître de jeu, et j''attends des guerriers et des mages qui n''on peur de rien. Je m''occupe des breuvages et du rationnement.', '88 rue Paul Langevin 13013 Marseille', '2015-01-25 22:56:19', '2015-02-14 23:23:17', 6, 18, 16, 'http://www.nerdpix.com/wp-content/uploads/2013/02/Zero-Charisma.jpg'),
(288, 'dds', 203, 'ds', 'ds', '2015-01-27 12:24:16', '2015-01-22 12:24:54', 0, 0, 0, 'http://www.nerdpix.com/wp-content/uploads/2013/02/Zero-Charisma.jpg');

--
-- Déclencheurs `event`
--
DELIMITER $$
CREATE TRIGGER `NEWEVENT` BEFORE INSERT ON `event`
 FOR EACH ROW SET NEW.personsnow := NEW.personsmax
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `event_user`
--

CREATE TABLE IF NOT EXISTS `event_user` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `joindate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `event_user`
--

INSERT INTO `event_user` (`id`, `user`, `joindate`) VALUES
(1, 1, '2015-01-25 20:43:58'),
(1, 42, '2015-01-25 19:20:53'),
(27, 1, '2015-01-25 18:52:15'),
(256, 1, '2015-01-25 23:04:36'),
(256, 42, '2015-01-25 18:53:35'),
(286, 203, '2015-01-27 12:43:16'),
(287, 1, '2015-01-25 23:34:30'),
(287, 203, '2015-01-27 12:24:41');

--
-- Déclencheurs `event_user`
--
DELIMITER $$
CREATE TRIGGER `ONPARTICIPATE` AFTER INSERT ON `event_user`
 FOR EACH ROW UPDATE event SET event.personsnow = event.personsnow - 1 WHERE event.id = NEW.id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ONREMOVEPARTICIPATE` AFTER DELETE ON `event_user`
 FOR EACH ROW UPDATE event SET event.personsnow = event.personsnow + 1 WHERE event.id = OLD.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(45) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthday` datetime NOT NULL,
  `registerdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastconnectiontime` datetime DEFAULT NULL,
  `mail` varchar(100) NOT NULL,
  `phonenumber` varchar(13) DEFAULT NULL,
  `twitter` varchar(30) DEFAULT NULL,
  `skype` varchar(45) DEFAULT NULL,
  `facebookuri` varchar(500) DEFAULT NULL,
  `website` varchar(500) DEFAULT NULL,
  `job` varchar(75) DEFAULT NULL,
  `description` text,
  `privacy` int(15) NOT NULL,
  `mailnotifications` tinyint(1) NOT NULL,
  `accesslevel` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `firstname`, `lastname`, `birthday`, `registerdate`, `lastconnectiontime`, `mail`, `phonenumber`, `twitter`, `skype`, `facebookuri`, `website`, `job`, `description`, `privacy`, `mailnotifications`, `accesslevel`) VALUES
(-1, 'unregistered', 'bcd3fa7f817e6ca232898f0109b11ad4fdabd406', 'Utilisateur', 'supprimé', '2015-01-22 00:00:00', '2015-01-25 22:16:40', NULL, 'noreply@dev.null', NULL, NULL, NULL, NULL, NULL, 'System internals', 'Utilisateur système (NE PAS SUPPRIMER)', 32640, 0, 0),
(1, 'thomas', 'bcd3fa7f817e6ca232898f0109b11ad4fdabd406', 'Thomas', 'Munoz', '1994-11-09 15:45:00', '2015-01-16 16:48:14', NULL, 'thomas.munoz30@gmail.com', '0698369472', 'thomasmunoz', 'thomas30450', 'thomas.munoz13', 'http://thomasmunoz.fr', 'Étudiant', 'Amateur de nouvelles technologies, je suis étudiant à l''IUT d''Aix-en-Provence où j''apprends la programmation de manière qualitative.', 32659, 1, 1),
(40, 'sknz', 'bcd3fa7f817e6ca232898f0109b11ad4fdabd406', 'SWAGGENS', 'YOLOTHENS', '1995-04-07 00:00:00', '2015-01-24 12:15:26', NULL, 'florandara@gmail.com', '0707070707', 'jeandupont', '', '', '', 'Izi money', 'G SUI BO ...', 32651, 1, 1),
(42, 'loick', 'bcd3fa7f817e6ca232898f0109b11ad4fdabd406', 'Loïck', 'MAHIEUX', '1995-04-09 00:00:00', '2015-01-24 13:23:45', NULL, 'loick111@gmail.com', '+33650197827', 'loick111', 'loick_111', 'mahieux.loick', 'http://me.loick.fr', 'Étudiant', 'MAHYOLO ... IZY', 32734, 0, 1),
(58, 'booba', 'bcd3fa7f817e6ca232898f0109b11ad4fdabd406', 'Eli', 'Yaffa', '1976-11-09 00:00:00', '2015-01-24 15:33:20', NULL, 'booba@lerapcetaitmieuxavant.fr', '0707070707', 'b2oba', '', '', '', 'Rappeur', 'Tu veux t''asseoir sur le trône ? Faudra t''asseoir sur mes genoux.', 32752, 0, 0),
(159, 'anthony', '738d988ea212b1b81b466541a501801805d52cc6', 'anthony', 'loroscio', '1995-02-17 00:00:00', '2015-01-25 15:02:51', NULL, 'anthonyloroscio@gmail.com', NULL, NULL, NULL, NULL, NULL, 'fv', 'sdv', 0, 0, 0),
(160, 'KAARIS', '27759563f847ff5cfcc8396495495da104ef55cf', 'Gnakouri', 'Okou', '1980-01-30 00:00:00', '2015-01-25 16:02:42', NULL, 'kaaris@rap.fr', '2727272727', 'kaaris', 'Kaaris', NULL, 'http://kaaris.fr', 'Rappeur', 'C''est pas tout que tu sois mort j''veux gagner par strangulation.', 1, 1, 0),
(193, 'azerty', 'bcd3fa7f817e6ca232898f0109b11ad4fdabd406', 'Azerty', 'NARENJI', '2015-01-17 00:00:00', '2015-01-25 23:53:17', NULL, 'florandara@gmail.com', '', '', '', '', '', 'Swag', 'Salut', 0, 0, 0),
(195, 'lol', 'bcd3fa7f817e6ca232898f0109b11ad4fdabd406', 'Lol', 'OMG', '1900-12-04 00:00:00', '2015-01-26 00:00:45', NULL, 'op@swag.fr', '000000', 'Nope', 'nope', 'OSEF', '??', 'Pole emploi', '???', 32763, 1, 1),
(201, 'floran', '4f2dfea73a7c2959703c68cd90da392ffa5dc216', 'Floran', 'NARENJI', '2015-01-23 00:00:00', '2015-01-27 11:55:58', NULL, 'florandara@gmail.com', '0660054500', '', '', '', '', 'Trader', 'Izi money', 6, 0, 0),
(203, 'a', '4187b2ceca24bb044e53bba37c06915bef0fa3e1', 'A', 'A', '2015-01-16 00:00:00', '2015-01-27 12:22:11', NULL, 'mmartin.nevot@gmail.com', '', '', '', '', '', 'dssdsd', 'dssdsd', 2, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user_friend`
--

CREATE TABLE IF NOT EXISTS `user_friend` (
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `valid` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user_friend`
--

INSERT INTO `user_friend` (`user1`, `user2`, `valid`) VALUES
(1, 40, 1),
(42, 40, 0),
(203, 193, 193),
(203, 201, 201);

-- --------------------------------------------------------

--
-- Structure de la table `user_reset`
--

CREATE TABLE IF NOT EXISTS `user_reset` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user_reset`
--

INSERT INTO `user_reset` (`id`, `token`) VALUES
(1, '767848e157ffaefd60ec1a14d0fa29b8'),
(42, '5f94e3744f935d03ccf429e7be81b258');

-- --------------------------------------------------------

--
-- Structure de la table `user_validation`
--

CREATE TABLE IF NOT EXISTS `user_validation` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user_validation`
--

INSERT INTO `user_validation` (`id`, `token`) VALUES
(195, 'e40a620ebe5e896192adc00268258fe48757e3e7');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`), ADD KEY `fk_article_1_idx` (`user`), ADD KEY `fk_article_2_idx` (`category`), ADD FULLTEXT KEY `text` (`text`), ADD FULLTEXT KEY `title` (`title`), ADD FULLTEXT KEY `text_2` (`text`), ADD FULLTEXT KEY `search` (`title`,`text`);

--
-- Index pour la table `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name_UNIQUE` (`name`), ADD FULLTEXT KEY `search` (`name`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`), ADD KEY `fk_comment_1_idx` (`user`);

--
-- Index pour la table `comment_article`
--
ALTER TABLE `comment_article`
  ADD PRIMARY KEY (`id`,`article`), ADD UNIQUE KEY `id_UNIQUE` (`id`), ADD KEY `fk_comment_article_1_idx` (`article`);

--
-- Index pour la table `comment_event`
--
ALTER TABLE `comment_event`
  ADD PRIMARY KEY (`id`,`event`), ADD UNIQUE KEY `id_UNIQUE` (`id`), ADD KEY `fk_comment_event_2_idx` (`event`);

--
-- Index pour la table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`), ADD KEY `fk_this_shit` (`user`);

--
-- Index pour la table `conversation_user`
--
ALTER TABLE `conversation_user`
  ADD PRIMARY KEY (`id`,`user`), ADD KEY `fk_conversation_1_idx` (`user`);

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`), ADD KEY `fk_event_1_idx` (`user`), ADD FULLTEXT KEY `search` (`name`,`description`,`address`);

--
-- Index pour la table `event_user`
--
ALTER TABLE `event_user`
  ADD PRIMARY KEY (`id`,`user`), ADD KEY `fk_event_user_2_idx` (`user`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username_UNIQUE` (`username`), ADD FULLTEXT KEY `search` (`username`,`firstname`,`lastname`,`description`), ADD FULLTEXT KEY `search1` (`username`,`firstname`,`lastname`);

--
-- Index pour la table `user_friend`
--
ALTER TABLE `user_friend`
  ADD PRIMARY KEY (`user1`,`user2`);

--
-- Index pour la table `user_reset`
--
ALTER TABLE `user_reset`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_validation`
--
ALTER TABLE `user_validation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT pour la table `article_category`
--
ALTER TABLE `article_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT pour la table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=289;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=204;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
ADD CONSTRAINT `fk_article_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_article_2` FOREIGN KEY (`category`) REFERENCES `article_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
ADD CONSTRAINT `fk_comment_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `comment_article`
--
ALTER TABLE `comment_article`
ADD CONSTRAINT `fk_comment_article_1` FOREIGN KEY (`article`) REFERENCES `article` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_comment_article_2` FOREIGN KEY (`id`) REFERENCES `comment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `comment_event`
--
ALTER TABLE `comment_event`
ADD CONSTRAINT `fk_comment_event_1` FOREIGN KEY (`id`) REFERENCES `comment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_comment_event_2` FOREIGN KEY (`event`) REFERENCES `event` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `conversation`
--
ALTER TABLE `conversation`
ADD CONSTRAINT `fk_this_shit` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `conversation_user`
--
ALTER TABLE `conversation_user`
ADD CONSTRAINT `fk_conversation_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_conversation_2` FOREIGN KEY (`id`) REFERENCES `conversation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
ADD CONSTRAINT `fk_event_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `event_user`
--
ALTER TABLE `event_user`
ADD CONSTRAINT `fk_event_user_1` FOREIGN KEY (`id`) REFERENCES `event` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_event_user_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `user_reset`
--
ALTER TABLE `user_reset`
ADD CONSTRAINT `user_reset_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_validation`
--
ALTER TABLE `user_validation`
ADD CONSTRAINT `fk_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
