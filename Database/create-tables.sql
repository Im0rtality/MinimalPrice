-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `mp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `phash` text NOT NULL,
  `rights` int(11) NOT NULL,
  `createdby` int(11) NOT NULL DEFAULT '0',
  `createdwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `phash`, `rights`, `createdby`, `createdwhen`) VALUES
(1, 'root', '435b41068e8665513a20070c033b08b9c66e4332', 65535, 0, '2012-09-11 18:54:55');

-- Full access user for testing purposes: username: "root", password: "toor"