/**The purpose of this query is to make Address a seperate table. 
	to accommplish this a new table for address with 11 fields is ceated,
	With these new changes the user.user_address is redundent and dropped. Transferring info over too costly.
	**/

CREATE TABLE `address` (
  `address_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `p_address` tinyint(1) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `line1` varchar(255) NOT NULL,
  `line2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);
  ADD KEY `user_id` (`user_id`);


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `address`
  ADD CONSTRAINT `fk_user_address` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id_number`) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE `users` DROP `u_address`;