--
-- Table structure for table `api`
--

CREATE TABLE `api` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` char(16) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `api_log`
--

CREATE TABLE `api_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `api_id` int(11) UNSIGNED NOT NULL,
  `action` tinyint(1) UNSIGNED NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `bandwidth`
--

CREATE TABLE `bandwidth` (
  `id` int(11) UNSIGNED NOT NULL,
  `vps_id` int(11) UNSIGNED NOT NULL,
  `used` int(11) UNSIGNED NOT NULL,
  `pure_used` int(11) UNSIGNED NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `datastore`
--

CREATE TABLE `datastore` (
  `id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  `space` int(11) UNSIGNED NOT NULL,
  `is_default` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `vsan` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `is_public` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `ip`
--

CREATE TABLE `ip` (
  `id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(45) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `netmask` varchar(255) NOT NULL,
  `mac_address` varchar(255) NOT NULL,
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `network` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `is_dhcp` tinyint(1) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `iso`
--

CREATE TABLE `iso` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `lost_password`
--

CREATE TABLE `lost_password` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `key` char(16) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `expired_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `os`
--

CREATE TABLE `os` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `operation_system` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `adapter` varchar(255) DEFAULT NULL,
  `guest` varchar(255) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ram` int(11) UNSIGNED NOT NULL,
  `cpu_mhz` int(11) UNSIGNED NOT NULL,
  `cpu_core` int(11) UNSIGNED NOT NULL,
  `hard` int(11) UNSIGNED NOT NULL,
  `band_width` bigint(20) NOT NULL,
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `server`
--

CREATE TABLE `server` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `port` smallint(11) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `vcenter_ip` varchar(255) DEFAULT NULL,
  `vcenter_username` varchar(255) DEFAULT NULL,
  `vcenter_password` varchar(255) NOT NULL,
  `network` varchar(255) DEFAULT NULL,
  `second_network` varchar(255) DEFAULT NULL,
  `version` int(11) UNSIGNED DEFAULT NULL,
  `virtualization` varchar(255) DEFAULT NULL,
  `dns1` varchar(255) NOT NULL DEFAULT '4.2.2.4',
  `dns2` varchar(255) NOT NULL DEFAULT '8.8.8.8',
  `server_address` varchar(255) NOT NULL,
  `console_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `key`, `value`) VALUES
(1, 'title', 'VPS Management'),
(2, 'terminate', '2'),
(3, 'language', 'en'),
(4, 'change_limit', '20');

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `auth_key`, `is_admin`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Admin', 'Admin', '@key', 1, 1579163518, 1579163518, 1);

--
-- Table structure for table `user_email`
--

CREATE TABLE `user_email` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `key` char(16) NOT NULL,
  `is_primary` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `is_confirmed` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_email`
--

INSERT INTO `user_email` (`id`, `user_id`, `email`, `key`, `is_primary`, `is_confirmed`, `created_at`, `updated_at`) VALUES
(1, 1, '@email', 'jshdufjthr75869i', 1, 1, 1579163518, 1579163518);

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(45) NOT NULL,
  `os_name` varchar(255) NOT NULL,
  `browser_name` varchar(255) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `user_password`
--

CREATE TABLE `user_password` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `hash` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_password`
--

INSERT INTO `user_password` (`id`, `user_id`, `hash`, `salt`, `password`, `created_at`) VALUES
(1, 1, 2, 'hgjfht76utjgih98', '@pass', 1579163518);

--
-- Table structure for table `vps`
--

CREATE TABLE `vps` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `datastore_id` int(11) UNSIGNED NOT NULL,
  `os_id` int(11) UNSIGNED DEFAULT NULL,
  `plan_id` int(11) UNSIGNED DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `plan_type` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `vps_ram` int(11) UNSIGNED DEFAULT NULL,
  `vps_cpu_mhz` int(11) UNSIGNED DEFAULT NULL,
  `vps_cpu_core` int(11) UNSIGNED DEFAULT NULL,
  `vps_hard` int(11) UNSIGNED DEFAULT NULL,
  `vps_band_width` int(11) UNSIGNED DEFAULT NULL,
  `reset_at` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `power` tinyint(1) UNSIGNED NOT NULL DEFAULT '2',
  `disk` varchar(255) DEFAULT NULL,
  `snapshot` tinyint(1) UNSIGNED NOT NULL DEFAULT '2',
  `extra_bw` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `change_limit` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `notify_at` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `vps_action`
--

CREATE TABLE `vps_action` (
  `id` int(11) UNSIGNED NOT NULL,
  `vps_id` int(11) UNSIGNED NOT NULL,
  `action` tinyint(1) UNSIGNED NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `vps_ip`
--

CREATE TABLE `vps_ip` (
  `id` int(11) UNSIGNED NOT NULL,
  `vps_id` int(11) UNSIGNED NOT NULL,
  `ip_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `api_log`
--
ALTER TABLE `api_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_id` (`api_id`);

--
-- Indexes for table `bandwidth`
--
ALTER TABLE `bandwidth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vps_id` (`vps_id`);

--
-- Indexes for table `datastore`
--
ALTER TABLE `datastore`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server_id` (`server_id`);

--
-- Indexes for table `ip`
--
ALTER TABLE `ip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server_id` (`server_id`);

--
-- Indexes for table `iso`
--
ALTER TABLE `iso`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lost_password`
--
ALTER TABLE `lost_password`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `os`
--
ALTER TABLE `os`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `server`
--
ALTER TABLE `server`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_key` (`auth_key`);

--
-- Indexes for table `user_email`
--
ALTER TABLE `user_email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_password`
--
ALTER TABLE `user_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vps`
--
ALTER TABLE `vps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `server_id` (`server_id`),
  ADD KEY `datastore_id` (`datastore_id`),
  ADD KEY `os_id` (`os_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `vps_action`
--
ALTER TABLE `vps_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vps_id` (`vps_id`);

--
-- Indexes for table `vps_ip`
--
ALTER TABLE `vps_ip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vps_id` (`vps_id`),
  ADD KEY `ip_id` (`ip_id`);

--
-- AUTO_INCREMENT for table `api`
--
ALTER TABLE `api`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `api_log`
--
ALTER TABLE `api_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `bandwidth`
--
ALTER TABLE `bandwidth`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `datastore`
--
ALTER TABLE `datastore`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `ip`
--
ALTER TABLE `ip`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `iso`
--
ALTER TABLE `iso`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `lost_password`
--
ALTER TABLE `lost_password`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `os`
--
ALTER TABLE `os`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `server`
--
ALTER TABLE `server`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
  
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
  
--
-- AUTO_INCREMENT for table `user_email`
--
ALTER TABLE `user_email`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
  
--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `user_password`
--
ALTER TABLE `user_password`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
  
--
-- AUTO_INCREMENT for table `vps`
--
ALTER TABLE `vps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `vps_action`
--
ALTER TABLE `vps_action`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `vps_ip`
--
ALTER TABLE `vps_ip`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `api_log`
--
ALTER TABLE `api_log`
  ADD CONSTRAINT `api_log_ibfk_1` FOREIGN KEY (`api_id`) REFERENCES `api` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `bandwidth`
--
ALTER TABLE `bandwidth`
  ADD CONSTRAINT `bandwidth_ibfk_1` FOREIGN KEY (`vps_id`) REFERENCES `vps` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `datastore`
--
ALTER TABLE `datastore`
  ADD CONSTRAINT `datastore_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `ip`
--
ALTER TABLE `ip`
  ADD CONSTRAINT `ip_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `lost_password`
--
ALTER TABLE `lost_password`
  ADD CONSTRAINT `lost_password_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_email`
--
ALTER TABLE `user_email`
  ADD CONSTRAINT `user_email_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_login`
--
ALTER TABLE `user_login`
  ADD CONSTRAINT `user_login_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_password`
--
ALTER TABLE `user_password`
  ADD CONSTRAINT `user_password_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `vps`
--
ALTER TABLE `vps`
  ADD CONSTRAINT `vps_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `vps_ibfk_2` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `vps_ibfk_3` FOREIGN KEY (`datastore_id`) REFERENCES `datastore` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `vps_ibfk_4` FOREIGN KEY (`os_id`) REFERENCES `os` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `vps_ibfk_5` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `vps_action`
--
ALTER TABLE `vps_action`
  ADD CONSTRAINT `vps_action_ibfk_1` FOREIGN KEY (`vps_id`) REFERENCES `vps` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `vps_ip`
--
ALTER TABLE `vps_ip`
  ADD CONSTRAINT `vps_ip_ibfk_1` FOREIGN KEY (`vps_id`) REFERENCES `vps` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `vps_ip_ibfk_2` FOREIGN KEY (`ip_id`) REFERENCES `ip` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;