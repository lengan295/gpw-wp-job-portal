REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '1.0.3', 'default');
CREATE TABLE IF NOT EXISTS `#__wj_portal_jswjsessiondata` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `usersessionid` char(64) NOT NULL,
  `sessionmsg` text CHARACTER SET utf8 NOT NULL,
  `sessionexpire` bigint(32) NOT NULL,
  `sessionfor` varchar(125) NOT NULL,
  `msgkey`varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



