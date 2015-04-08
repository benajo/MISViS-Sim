-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Apr 08, 2015 at 11:39 AM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `misvis_clean`
--

-- --------------------------------------------------------

--
-- Table structure for table `Clefs`
--

CREATE TABLE `Clefs` (
`Clef_ID` int(11) NOT NULL,
  `VexTabNotation` varchar(10) NOT NULL,
  `Name` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Clefs`
--

INSERT INTO `Clefs` (`Clef_ID`, `VexTabNotation`, `Name`) VALUES
(1, 'treble', 'Treble'),
(2, 'bass', 'Bass'),
(3, 'alto', 'Alto'),
(4, 'tenor', 'Tenor'),
(5, 'percussion', 'Percussion');

-- --------------------------------------------------------

--
-- Table structure for table `Durations`
--

CREATE TABLE `Durations` (
`Duration_ID` int(11) NOT NULL,
  `VexTabNotation` varchar(10) NOT NULL,
  `HTML` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Durations`
--

INSERT INTO `Durations` (`Duration_ID`, `VexTabNotation`, `HTML`, `Name`) VALUES
(1, ':w', 'Whole note', 'Whole note'),
(2, ':h', 'Half note', 'Half note'),
(3, ':q', 'Quarter note', 'Quarter note'),
(4, ':8', 'Eighth note', 'Eighth note'),
(5, ':16', 'Sixteenth note', 'Sixteenth note'),
(6, ':32', 'Thirty-second note', 'Thirty-second note');

-- --------------------------------------------------------

--
-- Table structure for table `Keys`
--

CREATE TABLE `Keys` (
`Key_ID` int(11) NOT NULL,
  `VexTabNotation` varchar(10) NOT NULL,
  `HTML` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Keys`
--

INSERT INTO `Keys` (`Key_ID`, `VexTabNotation`, `HTML`, `Name`) VALUES
(1, 'C', 'C', 'C'),
(2, 'Am', 'Am', 'A minor'),
(3, 'F', 'F', 'F'),
(4, 'Dm', 'Dm', 'D minor'),
(5, 'Bb', 'Bb', 'B flat'),
(6, 'Gm', 'Gm', 'G minor'),
(7, 'Eb', 'Eb', 'E flat'),
(8, 'Cm', 'Cm', 'C minor'),
(9, 'Ab', 'Ab', 'A flat'),
(10, 'Fm', 'Fm', 'F minor'),
(11, 'Db', 'Db', 'D flat'),
(12, 'Bbm', 'Bbm', 'B flat minor'),
(13, 'Gb', 'Gb', 'G flat'),
(14, 'Ebm', 'Ebm', 'E flat minor'),
(15, 'Cb', 'Cb', 'C flat'),
(16, 'Abm', 'Abm', 'A flat minor'),
(17, 'G', 'G', 'G'),
(18, 'Em', 'Em', 'E minor'),
(19, 'D', 'D', 'D'),
(20, 'Bm', 'Bm', 'B minor'),
(21, 'A', 'A', 'A'),
(22, 'F#m', 'F#m', 'F sharp minor'),
(23, 'E', 'E', 'E'),
(24, 'C#m', 'C#m', 'C sharp minor'),
(25, 'B', 'B', 'B'),
(26, 'G#m', 'G#m', 'G sharp minor'),
(27, 'F#', 'F#', 'F sharp'),
(28, 'D#m', 'D#m', 'D sharp minor'),
(29, 'C#', 'C#', 'G sharp'),
(30, 'A#m', 'A#m', 'A sharp minor');

-- --------------------------------------------------------

--
-- Table structure for table `Notes`
--

CREATE TABLE `Notes` (
`Note_ID` int(11) NOT NULL,
  `VexTabNotation` varchar(10) NOT NULL,
  `HTML` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `NonNote` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Notes`
--

INSERT INTO `Notes` (`Note_ID`, `VexTabNotation`, `HTML`, `Name`, `NonNote`) VALUES
(1, 'C', 'C', 'C', 0),
(2, 'C#', 'C&#9839;', 'C sharp', 0),
(3, 'D@', 'D&#9837;', 'D flat', 0),
(4, 'D', 'D', 'D', 0),
(5, 'D#', 'D&#9839;', 'D sharp', 0),
(6, 'E@', 'E&#9837;', 'E flat', 0),
(7, 'E', 'E', 'E', 0),
(8, 'F', 'F', 'F', 0),
(9, 'F#', 'F&#9839;', 'F sharp', 0),
(10, 'G@', 'G&#9837;', 'G flat', 0),
(11, 'G', 'G', 'G', 0),
(12, 'G#', 'G&#9839;', 'G sharp', 0),
(13, 'A@', 'A&#9837;', 'A flat', 0),
(14, 'A', 'A', 'A', 0),
(15, 'A#', 'A&#9839;', 'A sharp', 0),
(16, 'B@', 'B&#9837;', 'B flat', 0),
(17, 'B', 'B', 'B', 0),
(18, '|', '|', 'Bar-line', 1),
(19, '##', 'Rest', 'Rest', 1),
(20, '=||', 'Double bar', 'Double bar', 1),
(21, '=|:', 'Repeat begin', 'Repeat begin', 1),
(22, '=:|', 'Repeat end', 'Repeat end', 1),
(23, '=::', 'Double repeat', 'Double repeat', 1),
(24, '=|=', 'End bar', 'End bar', 1),
(25, '(', 'Begin chord', 'Begin chord', 1),
(26, ')', 'End chord', 'End chord', 1),
(27, 'Cn', 'C natural', 'C natural', 0),
(28, 'Dn', 'D natural', 'D natural', 0),
(29, 'En', 'E natural', 'E natural', 0),
(30, 'Fn', 'F natural', 'F natural', 0),
(31, 'Gn', 'G natural', 'G natural', 0),
(32, 'An', 'A natural', 'A natural', 0),
(33, 'Bn', 'B natural', 'B natural', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Pieces`
--

CREATE TABLE `Pieces` (
`Piece_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Title` varchar(150) NOT NULL,
  `Data` longtext NOT NULL,
  `Created` datetime NOT NULL,
  `Updated` datetime NOT NULL,
  `Manual` tinyint(1) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `PieceStaveNotes`
--

CREATE TABLE `PieceStaveNotes` (
`PieceStaveNote_ID` int(11) NOT NULL,
  `PieceStave_ID` int(11) NOT NULL,
  `Note_ID` int(11) NOT NULL,
  `Duration_ID` int(11) DEFAULT NULL,
  `Octave` int(1) DEFAULT NULL,
  `Dotted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `PieceStaves`
--

CREATE TABLE `PieceStaves` (
`PieceStave_ID` int(11) NOT NULL,
  `Piece_ID` int(11) NOT NULL,
  `Clef_ID` int(11) NOT NULL,
  `Key_ID` int(11) NOT NULL,
  `TopSpace` int(11) NOT NULL,
  `BottomSpace` int(11) NOT NULL,
  `TopTime` int(2) DEFAULT NULL,
  `BottomTime` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
`User_ID` int(11) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `CreatedDate` datetime NOT NULL,
  `UpdatedDate` datetime NOT NULL,
  `Admin` tinyint(1) NOT NULL DEFAULT '0',
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `ResetCode` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `UserSessions`
--

CREATE TABLE `UserSessions` (
`UserSession_ID` int(11) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Access` tinyint(1) NOT NULL DEFAULT '0',
  `Datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Clefs`
--
ALTER TABLE `Clefs`
 ADD PRIMARY KEY (`Clef_ID`);

--
-- Indexes for table `Durations`
--
ALTER TABLE `Durations`
 ADD PRIMARY KEY (`Duration_ID`);

--
-- Indexes for table `Keys`
--
ALTER TABLE `Keys`
 ADD PRIMARY KEY (`Key_ID`);

--
-- Indexes for table `Notes`
--
ALTER TABLE `Notes`
 ADD PRIMARY KEY (`Note_ID`);

--
-- Indexes for table `Pieces`
--
ALTER TABLE `Pieces`
 ADD PRIMARY KEY (`Piece_ID`), ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `PieceStaveNotes`
--
ALTER TABLE `PieceStaveNotes`
 ADD PRIMARY KEY (`PieceStaveNote_ID`), ADD KEY `Duration_ID` (`Duration_ID`), ADD KEY `PieceStave_ID` (`PieceStave_ID`), ADD KEY `Note_ID` (`Note_ID`);

--
-- Indexes for table `PieceStaves`
--
ALTER TABLE `PieceStaves`
 ADD PRIMARY KEY (`PieceStave_ID`), ADD KEY `Time_ID` (`Key_ID`), ADD KEY `Piece_ID` (`Piece_ID`), ADD KEY `Clef_ID` (`Clef_ID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
 ADD PRIMARY KEY (`User_ID`), ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `UserSessions`
--
ALTER TABLE `UserSessions`
 ADD PRIMARY KEY (`UserSession_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Clefs`
--
ALTER TABLE `Clefs`
MODIFY `Clef_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Durations`
--
ALTER TABLE `Durations`
MODIFY `Duration_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Keys`
--
ALTER TABLE `Keys`
MODIFY `Key_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `Notes`
--
ALTER TABLE `Notes`
MODIFY `Note_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `Pieces`
--
ALTER TABLE `Pieces`
MODIFY `Piece_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PieceStaveNotes`
--
ALTER TABLE `PieceStaveNotes`
MODIFY `PieceStaveNote_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PieceStaves`
--
ALTER TABLE `PieceStaves`
MODIFY `PieceStave_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `UserSessions`
--
ALTER TABLE `UserSessions`
MODIFY `UserSession_ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
