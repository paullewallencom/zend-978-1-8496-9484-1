-- Dumping database structure for book
CREATE DATABASE IF NOT EXISTS `book` 
USE `book`;


-- Dumping structure for table book.cards
CREATE TABLE IF NOT EXISTS `cards` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `color` enum('diamond','spade','heart','club') NOT NULL,
  `type` enum('number','picture') NOT NULL,
  `value` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table book.cards: ~48 rows (approximately)
INSERT INTO `cards` (`color`, `type`, `value`) VALUES
	('diamond', 'number', '1'),
	('diamond', 'number', '2'),
	('diamond', 'number', '3'),
	('diamond', 'number', '4'),
	('diamond', 'number', '5'),
	('diamond', 'number', '6'),
	('diamond', 'number', '7'),
	('diamond', 'number', '8'),
	('diamond', 'number', '9'),
	('diamond', 'picture', 'king'),
	('diamond', 'picture', 'jack'),
	('diamond', 'picture', 'queen'),
	('spade', 'number', '1'),
	('spade', 'number', '2'),
	('spade', 'number', '3'),
	('spade', 'number', '4'),
	('spade', 'number', '5'),
	('spade', 'number', '6'),
	('spade', 'number', '7'),
	('spade', 'number', '8'),
	('spade', 'number', '9'),
	('spade', 'picture', 'king'),
	('spade', 'picture', 'jack'),
	('spade', 'picture', 'queen'),
	('heart', 'number', '1'),
	('heart', 'number', '2'),
	('heart', 'number', '3'),
	('heart', 'number', '4'),
	('heart', 'number', '5'),
	('heart', 'number', '6'),
	('heart', 'number', '7'),
	('heart', 'number', '8'),
	('heart', 'number', '9'),
	('heart', 'picture', 'king'),
	('heart', 'picture', 'jack'),
	('heart', 'picture', 'queen'),
	('club', 'number', '1'),
	('club', 'number', '2'),
	('club', 'number', '3'),
	('club', 'number', '4'),
	('club', 'number', '5'),
	('club', 'number', '6'),
	('club', 'number', '7'),
	('club', 'number', '8'),
	('club', 'number', '9'),
	('club', 'picture', 'king'),
	('club', 'picture', 'jack'),
	('club', 'picture', 'queen');
