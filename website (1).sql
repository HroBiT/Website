-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 27 Maj 2023, 19:57
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `website`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reservation`
--

CREATE TABLE `reservation` (
  `ReservationID` int(11) NOT NULL,
  `Number` char(3) NOT NULL,
  `HowMany` char(3) NOT NULL,
  `Type` char(3) NOT NULL,
  `FromDate` date NOT NULL,
  `ToDate` date NOT NULL,
  `ReservationStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(60) DEFAULT NULL,
  `UserSurrname` varchar(60) DEFAULT NULL,
  `UserMail` varchar(40) NOT NULL,
  `UserLogin` varchar(20) NOT NULL,
  `UserPassword` varchar(80) DEFAULT NULL,
  `UserDateOfJoin` date DEFAULT NULL,
  `UserNick` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `UserSurrname`, `UserMail`, `UserLogin`, `UserPassword`, `UserDateOfJoin`, `UserNick`) VALUES
(1, 'Bras', 'Cia', 'mail@sam.pl', 'Admin', '123', '2023-03-09', 0),
(2, 'Brooo', 'DASDAS', 'ez@gmail.com', '123', '$2y$10$TRcZ8y/v4kEaDXj8EDgjQeOijmKbNhw78D6kiDjyuFPmYbJGhGf/q', NULL, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wpisy`
--

CREATE TABLE `wpisy` (
  `WpisID` int(11) NOT NULL,
  `AutorNick` varchar(50) DEFAULT NULL,
  `AutorEmail` varchar(80) NOT NULL,
  `Tresc` text DEFAULT NULL,
  `StatusWpisu` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `wpisy`
--

INSERT INTO `wpisy` (`WpisID`, `AutorNick`, `AutorEmail`, `Tresc`, `StatusWpisu`) VALUES
(5, '1', '', 'fdsafsa', 1),
(6, '1', '', 'fasdbgvdfs423', 1),
(9, 'gsdf', '', 'gdfgsdagasdg', 1),
(10, 'gsdf', 'abc@sam.com', 'gdfgsdagasdg', 1),
(11, 'gsdf', 'abc@sam.com', 'gdfgsdagasdg', 1),
(13, 'gsdf', 'abc@sam.com', 'gdfgsdagasdg', 0),
(14, 'Eyy', 'elazsda@gmail.com', 'EJJJJJJJ', 1),
(15, '123', '123@123.com', 'efS', 1),
(16, '123', 'abc@sam.com', 'fdsa', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ReservationID`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indeksy dla tabeli `wpisy`
--
ALTER TABLE `wpisy`
  ADD PRIMARY KEY (`WpisID`),
  ADD KEY `WpisID` (`WpisID`,`AutorNick`),
  ADD KEY `AutorID` (`AutorNick`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `wpisy`
--
ALTER TABLE `wpisy`
  MODIFY `WpisID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
