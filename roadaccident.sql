/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : roadaccident

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2016-03-10 09:15:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `brand`
-- ----------------------------
DROP TABLE IF EXISTS `brand`;
CREATE TABLE `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime NOT NULL,
  `supplier` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of brand
-- ----------------------------
INSERT INTO `brand` VALUES ('1', '0', '2016-03-02 13:59:49', '2016-03-02 13:59:50', '1', 'Kugo');
INSERT INTO `brand` VALUES ('2', '0', '2016-03-02 14:00:50', '2016-03-02 14:00:51', '1', 'Mondeo');

-- ----------------------------
-- Table structure for `image`
-- ----------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `vehicle` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of image
-- ----------------------------
INSERT INTO `image` VALUES ('1', 'public/images/vehicles/38/thumbnail/IMG_8288.JPG', '0', '38', '0', '', '2016-03-06 17:20:06');
INSERT INTO `image` VALUES ('2', 'public/images/vehicles/38/thumbnail/IMG_8288.JPG', '0', '38', '0', '', '2016-03-06 17:20:06');
INSERT INTO `image` VALUES ('3', 'public/images/vehicles/39/IMG_8288.JPG', '0', '39', '0', '', '2016-03-06 18:54:39');
INSERT INTO `image` VALUES ('4', 'public/images/vehicles/39/index.png', '0', '39', '0', '', '2016-03-06 18:54:39');
INSERT INTO `image` VALUES ('5', 'public/images/vehicles/39/thumbnail/IMG_8288.JPG', '0', '39', '0', '', '2016-03-06 18:54:39');
INSERT INTO `image` VALUES ('6', 'public/images/vehicles/39/thumbnail/index.png', '0', '39', '0', '', '2016-03-06 18:54:39');
INSERT INTO `image` VALUES ('7', 'public/images/vehicles/40/IMG_8288.JPG', '0', '40', '0', '', '2016-03-06 19:54:35');
INSERT INTO `image` VALUES ('8', 'public/images/vehicles/40/index.png', '0', '40', '0', '', '2016-03-06 19:54:35');
INSERT INTO `image` VALUES ('9', 'public/images/vehicles/40/PicMonkey Collage.jpg', '0', '40', '0', '', '2016-03-06 19:54:35');
INSERT INTO `image` VALUES ('10', 'public/images/vehicles/40/thumbnail/IMG_8288.JPG', '0', '40', '1', '', '2016-03-06 19:54:35');
INSERT INTO `image` VALUES ('11', 'public/images/vehicles/40/thumbnail/index.png', '0', '40', '1', '', '2016-03-06 19:54:35');
INSERT INTO `image` VALUES ('12', 'public/images/vehicles/40/thumbnail/PicMonkey Collage.jpg', '0', '40', '1', '', '2016-03-06 19:54:35');
INSERT INTO `image` VALUES ('13', 'public/images/vehicles/44/56dc897161d61IMG_8288.JPG', '0', '44', '0', '56dc897161d61IMG_8288.JPG', '2016-03-06 20:48:10');
INSERT INTO `image` VALUES ('14', 'public/images/vehicles/44/56dc8971620a3index.png', '0', '44', '0', '56dc8971620a3index.png', '2016-03-06 20:48:10');
INSERT INTO `image` VALUES ('15', 'public/images/vehicles/44/thumbnail/56dc897161d61IMG_8288.JPG', '0', '44', '1', '56dc897161d61IMG_8288.JPG', '2016-03-06 20:48:10');
INSERT INTO `image` VALUES ('16', 'public/images/vehicles/44/thumbnail/56dc8971620a3index.png', '0', '44', '1', '56dc8971620a3index.png', '2016-03-06 20:48:10');
INSERT INTO `image` VALUES ('17', 'public/images/vehicles/45/56dd4c9f67a3dIMG_8288.JPG', '0', '45', '0', '56dd4c9f67a3dIMG_8288.JPG', '2016-03-07 10:40:56');
INSERT INTO `image` VALUES ('18', 'public/images/vehicles/45/thumbnail/56dd4c9f67a3dIMG_8288.JPG', '0', '45', '1', '56dd4c9f67a3dIMG_8288.JPG', '2016-03-07 10:40:56');

-- ----------------------------
-- Table structure for `supplier`
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of supplier
-- ----------------------------
INSERT INTO `supplier` VALUES ('1', '0', '2016-03-02 14:00:24', '2016-03-02 14:00:25', 'Ford');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateEdited` datetime NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `vkId` int(11) NOT NULL,
  `okId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for `vehicle`
-- ----------------------------
DROP TABLE IF EXISTS `vehicle`;
CREATE TABLE `vehicle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateEdited` datetime NOT NULL,
  `brand` int(11) NOT NULL,
  `regnum` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of vehicle
-- ----------------------------
INSERT INTO `vehicle` VALUES ('1', '0', '2016-03-02 13:59:00', '2016-03-02 13:59:02', '1', '23e23e');
INSERT INTO `vehicle` VALUES ('2', '0', '2016-03-06 14:33:01', '2016-03-06 14:33:01', '0', 'b');
INSERT INTO `vehicle` VALUES ('3', '0', '2016-03-06 14:36:38', '2016-03-06 14:36:38', '1', '');
INSERT INTO `vehicle` VALUES ('4', '0', '2016-03-06 15:34:50', '2016-03-06 15:34:50', '1', '');
INSERT INTO `vehicle` VALUES ('5', '0', '2016-03-06 15:34:54', '2016-03-06 15:34:54', '1', '');
INSERT INTO `vehicle` VALUES ('6', '0', '2016-03-06 15:34:59', '2016-03-06 15:34:59', '1', '');
INSERT INTO `vehicle` VALUES ('7', '0', '2016-03-06 15:35:04', '2016-03-06 15:35:04', '1', '');
INSERT INTO `vehicle` VALUES ('8', '0', '2016-03-06 15:35:07', '2016-03-06 15:35:07', '1', '');
INSERT INTO `vehicle` VALUES ('9', '0', '2016-03-06 15:35:08', '2016-03-06 15:35:08', '1', '');
INSERT INTO `vehicle` VALUES ('10', '0', '2016-03-06 15:35:10', '2016-03-06 15:35:10', '1', '');
INSERT INTO `vehicle` VALUES ('11', '0', '2016-03-06 15:35:46', '2016-03-06 15:35:46', '1', '');
INSERT INTO `vehicle` VALUES ('12', '0', '2016-03-06 15:40:49', '2016-03-06 15:40:49', '1', '');
INSERT INTO `vehicle` VALUES ('13', '0', '2016-03-06 15:40:52', '2016-03-06 15:40:52', '1', '');
INSERT INTO `vehicle` VALUES ('14', '0', '2016-03-06 15:41:46', '2016-03-06 15:41:46', '1', '');
INSERT INTO `vehicle` VALUES ('15', '0', '2016-03-06 15:43:15', '2016-03-06 15:43:15', '1', '');
INSERT INTO `vehicle` VALUES ('16', '0', '2016-03-06 15:47:41', '2016-03-06 15:47:41', '1', '345346');
INSERT INTO `vehicle` VALUES ('17', '0', '2016-03-06 15:49:14', '2016-03-06 15:49:14', '1', '345346');
INSERT INTO `vehicle` VALUES ('18', '0', '2016-03-06 15:52:09', '2016-03-06 15:52:09', '1', '345346');
INSERT INTO `vehicle` VALUES ('19', '0', '2016-03-06 15:52:48', '2016-03-06 15:52:48', '1', '345346');
INSERT INTO `vehicle` VALUES ('20', '0', '2016-03-06 15:57:46', '2016-03-06 15:57:46', '1', '345346');
INSERT INTO `vehicle` VALUES ('21', '0', '2016-03-06 16:01:09', '2016-03-06 16:01:09', '1', '345346');
INSERT INTO `vehicle` VALUES ('22', '0', '2016-03-06 16:08:36', '2016-03-06 16:08:36', '1', '345346');
INSERT INTO `vehicle` VALUES ('23', '0', '2016-03-06 16:09:41', '2016-03-06 16:09:41', '1', '345346');
INSERT INTO `vehicle` VALUES ('24', '0', '2016-03-06 16:10:21', '2016-03-06 16:10:21', '1', '345346');
INSERT INTO `vehicle` VALUES ('25', '0', '2016-03-06 16:15:32', '2016-03-06 16:15:32', '1', '345346');
INSERT INTO `vehicle` VALUES ('26', '0', '2016-03-06 16:20:00', '2016-03-06 16:20:00', '1', '345346');
INSERT INTO `vehicle` VALUES ('27', '0', '2016-03-06 16:34:53', '2016-03-06 16:34:53', '1', '');
INSERT INTO `vehicle` VALUES ('28', '0', '2016-03-06 16:43:12', '2016-03-06 16:43:12', '1', '');
INSERT INTO `vehicle` VALUES ('29', '0', '2016-03-06 16:44:28', '2016-03-06 16:44:28', '1', '');
INSERT INTO `vehicle` VALUES ('30', '0', '2016-03-06 16:44:55', '2016-03-06 16:44:55', '1', '');
INSERT INTO `vehicle` VALUES ('31', '0', '2016-03-06 16:47:26', '2016-03-06 16:47:26', '1', '');
INSERT INTO `vehicle` VALUES ('32', '0', '2016-03-06 16:49:43', '2016-03-06 16:49:43', '1', '');
INSERT INTO `vehicle` VALUES ('33', '0', '2016-03-06 16:52:24', '2016-03-06 16:52:24', '1', '');
INSERT INTO `vehicle` VALUES ('34', '0', '2016-03-06 16:54:01', '2016-03-06 16:54:01', '1', '');
INSERT INTO `vehicle` VALUES ('35', '0', '2016-03-06 16:56:51', '2016-03-06 16:56:51', '1', '');
INSERT INTO `vehicle` VALUES ('36', '0', '2016-03-06 17:03:23', '2016-03-06 17:03:23', '1', '');
INSERT INTO `vehicle` VALUES ('37', '0', '2016-03-06 17:17:47', '2016-03-06 17:17:47', '1', '111');
INSERT INTO `vehicle` VALUES ('38', '0', '2016-03-06 17:20:06', '2016-03-06 17:20:06', '1', '111');
INSERT INTO `vehicle` VALUES ('39', '0', '2016-03-06 18:54:39', '2016-03-06 18:54:39', '1', '666');
INSERT INTO `vehicle` VALUES ('40', '0', '2016-03-06 19:54:35', '2016-03-06 19:54:35', '1', '666');
INSERT INTO `vehicle` VALUES ('41', '0', '2016-03-06 20:39:35', '2016-03-06 20:39:35', '1', '');
INSERT INTO `vehicle` VALUES ('42', '0', '2016-03-06 20:41:08', '2016-03-06 20:41:08', '1', '');
INSERT INTO `vehicle` VALUES ('43', '0', '2016-03-06 20:46:37', '2016-03-06 20:46:37', '1', '666');
INSERT INTO `vehicle` VALUES ('44', '0', '2016-03-06 20:48:10', '2016-03-06 20:48:10', '1', '6665');
INSERT INTO `vehicle` VALUES ('45', '0', '2016-03-07 10:40:55', '2016-03-07 10:40:55', '1', '111');

-- ----------------------------
-- Table structure for `vehicleimage`
-- ----------------------------
DROP TABLE IF EXISTS `vehicleimage`;
CREATE TABLE `vehicleimage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of vehicleimage
-- ----------------------------
