/**The purpose of this query is to make Address a seperate table. 
	to accommplish this a new table for address with 9 fields is ceated,
	also a user_address table is needed to map users with their addresses and see IF primary address.
	With these new changes the user.user_address is redundent and dropped. Transferring info over too costly.
	**/

	CREATE TABLE `address` (
  `address_id` int(255) NOT NULL,
  `address_fname` varchar(255) NOT NULL,
  `address_lname` varchar(255) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) NOT NULL,
  `address_city` varchar(255) NOT NULL,
  `address_state` varchar(255) NOT NULL,
  `address_zip` varchar(255) NOT NULL,
  `address_country` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(255) NOT NULL AUTO_INCREMENT;



CREATE TABLE `user_address` (
  `user_id` int(255) NOT NULL,
  `address_id` int(255) NOT NULL,
  `p_address` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



ALTER TABLE `users` DROP `u_address`;