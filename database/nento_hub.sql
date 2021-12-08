-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 30, 2021 at 11:43 AM
-- Server version: 5.7.33
-- PHP Version: 7.0.33-50+0~20210501.55+debian9~1.gbpd59059

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nento029_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_timelines`
--

CREATE TABLE `activity_timelines` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `activity` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addon_packages`
--

CREATE TABLE `addon_packages` (
  `id` int(11) NOT NULL,
  `package_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `text_messages_credit` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `voice_messages_credit` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `user_country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credits_carryover` tinyint(4) NOT NULL DEFAULT '1',
  `logs` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agreement_location`
--

CREATE TABLE `agreement_location` (
  `id` int(11) NOT NULL,
  `agreement_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `street_address` text,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `log` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `all_keywords`
--

CREATE TABLE `all_keywords` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `keyword` text,
  `ref_id` bigint(111) NOT NULL DEFAULT '0',
  `keyword_type` int(111) NOT NULL DEFAULT '0',
  `module_type` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `answer_subscribers`
--

CREATE TABLE `answer_subscribers` (
  `id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `app_date_time` datetime DEFAULT NULL,
  `appointment_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>Unconfirmed,1=>Confirmed,2=>Cancelled,3=>Rescheduled',
  `scheduled` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>Not Scheduled,1=>Scheduled',
  `created` datetime DEFAULT NULL,
  `is_testing` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_csvs`
--

CREATE TABLE `appointment_csvs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `phone_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appointment_status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_date_time` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_settings`
--

CREATE TABLE `appointment_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `cancel_keyword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancel_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancel_color_picker` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancel_email_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancel_email_from` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancel_email_subject` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reschedule_keyword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancel_email_body` text COLLATE utf8_unicode_ci,
  `reschedule_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reschedule_color_picker` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reschedule_email_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reschedule_email_from` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reschedule_email_subject` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reschedule_email_body` text COLLATE utf8_unicode_ci,
  `confirm_keyword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_color_picker` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_email_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_email_from` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_email_subject` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_email_body` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `automation_connections`
--

CREATE TABLE `automation_connections` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `connection_type` int(11) NOT NULL DEFAULT '0' COMMENT '1=wifi,2=reputation,3=waivers,4=cashierwithlogin,5=cashierwithoutlogin,6=webwidgets,7=coupenaproved,8=cashiersstampreward',
  `visit_count` int(11) DEFAULT '0',
  `repet_visit_count` varchar(255) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `response_status` text,
  `response_msg` text,
  `sent_message` text,
  `message_repet` int(11) DEFAULT '0',
  `miss_visit_count` varchar(255) DEFAULT '1',
  `miss_repeat_date` datetime DEFAULT NULL,
  `miss_response_status` varchar(255) DEFAULT NULL,
  `miss_response_msg` text,
  `miss_sent_message` text,
  `missu_responder_status` int(11) NOT NULL DEFAULT '0',
  `responder_id` int(111) NOT NULL DEFAULT '0',
  `send_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `automation_marketings`
--

CREATE TABLE `automation_marketings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `location_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `email_template_id` int(11) DEFAULT NULL,
  `responder_type` int(11) DEFAULT '1',
  `connection_list` varchar(255) DEFAULT NULL,
  `visit_count` int(11) NOT NULL DEFAULT '0',
  `visit_message` text,
  `redirect_link` varchar(255) DEFAULT NULL,
  `visit_msg_send_after` varchar(255) NOT NULL,
  `visit_msg_hour` int(11) NOT NULL DEFAULT '1' COMMENT '1minute,2hour,3day',
  `miss_msg` text,
  `miss_coupon` int(11) DEFAULT '0',
  `miss_msg_send_after` varchar(255) DEFAULT NULL,
  `miss_msg_day` int(11) NOT NULL DEFAULT '1' COMMENT '1minute,2hour,3day',
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bambora_payment_notifications`
--

CREATE TABLE `bambora_payment_notifications` (
  `id` bigint(255) NOT NULL,
  `trn_id` text,
  `rbaccountid` bigint(255) DEFAULT NULL COMMENT 'means recurring billing Id',
  `authorizing_merchant_id` int(111) NOT NULL DEFAULT '0',
  `approved` int(111) DEFAULT NULL,
  `message_id` int(111) DEFAULT NULL,
  `message` text,
  `auth_code` varchar(255) DEFAULT NULL,
  `order_number` bigint(255) DEFAULT '0',
  `trn_type` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT '0',
  `trn_date` datetime DEFAULT NULL,
  `full_response` text,
  `type` int(11) DEFAULT '0' COMMENT '1 - single payment, 2 - recurring payment',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `birthdays`
--

CREATE TABLE `birthdays` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `responder_type` int(11) DEFAULT '1',
  `email_template_id` int(11) DEFAULT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `systemmsg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `sent` int(11) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes',
  `sms_type` int(11) NOT NULL DEFAULT '0' COMMENT '1=>sms , 2=>mms',
  `image_url` text COLLATE utf8_unicode_ci,
  `isassigncoupon` int(11) NOT NULL DEFAULT '0',
  `assign_coupon_id` int(111) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_schedule_emails`
--

CREATE TABLE `bulk_schedule_emails` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `sechdule_type` int(11) NOT NULL DEFAULT '0',
  `template_id` int(111) DEFAULT '0',
  `group_email_id` bigint(20) DEFAULT '0',
  `sendfrom` varchar(255) DEFAULT NULL,
  `is_cron` int(11) DEFAULT '0',
  `sent` int(11) NOT NULL DEFAULT '0',
  `throttle` int(11) NOT NULL DEFAULT '0',
  `send_on` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_schedule_messages`
--

CREATE TABLE `bulk_schedule_messages` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `sechdule_type` int(11) NOT NULL DEFAULT '0',
  `message` text,
  `systemmsg` varchar(255) DEFAULT NULL,
  `msg_type` int(11) NOT NULL DEFAULT '0',
  `mms_text` text,
  `group_sms_id` bigint(20) DEFAULT '0',
  `sendfrom` varchar(255) NOT NULL,
  `is_cron` int(11) DEFAULT '0',
  `sent` int(11) NOT NULL DEFAULT '0',
  `rotate_number` int(11) DEFAULT '0',
  `throttle` int(11) NOT NULL DEFAULT '0',
  `send_on` datetime DEFAULT NULL,
  `before_per_day_limit` int(111) NOT NULL DEFAULT '0',
  `after_per_day_limit` int(111) NOT NULL DEFAULT '0',
  `comment` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0' COMMENT 'created user',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `profile_picture` text,
  `username` varchar(255) DEFAULT NULL,
  `password` text,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0- active. 1-deactive',
  `Authorization` varchar(255) DEFAULT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cashier_push_notifications`
--

CREATE TABLE `cashier_push_notifications` (
  `id` int(111) NOT NULL,
  `user_id` int(111) DEFAULT NULL,
  `location_id` int(111) DEFAULT NULL,
  `cashier_id` int(111) DEFAULT NULL,
  `device_token` text,
  `notification_message` text,
  `redirect_url` varchar(255) DEFAULT NULL,
  `print_url` varchar(255) DEFAULT NULL,
  `notification_type` varchar(55) DEFAULT NULL,
  `response` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cashier_reference_users`
--

CREATE TABLE `cashier_reference_users` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `cashier_id` int(111) NOT NULL DEFAULT '0',
  `subscribe_keyword` text,
  `type` int(111) NOT NULL DEFAULT '0' COMMENT '1 - coupon, 2 - poll, 3 - contest',
  `refrence_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` int(111) NOT NULL DEFAULT '0',
  `phone_number` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `checkin_point_options`
--

CREATE TABLE `checkin_point_options` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `checkin_points` int(11) NOT NULL DEFAULT '0',
  `qrcode_points` int(11) NOT NULL DEFAULT '0',
  `is_leader_board_active` tinyint(2) NOT NULL DEFAULT '0',
  `is_sms_notification` tinyint(2) NOT NULL DEFAULT '0',
  `is_email_notification` tinyint(2) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `last_reset_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `id` int(11) NOT NULL,
  `registration_charge` float DEFAULT NULL,
  `tax_percentage` float NOT NULL DEFAULT '0',
  `free_sms` int(11) NOT NULL DEFAULT '0',
  `free_voice` int(11) NOT NULL DEFAULT '0',
  `referral_amount` float DEFAULT NULL,
  `recurring_referral_percent` float DEFAULT NULL,
  `site_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sitename` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_sms_method` int(2) NOT NULL DEFAULT '0' COMMENT '0 means twillio 1 means telnyx',
  `twilio_accountSid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twilio_auth_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telnyx_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telnyx_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telnyx_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidth_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidth_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidth_user_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidth_appid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nexmo_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nexmo_secret` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plivo_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plivo_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plivoapp_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `2CO_account_ID` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `2CO_account_activation_prod_ID` int(11) NOT NULL DEFAULT '0',
  `paypal_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `support_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profilestartdate` int(20) NOT NULL DEFAULT '0',
  `payment_gateway` int(11) NOT NULL DEFAULT '0' COMMENT '1=PayPal,2=Stripe,3=Both',
  `pay_activation_fees` int(11) NOT NULL DEFAULT '0',
  `mobile_page_limit` int(11) NOT NULL DEFAULT '0',
  `payment_currency_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `AllowTollFreeNumbers` int(11) NOT NULL DEFAULT '0',
  `facebook_appid` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_appsecret` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FBTwitterSharing` int(11) NOT NULL DEFAULT '1',
  `optmsg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday_msg` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_capture_msg` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_capture_msg` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bitly_username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bitly_api_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bitly_access_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logout_url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `theme_color` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `charge_for_additional_numbers` int(11) NOT NULL DEFAULT '0',
  `filepickerapikey` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filepickeron` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numverify` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numverifyurl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `require_monthly_getnumber` tinyint(4) NOT NULL DEFAULT '0',
  `require_real_email` tinyint(4) NOT NULL DEFAULT '0',
  `autoresponders` tinyint(4) NOT NULL DEFAULT '1',
  `importcontacts` tinyint(4) NOT NULL DEFAULT '1',
  `shortlinks` tinyint(4) NOT NULL DEFAULT '1',
  `voicebroadcast` tinyint(4) NOT NULL DEFAULT '1',
  `polls` tinyint(4) NOT NULL DEFAULT '1',
  `contests` tinyint(4) NOT NULL DEFAULT '1',
  `loyaltyprograms` tinyint(4) NOT NULL DEFAULT '1',
  `kioskbuilder` tinyint(4) NOT NULL DEFAULT '1',
  `birthdaywishes` tinyint(4) NOT NULL DEFAULT '1',
  `mobilepagebuilder` tinyint(4) NOT NULL DEFAULT '1',
  `webwidgets` tinyint(4) NOT NULL DEFAULT '1',
  `smschat` tinyint(4) NOT NULL DEFAULT '1',
  `qrcodes` tinyint(4) NOT NULL DEFAULT '1',
  `calendarscheduler` tinyint(4) NOT NULL DEFAULT '1',
  `appointments` tinyint(4) NOT NULL DEFAULT '1',
  `groups` tinyint(4) NOT NULL DEFAULT '1',
  `contactlist` tinyint(4) NOT NULL DEFAULT '1',
  `sendsms` tinyint(4) NOT NULL DEFAULT '1',
  `affiliates` tinyint(4) NOT NULL DEFAULT '1',
  `getnumbers` tinyint(4) NOT NULL DEFAULT '1',
  `location` tinyint(4) NOT NULL DEFAULT '0',
  `groupt_message_queue` tinyint(4) NOT NULL DEFAULT '0',
  `contact_message_queue` tinyint(4) NOT NULL DEFAULT '0',
  `appointments_settings` tinyint(4) NOT NULL DEFAULT '0',
  `appointments_calendar` tinyint(4) NOT NULL DEFAULT '0',
  `import_appointments` tinyint(4) NOT NULL DEFAULT '0',
  `wallet_options` tinyint(4) NOT NULL DEFAULT '0',
  `cashier` tinyint(4) NOT NULL DEFAULT '0',
  `coupon` tinyint(4) NOT NULL DEFAULT '0',
  `kiosk_reports` tinyint(4) NOT NULL DEFAULT '0',
  `sub_unsub_reports` tinyint(4) NOT NULL DEFAULT '0',
  `logs` tinyint(4) NOT NULL DEFAULT '0',
  `character_checker` tinyint(4) NOT NULL DEFAULT '0',
  `emailsmtp` tinyint(4) NOT NULL DEFAULT '0',
  `chargeincomingsms` tinyint(4) NOT NULL DEFAULT '0',
  `cashier_leader_board` int(11) NOT NULL DEFAULT '0',
  `access_token` text COLLATE utf8_unicode_ci,
  `google_drive_status` tinyint(2) NOT NULL DEFAULT '0',
  `google_drive_response` text COLLATE utf8_unicode_ci,
  `google_drive_folder_id` text COLLATE utf8_unicode_ci,
  `google_drive_error` text COLLATE utf8_unicode_ci,
  `updated_date_drive` datetime DEFAULT NULL COMMENT ' if any exception in response then this will be updated '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` int(111) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `countrycodetel` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `faxcountrycodetel` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `carrier` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favorite` tinyint(4) NOT NULL DEFAULT '0',
  `un_subscribers` tinyint(4) NOT NULL DEFAULT '0',
  `color` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastmsg` datetime DEFAULT NULL,
  `stickysender` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `is_testing` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_groups`
--

CREATE TABLE `contact_groups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
  `contest_id` int(11) NOT NULL DEFAULT '0',
  `group_subscribers` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `un_subscribers` int(11) NOT NULL DEFAULT '0',
  `do_not_call` int(11) NOT NULL DEFAULT '0',
  `subscribed_by_sms` int(11) NOT NULL DEFAULT '0' COMMENT '0=csv,1=sms,2=widget,3=kiosk,4=wifi,5=waiver,6=reputation,7=menuonline',
  `active` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `is_notify_register` tinyint(2) NOT NULL DEFAULT '0',
  `responder_id` int(111) NOT NULL DEFAULT '0',
  `is_qrscan_notify` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = no, 1 = yes',
  `qrscan_responderid` int(111) NOT NULL DEFAULT '0',
  `coupon_share_limit` int(111) NOT NULL DEFAULT '0',
  `coupon_shared` int(111) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contests`
--

CREATE TABLE `contests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `maxentries` tinyint(4) NOT NULL DEFAULT '0',
  `keyword` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `system_message` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `totalsubscriber` int(11) NOT NULL DEFAULT '0',
  `winning_phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contest_subscribers`
--

CREATE TABLE `contest_subscribers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `contest_id` int(11) NOT NULL DEFAULT '0',
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country_gateways`
--

CREATE TABLE `country_gateways` (
  `id` int(11) NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_type` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupens`
--

CREATE TABLE `coupens` (
  `id` int(11) NOT NULL,
  `responder_type` int(11) DEFAULT '1' COMMENT '1="sms",2="email"',
  `sendernumber` varchar(55) DEFAULT NULL,
  `senderemail` varchar(255) DEFAULT NULL,
  `group` varchar(100) DEFAULT NULL,
  `coupon_type` int(11) NOT NULL DEFAULT '0' COMMENT ' 0 - blast, 1 - autorespoder',
  `optin_group` int(111) NOT NULL DEFAULT '0',
  `is_all_location` int(11) NOT NULL DEFAULT '1',
  `location` varchar(255) DEFAULT NULL,
  `coupenname` varchar(100) DEFAULT NULL,
  `startdate` varchar(255) DEFAULT NULL,
  `enddate` varchar(255) DEFAULT NULL,
  `is_scheduled` int(11) NOT NULL DEFAULT '0',
  `scheduledate` datetime DEFAULT NULL,
  `rotate_number` int(11) NOT NULL DEFAULT '0',
  `is_share` int(11) NOT NULL DEFAULT '0',
  `throttle` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email_template_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `clientlogo` varchar(250) DEFAULT NULL,
  `keyword` varchar(250) DEFAULT NULL,
  `allowredendent` varchar(50) DEFAULT NULL,
  `validdays` text,
  `coupenimage` varchar(250) DEFAULT NULL,
  `variations` varchar(255) DEFAULT NULL,
  `message` text,
  `fine_print` text,
  `coupon_pp_text` text,
  `messagelink` varchar(100) DEFAULT NULL,
  `coupenheader` text,
  `mendatory_message` varchar(255) DEFAULT NULL,
  `scan_other_location` int(11) NOT NULL DEFAULT '0' COMMENT '0- can not scan on other location, 1- can scan on other location',
  `if_already_issued_message` text,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `detailtext` text,
  `amount` varchar(100) DEFAULT NULL,
  `buttontext` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupen_schedule_messages`
--

CREATE TABLE `coupen_schedule_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `responder_type` int(11) DEFAULT '1',
  `recurring_id` int(11) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci,
  `systemmsg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_on` datetime DEFAULT NULL,
  `is_cron` int(111) NOT NULL DEFAULT '0',
  `sent` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `rotate_number` int(11) NOT NULL DEFAULT '0',
  `msg_type` int(11) NOT NULL DEFAULT '0',
  `mms_text` text COLLATE utf8_unicode_ci,
  `pick_file` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `throttle` int(11) NOT NULL DEFAULT '0',
  `alphasender` tinyint(4) NOT NULL DEFAULT '0',
  `alphasender_input` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendfrom` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `senderemail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(111) NOT NULL DEFAULT '0',
  `coupen_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` int(111) NOT NULL DEFAULT '0',
  `email_template_id` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `before_per_day_limit` int(111) NOT NULL DEFAULT '0',
  `after_per_day_limit` int(111) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_logs`
--

CREATE TABLE `cron_logs` (
  `id` int(111) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `type` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `deviceid` varchar(30) DEFAULT NULL,
  `customer_mac` varchar(30) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `platform_name` varchar(255) DEFAULT NULL,
  `platform_type` varchar(255) DEFAULT NULL,
  `count_visits` int(11) NOT NULL DEFAULT '0',
  `is_email_verified` int(2) DEFAULT '0' COMMENT '0 = not,1 = yes',
  `email_otp` varchar(255) DEFAULT NULL,
  `email_verify_date` datetime DEFAULT NULL,
  `is_unsubscribed` int(2) NOT NULL DEFAULT '0' COMMENT '0 = not, 1 = yes',
  `sms_otp` varchar(255) DEFAULT NULL,
  `unsubscribe_date` datetime DEFAULT NULL,
  `is_notify_register` int(2) NOT NULL DEFAULT '0',
  `responder_id` int(111) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_blocked` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `blocked_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_visits`
--

CREATE TABLE `customer_visits` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `deviceid` varchar(300) DEFAULT NULL,
  `customer_mac` varchar(30) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `platform_name` varchar(255) DEFAULT NULL,
  `platform_type` varchar(255) DEFAULT NULL,
  `sms_otp` varchar(255) DEFAULT NULL,
  `sms_otp_date` date DEFAULT NULL,
  `rst_url` text,
  `thankyou_status` int(2) NOT NULL DEFAULT '0',
  `rating_status` int(2) NOT NULL DEFAULT '0',
  `user_rating_status` int(2) NOT NULL DEFAULT '0',
  `followup_status` int(2) NOT NULL DEFAULT '0',
  `missyou_status` int(2) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `daily_summary`
--

CREATE TABLE `daily_summary` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `filename` text,
  `description` text,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dbconfigs`
--

CREATE TABLE `dbconfigs` (
  `id` int(11) NOT NULL,
  `dbusername` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dbname` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dbpassword` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `device_key` varchar(15) DEFAULT NULL,
  `deviceid` varchar(55) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deviceport` int(5) NOT NULL DEFAULT '0',
  `devicepass` varchar(128) DEFAULT '96wImExVQPmJvd46',
  `devicename` varchar(255) DEFAULT NULL,
  `deviceaddress` varchar(255) DEFAULT NULL,
  `devicedesc` varchar(255) DEFAULT NULL,
  `incloud` varchar(1) NOT NULL DEFAULT 'N',
  `redirect_url` varchar(55) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `locationlat` varchar(255) DEFAULT NULL,
  `locationlng` varchar(255) DEFAULT NULL,
  `devicedescrption` text,
  `firmware_build_date` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `model_or_comment` varchar(255) DEFAULT NULL,
  `offline_for` varchar(255) DEFAULT NULL,
  `port` varchar(255) DEFAULT NULL,
  `ssid` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `uptime` varchar(255) DEFAULT NULL,
  `gateway` varchar(255) DEFAULT NULL,
  `ipv4` varchar(255) DEFAULT NULL,
  `extip` varchar(255) DEFAULT NULL,
  `max_session_length` varchar(255) NOT NULL DEFAULT '900',
  `is_probes_status` int(2) NOT NULL DEFAULT '0',
  `download_speed` varchar(255) DEFAULT '5',
  `upload_speed` varchar(255) DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dex_payment_notifications`
--

CREATE TABLE `dex_payment_notifications` (
  `id` int(111) NOT NULL,
  `customer_token` varchar(255) DEFAULT NULL,
  `paymethod_token` varchar(255) DEFAULT NULL,
  `transaction_id` text,
  `schedule_id` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `full_response` text,
  `message` text,
  `type` tinyint(2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplates`
--

CREATE TABLE `emailtemplates` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `template_type` int(11) NOT NULL DEFAULT '1',
  `template_name` varchar(255) DEFAULT NULL,
  `emailsubject` text,
  `content` longtext,
  `html_content` longtext,
  `image_path` varchar(255) DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `email_attachments`
--

CREATE TABLE `email_attachments` (
  `id` int(11) NOT NULL,
  `schedule_email_id` int(11) NOT NULL,
  `attachment` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(111) NOT NULL,
  `group_email_id` bigint(111) NOT NULL DEFAULT '0',
  `user_id` bigint(111) NOT NULL DEFAULT '0',
  `template_id` int(111) NOT NULL DEFAULT '0',
  `group_id` bigint(111) NOT NULL DEFAULT '0',
  `contact_id` bigint(111) NOT NULL DEFAULT '0',
  `coupon_id` bigint(255) NOT NULL DEFAULT '0',
  `responder_id` bigint(255) NOT NULL DEFAULT '0',
  `responder_type` bigint(255) NOT NULL DEFAULT '0',
  `email_id` text,
  `message_id` text,
  `sendto` varchar(255) DEFAULT NULL,
  `sendfrom` varchar(255) DEFAULT NULL,
  `email_status` text,
  `email_response` text,
  `status_response` text,
  `sent_status` int(11) NOT NULL DEFAULT '0',
  `delivered_status` int(11) NOT NULL DEFAULT '0' COMMENT 'sent / delivered / delivered / ',
  `opened_status` int(11) NOT NULL DEFAULT '0' COMMENT 'opened / open / unique_opened',
  `clicked_status` int(11) NOT NULL DEFAULT '0' COMMENT 'clicked / click',
  `unsubscribed_status` int(11) NOT NULL DEFAULT '0' COMMENT 'unsubscribed / unsubscribe',
  `spam_status` int(11) NOT NULL DEFAULT '0' COMMENT 'spam / complained / spamreport / Abuse',
  `failed_status` int(11) NOT NULL DEFAULT '0' COMMENT 'dropped / hard_bounce / soft_bounce / bounced / failed / rejected / bounce / dropped / blocked',
  `module_type` int(111) NOT NULL DEFAULT '0',
  `module_type_text` varchar(255) DEFAULT NULL,
  `email_service` text,
  `created` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fail_group_logs`
--

CREATE TABLE `fail_group_logs` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` int(111) NOT NULL DEFAULT '0',
  `from_group_id` varchar(255) DEFAULT NULL,
  `to_group_id` varchar(255) DEFAULT NULL,
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '0 = means fail , 1 = means success',
  `type_text` varchar(55) DEFAULT NULL COMMENT '''Fail'',''Success''',
  `comment` text,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menu_group_id` int(11) DEFAULT NULL COMMENT 'Reference of item_menu id',
  `customer_category_id` int(11) DEFAULT NULL COMMENT 'Reference of menuonline customer category',
  `keyword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_type` int(11) NOT NULL DEFAULT '0',
  `wifi_location_id` int(111) DEFAULT NULL,
  `is_wifi_active` int(2) NOT NULL DEFAULT '0',
  `location` int(11) NOT NULL DEFAULT '0',
  `is_send_welcome_sms` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 - send, 1 - not send sms',
  `auto_message` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `system_message` varchar(130) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifmember_message` varchar(160) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'You are already subscribed to this list',
  `sms_type` int(11) DEFAULT NULL COMMENT '1=> sms ,2=>mms',
  `bithday_enable` int(11) NOT NULL DEFAULT '0',
  `image_url` text COLLATE utf8_unicode_ci,
  `active` int(11) DEFAULT '0',
  `totalsubscriber` int(11) NOT NULL DEFAULT '0',
  `notify_signup` int(11) NOT NULL DEFAULT '0',
  `mobile_number_input` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `property_address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `property_price` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `property_bed` tinyint(4) DEFAULT NULL,
  `property_bath` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `property_description` text COLLATE utf8_unicode_ci,
  `property_url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vehicle_year` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vehicle_make` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vehicle_model` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vehicle_mileage` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vehicle_price` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vehicle_description` text COLLATE utf8_unicode_ci,
  `vehicle_url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `double_optin` tinyint(4) NOT NULL DEFAULT '0',
  `group_sms_type` int(2) NOT NULL DEFAULT '1' COMMENT '1 = success sms, 2 = failed sms'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_email_blasts`
--

CREATE TABLE `group_email_blasts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `schedule_email_id` int(111) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `totals` int(11) NOT NULL DEFAULT '0',
  `total_successful_messages` int(11) NOT NULL DEFAULT '0',
  `total_failed_messages` int(11) DEFAULT '0',
  `isdeleted` int(11) NOT NULL DEFAULT '0',
  `responder` int(11) NOT NULL DEFAULT '0',
  `fail_ids` text COLLATE utf8_unicode_ci COMMENT 'log table id store',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_sms_blasts`
--

CREATE TABLE `group_sms_blasts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `schedule_sms_id` int(111) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `totals` int(11) NOT NULL DEFAULT '0',
  `total_successful_messages` int(11) NOT NULL DEFAULT '0',
  `total_failed_messages` int(11) DEFAULT '0',
  `isdeleted` int(11) NOT NULL DEFAULT '0',
  `responder` int(11) NOT NULL DEFAULT '0',
  `fail_ids` text COLLATE utf8_unicode_ci COMMENT 'log table id store',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instant_payment_notifications`
--

CREATE TABLE `instant_payment_notifications` (
  `id` char(36) NOT NULL,
  `notify_version` varchar(64) DEFAULT NULL COMMENT 'IPN Version Number',
  `verify_sign` varchar(127) DEFAULT NULL COMMENT 'Encrypted string used to verify the authenticityof the tansaction',
  `test_ipn` int(11) DEFAULT NULL,
  `address_city` varchar(40) DEFAULT NULL COMMENT 'City of customers address',
  `address_country` varchar(64) DEFAULT NULL COMMENT 'Country of customers address',
  `address_country_code` varchar(2) DEFAULT NULL COMMENT 'Two character ISO 3166 country code',
  `address_name` varchar(128) DEFAULT NULL COMMENT 'Name used with address (included when customer provides a Gift address)',
  `address_state` varchar(40) DEFAULT NULL COMMENT 'State of customer address',
  `address_status` varchar(20) DEFAULT NULL COMMENT 'confirmed/unconfirmed',
  `address_street` varchar(200) DEFAULT NULL COMMENT 'Customer''s street address',
  `address_zip` varchar(20) DEFAULT NULL COMMENT 'Zip code of customer''s address',
  `first_name` varchar(64) DEFAULT NULL COMMENT 'Customer''s first name',
  `last_name` varchar(64) DEFAULT NULL COMMENT 'Customer''s last name',
  `payer_business_name` varchar(127) DEFAULT NULL COMMENT 'Customer''s company name, if customer represents a business',
  `payer_email` varchar(127) DEFAULT NULL COMMENT 'Customer''s primary email address. Use this email to provide any credits',
  `payer_id` varchar(13) DEFAULT NULL COMMENT 'Unique customer ID.',
  `payer_status` varchar(20) DEFAULT NULL COMMENT 'verified/unverified',
  `contact_phone` varchar(20) DEFAULT NULL COMMENT 'Customer''s telephone number.',
  `residence_country` varchar(2) DEFAULT NULL COMMENT 'Two-Character ISO 3166 country code',
  `business` varchar(127) DEFAULT NULL COMMENT 'Email address or account ID of the payment recipient (that is, the merchant). Equivalent to the values of receiver_email (If payment is sent to primary account) and business set in the Website Payment HTML.',
  `item_name` varchar(127) DEFAULT NULL COMMENT 'Item name as passed by you, the merchant. Or, if not passed by you, as entered by your customer. If this is a shopping cart transaction, Paypal will append the number of the item (e.g., item_name_1,item_name_2, and so forth).',
  `item_number` varchar(127) DEFAULT NULL COMMENT 'Pass-through variable for you to track purchases. It will get passed back to you at the completion of the payment. If omitted, no variable will be passed back to you.',
  `item_number1` varchar(55) DEFAULT NULL,
  `item_number2` varchar(55) DEFAULT NULL,
  `item_number3` varchar(55) DEFAULT NULL,
  `item_number4` varchar(55) DEFAULT NULL,
  `item_number5` varchar(55) DEFAULT NULL,
  `quantity` varchar(127) DEFAULT NULL COMMENT 'Quantity as entered by your customer or as passed by you, the merchant. If this is a shopping cart transaction, PayPal appends the number of the item (e.g., quantity1,quantity2).',
  `receiver_email` varchar(127) DEFAULT NULL COMMENT 'Primary email address of the payment recipient (that is, the merchant). If the payment is sent to a non-primary email address on your PayPal account, the receiver_email is still your primary email.',
  `receiver_id` varchar(13) DEFAULT NULL COMMENT 'Unique account ID of the payment recipient (i.e., the merchant). This is the same as the recipients referral ID.',
  `custom` varchar(255) DEFAULT NULL COMMENT 'Custom value as passed by you, the merchant. These are pass-through variables that are never presented to your customer.',
  `table_type` varchar(255) DEFAULT NULL,
  `invoice` varchar(127) DEFAULT NULL COMMENT 'Pass through variable you can use to identify your invoice number for this purchase. If omitted, no variable is passed back.',
  `memo` varchar(255) DEFAULT NULL COMMENT 'Memo as entered by your customer in PayPal Website Payments note field.',
  `option_name1` varchar(64) DEFAULT NULL COMMENT 'Option name 1 as requested by you',
  `option_name2` varchar(64) DEFAULT NULL COMMENT 'Option 2 name as requested by you',
  `option_selection1` varchar(200) DEFAULT NULL COMMENT 'Option 1 choice as entered by your customer',
  `option_selection2` varchar(200) DEFAULT NULL COMMENT 'Option 2 choice as entered by your customer',
  `tax` decimal(10,2) DEFAULT NULL COMMENT 'Amount of tax charged on payment',
  `auth_id` varchar(19) DEFAULT NULL COMMENT 'Authorization identification number',
  `auth_exp` varchar(28) DEFAULT NULL COMMENT 'Authorization expiration date and time, in the following format: HH:MM:SS DD Mmm YY, YYYY PST',
  `auth_amount` int(11) DEFAULT NULL COMMENT 'Authorization amount',
  `auth_status` varchar(20) DEFAULT NULL COMMENT 'Status of authorization',
  `num_cart_items` int(11) DEFAULT NULL COMMENT 'If this is a PayPal shopping cart transaction, number of items in the cart',
  `parent_txn_id` varchar(19) DEFAULT NULL COMMENT 'In the case of a refund, reversal, or cancelled reversal, this variable contains the txn_id of the original transaction, while txn_id contains a new ID for the new transaction.',
  `payment_date` varchar(28) DEFAULT NULL COMMENT 'Time/date stamp generated by PayPal, in the following format: HH:MM:SS DD Mmm YY, YYYY PST',
  `payment_status` varchar(20) DEFAULT NULL COMMENT 'Payment status of the payment',
  `payment_type` varchar(10) DEFAULT NULL COMMENT 'echeck/instant',
  `pending_reason` varchar(20) DEFAULT NULL COMMENT 'This variable is only set if payment_status=pending',
  `reason_code` varchar(20) DEFAULT NULL COMMENT 'This variable is only set if payment_status=reversed',
  `remaining_settle` int(11) DEFAULT NULL COMMENT 'Remaining amount that can be captured with Authorization and Capture',
  `shipping_method` varchar(64) DEFAULT NULL COMMENT 'The name of a shipping method from the shipping calculations section of the merchants account profile. The buyer selected the named shipping method for this transaction',
  `shipping` decimal(10,2) DEFAULT NULL COMMENT 'Shipping charges associated with this transaction. Format unsigned, no currency symbol, two decimal places',
  `transaction_entity` varchar(20) DEFAULT NULL COMMENT 'Authorization and capture transaction entity',
  `txn_id` varchar(19) DEFAULT NULL COMMENT 'A unique transaction ID generated by PayPal',
  `txn_type` varchar(255) DEFAULT NULL COMMENT 'cart/express_checkout/send-money/virtual-terminal/web-accept',
  `exchange_rate` decimal(10,2) DEFAULT NULL COMMENT 'Exchange rate used if a currency conversion occured',
  `mc_currency` varchar(3) DEFAULT NULL COMMENT 'Three character country code. For payment IPN notifications, this is the currency of the payment, for non-payment subscription IPN notifications, this is the currency of the subscription.',
  `mc_fee` decimal(10,2) DEFAULT NULL COMMENT 'Transaction fee associated with the payment, mc_gross minus mc_fee equals the amount deposited into the receiver_email account. Equivalent to payment_fee for USD payments. If this amount is negative, it signifies a refund or reversal, and either ofthose p',
  `mc_gross` decimal(10,2) DEFAULT NULL COMMENT 'Full amount of the customer''s payment',
  `mc_handling` decimal(10,2) DEFAULT NULL COMMENT 'Total handling charge associated with the transaction',
  `mc_shipping` decimal(10,2) DEFAULT NULL COMMENT 'Total shipping amount associated with the transaction',
  `payment_fee` decimal(10,2) DEFAULT NULL COMMENT 'USD transaction fee associated with the payment',
  `payment_gross` decimal(10,2) DEFAULT NULL COMMENT 'Full USD amount of the customers payment transaction, before payment_fee is subtracted',
  `settle_amount` decimal(10,2) DEFAULT NULL COMMENT 'Amount that is deposited into the account''s primary balance after a currency conversion',
  `settle_currency` varchar(3) DEFAULT NULL COMMENT 'Currency of settle amount. Three digit currency code',
  `auction_buyer_id` varchar(64) DEFAULT NULL COMMENT 'The customer''s auction ID.',
  `auction_closing_date` varchar(28) DEFAULT NULL COMMENT 'The auction''s close date. In the format: HH:MM:SS DD Mmm YY, YYYY PSD',
  `auction_multi_item` int(11) DEFAULT NULL COMMENT 'The number of items purchased in multi-item auction payments',
  `for_auction` varchar(10) DEFAULT NULL COMMENT 'This is an auction payment - payments made using Pay for eBay Items or Smart Logos - as well as send money/money request payments with the type eBay items or Auction Goods(non-eBay)',
  `subscr_date` varchar(28) DEFAULT NULL COMMENT 'Start date or cancellation date depending on whether txn_type is subcr_signup or subscr_cancel',
  `subscr_effective` varchar(28) DEFAULT NULL COMMENT 'Date when a subscription modification becomes effective',
  `period1` varchar(10) DEFAULT NULL COMMENT '(Optional) Trial subscription interval in days, weeks, months, years (example a 4 day interval is 4 D',
  `period2` varchar(10) DEFAULT NULL COMMENT '(Optional) Trial period',
  `period3` varchar(10) DEFAULT NULL COMMENT 'Regular subscription interval in days, weeks, months, years',
  `amount1` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for Trial period 1 for USD',
  `amount2` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for Trial period 2 for USD',
  `amount3` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for regular subscription  period 1 for USD',
  `mc_amount1` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for trial period 1 regardless of currency',
  `mc_amount2` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for trial period 2 regardless of currency',
  `mc_amount3` decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for regular subscription period regardless of currency',
  `recurring` varchar(1) DEFAULT NULL COMMENT 'Indicates whether rate recurs (1 is yes, blank is no)',
  `reattempt` varchar(1) DEFAULT NULL COMMENT 'Indicates whether reattempts should occur on payment failure (1 is yes, blank is no)',
  `retry_at` varchar(255) DEFAULT NULL COMMENT 'Date PayPal will retry a failed subscription payment',
  `recur_times` int(11) DEFAULT NULL COMMENT 'The number of payment installations that will occur at the regular rate',
  `username` varchar(255) DEFAULT NULL COMMENT '(Optional) Username generated by PayPal and given to subscriber to access the subscription',
  `password` varchar(255) DEFAULT NULL COMMENT '(Optional) Password generated by PayPal and given to subscriber to access the subscription (Encrypted)',
  `subscr_id` varchar(255) DEFAULT NULL COMMENT 'ID generated by PayPal for the subscriber',
  `case_id` varchar(255) DEFAULT NULL COMMENT 'Case identification number',
  `case_type` varchar(255) DEFAULT NULL COMMENT 'complaint/chargeback',
  `case_creation_date` varchar(255) DEFAULT NULL COMMENT 'Date/Time the case was registered',
  `payment_cycle` varchar(255) DEFAULT NULL,
  `next_payment_date` varchar(255) DEFAULT NULL,
  `initial_payment_amount` float NOT NULL DEFAULT '0',
  `rp_invoice_id` text,
  `currency_code` varchar(255) DEFAULT NULL,
  `time_created` varchar(255) DEFAULT NULL,
  `period_type` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `amount_per_cycle` float DEFAULT '0',
  `profile_status` varchar(255) DEFAULT NULL,
  `charset` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT '0',
  `outstanding_balance` float NOT NULL DEFAULT '0',
  `recurring_payment_id` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `ipn_track_id` varchar(255) DEFAULT NULL,
  `full_response` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `deal_id` int(111) NOT NULL DEFAULT '0' COMMENT 'Only for WIFI Company Customer',
  `txnid` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `package_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `pdf_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `txnid` text COLLATE utf8_unicode_ci,
  `invoice_id` int(111) NOT NULL DEFAULT '0',
  `name` text COLLATE utf8_unicode_ci,
  `amount` float DEFAULT '0',
  `created` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applies`
--

CREATE TABLE `job_applies` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `job_unique_id` varchar(255) DEFAULT NULL,
  `job_id` int(111) NOT NULL DEFAULT '0',
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `countrycodetel` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `working_hours` varchar(255) DEFAULT NULL,
  `upload_resume` text,
  `job_position` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `job_requirements`
--

CREATE TABLE `job_requirements` (
  `id` bigint(111) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `post_name` varchar(255) DEFAULT NULL,
  `optin_group` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `is_uploadresume` int(11) NOT NULL DEFAULT '0',
  `is_firstname` int(11) NOT NULL DEFAULT '0',
  `is_lastname` int(11) NOT NULL DEFAULT '0',
  `is_phonenumber` int(11) NOT NULL DEFAULT '0',
  `is_working_hours` int(11) NOT NULL DEFAULT '0',
  `is_position` int(11) DEFAULT '0',
  `is_terms` int(11) NOT NULL DEFAULT '0',
  `terms` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kiosks`
--

CREATE TABLE `kiosks` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `loyalty_id` int(11) NOT NULL DEFAULT '0',
  `countrycodetel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_id` int(111) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `background_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `style` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `textheader` text COLLATE utf8_unicode_ci,
  `alignment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `font` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fontsize` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colortheme` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primarycolor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secondarycolor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `styleB` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `styleI` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `styleU` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `joinbuttons` int(11) DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `punchcard` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `checkpoints` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `joinbutton` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checkin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mypoints` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `buttoncolor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `textcolor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keypad_button_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keypad_text_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body_text` text COLLATE utf8_unicode_ci,
  `url_link` text COLLATE utf8_unicode_ci,
  `url_link_styleB` int(11) NOT NULL DEFAULT '0' COMMENT '0 = no,1 = yes',
  `url_link_styleI` int(11) NOT NULL DEFAULT '0' COMMENT '0 = no,1 = yes',
  `url_link_styleU` int(11) NOT NULL DEFAULT '0' COMMENT '0 = no,1 = yes',
  `url_link_color` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url_link_font` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url_link_size` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bottom_text_alignment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body_text_font` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body_text_size` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bottom_text_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body_text_styleB` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `body_text_styleI` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `body_text_styleU` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `firstname` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `lastname` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `email` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `dob` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `birthdate_message` text COLLATE utf8_unicode_ci,
  `show_termscondition` int(11) NOT NULL DEFAULT '0',
  `terms_conditions_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL COMMENT '0=>No,1=>Yes',
  `image_kiosk` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kiosks_templates`
--

CREATE TABLE `kiosks_templates` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `loyalty_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `background_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `style` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `textheader` text COLLATE utf8_unicode_ci NOT NULL,
  `alignment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `font` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fontsize` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `styleB` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `styleI` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `styleU` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `joinbuttons` int(11) DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `punchcard` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `checkpoints` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `joinbutton` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checkin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mypoints` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `buttoncolor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `textcolor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keypad_button_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keypad_text_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bottom_text` text COLLATE utf8_unicode_ci,
  `bottom_text_alignment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bottom_text_font` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bottom_text_size` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bottom_text_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bottom_text_styleB` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `bottom_text_styleI` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `bottom_text_styleU` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `firstname` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `lastname` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `email` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `dob` int(11) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `show_termscondition` int(11) DEFAULT '0',
  `terms_conditions_id` int(11) NOT NULL DEFAULT '0',
  `created_by` int(111) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL COMMENT '0=>No,1=>Yes',
  `image_kiosk` text COLLATE utf8_unicode_ci,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0- created by admin, 1 - created by users',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `location_name` varchar(50) DEFAULT NULL,
  `phone` varchar(55) DEFAULT NULL,
  `email_address` varchar(55) DEFAULT NULL,
  `location_address` varchar(500) DEFAULT NULL,
  `locationlat` varchar(255) DEFAULT NULL,
  `locationlng` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `reset_rights_cashier_id` int(11) NOT NULL DEFAULT '0',
  `is_leader_board_on` tinyint(2) DEFAULT '0',
  `city` varchar(55) DEFAULT NULL,
  `state` varchar(55) DEFAULT NULL,
  `zip` varchar(55) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_push` varchar(255) DEFAULT NULL,
  `session_time_out` varchar(255) DEFAULT NULL,
  `session_lenght_min` varchar(255) DEFAULT NULL,
  `session_lenght_max` varchar(255) DEFAULT NULL,
  `send_sms_location` varchar(255) DEFAULT NULL,
  `session_sms_time` varchar(255) DEFAULT NULL,
  `session_sms_user_time` varchar(255) DEFAULT NULL,
  `send_email_location` varchar(255) DEFAULT NULL,
  `session_email_time` varchar(255) DEFAULT NULL,
  `session_email_user_time` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `group_sms_id` int(11) NOT NULL DEFAULT '0',
  `sms_id` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `group_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `coupon_id` int(111) NOT NULL DEFAULT '0',
  `responder_id` int(111) NOT NULL DEFAULT '0',
  `responder_type` int(11) NOT NULL DEFAULT '0',
  `sendfrom` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_to_sms_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text_message` text COLLATE utf8_unicode_ci,
  `image_url` text COLLATE utf8_unicode_ci,
  `voice_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `call_duration` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `route` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inbox',
  `msg_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `inbox_type` tinyint(4) NOT NULL DEFAULT '1',
  `sms_status` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `error_message` text COLLATE utf8_unicode_ci,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `msgsound` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `module_type` tinyint(10) NOT NULL DEFAULT '0',
  `module_type_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `before_credit` float DEFAULT NULL,
  `after_credit` float DEFAULT NULL,
  `is_credit_deduct` int(11) NOT NULL DEFAULT '0',
  `is_call_status_trigger` int(2) NOT NULL DEFAULT '0',
  `api_type` int(2) NOT NULL DEFAULT '0' COMMENT '0 means admin twillio, 8 means admin telnyx, 9 means user twillio, 10 means user telnyx',
  `api_company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Admin, User Twillio, User Telnyx',
  `sms_response` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_post_requests`
--

CREATE TABLE `log_post_requests` (
  `id` int(111) NOT NULL,
  `request` text,
  `response` text,
  `created` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menuonline_group`
--

CREATE TABLE `menuonline_group` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_category_id` int(11) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menuonline_responders`
--

CREATE TABLE `menuonline_responders` (
  `id` int(111) NOT NULL,
  `user_id` int(111) DEFAULT NULL,
  `location_id` int(111) DEFAULT NULL,
  `menu_id` int(111) DEFAULT NULL,
  `contact_id` int(111) DEFAULT NULL,
  `contact_group_id` int(111) DEFAULT NULL,
  `group_id` int(111) DEFAULT NULL,
  `responder_id` int(111) DEFAULT NULL,
  `resp_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 = Thank you, 2 = Thank you + Rating, 3 = Follow up, 4 = miss you',
  `responder_type` varchar(255) DEFAULT NULL COMMENT 'New Order',
  `responder_type_int` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 = New Order',
  `is_triggered` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_pages`
--

CREATE TABLE `mobile_pages` (
  `id` int(11) NOT NULL,
  `user_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `header_logo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `headerfont_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_fonts` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_font_size` int(11) NOT NULL DEFAULT '0',
  `ipad_message_text_size` int(11) NOT NULL DEFAULT '0',
  `ipad_message_font` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer_font` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer_font_size` int(11) NOT NULL DEFAULT '0',
  `description_font` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description_font_size` int(11) NOT NULL DEFAULT '0',
  `bodybg_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footertext_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footertext` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map_url` text COLLATE utf8_unicode_ci,
  `image_path` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipad-message` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_text` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offertxt_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discriptiontxt_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `messagetxt_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tw_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ins_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bodytx_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bodybtn_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_bg_image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bodybg_image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_ipad_message` text COLLATE utf8_unicode_ci,
  `offer_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0= not active, 1=Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_templates`
--

CREATE TABLE `mobile_templates` (
  `id` int(11) NOT NULL,
  `user_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `header_logo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `headerfont_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_fonts` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_font_size` int(11) NOT NULL DEFAULT '0',
  `ipad_message_text_size` int(11) NOT NULL DEFAULT '0',
  `ipad_message_font` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer_font` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer_font_size` int(11) NOT NULL DEFAULT '0',
  `description_font` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description_font_size` int(11) DEFAULT '0',
  `bodybg_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footertext_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footertext` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map_url` text COLLATE utf8_unicode_ci,
  `image_path` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipad-message` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_text` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offertxt_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discriptiontxt_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `messagetxt_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tw_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ins_link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bodytx_color` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bodybtn_color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header_bg_image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bodybg_image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_ipad_message` text COLLATE utf8_unicode_ci,
  `offer_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '0= not active, 1=Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_number_packages`
--

CREATE TABLE `monthly_number_packages` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `package_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `total_secondary_numbers` int(11) NOT NULL DEFAULT '0',
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_packages`
--

CREATE TABLE `monthly_packages` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `package_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `text_messages_credit` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `voice_messages_credit` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `user_country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credits_carryover` tinyint(4) NOT NULL DEFAULT '1',
  `autoresponders` tinyint(4) NOT NULL DEFAULT '1',
  `importcontacts` tinyint(4) NOT NULL DEFAULT '1',
  `shortlinks` tinyint(4) NOT NULL DEFAULT '1',
  `voicebroadcast` tinyint(4) NOT NULL DEFAULT '1',
  `polls` tinyint(4) NOT NULL DEFAULT '1',
  `contests` tinyint(4) NOT NULL DEFAULT '1',
  `loyaltyprograms` tinyint(4) NOT NULL DEFAULT '1',
  `kioskbuilder` tinyint(4) NOT NULL DEFAULT '1',
  `birthdaywishes` tinyint(4) NOT NULL DEFAULT '1',
  `mobilepagebuilder` tinyint(4) NOT NULL DEFAULT '1',
  `webwidgets` tinyint(4) NOT NULL DEFAULT '1',
  `smschat` tinyint(4) NOT NULL DEFAULT '1',
  `qrcodes` tinyint(4) NOT NULL DEFAULT '1',
  `calendarscheduler` tinyint(4) NOT NULL DEFAULT '1',
  `appointments` tinyint(4) NOT NULL DEFAULT '1',
  `groups` tinyint(4) NOT NULL DEFAULT '1',
  `contactlist` tinyint(4) NOT NULL DEFAULT '1',
  `sendsms` tinyint(4) NOT NULL DEFAULT '1',
  `affiliates` tinyint(4) NOT NULL DEFAULT '1',
  `getnumbers` tinyint(4) NOT NULL DEFAULT '1',
  `location` tinyint(4) NOT NULL DEFAULT '0',
  `groupt_message_queue` tinyint(4) NOT NULL DEFAULT '0',
  `contact_message_queue` tinyint(4) NOT NULL DEFAULT '0',
  `appointments_settings` tinyint(4) NOT NULL DEFAULT '0',
  `appointments_calendar` tinyint(4) NOT NULL DEFAULT '0',
  `import_appointments` tinyint(4) NOT NULL DEFAULT '0',
  `wallet_options` tinyint(4) NOT NULL DEFAULT '0',
  `cashier` tinyint(4) NOT NULL DEFAULT '0',
  `coupon` tinyint(4) NOT NULL DEFAULT '0',
  `kiosk_reports` tinyint(4) NOT NULL DEFAULT '0',
  `sub_unsub_reports` tinyint(4) NOT NULL DEFAULT '0',
  `character_checker` tinyint(4) NOT NULL DEFAULT '0',
  `logs` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_pages`
--

CREATE TABLE `offer_pages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bg_color` varchar(255) DEFAULT NULL,
  `title_1` text,
  `title_2` text,
  `title_3` text,
  `numbers` varchar(255) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `optiona` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `optionb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `autorsponder_message` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `credit` int(11) NOT NULL DEFAULT '0',
  `type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paypal_items`
--

CREATE TABLE `paypal_items` (
  `id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `instant_payment_notification_id` varchar(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_number` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mc_gross` float(10,2) DEFAULT NULL,
  `mc_shipping` float(10,2) DEFAULT NULL,
  `mc_handling` float(10,2) DEFAULT NULL,
  `tax` float(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(111) NOT NULL,
  `plan_title` varchar(255) DEFAULT NULL,
  `plan_amount` float DEFAULT NULL,
  `setup_fee` int(11) NOT NULL DEFAULT '0',
  `pickup_app_frontend` int(11) NOT NULL DEFAULT '0',
  `web_pos_system` int(11) NOT NULL DEFAULT '0',
  `offline_mobile_pos` int(11) NOT NULL DEFAULT '0',
  `witress_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_display` int(11) NOT NULL DEFAULT '0',
  `pos_equipment` varchar(255) DEFAULT NULL,
  `booking_reservation` int(11) NOT NULL DEFAULT '0',
  `queue_management` int(11) DEFAULT '0',
  `digital_signage` varchar(255) DEFAULT NULL,
  `seo_video_reviews` int(11) NOT NULL DEFAULT '0',
  `additional_screen` int(11) NOT NULL DEFAULT '0',
  `money_back_guarantee` varchar(255) DEFAULT NULL,
  `onboarding_training` varchar(255) DEFAULT NULL,
  `kiosk_builder` int(11) DEFAULT NULL,
  `cashier_panel` int(11) DEFAULT NULL,
  `sms_loyalty_program` int(11) DEFAULT NULL,
  `access_to_data` int(11) DEFAULT NULL,
  `bulk_sms` int(11) DEFAULT NULL,
  `virtual_wallet` int(11) DEFAULT NULL,
  `automated_marketing` int(11) DEFAULT NULL,
  `scheduled_sms` int(11) DEFAULT NULL,
  `mobile_opt_in_unlimited_keywords` int(11) DEFAULT NULL,
  `qr_code_creation` int(11) DEFAULT NULL,
  `voice_broadcast` int(11) DEFAULT NULL,
  `two_way_sms_chat` int(11) DEFAULT NULL,
  `appointment_reminder` int(11) DEFAULT NULL,
  `web_sign_up_widgets` int(11) DEFAULT NULL,
  `smart_trackable_coupon` int(11) DEFAULT NULL,
  `smart_wifi` int(11) DEFAULT NULL,
  `wifi_deepanalytics` int(2) NOT NULL DEFAULT '0',
  `equipment` varchar(255) DEFAULT NULL,
  `cashier_leader_board` int(2) NOT NULL DEFAULT '0',
  `sms_inclusion` varchar(255) DEFAULT NULL,
  `voice_inclusion` varchar(255) DEFAULT NULL,
  `is_credit_carry_forward` int(11) NOT NULL DEFAULT '0',
  `job_requirements` int(11) NOT NULL DEFAULT '0',
  `waivers` int(11) NOT NULL DEFAULT '0',
  `reputation` int(11) NOT NULL DEFAULT '0',
  `signage` int(2) NOT NULL DEFAULT '0',
  `add_on_per_number_month_for_additional_numbers` varchar(255) DEFAULT NULL,
  `addon` varchar(255) DEFAULT NULL,
  `addon_voice` int(111) NOT NULL DEFAULT '0',
  `additional_number` varchar(255) DEFAULT NULL,
  `contract` varchar(255) DEFAULT NULL,
  `trial_days` int(11) NOT NULL DEFAULT '0',
  `trial_type` varchar(55) DEFAULT NULL COMMENT 'days, month',
  `plan_type` int(11) NOT NULL DEFAULT '0' COMMENT '0- predefine plan, 1- custom plan',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `deal_id` int(111) NOT NULL DEFAULT '0',
  `agreement_id` int(111) NOT NULL DEFAULT '0',
  `setup_programming` varchar(255) DEFAULT '0',
  `hardware_integration` varchar(255) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plan_users`
--

CREATE TABLE `plan_users` (
  `id` int(111) NOT NULL,
  `plan_id` int(111) NOT NULL DEFAULT '0',
  `user_id` int(111) NOT NULL DEFAULT '0',
  `username` varchar(255) DEFAULT NULL,
  `plan_title` varchar(255) DEFAULT NULL,
  `plan_amount` float DEFAULT NULL,
  `setup_fee` float NOT NULL DEFAULT '0',
  `pickup_app_frontend` int(11) NOT NULL DEFAULT '0',
  `web_pos_system` int(11) NOT NULL DEFAULT '0',
  `offline_mobile_pos` int(11) NOT NULL DEFAULT '0',
  `witress_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_display` int(11) NOT NULL DEFAULT '0',
  `pos_equipment` int(11) NOT NULL DEFAULT '0',
  `booking_reservation` int(11) NOT NULL DEFAULT '0',
  `queue_management` int(11) NOT NULL DEFAULT '0',
  `seo_video_reviews` int(11) NOT NULL DEFAULT '0',
  `kiosk_builder` varchar(255) DEFAULT NULL,
  `cashier_panel` varchar(255) DEFAULT NULL,
  `sms_loyalty_program` varchar(255) DEFAULT NULL,
  `access_to_data` varchar(255) DEFAULT NULL,
  `bulk_sms` varchar(255) DEFAULT NULL,
  `virtual_wallet` varchar(255) DEFAULT NULL,
  `automated_marketing` varchar(255) DEFAULT NULL,
  `scheduled_sms` varchar(255) DEFAULT NULL,
  `mobile_opt_in_unlimited_keywords` varchar(255) DEFAULT NULL,
  `qr_code_creation` varchar(255) DEFAULT NULL,
  `voice_broadcast` varchar(255) DEFAULT NULL,
  `two_way_sms_chat` varchar(255) DEFAULT NULL,
  `appointment_reminder` varchar(255) DEFAULT NULL,
  `web_sign_up_widgets` varchar(255) DEFAULT NULL,
  `smart_trackable_coupon` varchar(255) DEFAULT NULL,
  `smart_wifi` varchar(255) DEFAULT NULL,
  `wifi_deepanalytics` varchar(255) DEFAULT NULL,
  `equipment` varchar(255) DEFAULT NULL,
  `cashier_leader_board` int(2) NOT NULL DEFAULT '0',
  `sms_inclusion` varchar(255) DEFAULT NULL,
  `voice_inclusion` varchar(255) DEFAULT NULL,
  `is_credit_carry_forward` int(11) NOT NULL DEFAULT '0',
  `job_requirements` int(11) NOT NULL DEFAULT '0',
  `waivers` int(11) NOT NULL DEFAULT '0',
  `reputation` int(11) NOT NULL DEFAULT '0',
  `signage` int(2) NOT NULL DEFAULT '0',
  `add_on_per_number_month_for_additional_numbers` varchar(255) DEFAULT NULL,
  `addon` varchar(255) DEFAULT '0',
  `addon_voice` varchar(255) DEFAULT '0',
  `additional_number` varchar(255) DEFAULT NULL,
  `contract` varchar(255) DEFAULT NULL,
  `trial_days` int(11) NOT NULL DEFAULT '0',
  `is_active` int(11) NOT NULL DEFAULT '0' COMMENT '0 - active, 1 - inactive ',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qrcods`
--

CREATE TABLE `qrcods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `optin_group` int(111) NOT NULL DEFAULT '0',
  `keyword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `question` text COLLATE utf8_unicode_ci,
  `active` int(11) NOT NULL DEFAULT '0',
  `autoreply_message` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'new_user_id',
  `referred_by` int(11) NOT NULL DEFAULT '0' COMMENT 'referred_by',
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `paid_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-unpaid,1-paid',
  `type` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `account_activated` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reputations`
--

CREATE TABLE `reputations` (
  `id` bigint(111) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `user_id` int(111) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `location_id` varchar(255) DEFAULT NULL,
  `optin_groupid` int(111) DEFAULT NULL,
  `reputations_name` varchar(255) DEFAULT NULL,
  `theme_color` text,
  `stepone_title_one` text,
  `stepone_title_two` text,
  `stepone_title_three` text,
  `stepone_button_title` text,
  `countrycode` int(11) DEFAULT NULL,
  `is_sendsms` int(11) NOT NULL DEFAULT '0',
  `question_a` text,
  `question_b` text,
  `question_c` text,
  `question_d` text,
  `question_title_a` varchar(255) DEFAULT NULL,
  `question_title_b` varchar(255) DEFAULT NULL,
  `question_title_c` varchar(255) DEFAULT NULL,
  `question_title_d` varchar(255) DEFAULT NULL,
  `is_firstname` int(11) DEFAULT '0',
  `is_email` int(11) NOT NULL DEFAULT '0',
  `message` text,
  `respond_message` text,
  `auto_message` text,
  `feedback_title` text,
  `steptwo_btnone` text,
  `stepthree_titleone` text,
  `stepthree_titletwo` text,
  `stepthree_quesone` text,
  `stepthree_questwo` text,
  `icon_type` varchar(111) NOT NULL DEFAULT '0',
  `feedback_ques` int(11) NOT NULL DEFAULT '0',
  `stepfour_fieldone` text,
  `stepfour_fieldtwo` text,
  `stepfour_fieldthree` varchar(255) DEFAULT NULL,
  `stepfour_fieldfour` varchar(255) DEFAULT NULL,
  `stepfour_additional_fields` text,
  `stepfour_btnone` text,
  `stepfour_btntwo` text,
  `stepfive_titleone` text,
  `stepfive_titletwo` text,
  `stepfive_titlethree` text,
  `reputation_logo` varchar(255) DEFAULT NULL,
  `is_notify_manager` int(11) NOT NULL DEFAULT '0',
  `countrycode_manager` varchar(255) DEFAULT NULL,
  `manager_number` varchar(255) DEFAULT NULL,
  `manager_email` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `last_seen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reputation_feedbacks`
--

CREATE TABLE `reputation_feedbacks` (
  `id` int(11) NOT NULL,
  `unique_code` text,
  `reputations_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `location_id` varchar(255) DEFAULT NULL,
  `optin_groupid` int(11) DEFAULT NULL,
  `answer_a` int(11) DEFAULT NULL,
  `answer_b` int(11) DEFAULT NULL,
  `answer_c` int(11) DEFAULT NULL,
  `answer_d` int(11) DEFAULT NULL,
  `description` text,
  `response_back` int(11) DEFAULT NULL,
  `received_offer` int(11) DEFAULT NULL,
  `customer_number` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `manager_comment` text,
  `is_link_open` int(11) DEFAULT '0',
  `additional_fields` text,
  `viewfeedback_longurl` text,
  `viewfeedback_shorturl` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reset_leader_boards`
--

CREATE TABLE `reset_leader_boards` (
  `id` int(111) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL DEFAULT '0',
  `reset_date` datetime DEFAULT NULL,
  `reset_by` int(11) NOT NULL DEFAULT '0',
  `reset_by_utype` varchar(55) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `responders`
--

CREATE TABLE `responders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `responder_type` int(11) DEFAULT '1' COMMENT '1="sms",2="email"',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `isassigncoupon` int(11) NOT NULL DEFAULT '0',
  `assign_coupon_id` int(111) DEFAULT NULL,
  `signupcouponlimit` int(111) NOT NULL DEFAULT '0',
  `once_againsend` int(11) NOT NULL DEFAULT '0',
  `noofshare` int(111) NOT NULL DEFAULT '0',
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `option1_message` text COLLATE utf8_unicode_ci,
  `option2_message` text COLLATE utf8_unicode_ci,
  `inform_name` text COLLATE utf8_unicode_ci,
  `countrycodetel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inform_phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inform_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inform_message` text COLLATE utf8_unicode_ci,
  `email_template_id` int(11) DEFAULT NULL,
  `option3_message` text COLLATE utf8_unicode_ci,
  `option4_message` text COLLATE utf8_unicode_ci,
  `option5_message` text COLLATE utf8_unicode_ci,
  `review_url` text COLLATE utf8_unicode_ci,
  `short_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rating_ques_1` text COLLATE utf8_unicode_ci,
  `rating_ques_2` text COLLATE utf8_unicode_ci,
  `rating_ques_3` text COLLATE utf8_unicode_ci,
  `systemmsg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `ishour` tinyint(4) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0',
  `wifi_trigger` int(11) NOT NULL DEFAULT '0',
  `waiver_trigger` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 = yes',
  `menuonline_neworder_trigger` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `thankyou_qrsignup_trigger` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1  yes',
  `sent` int(11) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes',
  `sms_type` int(11) NOT NULL DEFAULT '0' COMMENT '1=>sms , 2=>mms',
  `image_url` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `responder_probes`
--

CREATE TABLE `responder_probes` (
  `id` int(111) NOT NULL,
  `user_id` int(111) DEFAULT NULL,
  `customer_id` int(111) DEFAULT NULL,
  `customer_mac` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `device_id` int(111) DEFAULT NULL,
  `device_mac` varchar(255) DEFAULT NULL,
  `location_id` int(111) DEFAULT NULL,
  `responder_id` int(111) DEFAULT NULL,
  `responder_type` int(2) NOT NULL DEFAULT '0' COMMENT '1 = SMS, 2 = Email',
  `is_triggered` int(2) NOT NULL DEFAULT '0' COMMENT '0 = Not triggered, 1 = triggerd',
  `probes_id` int(111) DEFAULT NULL COMMENT 'This is probes table record id',
  `probes_date` date DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_emails`
--

CREATE TABLE `schedule_emails` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `template_id` int(111) DEFAULT '0',
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_on` datetime DEFAULT NULL,
  `sent` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `throttle` int(11) NOT NULL DEFAULT '0',
  `is_immediately` int(11) NOT NULL DEFAULT '0',
  `duplicate_contacts` text COLLATE utf8_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_email_groups`
--

CREATE TABLE `schedule_email_groups` (
  `id` int(11) NOT NULL,
  `schedule_email_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_messages`
--

CREATE TABLE `schedule_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `recurring_id` int(11) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci,
  `systemmsg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_on` datetime DEFAULT NULL,
  `sent` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `rotate_number` int(11) NOT NULL DEFAULT '0',
  `is_msg_template_required` int(11) NOT NULL DEFAULT '0' COMMENT '0 = no, 1 = yes',
  `msg_type` int(11) NOT NULL DEFAULT '0',
  `mms_text` text COLLATE utf8_unicode_ci,
  `pick_file` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `throttle` int(11) NOT NULL DEFAULT '0',
  `alphasender` tinyint(4) NOT NULL DEFAULT '0',
  `is_immediately` int(11) NOT NULL DEFAULT '0',
  `alphasender_input` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendfrom` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duplicate_contacts` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_message_groups`
--

CREATE TABLE `schedule_message_groups` (
  `id` int(11) NOT NULL,
  `schedule_sms_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shortlinks`
--

CREATE TABLE `shortlinks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `shortname` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `short_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shortcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `short_urls`
--

CREATE TABLE `short_urls` (
  `id` bigint(255) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `shortname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_generate_shortlink` int(11) DEFAULT '0' COMMENT '0 - means generate from code, 1 - short link generate from short link menu, 2 - generate from sales nento',
  `long_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `short_code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `single_schedule_messages`
--

CREATE TABLE `single_schedule_messages` (
  `id` int(11) NOT NULL,
  `schedule_sms_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalties`
--

CREATE TABLE `smsloyalties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `program_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `no_end_date` int(11) NOT NULL DEFAULT '0',
  `coupancode` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codestatus` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reachgoal` int(11) NOT NULL DEFAULT '0',
  `punchcard_type` int(11) NOT NULL DEFAULT '0' COMMENT '0- manully punch by user, 1- punch approve by cashier',
  `is_sent_sms` int(11) NOT NULL DEFAULT '0',
  `limit_per_day_checkin` int(11) NOT NULL DEFAULT '0',
  `addpoints` text COLLATE utf8_unicode_ci,
  `reachedatgoal` text COLLATE utf8_unicode_ci,
  `checkstatus` text COLLATE utf8_unicode_ci,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1=>SMS,2=>MMS',
  `image` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `notify_punch_code` int(11) NOT NULL DEFAULT '0',
  `my_email_address` int(11) NOT NULL DEFAULT '0',
  `email_address` int(11) NOT NULL DEFAULT '0',
  `email_address_input` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_number` int(11) NOT NULL DEFAULT '0',
  `mobile_number_input` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'english',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalties_rewards`
--

CREATE TABLE `smsloyalties_rewards` (
  `id` int(111) NOT NULL,
  `smsloyalties_id` int(111) DEFAULT '0',
  `user_id` int(111) DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `stamps` int(111) NOT NULL DEFAULT '0',
  `rewards` varchar(255) DEFAULT NULL,
  `is_winner` int(11) NOT NULL DEFAULT '0',
  `is_redemptions` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalties_users_rewards`
--

CREATE TABLE `smsloyalties_users_rewards` (
  `id` bigint(20) NOT NULL,
  `smsloyalties_id` int(111) NOT NULL DEFAULT '0',
  `user_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` bigint(111) NOT NULL DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `stamps` int(111) NOT NULL DEFAULT '0',
  `rewards` varchar(255) DEFAULT NULL,
  `is_winner` int(11) NOT NULL DEFAULT '0',
  `is_redemptions` int(11) NOT NULL DEFAULT '0',
  `cashier_id` int(111) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `redeem_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalties_users_rewards_withoutcashiers`
--

CREATE TABLE `smsloyalties_users_rewards_withoutcashiers` (
  `id` bigint(20) NOT NULL,
  `smsloyalties_id` int(111) NOT NULL DEFAULT '0',
  `user_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` bigint(111) NOT NULL DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `stamps` int(111) NOT NULL DEFAULT '0',
  `rewards` varchar(255) DEFAULT NULL,
  `is_winner` int(11) NOT NULL DEFAULT '0',
  `is_redemptions` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `sms_response` text,
  `cashier_id` int(111) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `redeem_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalty_users`
--

CREATE TABLE `smsloyalty_users` (
  `id` int(11) NOT NULL,
  `unique_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `sms_loyalty_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `keyword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `count_trial` int(11) NOT NULL DEFAULT '0',
  `thankyou_status` int(11) NOT NULL DEFAULT '0',
  `rating_status` int(11) NOT NULL DEFAULT '0',
  `user_rating_status` int(11) NOT NULL DEFAULT '0',
  `followup_status` int(11) NOT NULL DEFAULT '0',
  `missyou_status` int(11) NOT NULL DEFAULT '0',
  `punch_date` datetime DEFAULT NULL,
  `is_winner` int(11) NOT NULL DEFAULT '0',
  `redemptions` int(11) NOT NULL DEFAULT '0',
  `msg_date` date DEFAULT NULL,
  `is_csv_upload` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalty_users_requests`
--

CREATE TABLE `smsloyalty_users_requests` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(111) DEFAULT '0',
  `unique_code` text,
  `kiosks_unique_code` varchar(255) DEFAULT NULL,
  `countrycodetel` varchar(111) DEFAULT NULL,
  `user_phone_number` varchar(255) DEFAULT NULL,
  `smsloyalties_id` int(111) NOT NULL DEFAULT '0',
  `smsloyalties_group_id` int(111) NOT NULL DEFAULT '0',
  `cashier_id` int(111) NOT NULL DEFAULT '0',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `is_redemtion` int(11) NOT NULL DEFAULT '0',
  `is_force` int(11) NOT NULL DEFAULT '0' COMMENT ' 0 - normal punch, 1- force punch, 2 - bulk checkin by cashier ',
  `given_bulk_stamp` int(11) DEFAULT NULL,
  `reward_id` int(111) NOT NULL DEFAULT '0',
  `response_status` int(11) NOT NULL DEFAULT '0',
  `response_msg` text,
  `sms_response` text,
  `is_new_subscriber` int(111) NOT NULL DEFAULT '0',
  `is_stamp_type` int(11) NOT NULL DEFAULT '0' COMMENT '0 - checkin by cahier, 1 - cashier manual checkin - added on 18112019',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `smsloyalty_users_requests_withoutcashiers`
--

CREATE TABLE `smsloyalty_users_requests_withoutcashiers` (
  `id` bigint(20) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `unique_code` text,
  `kiosks_unique_code` varchar(255) DEFAULT NULL,
  `countrycodetel` varchar(111) DEFAULT NULL,
  `user_phone_number` varchar(255) DEFAULT NULL,
  `smsloyalties_id` int(111) NOT NULL DEFAULT '0',
  `smsloyalties_group_id` int(111) NOT NULL DEFAULT '0',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `is_redemtion` int(11) NOT NULL DEFAULT '0',
  `is_force` int(11) NOT NULL DEFAULT '0' COMMENT '0 - normal punch, 1- force punch',
  `response_status` int(11) NOT NULL DEFAULT '0',
  `response_msg` text,
  `sms_response` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `smstemplates`
--

CREATE TABLE `smstemplates` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `messagename` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_template` varchar(1588) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_credit_logs`
--

CREATE TABLE `sms_credit_logs` (
  `sms_credit_log_id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `module_type` tinyint(6) NOT NULL DEFAULT '0' COMMENT '1=contact,2= send wallet link',
  `module_type_text` varchar(255) DEFAULT NULL,
  `before_credit` float DEFAULT NULL,
  `after_credit` float DEFAULT NULL,
  `used_credit` float DEFAULT NULL,
  `referance_id` int(111) NOT NULL DEFAULT '0',
  `number_of_recipient` int(111) DEFAULT NULL COMMENT 'number of persons who received msg',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `social_profiles`
--

CREATE TABLE `social_profiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `social_network_name` varchar(64) DEFAULT NULL,
  `social_network_id` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `display_name` varchar(128) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `link` varchar(512) NOT NULL,
  `picture` varchar(512) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `splashes`
--

CREATE TABLE `splashes` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `location_id` int(111) DEFAULT '0',
  `socnets` varchar(255) DEFAULT NULL,
  `user_id` bigint(111) DEFAULT NULL,
  `countrycodetel` int(111) DEFAULT NULL,
  `redirect_url` text,
  `welcome_message` varchar(255) DEFAULT NULL,
  `auth_welcomemess` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `bg_url` varchar(255) DEFAULT NULL,
  `box_url` varchar(255) DEFAULT NULL,
  `prom_message` text,
  `auth_desc` text,
  `textcolor` varchar(50) DEFAULT NULL,
  `boxcolor` varchar(50) DEFAULT NULL,
  `bgcolor` varchar(50) DEFAULT NULL,
  `linescolor` varchar(50) DEFAULT NULL,
  `linkscolor` varchar(50) DEFAULT NULL,
  `colortheme` varchar(255) DEFAULT NULL,
  `connect_before` varchar(255) DEFAULT NULL,
  `connect_after` varchar(255) DEFAULT NULL,
  `terms_box` varchar(255) DEFAULT NULL,
  `req_password` varchar(255) DEFAULT NULL,
  `on_success_login` varchar(7) DEFAULT NULL,
  `last_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_birthdate_required` int(2) NOT NULL DEFAULT '0' COMMENT '0 = not 1 = yes',
  `is_gender_required` int(2) NOT NULL DEFAULT '0' COMMENT '0 = not 1 = yes',
  `is_name_required` int(2) NOT NULL DEFAULT '0',
  `is_email_required` int(2) NOT NULL DEFAULT '0',
  `is_zip_required` int(2) NOT NULL DEFAULT '0',
  `term_condition_id` int(2) NOT NULL DEFAULT '0',
  `random` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stripes`
--

CREATE TABLE `stripes` (
  `id` int(11) NOT NULL,
  `secret_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publishable_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subaccounts`
--

CREATE TABLE `subaccounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '1=active,0=deactive',
  `autoresponders` tinyint(4) NOT NULL DEFAULT '1',
  `importcontacts` tinyint(4) NOT NULL DEFAULT '1',
  `shortlinks` tinyint(4) NOT NULL DEFAULT '1',
  `voicebroadcast` tinyint(4) NOT NULL DEFAULT '1',
  `polls` tinyint(4) NOT NULL DEFAULT '1',
  `contests` tinyint(4) NOT NULL DEFAULT '1',
  `loyaltyprograms` tinyint(4) NOT NULL DEFAULT '1',
  `kioskbuilder` tinyint(4) NOT NULL DEFAULT '1',
  `birthdaywishes` tinyint(4) NOT NULL DEFAULT '1',
  `mobilepagebuilder` tinyint(4) NOT NULL DEFAULT '1',
  `message_templates` tinyint(4) NOT NULL DEFAULT '0',
  `webwidgets` tinyint(4) NOT NULL DEFAULT '1',
  `smschat` tinyint(4) NOT NULL DEFAULT '1',
  `qrcodes` tinyint(4) NOT NULL DEFAULT '1',
  `calendarscheduler` tinyint(4) NOT NULL DEFAULT '1',
  `appointments` tinyint(4) NOT NULL DEFAULT '1',
  `groups` tinyint(4) NOT NULL DEFAULT '1',
  `hub_groups` tinyint(2) NOT NULL DEFAULT '1',
  `wifi_groups` tinyint(2) NOT NULL DEFAULT '1',
  `contactlist` tinyint(4) NOT NULL DEFAULT '1',
  `sendsms` tinyint(4) NOT NULL DEFAULT '1',
  `logs` tinyint(4) NOT NULL DEFAULT '1',
  `reports` tinyint(4) NOT NULL DEFAULT '1',
  `affiliates` tinyint(4) NOT NULL DEFAULT '1',
  `getnumbers` tinyint(4) NOT NULL DEFAULT '1',
  `makepurchases` tinyint(4) NOT NULL DEFAULT '1',
  `location` tinyint(4) DEFAULT '0',
  `groupt_message_queue` tinyint(4) NOT NULL DEFAULT '0',
  `contact_message_queue` tinyint(4) NOT NULL DEFAULT '0',
  `email_template` tinyint(4) NOT NULL DEFAULT '0',
  `send_bulk_email` tinyint(4) NOT NULL DEFAULT '0',
  `group_message_queue_email` tinyint(4) NOT NULL DEFAULT '0',
  `appointments_settings` tinyint(4) DEFAULT '0',
  `appointments_calendar` tinyint(4) NOT NULL DEFAULT '0',
  `import_appointments` tinyint(4) NOT NULL DEFAULT '0',
  `wallet_options` tinyint(4) NOT NULL DEFAULT '0',
  `leader_board` tinyint(4) NOT NULL DEFAULT '0',
  `cashier` tinyint(4) NOT NULL DEFAULT '0',
  `coupon` tinyint(4) NOT NULL DEFAULT '0',
  `kiosk_reports` tinyint(4) NOT NULL DEFAULT '0',
  `sub_unsub_reports` tinyint(4) NOT NULL DEFAULT '0',
  `character_checker` tinyint(4) NOT NULL DEFAULT '0',
  `allowed_location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allowed_hotspot` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_requirements` int(11) NOT NULL DEFAULT '0',
  `waivers` int(11) NOT NULL DEFAULT '0',
  `table_management` tinyint(4) NOT NULL DEFAULT '0',
  `waiting_list_location` tinyint(4) NOT NULL DEFAULT '0',
  `download` tinyint(4) NOT NULL DEFAULT '0',
  `offer_page` tinyint(4) NOT NULL DEFAULT '0',
  `settings` tinyint(4) NOT NULL DEFAULT '0',
  `appointment_reminder` tinyint(4) NOT NULL DEFAULT '0',
  `appointment_calender` tinyint(4) NOT NULL DEFAULT '0',
  `reputation` int(11) NOT NULL DEFAULT '0',
  `reputation_dashboard` int(2) DEFAULT '0',
  `signage` int(2) NOT NULL DEFAULT '0',
  `smart_hub` tinyint(4) NOT NULL DEFAULT '0',
  `smart_wifi` int(2) NOT NULL DEFAULT '0',
  `deep_analytics` int(2) NOT NULL DEFAULT '0',
  `automated_marketing` tinyint(4) NOT NULL DEFAULT '0',
  `wifi_kiosk_report` tinyint(4) NOT NULL DEFAULT '0',
  `customer_point_report` tinyint(4) NOT NULL DEFAULT '0',
  `customer_feedback` tinyint(4) NOT NULL DEFAULT '0',
  `reward_history` tinyint(4) NOT NULL DEFAULT '0',
  `marketing_logs` tinyint(4) NOT NULL DEFAULT '0',
  `credit_logs` tinyint(4) NOT NULL DEFAULT '0',
  `email_logs` tinyint(4) NOT NULL DEFAULT '0',
  `manage_contacts` tinyint(4) NOT NULL DEFAULT '0',
  `signage_response` text COLLATE utf8_unicode_ci,
  `signage_login_res` text COLLATE utf8_unicode_ci,
  `signage_login_token` text COLLATE utf8_unicode_ci,
  `hub_login_token` text COLLATE utf8_unicode_ci,
  `signage_upd_response` text COLLATE utf8_unicode_ci,
  `signage_id` text COLLATE utf8_unicode_ci,
  `setup_location` tinyint(2) NOT NULL DEFAULT '0',
  `loyalty_programs` tinyint(2) NOT NULL DEFAULT '0',
  `marketing` tinyint(2) NOT NULL DEFAULT '0',
  `tools` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tables_managements`
--

CREATE TABLE `tables_managements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `table_size` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `telnyx_api_responses`
--

CREATE TABLE `telnyx_api_responses` (
  `id` int(111) NOT NULL,
  `response` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(100) DEFAULT NULL,
  `template_path` varchar(100) DEFAULT NULL,
  `header_bg_image` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_otp_users`
--

CREATE TABLE `temp_otp_users` (
  `id` int(111) NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `location_id` int(11) NOT NULL DEFAULT '0',
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_mac` varchar(255) DEFAULT NULL,
  `router_mac` varchar(255) DEFAULT NULL,
  `OTP` varchar(255) DEFAULT NULL,
  `otp_send_date` datetime DEFAULT NULL,
  `otp_end_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `terms_and_conditions`
--

CREATE TABLE `terms_and_conditions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `terms_and_conditions` text,
  `created_datetime` datetime DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile_pic` text COLLATE utf8_unicode_ci,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `countrycodetel` int(111) DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_logo` text COLLATE utf8_unicode_ci,
  `describe_company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '1=small bussiness,2=large brand,3=agengy,4=other',
  `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_loc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_location` int(11) DEFAULT '0',
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurring_paypal_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurring_checkout_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monthly_stripe_subscription_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monthly_number_subscription_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_balance` int(11) DEFAULT '0',
  `voice_balance` int(11) DEFAULT '0',
  `is_sms_credit_purchased` tinyint(2) NOT NULL DEFAULT '0',
  `total_sms_credit_purchased` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_voice_credit_purchased` tinyint(2) NOT NULL DEFAULT '0',
  `total_voice_credit_purchased` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assigned_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT '0',
  `phone_sid` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `defaultgreeting` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Leave a message for THIS VOICEMAIL at the beep and Press the star key when finished.',
  `active` tinyint(2) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `voicemailnotifymail` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apikey` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_alerts` int(11) NOT NULL DEFAULT '1' COMMENT '1=''As they happen'',2=''dally summary''',
  `email_alert_options` int(11) NOT NULL DEFAULT '1' COMMENT '0=on,1=off',
  `low_sms_balances` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_alert_credit_options` int(11) NOT NULL DEFAULT '0' COMMENT '0=on,1=off',
  `sms_credit_balance_email_alerts` int(11) NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `low_voice_balances` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `VM_credit_balance_email_alerts` int(11) NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `account_activated` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `register` int(11) NOT NULL DEFAULT '0',
  `package` int(11) NOT NULL DEFAULT '0',
  `plan_recurring_amount` float DEFAULT '0',
  `is_trial` int(11) NOT NULL DEFAULT '0',
  `is_freeplan_cancel` int(2) NOT NULL DEFAULT '0',
  `is_trial_expire` int(11) DEFAULT '0',
  `trial_package_start_date` datetime DEFAULT NULL,
  `trial_package_end_date` datetime DEFAULT NULL,
  `next_renewal_dates` date DEFAULT NULL,
  `number_package` int(11) NOT NULL DEFAULT '0',
  `number_next_renewal_dates` date DEFAULT NULL,
  `number_limit_set` int(11) NOT NULL DEFAULT '0' COMMENT '0=off,1=on',
  `pay_activation_fees_active` int(11) NOT NULL DEFAULT '0',
  `incomingsms_alerts` int(11) NOT NULL DEFAULT '1' COMMENT '0=on,1=off',
  `incomingsms_emailalerts` int(11) NOT NULL DEFAULT '0' COMMENT '1=''Email'',2=''SMS''',
  `smsalerts_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `welcome_msg_type` int(11) NOT NULL DEFAULT '0',
  `mp3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_type` int(11) DEFAULT NULL,
  `sms` int(11) NOT NULL DEFAULT '0',
  `mms` int(11) NOT NULL DEFAULT '0',
  `voice` int(11) NOT NULL DEFAULT '0',
  `fax` int(11) NOT NULL DEFAULT '0',
  `number_limit` int(11) NOT NULL DEFAULT '1',
  `number_limit_count` int(11) NOT NULL DEFAULT '0',
  `birthday_wishes` int(11) NOT NULL DEFAULT '1',
  `capture_email_name` int(11) NOT NULL DEFAULT '1',
  `broadcast` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_to_sms` int(11) NOT NULL DEFAULT '1',
  `api_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keyword` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partnerid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partnerpassword` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incomingcall_forward` int(11) NOT NULL DEFAULT '1',
  `assign_callforward` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `callforward_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `IP_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pickup_app_frontend` int(11) NOT NULL DEFAULT '0',
  `web_pos_system` int(11) NOT NULL DEFAULT '0',
  `offline_mobile_pos` int(11) NOT NULL DEFAULT '0',
  `witress_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_display` int(11) NOT NULL DEFAULT '0',
  `pos_equipment` int(11) NOT NULL DEFAULT '0',
  `booking_reservation` int(11) NOT NULL DEFAULT '0',
  `queue_management` int(11) NOT NULL DEFAULT '0',
  `seo_video_reviews` int(11) NOT NULL DEFAULT '0',
  `autoresponders` tinyint(4) NOT NULL DEFAULT '0',
  `importcontacts` tinyint(4) NOT NULL DEFAULT '0',
  `shortlinks` tinyint(4) NOT NULL DEFAULT '0',
  `voicebroadcast` tinyint(4) NOT NULL DEFAULT '0',
  `polls` tinyint(4) NOT NULL DEFAULT '0',
  `contests` tinyint(4) NOT NULL DEFAULT '0',
  `loyaltyprograms` tinyint(4) NOT NULL DEFAULT '0',
  `kioskbuilder` tinyint(4) NOT NULL DEFAULT '0',
  `birthdaywishes` tinyint(4) NOT NULL DEFAULT '0',
  `mobilepagebuilder` tinyint(4) NOT NULL DEFAULT '0',
  `message_templates` int(11) NOT NULL DEFAULT '1',
  `webwidgets` tinyint(4) NOT NULL DEFAULT '0',
  `smschat` tinyint(4) NOT NULL DEFAULT '0',
  `cashier_leader_board` tinyint(2) NOT NULL DEFAULT '0',
  `leader_board` tinyint(2) NOT NULL DEFAULT '0',
  `qrcodes` tinyint(4) NOT NULL DEFAULT '0',
  `calendarscheduler` tinyint(4) NOT NULL DEFAULT '0',
  `appointments` tinyint(4) NOT NULL DEFAULT '0',
  `groups` tinyint(4) NOT NULL DEFAULT '0',
  `hub_groups` tinyint(4) NOT NULL DEFAULT '1',
  `wifi_groups` tinyint(4) NOT NULL DEFAULT '1',
  `contactlist` tinyint(4) NOT NULL DEFAULT '0',
  `sendsms` tinyint(4) NOT NULL DEFAULT '0',
  `logs` tinyint(4) NOT NULL DEFAULT '0',
  `reports` tinyint(4) NOT NULL DEFAULT '0',
  `affiliates` tinyint(4) NOT NULL DEFAULT '0',
  `getnumbers` tinyint(4) NOT NULL DEFAULT '0',
  `makepurchases` tinyint(4) NOT NULL DEFAULT '1',
  `location` tinyint(4) NOT NULL DEFAULT '0',
  `groupt_message_queue` tinyint(4) NOT NULL DEFAULT '1',
  `contact_message_queue` tinyint(4) NOT NULL DEFAULT '1',
  `email_template` tinyint(4) NOT NULL DEFAULT '1',
  `send_bulk_email` tinyint(4) NOT NULL DEFAULT '1',
  `group_message_queue_email` tinyint(4) NOT NULL DEFAULT '1',
  `appointments_settings` tinyint(4) NOT NULL DEFAULT '0',
  `appointment_reminder` tinyint(2) NOT NULL DEFAULT '1',
  `appointments_calendar` tinyint(4) NOT NULL DEFAULT '1',
  `import_appointments` tinyint(4) NOT NULL DEFAULT '1',
  `wallet_options` tinyint(4) NOT NULL DEFAULT '0',
  `cashier` tinyint(4) NOT NULL DEFAULT '0',
  `coupon` tinyint(4) NOT NULL DEFAULT '0',
  `kiosk_reports` tinyint(4) NOT NULL DEFAULT '0',
  `sub_unsub_reports` tinyint(4) NOT NULL DEFAULT '1',
  `character_checker` tinyint(4) NOT NULL DEFAULT '0',
  `alphasender` tinyint(4) NOT NULL DEFAULT '0',
  `incoming_nonkeyword` tinyint(4) NOT NULL DEFAULT '0',
  `nonkeyword_autoresponse` varchar(320) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_apikey` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_listid` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_service` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=Mailchimp,2=GetResponse,3=ActiveCampaign,4=AWeber,5=Sendinblue',
  `email_apiurl` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `consumerkey` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `consumersecret` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accesskey` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accesssecret` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_service_type` int(11) DEFAULT '0',
  `sid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authtoken` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telnyx_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telnyx_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telnyx_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twillio_sid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twillio_auth_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidthuserid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastlogin_username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastlogin_datetime` datetime DEFAULT NULL,
  `terms_conditions` text COLLATE utf8_unicode_ci,
  `sms_inclusion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `voice_inclusion` varchar(111) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addon_voice` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `additional_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `OTP` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_otp_verified` tinyint(2) NOT NULL DEFAULT '0',
  `is_email_verified` int(11) NOT NULL DEFAULT '0',
  `is_payment_verified` int(11) NOT NULL DEFAULT '0',
  `recurring_profile_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurring_profile_status` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `register_type` int(11) NOT NULL DEFAULT '0',
  `plan_amount` float NOT NULL DEFAULT '0',
  `plan_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_credit_carry_forward` int(11) NOT NULL DEFAULT '0',
  `access_to_data` int(11) NOT NULL DEFAULT '0',
  `scheduled_sms` int(111) NOT NULL DEFAULT '0',
  `mobile_opt_in_unlimited_keywords` int(111) NOT NULL DEFAULT '0',
  `smart_wifi` int(11) NOT NULL DEFAULT '0',
  `smart_hub` int(11) NOT NULL DEFAULT '0',
  `deep_analytics` int(11) NOT NULL DEFAULT '0',
  `contract` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_profile_active` tinyint(2) NOT NULL DEFAULT '0',
  `startfirstdatecharge` date DEFAULT NULL,
  `endfirstdatecharge` date DEFAULT NULL,
  `firstpaymentamount` float NOT NULL DEFAULT '0',
  `reoccuring_date` date DEFAULT NULL,
  `reoccuring_amount` float NOT NULL DEFAULT '0',
  `contract_period` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan_start_date` datetime DEFAULT NULL,
  `plan_cancel_date` datetime DEFAULT NULL,
  `is_plan_updated` tinyint(2) NOT NULL DEFAULT '0',
  `website` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address` text COLLATE utf8_unicode_ci,
  `address_line2` text COLLATE utf8_unicode_ci,
  `city` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `min_session_length` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_session_length` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_push` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_time_out` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cummulative_income` float NOT NULL DEFAULT '0',
  `job_requirements` int(11) NOT NULL DEFAULT '0',
  `table_management` tinyint(2) NOT NULL DEFAULT '1',
  `waiting_list_location` tinyint(2) NOT NULL DEFAULT '1',
  `download` tinyint(2) NOT NULL DEFAULT '1',
  `offer_page` tinyint(2) NOT NULL DEFAULT '1',
  `settings` tinyint(2) NOT NULL DEFAULT '1',
  `waivers` int(11) NOT NULL DEFAULT '0',
  `reputation` int(11) NOT NULL DEFAULT '0',
  `signage` int(2) NOT NULL DEFAULT '0',
  `automated_marketing` int(2) NOT NULL DEFAULT '0',
  `relevant_support` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relevant_support_other` text COLLATE utf8_unicode_ci,
  `geoplugin_reponse` text COLLATE utf8_unicode_ci,
  `signage_response` text COLLATE utf8_unicode_ci,
  `signage_id` text COLLATE utf8_unicode_ci,
  `signage_upd_response` text COLLATE utf8_unicode_ci,
  `signage_login_res` text COLLATE utf8_unicode_ci,
  `signage_login_token` text COLLATE utf8_unicode_ci,
  `hub_login_token` text COLLATE utf8_unicode_ci,
  `is_probes_status` int(2) NOT NULL DEFAULT '0',
  `is_new_user` int(11) NOT NULL DEFAULT '0' COMMENT '1 - new user signup, 0 - old user , this status use for display buy number help tour',
  `oauth_provider` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oauth_uid` text COLLATE utf8_unicode_ci,
  `deal_id` int(111) NOT NULL DEFAULT '0',
  `agreement_id` int(111) NOT NULL DEFAULT '0',
  `customer_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profiles_reponse` text COLLATE utf8_unicode_ci,
  `email_service_type` int(11) DEFAULT '0' COMMENT '1="mailchimp",2="aweber",3="sendinblue",4="pepipost"',
  `mailchimp_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aweber_auth_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendinblue_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendinblue_smtp` text COLLATE utf8_unicode_ci,
  `pepipost_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pepipost_smtp` text COLLATE utf8_unicode_ci,
  `mailgun_api_key` text COLLATE utf8_unicode_ci,
  `mail_gun_smtp` text COLLATE utf8_unicode_ci,
  `sendgrid_api_key` text COLLATE utf8_unicode_ci,
  `sendgrid_smtp` text COLLATE utf8_unicode_ci,
  `from_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'https://www.facebook.com',
  `instagram_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'https://www.instagram.com',
  `youtube_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'https://www.youtube.com',
  `twitter_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'https://www.youtube.com',
  `googleplus_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'https://plus.google.com',
  `linkedin_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'https://www.linkedin.com',
  `zero_bounce_key` text COLLATE utf8_unicode_ci,
  `per_day_sms_limit` int(111) NOT NULL DEFAULT '300',
  `is_blocked` int(11) NOT NULL DEFAULT '0' COMMENT '0-active, 1-blocked',
  `is_default_saved` int(2) NOT NULL DEFAULT '0' COMMENT '0 means no, 1 means yes',
  `user_signup_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=Sales,1=Hub frontend',
  `download_speed` varchar(255) COLLATE utf8_unicode_ci DEFAULT '5',
  `upload_speed` varchar(255) COLLATE utf8_unicode_ci DEFAULT '5',
  `wifi_kiosk_report` tinyint(2) NOT NULL DEFAULT '1',
  `customer_point_report` tinyint(2) NOT NULL DEFAULT '1',
  `customer_feedback` tinyint(2) NOT NULL DEFAULT '1',
  `reward_history` tinyint(2) NOT NULL DEFAULT '1',
  `marketing_logs` tinyint(2) NOT NULL DEFAULT '1',
  `credit_logs` tinyint(2) NOT NULL DEFAULT '1',
  `email_logs` tinyint(2) NOT NULL DEFAULT '1',
  `manage_contacts` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_cards`
--

CREATE TABLE `users_cards` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `c_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `c_number` text COLLATE latin1_general_ci,
  `c_edate` text COLLATE latin1_general_ci,
  `c_cvv` text COLLATE latin1_general_ci,
  `c_email` text COLLATE latin1_general_ci,
  `c_street` text COLLATE latin1_general_ci,
  `c_city` text COLLATE latin1_general_ci,
  `c_state` text COLLATE latin1_general_ci,
  `c_countrycode` text COLLATE latin1_general_ci,
  `c_zip` text COLLATE latin1_general_ci,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_ratings`
--

CREATE TABLE `users_ratings` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `responder_id` int(111) NOT NULL DEFAULT '0',
  `user_id` int(111) DEFAULT '0',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `smsloyalties_users_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` int(111) NOT NULL DEFAULT '0',
  `cashier_id` int(11) NOT NULL DEFAULT '0',
  `rating_long_url` text,
  `rating_short_url` text,
  `rating_experience` int(111) NOT NULL DEFAULT '0',
  `rating_service` int(111) NOT NULL DEFAULT '0',
  `rating_food` int(111) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1 - smsloyalty rating',
  `user_message_status` text,
  `report_message_status` text,
  `is_feedback` int(111) NOT NULL DEFAULT '0',
  `feedback` varchar(255) DEFAULT NULL,
  `is_testing` int(11) NOT NULL DEFAULT '0' COMMENT ' 0- not testing purpose, 1- it is testing purpose ',
  `testing_number` varchar(255) DEFAULT NULL,
  `sendby` int(11) NOT NULL DEFAULT '0' COMMENT '0 - sms, 1 - email',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_coupens`
--

CREATE TABLE `user_coupens` (
  `id` bigint(20) NOT NULL,
  `coupen_id` bigint(20) NOT NULL DEFAULT '0',
  `group_id` bigint(20) NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `unique_code` varchar(255) DEFAULT NULL,
  `long_url` text,
  `access_url` text,
  `coupen_reedem_limit` varchar(255) DEFAULT NULL,
  `used_coupen` varchar(255) DEFAULT NULL,
  `is_link_open` int(11) NOT NULL DEFAULT '0',
  `is_testing` int(11) NOT NULL DEFAULT '0' COMMENT '0- not testing purpose,  1- it is testing purpose',
  `testing_number` varchar(50) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `is_wifi_responder_coupon` int(11) NOT NULL DEFAULT '0' COMMENT 'coupon create from wifi signup respoder',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_coupen_logs`
--

CREATE TABLE `user_coupen_logs` (
  `id` bigint(20) NOT NULL,
  `coupen_code` text,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `cashier_id` int(111) NOT NULL DEFAULT '0',
  `customer_id` int(111) DEFAULT '0',
  `customer_no` varchar(255) DEFAULT NULL,
  `location_id` int(111) NOT NULL DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `status` int(111) NOT NULL DEFAULT '0',
  `response_status` int(11) NOT NULL DEFAULT '0',
  `response_msg` text,
  `user_view_status` int(111) NOT NULL DEFAULT '0',
  `is_testing` int(10) NOT NULL DEFAULT '0' COMMENT '0- not testing purpose, 1- it is testing purpose ',
  `created_at` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `message` text,
  `created_by` int(11) NOT NULL DEFAULT '0' COMMENT '1 - change by admin,2 - user based',
  `is_view` tinyint(2) NOT NULL DEFAULT '1',
  `log_type` tinyint(2) NOT NULL DEFAULT '0',
  `log_type_text` varchar(55) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_numbers`
--

CREATE TABLE `user_numbers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `is_admin_number` int(2) NOT NULL DEFAULT '0' COMMENT '0 means admin number , 1 means user purchased number',
  `number_company` int(2) NOT NULL DEFAULT '0' COMMENT '0 means twillio, 1 means telnyx',
  `number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_sid` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_type` int(11) NOT NULL DEFAULT '0' COMMENT '0-recurring, 1- monthly purchase and then automatically permanent number release from user and twillio',
  `api_method` int(11) NOT NULL DEFAULT '0',
  `api_type` int(11) NOT NULL DEFAULT '0' COMMENT '0 - admin twillio, 8- admin telnyx, 9 - user twillio, 10 - user telnyx',
  `country_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `sms` int(11) NOT NULL DEFAULT '0',
  `mms` int(11) NOT NULL DEFAULT '0',
  `voice` int(11) NOT NULL DEFAULT '0',
  `fax` int(11) NOT NULL DEFAULT '0',
  `is_on_hold` tinyint(2) NOT NULL DEFAULT '0',
  `hold_start_date` datetime DEFAULT NULL,
  `hold_end_date` datetime DEFAULT NULL,
  `next_renewal_date` date DEFAULT NULL,
  `payment_currency` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cad',
  `stripe_plan_id` text COLLATE utf8_unicode_ci,
  `subscriptionid` text COLLATE utf8_unicode_ci,
  `dex_paymethod_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dex_transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dex_subscriptionid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number_recurring_amount` float NOT NULL DEFAULT '0',
  `transaction_id` text COLLATE utf8_unicode_ci,
  `billing_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = auto billing, 1 = manual billing, 2 = admin free assign',
  `is_first_purchase` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = Yes, 1 = no',
  `is_fail_payment` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = Not, 1 = Yes',
  `fail_payment_reason` text COLLATE utf8_unicode_ci,
  `daily_sms_limit` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '200'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_number_historys`
--

CREATE TABLE `user_number_historys` (
  `id` int(111) NOT NULL,
  `user_id` int(111) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `number_company` int(2) NOT NULL DEFAULT '0' COMMENT '0 means twillio, 1 means telnyx',
  `purchase_type` int(2) NOT NULL DEFAULT '0' COMMENT '0-recurring, 1- monthly purchase and then automatically permanent number release from user and twillio,2 = admin assigned',
  `log_type` varchar(255) DEFAULT NULL COMMENT 'Assigned | Released',
  `assigned_at` datetime DEFAULT NULL,
  `before_per_day_sms_limit` int(11) NOT NULL DEFAULT '0',
  `after_per_day_sms_limit` int(11) NOT NULL DEFAULT '0',
  `released_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_wallets`
--

CREATE TABLE `user_wallets` (
  `id` bigint(20) NOT NULL,
  `unique_code` text,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `location_id` int(111) NOT NULL DEFAULT '0',
  `contact_id` int(111) NOT NULL DEFAULT '0',
  `user_share_coupon_count` int(111) NOT NULL DEFAULT '0',
  `user_phone_number` varchar(255) DEFAULT NULL,
  `long_url` text,
  `short_url` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `voice_messages`
--

CREATE TABLE `voice_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `message_type` int(11) NOT NULL DEFAULT '0',
  `text_message` text COLLATE utf8_unicode_ci,
  `audio` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waitinglist_sms_texts`
--

CREATE TABLE `waitinglist_sms_texts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `waiting_list_sms` varchar(500) NOT NULL DEFAULT 'You have been added to the waiting list. We will contact you As soon as it''s ready. Someone from %location_name% will contact you.',
  `confirmation_sms` varchar(500) NOT NULL DEFAULT 'Hello, %customer_name% Your table is almost ready. Please make your way back to %location_name% as soon as possible.',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `waiting_lists`
--

CREATE TABLE `waiting_lists` (
  `id` int(111) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `country_code` int(11) DEFAULT NULL,
  `customer_mobile` varchar(255) DEFAULT NULL,
  `quotade_time` varchar(255) DEFAULT NULL,
  `notes` text,
  `table_id` int(11) DEFAULT NULL,
  `table_no` varchar(255) DEFAULT NULL,
  `persons_size` varchar(255) DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '0 = added,1 = notified,2=confirmed,3=assigned,4=cancelled',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cancelled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `waivers`
--

CREATE TABLE `waivers` (
  `id` bigint(111) NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `location_id` varchar(255) DEFAULT NULL,
  `wavier_name` varchar(255) DEFAULT NULL,
  `waiver_description` text,
  `optin_group` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `colortheme` varchar(255) DEFAULT NULL,
  `countrycodetel` varchar(255) DEFAULT NULL,
  `file` text,
  `business_logo` text,
  `is_send_message` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 - send, 1 - not send',
  `message_text` text,
  `is_adult_firstname` int(11) NOT NULL DEFAULT '0',
  `is_adult_lastname` int(11) NOT NULL DEFAULT '0',
  `is_adult_email` int(11) NOT NULL DEFAULT '0',
  `is_adult_gender` int(11) NOT NULL DEFAULT '0',
  `is_adult_birthdate` int(11) NOT NULL DEFAULT '0',
  `is_child_firstname` int(11) NOT NULL DEFAULT '0',
  `is_child_lastname` int(11) NOT NULL DEFAULT '0',
  `is_child_gender` int(11) NOT NULL DEFAULT '0',
  `is_child_birthdate` int(11) NOT NULL DEFAULT '0',
  `is_mailaddress` int(11) NOT NULL DEFAULT '0',
  `is_city` int(11) NOT NULL DEFAULT '0',
  `is_country` int(11) NOT NULL DEFAULT '0',
  `is_state` int(11) NOT NULL DEFAULT '0',
  `is_zipcode` int(11) NOT NULL DEFAULT '0',
  `is_phone_number` int(11) NOT NULL DEFAULT '0',
  `is_terms_condition` int(11) NOT NULL DEFAULT '0',
  `terms_condition` text,
  `is_child_add` int(11) NOT NULL DEFAULT '1',
  `is_address_info` int(11) NOT NULL DEFAULT '0',
  `description_first` text,
  `description_second` text,
  `description_third` text,
  `description_fourth` text,
  `firstname_label` varchar(255) DEFAULT NULL,
  `secondname_label` varchar(255) DEFAULT NULL,
  `email_label` varchar(255) DEFAULT NULL,
  `gender_label` varchar(255) DEFAULT NULL,
  `dob_label` varchar(255) DEFAULT NULL,
  `mailing_label` varchar(255) DEFAULT NULL,
  `city_label` varchar(255) DEFAULT NULL,
  `country_label` varchar(255) DEFAULT NULL,
  `state_label` varchar(255) DEFAULT NULL,
  `zip_label` varchar(255) DEFAULT NULL,
  `mobile_label` varchar(255) DEFAULT NULL,
  `cfirstname_label` varchar(255) DEFAULT NULL,
  `csecondname_label` varchar(255) DEFAULT NULL,
  `cgender_label` varchar(255) DEFAULT NULL,
  `cdob_label` varchar(255) DEFAULT NULL,
  `adult_label` varchar(255) NOT NULL,
  `address_label` varchar(255) DEFAULT NULL,
  `like_check` varchar(255) DEFAULT NULL,
  `child_label` varchar(255) DEFAULT NULL,
  `review_label` varchar(255) DEFAULT NULL,
  `terms1` varchar(255) DEFAULT NULL,
  `terms2` varchar(255) DEFAULT NULL,
  `self_btn` varchar(255) DEFAULT NULL,
  `cself_btn` varchar(255) DEFAULT NULL,
  `cadd_btn` varchar(255) DEFAULT NULL,
  `cnext_btn` varchar(255) DEFAULT NULL,
  `changeinfo_btn` varchar(255) DEFAULT NULL,
  `submit_btn` varchar(255) DEFAULT NULL,
  `thanks_msg` varchar(255) DEFAULT NULL,
  `add_adult_filed` text,
  `add_address_adult_filed` text,
  `add_child_filed` text,
  `extra_adult_fields` text,
  `extra_child_fields` text,
  `is_send_sms` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'If status has 0 then stop message when submit waiver form',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `waiver_child_infos`
--

CREATE TABLE `waiver_child_infos` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) DEFAULT NULL,
  `waiver_info_id` int(111) DEFAULT NULL,
  `child_firstname` varchar(255) DEFAULT NULL,
  `child_lastname` varchar(255) DEFAULT NULL,
  `child_gender` varchar(255) DEFAULT NULL,
  `child_birthdate` varchar(255) DEFAULT NULL,
  `child_extra_infos` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `waiver_infos`
--

CREATE TABLE `waiver_infos` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) DEFAULT NULL,
  `waiver_unique_id` varchar(255) DEFAULT NULL,
  `adult_firstname` varchar(255) DEFAULT NULL,
  `adult_lastname` varchar(255) DEFAULT NULL,
  `adult_email` varchar(255) DEFAULT NULL,
  `adult_gender` varchar(255) DEFAULT NULL,
  `adult_birthdate` varchar(255) DEFAULT NULL,
  `child_firstname` varchar(55) DEFAULT NULL,
  `child_lastname` varchar(55) DEFAULT NULL,
  `child_gender` varchar(55) DEFAULT NULL,
  `child_birthdate` varchar(55) DEFAULT NULL,
  `recieve_offers` varchar(255) DEFAULT NULL,
  `mailaddress` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `adult_extra_infos` text,
  `child_extra_infos` text,
  `adult_extra_address_infos` text,
  `is_waiver_trigger` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_options`
--

CREATE TABLE `wallet_options` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `islink` int(11) NOT NULL DEFAULT '0',
  `isshare` int(11) NOT NULL DEFAULT '0',
  `isprofile` int(11) NOT NULL DEFAULT '0',
  `coupon_share_limit` int(11) NOT NULL DEFAULT '0',
  `location_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wifi_redirect_url_logs`
--

CREATE TABLE `wifi_redirect_url_logs` (
  `id` int(111) NOT NULL,
  `url` text,
  `customer_mac` varchar(255) DEFAULT NULL,
  `router_mac` varchar(255) DEFAULT NULL,
  `location_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_timelines`
--
ALTER TABLE `activity_timelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `addon_packages`
--
ALTER TABLE `addon_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agreement_location`
--
ALTER TABLE `agreement_location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agreement_id` (`agreement_id`,`user_id`);

--
-- Indexes for table `all_keywords`
--
ALTER TABLE `all_keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answer_subscribers`
--
ALTER TABLE `answer_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `appointment_csvs`
--
ALTER TABLE `appointment_csvs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointment_settings`
--
ALTER TABLE `appointment_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `automation_connections`
--
ALTER TABLE `automation_connections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`location_id`,`groupid`,`phone_number`,`connection_type`,`responder_id`);

--
-- Indexes for table `automation_marketings`
--
ALTER TABLE `automation_marketings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`,`user_id`,`email_template_id`,`status`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `bambora_payment_notifications`
--
ALTER TABLE `bambora_payment_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `birthdays`
--
ALTER TABLE `birthdays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bulk_schedule_emails`
--
ALTER TABLE `bulk_schedule_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`group_id`,`contact_id`,`sechdule_type`,`group_email_id`,`is_cron`,`sent`,`send_on`);

--
-- Indexes for table `bulk_schedule_messages`
--
ALTER TABLE `bulk_schedule_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`contact_id`,`group_sms_id`,`sendfrom`,`is_cron`,`sent`,`send_on`),
  ADD KEY `sechdule_type` (`sechdule_type`);

--
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unique_id` (`unique_id`,`user_id`,`location_id`,`status`),
  ADD KEY `Authorization` (`Authorization`,`device_token`,`device_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `cashier_push_notifications`
--
ALTER TABLE `cashier_push_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `cashier_id` (`cashier_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `cashier_reference_users`
--
ALTER TABLE `cashier_reference_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`cashier_id`,`type`,`refrence_id`,`contact_id`);

--
-- Indexes for table `checkin_point_options`
--
ALTER TABLE `checkin_point_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_percentage` (`tax_percentage`,`free_sms`,`free_voice`,`site_url`,`sitename`,`admin_sms_method`,`twilio_accountSid`,`twilio_auth_token`),
  ADD KEY `telnyx_username` (`telnyx_username`,`telnyx_key`,`telnyx_token`),
  ADD KEY `referral_amount` (`referral_amount`,`recurring_referral_percent`);

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`group_id`,`contact_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`),
  ADD KEY `phone_number` (`phone_number`),
  ADD KEY `stickysender` (`stickysender`),
  ADD KEY `id` (`id`),
  ADD KEY `is_testing` (`is_testing`);

--
-- Indexes for table `contact_groups`
--
ALTER TABLE `contact_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id_2` (`user_id`,`subscribed_by_sms`),
  ADD KEY `user_id_3` (`user_id`,`un_subscribers`,`subscribed_by_sms`),
  ADD KEY `user_id_4` (`user_id`,`un_subscribers`,`created`),
  ADD KEY `id` (`id`),
  ADD KEY `is_notify_register` (`is_notify_register`);

--
-- Indexes for table `contests`
--
ALTER TABLE `contests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contest_subscribers`
--
ALTER TABLE `contest_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_number` (`phone_number`);

--
-- Indexes for table `country_gateways`
--
ALTER TABLE `country_gateways`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country` (`country`);

--
-- Indexes for table `coupens`
--
ALTER TABLE `coupens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`coupon_type`,`is_all_location`,`startdate`,`enddate`,`is_scheduled`,`user_id`,`status`,`is_deleted`);

--
-- Indexes for table `coupen_schedule_messages`
--
ALTER TABLE `coupen_schedule_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recurring_id` (`recurring_id`),
  ADD KEY `id` (`id`),
  ADD KEY `send_on` (`send_on`),
  ADD KEY `is_cron` (`is_cron`),
  ADD KEY `sent` (`sent`),
  ADD KEY `sendto` (`sendto`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `coupen_id` (`coupen_id`);

--
-- Indexes for table `cron_logs`
--
ALTER TABLE `cron_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `deviceid` (`deviceid`,`customer_mac`,`platform_name`),
  ADD KEY `birthdate` (`birthdate`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `email` (`email`),
  ADD KEY `phone` (`phone`),
  ADD KEY `created` (`created`),
  ADD KEY `is_blocked` (`is_blocked`);

--
-- Indexes for table `customer_visits`
--
ALTER TABLE `customer_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `email` (`email`),
  ADD KEY `customer_mac` (`customer_mac`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `deviceid` (`deviceid`),
  ADD KEY `created` (`created`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `birthdate` (`birthdate`);

--
-- Indexes for table `daily_summary`
--
ALTER TABLE `daily_summary`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `dbconfigs`
--
ALTER TABLE `dbconfigs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `is_probes_status` (`is_probes_status`),
  ADD KEY `deviceid` (`deviceid`);

--
-- Indexes for table `dex_payment_notifications`
--
ALTER TABLE `dex_payment_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emailtemplates`
--
ALTER TABLE `emailtemplates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`user_id`);

--
-- Indexes for table `email_attachments`
--
ALTER TABLE `email_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_email_id` (`schedule_email_id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_email_id` (`group_email_id`,`user_id`,`group_id`,`contact_id`);

--
-- Indexes for table `fail_group_logs`
--
ALTER TABLE `fail_group_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `keyword` (`keyword`),
  ADD KEY `wifi_location_id` (`wifi_location_id`,`is_wifi_active`,`location`,`group_sms_type`),
  ADD KEY `id` (`id`),
  ADD KEY `menu_group_id` (`menu_group_id`),
  ADD KEY `customer_category_id` (`customer_category_id`);

--
-- Indexes for table `group_email_blasts`
--
ALTER TABLE `group_email_blasts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `schedule_email_id` (`schedule_email_id`),
  ADD KEY `isdeleted` (`isdeleted`);

--
-- Indexes for table `group_sms_blasts`
--
ALTER TABLE `group_sms_blasts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `isdeleted` (`isdeleted`);

--
-- Indexes for table `instant_payment_notifications`
--
ALTER TABLE `instant_payment_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `job_applies`
--
ALTER TABLE `job_applies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`job_unique_id`,`job_id`);

--
-- Indexes for table `job_requirements`
--
ALTER TABLE `job_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kiosks`
--
ALTER TABLE `kiosks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`),
  ADD KEY `loyalty_id` (`loyalty_id`);

--
-- Indexes for table `kiosks_templates`
--
ALTER TABLE `kiosks_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `loyalty_id` (`loyalty_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unique_id` (`unique_id`),
  ADD KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_sms_id` (`group_sms_id`),
  ADD KEY `sms_id` (`sms_id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `ticket` (`ticket`),
  ADD KEY `phone_number` (`phone_number`),
  ADD KEY `user_id` (`user_id`,`route`,`msg_type`,`created`),
  ADD KEY `route` (`route`,`msg_type`,`sms_status`,`created`),
  ADD KEY `user_id_2` (`user_id`,`route`,`msg_type`,`read`),
  ADD KEY `created` (`created`),
  ADD KEY `api_type` (`api_type`),
  ADD KEY `api_company` (`api_company`),
  ADD KEY `before_credit` (`before_credit`),
  ADD KEY `after_credit` (`after_credit`),
  ADD KEY `module_type` (`module_type`),
  ADD KEY `module_type_text` (`module_type_text`);

--
-- Indexes for table `log_post_requests`
--
ALTER TABLE `log_post_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menuonline_group`
--
ALTER TABLE `menuonline_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`customer_category_id`);

--
-- Indexes for table `menuonline_responders`
--
ALTER TABLE `menuonline_responders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`location_id`,`menu_id`,`contact_id`,`contact_group_id`,`group_id`,`responder_id`,`resp_type`,`responder_type`);

--
-- Indexes for table `mobile_pages`
--
ALTER TABLE `mobile_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `mobile_templates`
--
ALTER TABLE `mobile_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `monthly_number_packages`
--
ALTER TABLE `monthly_number_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_packages`
--
ALTER TABLE `monthly_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer_pages`
--
ALTER TABLE `offer_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_items`
--
ALTER TABLE `paypal_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_users`
--
ALTER TABLE `plan_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qrcods`
--
ALTER TABLE `qrcods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reputations`
--
ALTER TABLE `reputations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unique_id` (`unique_id`,`user_id`,`location_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `reputation_feedbacks`
--
ALTER TABLE `reputation_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reputations_id` (`reputations_id`,`user_id`,`location_id`);

--
-- Indexes for table `reset_leader_boards`
--
ALTER TABLE `reset_leader_boards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`location_id`);

--
-- Indexes for table `responders`
--
ALTER TABLE `responders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `responder_type` (`responder_type`,`location_id`,`days`,`status`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `responder_probes`
--
ALTER TABLE `responder_probes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `device_id` (`device_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `is_triggered` (`is_triggered`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `probes_id` (`probes_id`);

--
-- Indexes for table `schedule_emails`
--
ALTER TABLE `schedule_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`,`is_immediately`);

--
-- Indexes for table `schedule_email_groups`
--
ALTER TABLE `schedule_email_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_email_id` (`schedule_email_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `schedule_messages`
--
ALTER TABLE `schedule_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recurring_id` (`recurring_id`),
  ADD KEY `send_on` (`send_on`,`sent`,`is_immediately`,`sendfrom`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `schedule_message_groups`
--
ALTER TABLE `schedule_message_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_sms_id` (`schedule_sms_id`,`group_id`);

--
-- Indexes for table `shortlinks`
--
ALTER TABLE `shortlinks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shortcode` (`shortcode`);

--
-- Indexes for table `short_urls`
--
ALTER TABLE `short_urls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `short_code` (`short_code`),
  ADD KEY `is_generate_shortlink` (`is_generate_shortlink`);

--
-- Indexes for table `single_schedule_messages`
--
ALTER TABLE `single_schedule_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_sms_id` (`schedule_sms_id`,`contact_id`);

--
-- Indexes for table `smsloyalties`
--
ALTER TABLE `smsloyalties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`),
  ADD KEY `is_sent_sms` (`is_sent_sms`),
  ADD KEY `type` (`type`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `smsloyalties_rewards`
--
ALTER TABLE `smsloyalties_rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smsloyalties_users_rewards`
--
ALTER TABLE `smsloyalties_users_rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smsloyalties_users_rewards_withoutcashiers`
--
ALTER TABLE `smsloyalties_users_rewards_withoutcashiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smsloyalty_users`
--
ALTER TABLE `smsloyalty_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `sms_loyalty_id` (`sms_loyalty_id`),
  ADD KEY `thankyou_status` (`thankyou_status`,`rating_status`,`user_rating_status`,`followup_status`,`missyou_status`,`punch_date`);

--
-- Indexes for table `smsloyalty_users_requests`
--
ALTER TABLE `smsloyalty_users_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kiosks_unique_code` (`kiosks_unique_code`),
  ADD KEY `user_phone_number` (`user_phone_number`),
  ADD KEY `smsloyalties_id` (`smsloyalties_id`),
  ADD KEY `cashier_id` (`cashier_id`),
  ADD KEY `is_redemtion` (`is_redemtion`),
  ADD KEY `is_stamp_type` (`is_stamp_type`);

--
-- Indexes for table `smsloyalty_users_requests_withoutcashiers`
--
ALTER TABLE `smsloyalty_users_requests_withoutcashiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kiosks_unique_code` (`kiosks_unique_code`,`user_phone_number`,`smsloyalties_id`,`location_id`,`is_redemtion`);

--
-- Indexes for table `smstemplates`
--
ALTER TABLE `smstemplates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `sms_credit_logs`
--
ALTER TABLE `sms_credit_logs`
  ADD PRIMARY KEY (`sms_credit_log_id`);

--
-- Indexes for table `social_profiles`
--
ALTER TABLE `social_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `splashes`
--
ALTER TABLE `splashes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unique_id` (`unique_id`,`location_id`,`user_id`);

--
-- Indexes for table `stripes`
--
ALTER TABLE `stripes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `secret_key` (`secret_key`,`publishable_key`);

--
-- Indexes for table `subaccounts`
--
ALTER TABLE `subaccounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `tables_managements`
--
ALTER TABLE `tables_managements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telnyx_api_responses`
--
ALTER TABLE `telnyx_api_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_otp_users`
--
ALTER TABLE `temp_otp_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `email` (`email`),
  ADD KEY `phone` (`phone`),
  ADD KEY `assigned_number` (`assigned_number`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `users_cards`
--
ALTER TABLE `users_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_ratings`
--
ALTER TABLE `users_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unique_id` (`unique_id`,`responder_id`,`user_id`,`location_id`,`smsloyalties_users_id`,`contact_id`,`cashier_id`,`is_testing`);

--
-- Indexes for table `user_coupens`
--
ALTER TABLE `user_coupens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `coupen_id` (`coupen_id`,`group_id`,`user_id`),
  ADD KEY `unique_code` (`unique_code`);

--
-- Indexes for table `user_coupen_logs`
--
ALTER TABLE `user_coupen_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`cashier_id`,`customer_id`,`location_id`,`is_testing`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`is_view`);

--
-- Indexes for table `user_numbers`
--
ALTER TABLE `user_numbers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `number` (`number`),
  ADD KEY `purchase_type` (`purchase_type`);

--
-- Indexes for table `user_number_historys`
--
ALTER TABLE `user_number_historys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `number` (`number`),
  ADD KEY `purchase_type` (`purchase_type`),
  ADD KEY `log_type` (`log_type`);

--
-- Indexes for table `user_wallets`
--
ALTER TABLE `user_wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `voice_messages`
--
ALTER TABLE `voice_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `waitinglist_sms_texts`
--
ALTER TABLE `waitinglist_sms_texts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waiting_lists`
--
ALTER TABLE `waiting_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`location_id`,`table_id`,`table_no`);

--
-- Indexes for table `waivers`
--
ALTER TABLE `waivers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`unique_id`,`user_id`,`location_id`,`keyword`);

--
-- Indexes for table `waiver_child_infos`
--
ALTER TABLE `waiver_child_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`waiver_info_id`);

--
-- Indexes for table `waiver_infos`
--
ALTER TABLE `waiver_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`waiver_unique_id`,`is_waiver_trigger`);

--
-- Indexes for table `wallet_options`
--
ALTER TABLE `wallet_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wifi_redirect_url_logs`
--
ALTER TABLE `wifi_redirect_url_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_mac` (`customer_mac`),
  ADD KEY `router_mac` (`router_mac`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_timelines`
--
ALTER TABLE `activity_timelines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=653096;
--
-- AUTO_INCREMENT for table `addon_packages`
--
ALTER TABLE `addon_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `agreement_location`
--
ALTER TABLE `agreement_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT for table `all_keywords`
--
ALTER TABLE `all_keywords`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2478;
--
-- AUTO_INCREMENT for table `answer_subscribers`
--
ALTER TABLE `answer_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `appointment_csvs`
--
ALTER TABLE `appointment_csvs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `appointment_settings`
--
ALTER TABLE `appointment_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `automation_connections`
--
ALTER TABLE `automation_connections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99554;
--
-- AUTO_INCREMENT for table `automation_marketings`
--
ALTER TABLE `automation_marketings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `bambora_payment_notifications`
--
ALTER TABLE `bambora_payment_notifications`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3161;
--
-- AUTO_INCREMENT for table `birthdays`
--
ALTER TABLE `birthdays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `bulk_schedule_emails`
--
ALTER TABLE `bulk_schedule_emails`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18937;
--
-- AUTO_INCREMENT for table `bulk_schedule_messages`
--
ALTER TABLE `bulk_schedule_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=695483;
--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=585;
--
-- AUTO_INCREMENT for table `cashier_push_notifications`
--
ALTER TABLE `cashier_push_notifications`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6892;
--
-- AUTO_INCREMENT for table `cashier_reference_users`
--
ALTER TABLE `cashier_reference_users`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `checkin_point_options`
--
ALTER TABLE `checkin_point_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `connections`
--
ALTER TABLE `connections`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81850;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353882;
--
-- AUTO_INCREMENT for table `contact_groups`
--
ALTER TABLE `contact_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=406319;
--
-- AUTO_INCREMENT for table `contests`
--
ALTER TABLE `contests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `contest_subscribers`
--
ALTER TABLE `contest_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `country_gateways`
--
ALTER TABLE `country_gateways`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;
--
-- AUTO_INCREMENT for table `coupens`
--
ALTER TABLE `coupens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=499;
--
-- AUTO_INCREMENT for table `coupen_schedule_messages`
--
ALTER TABLE `coupen_schedule_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26044;
--
-- AUTO_INCREMENT for table `cron_logs`
--
ALTER TABLE `cron_logs`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1599621;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32538;
--
-- AUTO_INCREMENT for table `customer_visits`
--
ALTER TABLE `customer_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84349;
--
-- AUTO_INCREMENT for table `daily_summary`
--
ALTER TABLE `daily_summary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dbconfigs`
--
ALTER TABLE `dbconfigs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;
--
-- AUTO_INCREMENT for table `dex_payment_notifications`
--
ALTER TABLE `dex_payment_notifications`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `emailtemplates`
--
ALTER TABLE `emailtemplates`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;
--
-- AUTO_INCREMENT for table `email_attachments`
--
ALTER TABLE `email_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23262;
--
-- AUTO_INCREMENT for table `fail_group_logs`
--
ALTER TABLE `fail_group_logs`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18975;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2552;
--
-- AUTO_INCREMENT for table `group_email_blasts`
--
ALTER TABLE `group_email_blasts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `group_sms_blasts`
--
ALTER TABLE `group_sms_blasts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13150;
--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3178;
--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;
--
-- AUTO_INCREMENT for table `job_applies`
--
ALTER TABLE `job_applies`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `job_requirements`
--
ALTER TABLE `job_requirements`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `kiosks`
--
ALTER TABLE `kiosks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=584;
--
-- AUTO_INCREMENT for table `kiosks_templates`
--
ALTER TABLE `kiosks_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1305;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1017561;
--
-- AUTO_INCREMENT for table `log_post_requests`
--
ALTER TABLE `log_post_requests`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1802;
--
-- AUTO_INCREMENT for table `menuonline_group`
--
ALTER TABLE `menuonline_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `menuonline_responders`
--
ALTER TABLE `menuonline_responders`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;
--
-- AUTO_INCREMENT for table `mobile_pages`
--
ALTER TABLE `mobile_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `mobile_templates`
--
ALTER TABLE `mobile_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `monthly_number_packages`
--
ALTER TABLE `monthly_number_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `monthly_packages`
--
ALTER TABLE `monthly_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `offer_pages`
--
ALTER TABLE `offer_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;
--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;
--
-- AUTO_INCREMENT for table `plan_users`
--
ALTER TABLE `plan_users`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `qrcods`
--
ALTER TABLE `qrcods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;
--
-- AUTO_INCREMENT for table `reputations`
--
ALTER TABLE `reputations`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT for table `reputation_feedbacks`
--
ALTER TABLE `reputation_feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1508;
--
-- AUTO_INCREMENT for table `reset_leader_boards`
--
ALTER TABLE `reset_leader_boards`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;
--
-- AUTO_INCREMENT for table `responders`
--
ALTER TABLE `responders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1888;
--
-- AUTO_INCREMENT for table `responder_probes`
--
ALTER TABLE `responder_probes`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=858;
--
-- AUTO_INCREMENT for table `schedule_emails`
--
ALTER TABLE `schedule_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `schedule_email_groups`
--
ALTER TABLE `schedule_email_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `schedule_messages`
--
ALTER TABLE `schedule_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1432;
--
-- AUTO_INCREMENT for table `schedule_message_groups`
--
ALTER TABLE `schedule_message_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1961;
--
-- AUTO_INCREMENT for table `shortlinks`
--
ALTER TABLE `shortlinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `short_urls`
--
ALTER TABLE `short_urls`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=392204;
--
-- AUTO_INCREMENT for table `single_schedule_messages`
--
ALTER TABLE `single_schedule_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=464;
--
-- AUTO_INCREMENT for table `smsloyalties`
--
ALTER TABLE `smsloyalties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=655;
--
-- AUTO_INCREMENT for table `smsloyalties_rewards`
--
ALTER TABLE `smsloyalties_rewards`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=970;
--
-- AUTO_INCREMENT for table `smsloyalties_users_rewards`
--
ALTER TABLE `smsloyalties_users_rewards`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90654;
--
-- AUTO_INCREMENT for table `smsloyalties_users_rewards_withoutcashiers`
--
ALTER TABLE `smsloyalties_users_rewards_withoutcashiers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=821;
--
-- AUTO_INCREMENT for table `smsloyalty_users`
--
ALTER TABLE `smsloyalty_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9143;
--
-- AUTO_INCREMENT for table `smsloyalty_users_requests`
--
ALTER TABLE `smsloyalty_users_requests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28112;
--
-- AUTO_INCREMENT for table `smsloyalty_users_requests_withoutcashiers`
--
ALTER TABLE `smsloyalty_users_requests_withoutcashiers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6266;
--
-- AUTO_INCREMENT for table `smstemplates`
--
ALTER TABLE `smstemplates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `sms_credit_logs`
--
ALTER TABLE `sms_credit_logs`
  MODIFY `sms_credit_log_id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `social_profiles`
--
ALTER TABLE `social_profiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `splashes`
--
ALTER TABLE `splashes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1459;
--
-- AUTO_INCREMENT for table `stripes`
--
ALTER TABLE `stripes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subaccounts`
--
ALTER TABLE `subaccounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;
--
-- AUTO_INCREMENT for table `tables_managements`
--
ALTER TABLE `tables_managements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `telnyx_api_responses`
--
ALTER TABLE `telnyx_api_responses`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9988;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `temp_otp_users`
--
ALTER TABLE `temp_otp_users`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41835;
--
-- AUTO_INCREMENT for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=924;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1542;
--
-- AUTO_INCREMENT for table `users_cards`
--
ALTER TABLE `users_cards`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;
--
-- AUTO_INCREMENT for table `users_ratings`
--
ALTER TABLE `users_ratings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33475;
--
-- AUTO_INCREMENT for table `user_coupens`
--
ALTER TABLE `user_coupens`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154402;
--
-- AUTO_INCREMENT for table `user_coupen_logs`
--
ALTER TABLE `user_coupen_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;
--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27733;
--
-- AUTO_INCREMENT for table `user_numbers`
--
ALTER TABLE `user_numbers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1447;
--
-- AUTO_INCREMENT for table `user_number_historys`
--
ALTER TABLE `user_number_historys`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=692;
--
-- AUTO_INCREMENT for table `user_wallets`
--
ALTER TABLE `user_wallets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12212;
--
-- AUTO_INCREMENT for table `voice_messages`
--
ALTER TABLE `voice_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `waitinglist_sms_texts`
--
ALTER TABLE `waitinglist_sms_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `waiting_lists`
--
ALTER TABLE `waiting_lists`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;
--
-- AUTO_INCREMENT for table `waivers`
--
ALTER TABLE `waivers`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=453;
--
-- AUTO_INCREMENT for table `waiver_child_infos`
--
ALTER TABLE `waiver_child_infos`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `waiver_infos`
--
ALTER TABLE `waiver_infos`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13239;
--
-- AUTO_INCREMENT for table `wallet_options`
--
ALTER TABLE `wallet_options`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;
--
-- AUTO_INCREMENT for table `wifi_redirect_url_logs`
--
ALTER TABLE `wifi_redirect_url_logs`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1324241;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
