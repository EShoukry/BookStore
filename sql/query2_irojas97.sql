DROP TABLE `credit_card`;

-- --------------------------------------------------------

--
-- Table structure for table `credit_card`
--

CREATE TABLE `credit_card` (
  `CC_id` int(20) NOT NULL,
  `user_id` int(255) NOT NULL,
  `add_id` int(255) NOT NULL,
  `p_CC` tinyint(1) NOT NULL,
  `CC_title` varchar(255) NOT NULL,
  `CC_secure_code` varchar(255) NOT NULL,
  `CC_expmm` int(2) NOT NULL,
  `CC_expyy` int(4) NOT NULL,
  `CC_number` varchar(255) NOT NULL,
  `CC_four` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Indexes for table `credit_card`
--
ALTER TABLE `credit_card`
  ADD PRIMARY KEY (`CC_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `add_id` (`add_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `credit_card`
--
ALTER TABLE `credit_card`
  MODIFY `CC_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `credit_card`
--
ALTER TABLE `credit_card`
  ADD CONSTRAINT `fk_add_creditcard` FOREIGN KEY (`add_id`) REFERENCES `address` (`address_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_creditcard` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id_number`) ON DELETE CASCADE ON UPDATE CASCADE;

