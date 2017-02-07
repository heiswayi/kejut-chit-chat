CREATE TABLE ip_shouts (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT(8) NOT NULL,
    shout_time INT(32) NOT NULL,
    shout_msg TEXT NOT NULL
);
CREATE TABLE ip_updates (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT(8) NOT NULL,
    shout_time INT(32) NOT NULL,
    shout_msg TEXT NOT NULL
);
CREATE TABLE ip_requests (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT(8) NOT NULL,
    shout_time INT(32) NOT NULL,
    shout_msg TEXT NOT NULL
);
CREATE TABLE ip_reply (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    request_id INT(8) NOT NULL,
    reply_time INT(32) NOT NULL,
    reply_msg TEXT NOT NULL,
    replier_id INT(8) NOT NULL
);
CREATE TABLE ip_uplikes (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    shout_id INT(8) NOT NULL,
    user_id INT(8) NOT NULL
);
CREATE TABLE ip_sharerlinks (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT(8) NOT NULL,
    sharername VARCHAR(255) NOT NULL,
    sharerurl VARCHAR(255) NOT NULL,
    sharerdesc TEXT NOT NULL,
    sharerdate INT(32) NOT NULL,
    status INT(8) NOT NULL DEFAULT 1
);
CREATE TABLE ip_shlikes (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sharer_id INT(8) NOT NULL,
    user_id INT(8) NOT NULL
);
CREATE TABLE ip_bans (
    id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT(8) NOT NULL,
    user_ip VARCHAR(255) NOT NULL
);