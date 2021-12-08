-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 30, 2021 at 11:26 AM
-- Server version: 5.7.33
-- PHP Version: 7.0.33-50+0~20210501.55+debian9~1.gbpd59059

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nento029_sales`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_charges`
--

CREATE TABLE `additional_charges` (
  `id` int(111) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `txnid` mediumtext,
  `company_id` int(111) NOT NULL DEFAULT '0',
  `deal_id` int(111) NOT NULL DEFAULT '0',
  `send_on` varchar(255) DEFAULT NULL,
  `charges_date` date DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `hst` float NOT NULL DEFAULT '0',
  `final_amount` float DEFAULT '0',
  `currency` varchar(50) DEFAULT NULL,
  `description` mediumtext,
  `status` int(11) NOT NULL DEFAULT '0',
  `pdf_file` text,
  `response` text,
  `payment_message` text,
  `email_id` text,
  `invoice_type` int(11) NOT NULL DEFAULT '0' COMMENT '0-additional charges, 1-manual invoice',
  `editable_invoice` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not 1 yes',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `agreement`
--

CREATE TABLE `agreement` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `client_id` int(111) NOT NULL DEFAULT '0',
  `sd_user_id` int(111) NOT NULL DEFAULT '0' COMMENT 'Scheduledown user id',
  `hub_user_id` int(111) NOT NULL DEFAULT '0',
  `hub_username` varchar(255) DEFAULT NULL,
  `hub_password` text,
  `client_type` varchar(255) DEFAULT NULL,
  `is_hub_access` int(11) NOT NULL DEFAULT '0',
  `is_wifi_access` int(11) NOT NULL DEFAULT '0',
  `is_menu_access` int(11) NOT NULL DEFAULT '0',
  `is_signage_access` int(11) NOT NULL DEFAULT '0',
  `is_scheduledown` tinyint(2) NOT NULL DEFAULT '0',
  `is_scheduledown_pro` tinyint(2) NOT NULL DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `send_sms_country_code` varchar(50) DEFAULT '1',
  `send_sms` varchar(255) DEFAULT NULL,
  `package` varchar(255) NOT NULL DEFAULT '0',
  `terms_id` int(11) NOT NULL DEFAULT '0',
  `tax_percentage` float NOT NULL DEFAULT '13',
  `plan_amount` varchar(255) DEFAULT NULL,
  `discount_percentage` varchar(255) DEFAULT NULL,
  `discount_amount` varchar(255) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `is_sendshipment` int(111) NOT NULL DEFAULT '0' COMMENT ' 0 - not send shipment, 1 - send shipment',
  `is_givefreetrial` int(11) NOT NULL DEFAULT '0',
  `trial_days` int(11) NOT NULL DEFAULT '0',
  `billing_phone_country_code` varchar(255) DEFAULT '1',
  `billing_type` int(11) NOT NULL DEFAULT '0' COMMENT '0- auto payment(Recurring), 1 - manual payment',
  `trems_agreement` text,
  `notes` text,
  `providence` varchar(255) DEFAULT NULL,
  `square_footage` varchar(255) DEFAULT NULL,
  `setup_programming` varchar(50) NOT NULL DEFAULT '0',
  `hardware_integration` varchar(255) NOT NULL DEFAULT '0',
  `profile_activation_charge` float DEFAULT '0',
  `location` int(11) NOT NULL DEFAULT '1',
  `same_as_billing_address` int(11) NOT NULL DEFAULT '0',
  `shipping_address` text,
  `shipping_streetno` text,
  `shipping_street_name` text,
  `shipping_city` text,
  `shipping_postcode` varchar(50) DEFAULT NULL,
  `shipping_province` text,
  `shipping_lat` varchar(255) DEFAULT NULL,
  `shipping_lon` varchar(255) DEFAULT NULL,
  `holder_name` varchar(255) DEFAULT NULL,
  `print_title` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `billing_address` text,
  `street_name` varchar(255) DEFAULT NULL,
  `street_no` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `log` varchar(255) DEFAULT NULL,
  `billing_phone` varchar(255) DEFAULT NULL,
  `alternate_phone_country_code` varchar(255) DEFAULT '1',
  `alternate_phone` varchar(255) DEFAULT NULL,
  `ext` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `billing_email` varchar(255) DEFAULT NULL,
  `personal_email` varchar(255) DEFAULT NULL,
  `terms` text,
  `terms_signature` text,
  `terms_signature_name` text,
  `terms_signature_image` text,
  `social_media` text,
  `online_ordering_system` int(11) NOT NULL DEFAULT '0',
  `accounting` int(11) NOT NULL DEFAULT '0',
  `human_resources` int(11) DEFAULT '0',
  `web_pos` int(11) NOT NULL DEFAULT '0',
  `pos_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_app` int(11) NOT NULL DEFAULT '0',
  `waiter_app` int(11) NOT NULL DEFAULT '0',
  `customer_app` int(11) NOT NULL DEFAULT '0',
  `plan_title` varchar(255) DEFAULT NULL,
  `pickup_app_frontend` int(11) NOT NULL DEFAULT '0',
  `web_pos_system` int(11) NOT NULL DEFAULT '0',
  `offline_mobile_pos` int(11) NOT NULL DEFAULT '0',
  `witress_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_display` int(11) NOT NULL DEFAULT '0',
  `pos_equipment` varchar(255) DEFAULT NULL,
  `booking_reservation` int(11) NOT NULL DEFAULT '0',
  `queue_management` int(11) NOT NULL DEFAULT '0',
  `pos_screen_counter` int(11) DEFAULT '0',
  `digital_signage` varchar(255) DEFAULT NULL,
  `seo_video_reviews` int(11) NOT NULL DEFAULT '0',
  `additional_screen` int(11) NOT NULL DEFAULT '0',
  `money_back_guarantee` varchar(255) DEFAULT NULL,
  `onboarding_training` varchar(255) DEFAULT NULL,
  `setup_fee` float DEFAULT '0',
  `kiosk_builder` tinyint(4) DEFAULT '0',
  `cashier_panel` tinyint(4) DEFAULT '0',
  `sms_loyalty_program` tinyint(4) DEFAULT '0',
  `access_to_data` tinyint(4) DEFAULT '0',
  `bulk_sms` tinyint(4) DEFAULT '0',
  `virtual_wallet` tinyint(4) NOT NULL DEFAULT '0',
  `automated_marketing` tinyint(4) NOT NULL DEFAULT '0',
  `scheduled_sms` tinyint(4) NOT NULL DEFAULT '0',
  `mobile_opt_in_unlimited_keywords` varchar(255) DEFAULT NULL,
  `qr_code_creation` tinyint(4) NOT NULL DEFAULT '0',
  `voice_broadcast` tinyint(4) NOT NULL DEFAULT '0',
  `two_way_sms_chat` tinyint(4) NOT NULL DEFAULT '0',
  `appointment_reminder` tinyint(4) NOT NULL DEFAULT '0',
  `web_sign_up_widgets` tinyint(4) NOT NULL DEFAULT '0',
  `smart_trackable_coupon` tinyint(4) NOT NULL DEFAULT '0',
  `smart_wifi` varchar(255) DEFAULT NULL,
  `wifi_deepanalytics` tinyint(4) NOT NULL DEFAULT '0',
  `cashier_leader_board` tinyint(4) NOT NULL DEFAULT '0',
  `sms_inclusion` varchar(255) NOT NULL DEFAULT '0',
  `voice_inclusion` varchar(255) NOT NULL DEFAULT '0',
  `is_credit_carry_forward` tinyint(4) NOT NULL DEFAULT '0',
  `job_requirements` tinyint(4) NOT NULL DEFAULT '0',
  `waivers` tinyint(4) NOT NULL DEFAULT '0',
  `reputation` tinyint(4) NOT NULL DEFAULT '0',
  `signage` tinyint(4) NOT NULL DEFAULT '0',
  `nento_ad` int(11) NOT NULL DEFAULT '0',
  `addon` varchar(255) DEFAULT NULL,
  `addon_voice` varchar(255) DEFAULT NULL,
  `additional_number` varchar(255) DEFAULT NULL,
  `signature_name` varchar(255) DEFAULT NULL,
  `signature` longtext,
  `representative_signature_name` varchar(255) DEFAULT NULL,
  `representative_signature` longtext,
  `representativesign_signature_name` varchar(255) DEFAULT NULL,
  `representativesign_signature` longtext,
  `authorizationsign_signature_name` varchar(255) DEFAULT NULL,
  `authorizationsign_signature` longtext,
  `customerinitials_signature_name` varchar(255) DEFAULT NULL,
  `customerinitials_signature` longtext,
  `payment_method` int(11) NOT NULL DEFAULT '0',
  `payment_type` int(11) NOT NULL DEFAULT '0',
  `routing_number` varchar(50) DEFAULT NULL,
  `transit` varchar(255) DEFAULT NULL,
  `inst` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `agreement_date` date DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_title` varchar(255) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `close` int(11) DEFAULT '0',
  `last_seen` datetime DEFAULT NULL,
  `send_email` int(11) NOT NULL DEFAULT '1',
  `last_sent_sms` datetime DEFAULT NULL,
  `customer_ip` varchar(255) DEFAULT NULL,
  `customer_last_change` datetime DEFAULT NULL,
  `representative_signature_image` varchar(255) DEFAULT NULL,
  `representativesign_signature_image` varchar(255) DEFAULT NULL,
  `signature_image` varchar(255) DEFAULT NULL,
  `authorizationsign_signature_image` varchar(255) DEFAULT NULL,
  `signature_mail` int(11) NOT NULL DEFAULT '0',
  `payment_mail` int(11) NOT NULL DEFAULT '0',
  `logo_mail` int(11) NOT NULL DEFAULT '0',
  `dex_customer_token` text,
  `dex_paymethod_token` text,
  `dex_transaction_id` text,
  `customer_code` varchar(255) DEFAULT NULL,
  `stripe_card_id` text,
  `payment_currency` varchar(55) NOT NULL DEFAULT 'cad',
  `payment_images` text,
  `profiles_reponse` text,
  `is_create_profile` int(11) NOT NULL DEFAULT '0',
  `customer_support` text,
  `whole_sale_sms_api` int(11) NOT NULL DEFAULT '0',
  `whole_sale_email_api` int(11) NOT NULL DEFAULT '0',
  `bulk_email` int(11) NOT NULL DEFAULT '0',
  `sweeptake_form` int(11) NOT NULL DEFAULT '0',
  `tablet` tinyint(5) DEFAULT '0',
  `router` tinyint(5) DEFAULT '0',
  `stand` tinyint(5) DEFAULT '0',
  `terminal` int(5) NOT NULL DEFAULT '0',
  `first_month_charge` tinyint(2) DEFAULT '0' COMMENT 'For generate agreement link',
  `last_month_charge` tinyint(2) DEFAULT '0' COMMENT 'For generate agreement link',
  `deposit_amount` int(11) DEFAULT '0' COMMENT 'For generate agreement link',
  `deposit_note` text COMMENT 'For generate agreement link',
  `charge_amount` int(11) DEFAULT '0' COMMENT 'For generate agreement link',
  `charge_note` text COMMENT 'For generate agreement link',
  `firstTimeCharge` varchar(50) DEFAULT '0' COMMENT 'For generate agreement link'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `agreement_setting`
--

CREATE TABLE `agreement_setting` (
  `id` int(111) NOT NULL,
  `plan_amount` varchar(30) NOT NULL DEFAULT '0',
  `tax_per` varchar(30) NOT NULL DEFAULT '0',
  `first_month_charge` tinyint(2) NOT NULL DEFAULT '0',
  `last_month_charge` tinyint(2) NOT NULL DEFAULT '0',
  `deposit` tinyint(2) NOT NULL DEFAULT '0',
  `deposit_amount` varchar(30) NOT NULL DEFAULT '0',
  `deposit_note` text NOT NULL,
  `charge` tinyint(2) NOT NULL DEFAULT '0',
  `charge_amount` varchar(30) NOT NULL DEFAULT '0',
  `charge_note` text NOT NULL,
  `first_time_charge` varchar(30) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission` int(1) NOT NULL,
  `read` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attempt_email_sms_logs`
--

CREATE TABLE `attempt_email_sms_logs` (
  `id` int(111) NOT NULL,
  `deal_id` int(111) DEFAULT NULL,
  `email_log_id` int(111) DEFAULT NULL,
  `sms_log_id` int(111) DEFAULT NULL,
  `sales_person_id` int(111) DEFAULT NULL,
  `send_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `backup`
--

CREATE TABLE `backup` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `buckets`
--

CREATE TABLE `buckets` (
  `id` bigint(111) NOT NULL,
  `name` text CHARACTER SET utf8,
  `source` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `message` text,
  `funnel_id` int(111) NOT NULL DEFAULT '0',
  `pipeline` int(111) DEFAULT '0',
  `stage` int(111) NOT NULL DEFAULT '0',
  `group_id` int(111) NOT NULL DEFAULT '0',
  `assign_to` int(111) DEFAULT '0',
  `deal_id` int(11) DEFAULT '0',
  `assign_on` datetime DEFAULT NULL,
  `added_by` int(111) DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(111) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `profile_pic` text,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_logo` text,
  `describe_company` varchar(255) DEFAULT NULL COMMENT ' 1=small bussiness,2=large brand,3=agengy,4=other ',
  `password` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(20) DEFAULT NULL,
  `billing_type` int(11) NOT NULL DEFAULT '0' COMMENT '0 - automatic, 1 - manual billing',
  `is_profile_create` int(111) NOT NULL DEFAULT '0',
  `is_sendshipment` int(11) DEFAULT '0' COMMENT ' 0 - not send shipment, 1 - send shipment ',
  `is_hub_access` int(11) NOT NULL DEFAULT '0',
  `is_wifi_access` int(11) NOT NULL DEFAULT '0',
  `is_menu_access` int(11) NOT NULL DEFAULT '0',
  `is_signage_access` int(11) NOT NULL DEFAULT '0',
  `is_scheduledown` int(111) NOT NULL DEFAULT '0',
  `is_scheduledown_pro` int(111) NOT NULL DEFAULT '0',
  `total_loc` int(111) NOT NULL DEFAULT '0',
  `total_location` int(111) NOT NULL DEFAULT '0',
  `user_payment_method` tinyint(2) DEFAULT '0' COMMENT '0=Stripe,1=Dex',
  `dex_customer_token` varchar(255) DEFAULT NULL,
  `dex_paymethod_token` varchar(255) DEFAULT NULL,
  `dex_transaction_id` text,
  `dex_subscriptionid` varchar(255) DEFAULT NULL,
  `stripe_plan_id` text,
  `customer_code` text,
  `stripe_card_id` text,
  `subscriptionid` text,
  `is_schedule_subscription` int(11) NOT NULL DEFAULT '0',
  `schedule_subscription_id` text,
  `payment_currency` varchar(55) NOT NULL DEFAULT 'cad',
  `sms_balance` int(111) NOT NULL DEFAULT '0',
  `voice_balance` int(111) NOT NULL DEFAULT '0',
  `is_sms_credit_purchased` int(11) NOT NULL DEFAULT '0',
  `total_sms_credit_purchased` int(111) NOT NULL DEFAULT '0',
  `is_voice_credit_purchased` int(11) NOT NULL DEFAULT '0',
  `total_voice_credit_purchased` int(111) NOT NULL DEFAULT '0',
  `fax_number` varchar(100) DEFAULT NULL,
  `assigned_number` varchar(100) NOT NULL DEFAULT '0',
  `phone_sid` varchar(255) DEFAULT NULL,
  `country_code` varchar(20) DEFAULT NULL,
  `user_country` varchar(100) DEFAULT NULL,
  `defaultgreeting` varchar(255) NOT NULL DEFAULT ' Leave a message for THIS VOICEMAIL at the beep and Press the star key when finished. ',
  `active` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `file_name` text,
  `email_alerts` int(11) NOT NULL DEFAULT '0' COMMENT ' 1=''As they happen'',2=''dally summary'' ',
  `email_alert_options` int(11) NOT NULL DEFAULT '0' COMMENT ' 0=on,1=off ',
  `low_sms_balances` int(111) NOT NULL DEFAULT '0',
  `email_alert_credit_options` int(11) NOT NULL DEFAULT '0' COMMENT ' 0=on,1=off ',
  `sms_credit_balance_email_alerts` int(11) NOT NULL DEFAULT '0' COMMENT ' 0=no,1=yes ',
  `low_voice_balances` int(111) NOT NULL DEFAULT '0',
  `VM_credit_balance_email_alerts` int(111) NOT NULL DEFAULT '0' COMMENT ' 0=no,1=yes',
  `account_activated` varchar(255) DEFAULT NULL,
  `register` int(11) NOT NULL DEFAULT '0',
  `package` int(11) NOT NULL DEFAULT '0',
  `plan_recurring_amount` float DEFAULT '0',
  `is_trial` int(11) NOT NULL DEFAULT '0',
  `is_menu_free_user` int(11) NOT NULL DEFAULT '0',
  `is_freeplan_cancel` int(11) NOT NULL DEFAULT '0',
  `is_trial_expire` int(11) NOT NULL DEFAULT '0',
  `trial_package_start_date` datetime DEFAULT NULL,
  `trial_package_end_date` datetime DEFAULT NULL,
  `next_renewal_dates` date DEFAULT NULL,
  `number_package` int(11) NOT NULL DEFAULT '0',
  `number_next_renewal_dates` date DEFAULT NULL,
  `number_limit_set` int(11) NOT NULL DEFAULT '0' COMMENT ' 0=off,1=on ',
  `pay_activation_fees_active` int(11) NOT NULL DEFAULT '0',
  `incomingsms_alerts` int(11) NOT NULL DEFAULT '0' COMMENT ' 0=on,1=off ',
  `incomingsms_emailalerts` int(11) NOT NULL DEFAULT '0' COMMENT ' 1=''Email'',2=''SMS'' ',
  `smsalerts_number` varchar(50) DEFAULT NULL,
  `makepurchases` int(11) NOT NULL DEFAULT '0',
  `timezone` varchar(255) DEFAULT NULL,
  `welcome_msg_type` int(11) NOT NULL DEFAULT '0',
  `mp3` varchar(255) DEFAULT NULL,
  `api_type` int(11) NOT NULL DEFAULT '0',
  `sms` int(11) NOT NULL DEFAULT '0',
  `mms` int(11) NOT NULL DEFAULT '0',
  `voice` int(11) NOT NULL DEFAULT '0',
  `fax` int(11) NOT NULL DEFAULT '0',
  `number_limit` int(11) NOT NULL DEFAULT '0',
  `number_limit_count` int(111) NOT NULL DEFAULT '0',
  `birthday_wishes` int(11) NOT NULL DEFAULT '0',
  `capture_email_name` int(11) NOT NULL DEFAULT '0',
  `broadcast` varchar(20) DEFAULT NULL,
  `email_to_sms` int(11) NOT NULL DEFAULT '0',
  `incomingcall_forward` int(11) NOT NULL DEFAULT '0',
  `assign_callforward` varchar(255) DEFAULT NULL,
  `callforward_number` varchar(255) DEFAULT NULL,
  `IP_address` varchar(100) DEFAULT NULL,
  `alphasender` int(11) NOT NULL DEFAULT '0',
  `incoming_nonkeyword` int(11) NOT NULL DEFAULT '0',
  `nonkeyword_autoresponse` varchar(255) DEFAULT NULL,
  `sid` varchar(255) DEFAULT NULL,
  `authtoken` varchar(255) DEFAULT NULL,
  `lastlogin_username` varchar(255) DEFAULT NULL,
  `lastlogin_datetime` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `OTP` varchar(50) DEFAULT NULL,
  `is_otp_verified` int(11) NOT NULL DEFAULT '0',
  `is_email_verified` int(11) NOT NULL DEFAULT '0',
  `is_payment_verified` int(11) NOT NULL DEFAULT '0',
  `register_type` int(11) NOT NULL DEFAULT '0',
  `is_profile_active` int(11) NOT NULL DEFAULT '0',
  `is_plan_updated` int(11) NOT NULL DEFAULT '0',
  `plan_start_date` datetime DEFAULT NULL,
  `plan_cancel_date` datetime DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `street_address` text,
  `address_line2` text,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `min_session_length` varchar(255) DEFAULT NULL,
  `max_session_length` varchar(255) DEFAULT NULL,
  `session_push` varchar(255) DEFAULT NULL,
  `session_time_out` varchar(255) DEFAULT NULL,
  `cummulative_income` float NOT NULL DEFAULT '0',
  `relevant_support` varchar(255) DEFAULT NULL,
  `relevant_support_other` text,
  `geoplugin_reponse` text,
  `signage_response` text,
  `signage_id` text,
  `signage_upd_response` text,
  `signage_login_res` text,
  `signage_login_token` text,
  `hub_login_token` text,
  `is_probes_status` int(11) NOT NULL DEFAULT '0',
  `is_new_user` int(11) DEFAULT '0' COMMENT ' 1 - new user signup, 0 - old user , this status use for display buy number help tour ',
  `oauth_provider` varchar(255) DEFAULT NULL,
  `oauth_uid` varchar(255) DEFAULT NULL,
  `deal_id` int(111) NOT NULL DEFAULT '0',
  `agreement_id` int(111) NOT NULL DEFAULT '0',
  `sd_user_id` int(111) NOT NULL DEFAULT '0' COMMENT 'Scheduledown user id',
  `about` text,
  `waiter_kitchenToken` text,
  `outlet` varchar(255) DEFAULT NULL,
  `counter` int(11) DEFAULT NULL,
  `screen_counter` int(11) DEFAULT NULL,
  `currently_used_screen` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(4) DEFAULT '0',
  `is_super_admin` tinyint(4) NOT NULL DEFAULT '0',
  `user_signup_type` int(111) DEFAULT '0' COMMENT '0=Sales,1=Hub frontend',
  `domain_name` varchar(255) DEFAULT NULL,
  `db_name` varchar(255) DEFAULT NULL,
  `profiles_reponse` text,
  `zero_bounce_key` text,
  `is_blocked` int(11) NOT NULL DEFAULT '0' COMMENT ' 0-active, 1-blocked ',
  `is_first_payment_response` int(2) NOT NULL DEFAULT '0' COMMENT 'on first user creation this will be as 0 and once we get response from stripe as invoice success will mark it as 1 after that it will work based on stripe response, purpose of this to avoid multiple credit inclusion',
  `is_fail_payment` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = Not, 1 = Yes',
  `fail_payment_reason` text,
  `is_profile_cancel` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = active, 1 = cancel',
  `is_refund_process` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = not, 1 = done',
  `ionic_auth` text,
  `device_token` text COMMENT ' Push notification token ',
  `device_type` varchar(50) DEFAULT NULL COMMENT ' For Push notification ',
  `withoutrecurring_status` int(11) NOT NULL DEFAULT '0' COMMENT '0 - without recurring, 1 - void, 2 - on hold, 3 - cancelled',
  `hub_training` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `signage_training` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `menuonline_training` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `recived_creditcard_details` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `master_sheet_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0="pending",1="completed",2="deleted"',
  `master_note` text,
  `reoccurring_date_added` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 = Yes, 2 = No, 3 = Void Cheque',
  `void_cheque_successful` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 Yes',
  `received_menu_from_customer` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 Yes',
  `menu_received_date` datetime DEFAULT NULL,
  `menu_creation` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 no, 1 yes',
  `com_digital_signage` int(5) NOT NULL DEFAULT '0',
  `com_router` int(5) NOT NULL DEFAULT '0',
  `com_tablet` int(5) NOT NULL DEFAULT '0',
  `com_stand` int(5) NOT NULL DEFAULT '0',
  `com_terminal` int(5) NOT NULL DEFAULT '0',
  `followup_threeday` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 no 1 yes',
  `followup_fourteenday` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 no 1 yes'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients_permissions`
--

CREATE TABLE `clients_permissions` (
  `id` bigint(111) NOT NULL,
  `plan_id` int(111) NOT NULL DEFAULT '0',
  `client_id` int(111) NOT NULL DEFAULT '0',
  `pickup_app_frontend` int(11) NOT NULL DEFAULT '0',
  `web_pos_system` int(11) NOT NULL DEFAULT '0',
  `offline_mobile_pos` int(11) NOT NULL DEFAULT '0',
  `witress_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_display` int(11) NOT NULL DEFAULT '0',
  `pos_equipment` varchar(255) DEFAULT NULL,
  `booking_reservation` int(11) NOT NULL DEFAULT '0',
  `queue_management` int(11) NOT NULL DEFAULT '0',
  `digital_signage` varchar(255) DEFAULT NULL,
  `sms_loyalty_program` int(11) NOT NULL DEFAULT '0',
  `kiosk_builder` int(11) NOT NULL DEFAULT '0',
  `cashier_panel` int(11) NOT NULL DEFAULT '0',
  `access_to_data` int(11) NOT NULL DEFAULT '0',
  `bulk_sms` int(11) NOT NULL DEFAULT '0',
  `virtual_wallet` int(11) NOT NULL DEFAULT '0',
  `automated_marketing` int(11) NOT NULL DEFAULT '0',
  `scheduled_sms` int(11) NOT NULL DEFAULT '0',
  `mobile_opt_in_unlimited_keywords` varchar(255) DEFAULT NULL,
  `qr_code_creation` int(11) NOT NULL DEFAULT '0',
  `voice_broadcast` int(11) NOT NULL DEFAULT '0',
  `two_way_sms_chat` int(11) NOT NULL DEFAULT '0',
  `appointment_reminder` int(11) NOT NULL DEFAULT '0',
  `web_sign_up_widgets` int(11) NOT NULL DEFAULT '0',
  `smart_trackable_coupon` int(11) NOT NULL DEFAULT '0',
  `smart_wifi` varchar(255) DEFAULT NULL,
  `job_requirements` int(11) NOT NULL DEFAULT '0',
  `waivers` int(11) NOT NULL DEFAULT '0',
  `reputation` int(11) NOT NULL DEFAULT '0',
  `cashier_leader_board` int(11) NOT NULL DEFAULT '0',
  `seo_video_reviews` int(11) NOT NULL DEFAULT '0',
  `sms_inclusion` int(111) NOT NULL DEFAULT '0',
  `voice_inclusion` int(111) NOT NULL DEFAULT '0',
  `is_credit_carry_forward` int(11) NOT NULL DEFAULT '0',
  `addon` int(111) NOT NULL DEFAULT '0',
  `addon_voice` int(111) NOT NULL DEFAULT '0',
  `additional_number` float NOT NULL DEFAULT '0',
  `additional_screen` int(111) NOT NULL DEFAULT '0',
  `contract` varchar(255) DEFAULT NULL,
  `trial_days` int(111) NOT NULL DEFAULT '0',
  `setup_fee` float NOT NULL DEFAULT '0',
  `money_back_guarantee` int(11) NOT NULL DEFAULT '0',
  `onboarding_training` varchar(255) DEFAULT NULL,
  `whole_sale_email_api` int(11) NOT NULL DEFAULT '0',
  `whole_sale_sms_api` int(11) NOT NULL DEFAULT '0',
  `sweeptake_form` int(11) NOT NULL DEFAULT '0',
  `customer_support` varchar(225) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `groups` mediumtext,
  `name` varchar(255) DEFAULT NULL,
  `known_as` varchar(255) DEFAULT NULL,
  `contact_person_name` varchar(255) DEFAULT NULL,
  `alternat_person_name` varchar(100) DEFAULT NULL,
  `phone_country_code` varchar(255) DEFAULT '1',
  `phone` varchar(100) DEFAULT NULL,
  `alternate_phone_country_code` varchar(255) DEFAULT '1',
  `alternate_phone` varchar(100) DEFAULT NULL,
  `email` text,
  `alternate_email` varchar(255) DEFAULT NULL,
  `bussiness_email` varchar(50) DEFAULT NULL,
  `address` mediumtext,
  `billing_address` text,
  `street_name` varchar(255) DEFAULT NULL,
  `street_no` varchar(255) DEFAULT NULL,
  `city` varchar(250) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `providence` varchar(55) DEFAULT NULL,
  `lat` varchar(55) DEFAULT NULL,
  `log` varchar(55) DEFAULT NULL,
  `shipping_address` text,
  `shipping_street_name` varchar(55) DEFAULT NULL,
  `shipping_streetno` varchar(55) DEFAULT NULL,
  `shipping_city` varchar(55) DEFAULT NULL,
  `shipping_postcode` varchar(55) DEFAULT NULL,
  `shipping_province` varchar(55) DEFAULT NULL,
  `shipping_lat` varchar(55) DEFAULT NULL,
  `shipping_lon` varchar(55) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `website` varchar(255) DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `package` varchar(255) DEFAULT NULL,
  `wifi_installation_date` date DEFAULT NULL,
  `kiosk_installation_date` date DEFAULT NULL,
  `notes` mediumtext,
  `notes_two` text,
  `singup_date` date DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `call_logs_per_client` varchar(255) DEFAULT NULL,
  `call_logs_per_client_two` text,
  `platform` varchar(255) DEFAULT NULL COMMENT 'only wifi client data store',
  `menuthat` varchar(255) DEFAULT NULL COMMENT 'only wifi client data store',
  `wifi_status` varchar(255) DEFAULT NULL COMMENT 'only wifi client data store',
  `ar_customers` varchar(255) DEFAULT NULL COMMENT 'only wifi client data store',
  `wifi_status_two` varchar(255) DEFAULT NULL COMMENT 'only wifi client data store',
  `skype` mediumtext,
  `facebook` mediumtext,
  `linkedIn` mediumtext,
  `twitter` mediumtext,
  `youtube` mediumtext,
  `google_plus` mediumtext,
  `pinterest` mediumtext,
  `tumblr` mediumtext,
  `instagram` mediumtext,
  `github` mediumtext,
  `digg` mediumtext,
  `status` int(111) DEFAULT '0',
  `is_take_first_charge` int(11) NOT NULL DEFAULT '0' COMMENT '0- not take, 1-take charge',
  `is_manual_payment_set` int(2) NOT NULL DEFAULT '0',
  `is_hub_import` int(1) NOT NULL DEFAULT '0' COMMENT '0-manual create, 1-import backend hub client, 2-import wifi client, 3- Nento Ad, 4-Frontend Hub',
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0- not delete, 1 - delete',
  `my_notes` text,
  `priority_level` int(2) NOT NULL DEFAULT '0' COMMENT '1 = Low,2 = Medium,3 = High',
  `hub_training` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `signage_training` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `menuonline_training` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `recived_creditcard_details` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `dig_signage_deleted` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 => No, 1 => Yes',
  `dig_signage_deleted_at` datetime DEFAULT NULL,
  `dig_signage_deleted_by` int(11) DEFAULT NULL,
  `hub_deleted` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 => No, 1 => Yes',
  `hub_deleted_at` datetime DEFAULT NULL,
  `hub_deleted_by` int(11) DEFAULT NULL,
  `menuonline_deleted` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 => No, 1 => Yes',
  `menuonline_deleted_at` datetime DEFAULT NULL,
  `menuonline_deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `company_contact_person`
--

CREATE TABLE `company_contact_person` (
  `id` int(111) NOT NULL,
  `company_id` int(111) NOT NULL DEFAULT '0',
  `deal_id` int(111) NOT NULL DEFAULT '0',
  `contact_first_name` varchar(255) DEFAULT NULL,
  `contact_last_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `company_status`
--

CREATE TABLE `company_status` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` mediumtext NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `description` mediumtext,
  `website` mediumtext,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `facebook` varchar(100) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `linkedIn` varchar(100) NOT NULL,
  `skype` varchar(100) NOT NULL,
  `youtube` mediumtext,
  `google_plus` mediumtext,
  `pinterest` mediumtext,
  `tumblr` mediumtext,
  `instagram` mediumtext,
  `github` mediumtext,
  `digg` mediumtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_deals`
--

CREATE TABLE `contact_deals` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cron_logs`
--

CREATE TABLE `cron_logs` (
  `id` bigint(111) NOT NULL,
  `type` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `custom_company`
--

CREATE TABLE `custom_company` (
  `id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_contacts`
--

CREATE TABLE `custom_contacts` (
  `id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `contact_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_deals`
--

CREATE TABLE `custom_deals` (
  `id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `deal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(2) NOT NULL,
  `module` int(2) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deal`
--

CREATE TABLE `deal` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `known_as` varchar(255) DEFAULT NULL,
  `country_code` varchar(255) DEFAULT '1',
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `personal_email` varchar(255) DEFAULT NULL,
  `contract_email` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `price` float DEFAULT '0',
  `last_name` varchar(255) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `bussiness_country_code` varchar(255) DEFAULT '1',
  `business_phone` varchar(255) DEFAULT NULL,
  `business_address` text,
  `product` varchar(255) DEFAULT NULL,
  `deposit` varchar(255) DEFAULT NULL,
  `notes` text,
  `status` int(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `onboarding_attend_userid` int(111) NOT NULL DEFAULT '0',
  `demo_attend_userid` int(111) NOT NULL DEFAULT '0',
  `attend_sales_person` int(10) DEFAULT NULL COMMENT 'this refer by default selected sales person or admin for general tab',
  `stage_id` int(11) DEFAULT NULL,
  `pipeline_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `is_company` int(11) NOT NULL DEFAULT '0',
  `is_hubprofile` int(11) NOT NULL DEFAULT '0',
  `permission` mediumtext,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `funnel_id` int(111) NOT NULL DEFAULT '0',
  `last_funnel_notification` int(111) NOT NULL DEFAULT '0',
  `last_notification_sent_on` datetime DEFAULT NULL,
  `is_send_funnel` int(11) NOT NULL DEFAULT '1',
  `is_funnel_email_send` int(111) NOT NULL DEFAULT '0',
  `is_funnel_email_send_new` int(111) NOT NULL DEFAULT '0',
  `is_funnel_sms_send` int(111) NOT NULL DEFAULT '0',
  `is_onboarding` int(11) NOT NULL DEFAULT '0' COMMENT '0-not received, 1-data recieved',
  `onboarding_datetime` datetime DEFAULT NULL,
  `is_onboarding_link_access` int(11) NOT NULL DEFAULT '0',
  `onboarding_link_access_on` datetime DEFAULT NULL,
  `onboarding_send_email` varchar(255) DEFAULT NULL,
  `is_onboarding_sentsms` int(11) NOT NULL DEFAULT '0',
  `sent_sms` int(11) NOT NULL DEFAULT '0',
  `sent_email` int(11) NOT NULL DEFAULT '0',
  `demo_reminder` varchar(255) DEFAULT '0' COMMENT '1-Immediate, 2-day before, 3-same day, 4-before hour',
  `demo_reminder_email_content` text,
  `demo_reminder_sms_content` text,
  `demo_sameday_reminder_email_descripion` text,
  `demo_sameday_reminder_sms_descripion` text,
  `demo_1hourbef_reminder_email_descripion` text,
  `demo_1hourbef_reminder_sms_descripion` text,
  `demo_event_datetime` datetime DEFAULT NULL,
  `demo_event_client_datetime` datetime DEFAULT NULL COMMENT 'date-time store depend on client timezone wise',
  `demo_event_name` varchar(255) DEFAULT NULL,
  `demo_event_description` text,
  `demo_send_email` varchar(255) DEFAULT NULL,
  `demo_email_subject` text,
  `demo_email_description` text,
  `demo_sms_description` text,
  `demo_reattend_on` datetime DEFAULT NULL,
  `demo_status` int(11) NOT NULL DEFAULT '1' COMMENT '1 - attend, 2 - not attend',
  `dex_customer_token` text,
  `dex_paymethod_token` varchar(255) DEFAULT NULL,
  `dex_transaction_id` text,
  `dex_subscriptionid` varchar(255) DEFAULT NULL,
  `stripe_plan_id` text,
  `subscriptionid` text,
  `customer_code` text COMMENT 'payment customer code',
  `stripe_card_id` text,
  `is_schedule_subscription` int(11) DEFAULT '0',
  `schedule_subscription_id` text,
  `bambora_recurring_accountid` varchar(255) DEFAULT NULL COMMENT 'only for WIFI company customer',
  `next_renewal_dates` date DEFAULT NULL,
  `profiles_reponse` text,
  `is_sms_unsubscribe` int(11) NOT NULL DEFAULT '0',
  `is_email_unsubscribe` int(11) NOT NULL DEFAULT '0',
  `is_shipment_delivery` int(11) NOT NULL DEFAULT '0' COMMENT '0-not shipment delivery, 1-shipment delivery',
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0- not delete, 1 - delete',
  `is_hide` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = Show , 1 = Hide',
  `demo_intrest` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 = may be later, 2 = not interested for now',
  `demo_contact_back` int(2) DEFAULT NULL,
  `demo_contact_back_date` datetime DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deal_duties`
--

CREATE TABLE `deal_duties` (
  `id` int(111) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `deal_id` int(111) DEFAULT NULL,
  `dutie_id` int(111) DEFAULT NULL,
  `dutie_detail_id` int(111) DEFAULT NULL,
  `group_id` varchar(255) DEFAULT NULL,
  `remark` text,
  `priority` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = low, 1 = medium, 2 = high',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = assigned, 1 = done, 2 = working on it, 3  = stuck, 4 = Recheck',
  `is_due_date` int(2) NOT NULL DEFAULT '0' COMMENT '0 - not due date , 1 - due date',
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL,
  `created_by` int(111) DEFAULT '0',
  `updated_by` int(111) NOT NULL DEFAULT '0',
  `assigned_by` int(111) DEFAULT '0',
  `completed_by` int(111) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deal_user_duties`
--

CREATE TABLE `deal_user_duties` (
  `id` int(111) NOT NULL,
  `deal_id` int(111) DEFAULT NULL,
  `dutie_id` int(111) DEFAULT NULL,
  `dutie_detail_id` int(111) DEFAULT NULL,
  `user_id` int(111) DEFAULT NULL,
  `detail` text COMMENT '	0 = assigned, 1 = done, 2 = working on it, 3 = stuck',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `discussion`
--

CREATE TABLE `discussion` (
  `id` int(11) NOT NULL,
  `message` mediumtext NOT NULL,
  `deal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent` int(2) NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `duties`
--

CREATE TABLE `duties` (
  `id` int(11) NOT NULL,
  `duties_name` varchar(255) NOT NULL,
  `group_id` varchar(255) DEFAULT NULL,
  `duties_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0- as single , 1- as multiple',
  `is_default` int(2) NOT NULL DEFAULT '0' COMMENT '0 - defualt admin duty, 1 - user created duty from assign duty in deal',
  `created_by` int(111) DEFAULT '0' COMMENT '0 = by admin, other means created by user',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dutiesdetails`
--

CREATE TABLE `dutiesdetails` (
  `id` int(11) NOT NULL,
  `duties_id` int(11) NOT NULL,
  `duties_name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplates`
--

CREATE TABLE `emailtemplates` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `template_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 => demo,2 => marketing,3 => information,4 => attempt',
  `template_name` varchar(255) DEFAULT NULL,
  `emailsubject` text,
  `content` longtext,
  `html_content` longtext,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `trans_id` text COLLATE utf8_unicode_ci,
  `email_id` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `deal_id` int(111) DEFAULT '0',
  `task_id` int(111) NOT NULL DEFAULT '0',
  `agreement_id` int(11) NOT NULL DEFAULT '0',
  `funnel_id` int(111) NOT NULL DEFAULT '0',
  `funnel_detail_id` int(111) NOT NULL DEFAULT '0',
  `emailtemplate_id` int(111) NOT NULL DEFAULT '0',
  `sendto` text COLLATE utf8_unicode_ci,
  `sendfrom` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_content` longtext COLLATE utf8_unicode_ci,
  `route` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inbox',
  `msg_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `inbox_type` tinyint(4) NOT NULL DEFAULT '1',
  `email_status` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_type_text` text COLLATE utf8_unicode_ci,
  `error_message` text COLLATE utf8_unicode_ci,
  `email_send_response` longtext COLLATE utf8_unicode_ci,
  `email_response` text COLLATE utf8_unicode_ci,
  `customer_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_email_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 - sign, 2 - logo, 3  - payment',
  `created` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_template_types`
--

CREATE TABLE `email_template_types` (
  `id` int(111) NOT NULL,
  `template_type` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `event_reminder` varchar(255) DEFAULT NULL,
  `email_subject` text,
  `event_remider_content` text,
  `start_date` date NOT NULL,
  `time` time DEFAULT NULL,
  `end_date` date NOT NULL,
  `end_time` time DEFAULT NULL COMMENT 'time store only for create event in calendar',
  `color` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `event_id` text COMMENT 'google calendar event id',
  `deal_id` int(111) DEFAULT '0',
  `booked_by` int(11) DEFAULT '0',
  `created_from` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = none, 1 = scheduled task under company',
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_category`
--

CREATE TABLE `expenses_category` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_leads_logs`
--

CREATE TABLE `facebook_leads_logs` (
  `id` bigint(255) NOT NULL,
  `response` text,
  `assign_result` text,
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 - facebook, 1 - linkedin',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_leads_stats`
--

CREATE TABLE `facebook_leads_stats` (
  `id` int(111) NOT NULL,
  `leads_month` varchar(255) DEFAULT NULL,
  `leads_year` varchar(255) DEFAULT NULL,
  `total_spend` float DEFAULT NULL,
  `cost_per_result` float DEFAULT NULL,
  `cost_per_sale` float DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq_category`
--

CREATE TABLE `faq_category` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `user_permission` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `funnel`
--

CREATE TABLE `funnel` (
  `id` bigint(111) NOT NULL,
  `unique_id` text,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `funnel_details`
--

CREATE TABLE `funnel_details` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `funnel_unique_id` text,
  `funnel_position` int(111) NOT NULL,
  `is_send_email` int(11) DEFAULT '0',
  `emailtemplate` int(11) NOT NULL DEFAULT '0',
  `is_send_sms` int(11) NOT NULL DEFAULT '0',
  `smstemplate` int(11) NOT NULL DEFAULT '0',
  `send_on` int(11) NOT NULL DEFAULT '0',
  `send_type` int(11) NOT NULL DEFAULT '0' COMMENT '1-Immediate, 2-Days, 3-Hours, 4-Minutes',
  `send_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `funnel_logs`
--

CREATE TABLE `funnel_logs` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `funnel_id` int(111) NOT NULL DEFAULT '0',
  `funnel_detail_id` int(111) NOT NULL DEFAULT '0',
  `deal_id` int(111) NOT NULL DEFAULT '0',
  `email_id` text,
  `email_to` varchar(255) NOT NULL DEFAULT '0',
  `email_send_response` text,
  `emailtemplate_id` int(111) DEFAULT '0',
  `sms_id` text,
  `sms_to` varchar(255) DEFAULT NULL,
  `sms_send_response` text,
  `smstemplate_id` int(111) NOT NULL DEFAULT '0',
  `is_notification_send` int(111) NOT NULL DEFAULT '0',
  `is_skip_notification` int(111) NOT NULL DEFAULT '0',
  `is_email_skip` int(11) NOT NULL DEFAULT '0',
  `is_sms_skip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gallary`
--

CREATE TABLE `gallary` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_note` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gallary_image`
--

CREATE TABLE `gallary_image` (
  `id` int(11) NOT NULL,
  `gallary_id` int(11) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `deal_name` varchar(255) NOT NULL,
  `known_as` varchar(255) NOT NULL,
  `reason` mediumtext,
  `pipeline` varchar(250) NOT NULL,
  `stage` varchar(250) NOT NULL,
  `user` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `status` smallint(2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hubemailtemplates`
--

CREATE TABLE `hubemailtemplates` (
  `id` bigint(111) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `template_type` int(11) DEFAULT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `emailsubject` text,
  `content` longtext,
  `html_content` longtext,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hub_email_logs`
--

CREATE TABLE `hub_email_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `trans_id` text COLLATE utf8_unicode_ci,
  `email_id` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `deal_id` int(111) DEFAULT '0',
  `task_id` int(111) NOT NULL DEFAULT '0',
  `agreement_id` int(11) NOT NULL DEFAULT '0',
  `funnel_id` int(111) NOT NULL DEFAULT '0',
  `funnel_detail_id` int(111) NOT NULL DEFAULT '0',
  `emailtemplate_id` int(111) NOT NULL DEFAULT '0',
  `sendto` text COLLATE utf8_unicode_ci,
  `sendfrom` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_content` longtext COLLATE utf8_unicode_ci,
  `route` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inbox',
  `msg_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `inbox_type` tinyint(4) NOT NULL DEFAULT '1',
  `email_status` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_type_text` text COLLATE utf8_unicode_ci,
  `error_message` text COLLATE utf8_unicode_ci,
  `email_send_response` longtext COLLATE utf8_unicode_ci,
  `email_response` text COLLATE utf8_unicode_ci,
  `customer_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_email_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 - sign, 2 - logo, 3  - payment',
  `created` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `inventory_type` int(11) NOT NULL DEFAULT '0' COMMENT '0-bulk, 1-serial',
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `mac_address` varchar(255) DEFAULT NULL,
  `qty` float NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_client`
--

CREATE TABLE `inventory_client` (
  `id` int(111) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `location_id` int(111) NOT NULL DEFAULT '0',
  `user_id` int(111) NOT NULL DEFAULT '0',
  `inventory_id` int(111) NOT NULL DEFAULT '0',
  `deal_id` int(111) NOT NULL DEFAULT '0',
  `tracking_no` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `qty` int(111) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0-not delivery, 1-delivery',
  `pdf_file` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `deal_id` int(111) NOT NULL DEFAULT '0' COMMENT 'Only for WIFI Company Customer',
  `txnid` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1 - first payment',
  `invoice_billing_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1  =auto billing, 2 = manual billing',
  `is_success` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 = success, 2 = fail',
  `invoice_type` int(2) NOT NULL DEFAULT '1' COMMENT '0 = First time payment 1 = Plan Subscribe  2 = Plan Reoccurring  3 = Number Subscribe  4 = Number Reoccurring 5 = Credit Purchase 6 = Addition Charges 7 = Manual Payment',
  `package_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cad_amount` float NOT NULL DEFAULT '0',
  `pdf_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_id` text COLLATE utf8_unicode_ci,
  `invoice_status_text` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = new,1 = cancel, 2 = refund',
  `editable_invoice` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not 1 yes',
  `is_view_in_account` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 - display, 1 - not display means this subscription charge successfull generate and this field for failed invoice',
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
-- Table structure for table `invoice_logs`
--

CREATE TABLE `invoice_logs` (
  `id` int(11) NOT NULL,
  `client_id` int(111) DEFAULT '0',
  `invoice_id` int(111) NOT NULL DEFAULT '0',
  `deal_id` varchar(255) DEFAULT NULL,
  `agreement_id` varchar(255) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `module_type_text` varchar(255) DEFAULT NULL,
  `total_price` varchar(255) DEFAULT NULL,
  `purchase_number` varchar(255) DEFAULT NULL,
  `payment_start_date` datetime DEFAULT NULL,
  `payment_end_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

CREATE TABLE `invoice_products` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` mediumtext NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_unit_price` decimal(10,2) NOT NULL,
  `product_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE `labels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `pipeline_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `label_deals`
--

CREATE TABLE `label_deals` (
  `id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL DEFAULT '0',
  `unique_id` text,
  `business_name` varchar(255) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `alternate_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `street_address` text,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `price_with_tax` float NOT NULL DEFAULT '0',
  `pos_screen_counter` int(11) NOT NULL DEFAULT '0',
  `pos_used_screen` int(11) NOT NULL DEFAULT '0',
  `service_type` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `longi` varchar(255) DEFAULT NULL,
  `notes` text,
  `location_type` int(11) NOT NULL DEFAULT '0' COMMENT '0 - agreement location, 1 - default location',
  `hub_training` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 not, 1 yes',
  `signage_training` tinyint(2) NOT NULL DEFAULT '0',
  `menuonline_training` tinyint(2) NOT NULL DEFAULT '0',
  `master_note` text,
  `received_menu_from_customer` tinyint(2) NOT NULL DEFAULT '0',
  `menu_received_date` datetime DEFAULT NULL,
  `menu_creation` tinyint(2) NOT NULL DEFAULT '0',
  `digital_signage` int(5) NOT NULL DEFAULT '0',
  `com_digital_signage` int(5) NOT NULL DEFAULT '0',
  `router` int(5) NOT NULL DEFAULT '0',
  `com_router` int(5) NOT NULL DEFAULT '0',
  `tablet` int(5) NOT NULL DEFAULT '0',
  `com_tablet` int(5) NOT NULL DEFAULT '0',
  `stand` int(5) NOT NULL DEFAULT '0',
  `com_stand` int(5) NOT NULL DEFAULT '0',
  `terminal` int(5) NOT NULL DEFAULT '0',
  `com_terminal` int(5) NOT NULL DEFAULT '0',
  `followup_threeday` tinyint(2) NOT NULL DEFAULT '0',
  `followup_fourteenday` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `location_permissions`
--

CREATE TABLE `location_permissions` (
  `id` int(111) NOT NULL,
  `location_id` int(111) DEFAULT NULL,
  `unique_id` text,
  `deal_id` int(111) DEFAULT NULL,
  `client_id` int(111) DEFAULT NULL,
  `pickup_app_frontend` int(11) NOT NULL DEFAULT '0',
  `web_pos_system` int(11) NOT NULL DEFAULT '0',
  `offline_mobile_pos` int(11) NOT NULL DEFAULT '0',
  `witress_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_display` int(11) NOT NULL DEFAULT '0',
  `booking_reservation` int(11) NOT NULL DEFAULT '0',
  `queue_management` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `sms_id` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `deal_id` bigint(111) NOT NULL DEFAULT '0',
  `funnel_id` int(111) NOT NULL DEFAULT '0',
  `funnel_detail_id` int(111) NOT NULL DEFAULT '0',
  `smstemplate_id` int(111) NOT NULL DEFAULT '0',
  `sendfrom` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text_message` text COLLATE utf8_unicode_ci,
  `route` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inbox',
  `msg_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `inbox_type` tinyint(4) NOT NULL DEFAULT '1',
  `sms_status` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `read` int(11) NOT NULL DEFAULT '0',
  `module_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_type_text` text COLLATE utf8_unicode_ci,
  `error_message` text COLLATE utf8_unicode_ci,
  `sms_send_response` text COLLATE utf8_unicode_ci,
  `sms_response` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `message` mediumtext NOT NULL,
  `message_to` int(11) NOT NULL,
  `message_by` int(11) NOT NULL,
  `read` int(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `note_deals`
--

CREATE TABLE `note_deals` (
  `id` int(11) NOT NULL,
  `note` text CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `method` int(11) NOT NULL,
  `note` mediumtext,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `agreement_id` varchar(255) DEFAULT NULL,
  `response` longtext,
  `module` varchar(255) DEFAULT NULL,
  `module_type_text` varchar(255) DEFAULT NULL,
  `customer_ip` varchar(255) DEFAULT NULL,
  `dex_customer_token` varchar(255) DEFAULT NULL,
  `customer_code` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pipeline`
--

CREATE TABLE `pipeline` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pipeline_permission`
--

CREATE TABLE `pipeline_permission` (
  `id` int(11) NOT NULL,
  `pipeline_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(111) NOT NULL,
  `plan_title` varchar(255) DEFAULT NULL,
  `plan_amount` float DEFAULT NULL,
  `setup_fee` varchar(255) NOT NULL DEFAULT '0',
  `pickup_app_frontend` int(11) NOT NULL DEFAULT '0',
  `web_pos_system` int(11) NOT NULL DEFAULT '0',
  `offline_mobile_pos` int(11) NOT NULL DEFAULT '0',
  `witress_app` int(11) NOT NULL DEFAULT '0',
  `kitchen_display` int(11) NOT NULL DEFAULT '0',
  `pos_equipment` varchar(255) DEFAULT '0',
  `booking_reservation` int(11) NOT NULL DEFAULT '0',
  `pos_screen_counter` int(11) DEFAULT '0',
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
  `bulk_sms` int(11) DEFAULT '0',
  `bulk_email` int(11) NOT NULL DEFAULT '0',
  `virtual_wallet` int(11) DEFAULT NULL,
  `automated_marketing` int(11) DEFAULT NULL,
  `scheduled_sms` int(11) DEFAULT NULL,
  `mobile_opt_in_unlimited_keywords` varchar(255) DEFAULT '0',
  `qr_code_creation` int(11) DEFAULT NULL,
  `voice_broadcast` int(11) DEFAULT NULL,
  `two_way_sms_chat` int(11) DEFAULT '0',
  `appointment_reminder` int(11) DEFAULT NULL,
  `web_sign_up_widgets` int(11) DEFAULT NULL,
  `smart_trackable_coupon` int(11) DEFAULT NULL,
  `smart_wifi` varchar(255) DEFAULT '0',
  `equipment` varchar(255) DEFAULT NULL,
  `cashier_leader_board` int(2) NOT NULL DEFAULT '0',
  `sms_inclusion` varchar(255) DEFAULT NULL,
  `voice_inclusion` varchar(255) DEFAULT NULL,
  `is_credit_carry_forward` int(11) NOT NULL DEFAULT '0',
  `job_requirements` int(11) NOT NULL DEFAULT '0',
  `waivers` int(11) NOT NULL DEFAULT '0',
  `reputation` int(11) NOT NULL DEFAULT '0',
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
  `hardware_warranty` int(11) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `whole_sale_email_api` int(11) DEFAULT '0',
  `whole_sale_sms_api` int(11) NOT NULL DEFAULT '0',
  `sweeptake_form` varchar(255) NOT NULL DEFAULT '0',
  `customer_support` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_deals`
--

CREATE TABLE `product_deals` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `discount` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_tasks`
--

CREATE TABLE `scheduled_tasks` (
  `id` int(111) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `schedule_task_id` int(111) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `task_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `customer_timezone` varchar(255) DEFAULT NULL,
  `customer_start_time` time DEFAULT NULL,
  `customer_end_time` time DEFAULT NULL,
  `event_id` int(111) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_payment_task`
--

CREATE TABLE `schedule_payment_task` (
  `id` int(111) NOT NULL,
  `company_id` int(111) DEFAULT NULL,
  `deal_id` int(111) DEFAULT NULL,
  `is_intial_payment_recived` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = no, 1 = yes',
  `outstanding_amount` float NOT NULL DEFAULT '0',
  `group_id` varchar(255) DEFAULT NULL,
  `user_id` int(111) DEFAULT NULL,
  `user_event_id_object` text,
  `payment_method_provided` varchar(255) DEFAULT NULL,
  `is_payment_fail` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 means no 2 means yes',
  `is_partially_or_fully_failed` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 means partially, 2 means fully',
  `contact_available_date` datetime DEFAULT NULL,
  `payment_method_intial_payment` varchar(255) DEFAULT NULL,
  `is_intial_collected` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 means no, 1 means yes',
  `is_partially_or_fully_collected` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 means partially 1 means fully',
  `customer_service_start_date` date DEFAULT NULL,
  `payment_method_on_start` varchar(255) DEFAULT NULL,
  `service_start_amount` float NOT NULL DEFAULT '0',
  `service_start_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tasks`
--

CREATE TABLE `schedule_tasks` (
  `id` int(111) NOT NULL,
  `task_name` varchar(255) DEFAULT NULL,
  `task_color` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `title` int(2) NOT NULL,
  `title_text` varchar(255) NOT NULL,
  `title_logo` varchar(255) NOT NULL,
  `language` varchar(50) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `currency_symbol` varchar(10) NOT NULL,
  `date_format` varchar(100) NOT NULL,
  `time_format` varchar(10) NOT NULL,
  `time_zone` varchar(50) NOT NULL,
  `pipeline` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings_company`
--

CREATE TABLE `settings_company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` mediumtext NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `system_email` varchar(100) NOT NULL,
  `system_email_from` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings_email`
--

CREATE TABLE `settings_email` (
  `id` int(11) NOT NULL,
  `protocol` varchar(255) DEFAULT NULL,
  `host` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `port` int(10) DEFAULT NULL,
  `message` int(2) NOT NULL DEFAULT '0',
  `ticket` int(2) NOT NULL DEFAULT '0',
  `add_user` int(2) NOT NULL DEFAULT '0',
  `facebook_link` text,
  `instagram_link` text,
  `youtube_link` text,
  `twitter_link` text,
  `google_plus_link` text,
  `linkedin_link` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `bussiness_name` varchar(255) NOT NULL,
  `person_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_templates`
--

CREATE TABLE `sms_templates` (
  `id` int(111) NOT NULL,
  `user_id` int(111) DEFAULT '0',
  `sms_type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `template_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 => demo,2 => marketing,3 => information,4 => attempt',
  `image` text,
  `message` text,
  `short_code` varchar(255) DEFAULT NULL,
  `long_url` varchar(255) DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE `source` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `source_deals`
--

CREATE TABLE `source_deals` (
  `id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stages`
--

CREATE TABLE `stages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` int(2) NOT NULL,
  `pipeline_id` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `pipeline_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `status_deals`
--

CREATE TABLE `status_deals` (
  `id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_payment_notifications`
--

CREATE TABLE `stripe_payment_notifications` (
  `id` bigint(255) NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `trn_id` text,
  `subscriptionid` varchar(255) DEFAULT NULL COMMENT 'means recurring billing Id',
  `invoice_id` text,
  `payment_intent` varchar(255) DEFAULT NULL,
  `refunds_id` varchar(255) DEFAULT NULL,
  `authorizing_merchant_id` int(111) NOT NULL DEFAULT '0',
  `approved` varchar(255) DEFAULT NULL,
  `message` text,
  `auth_code` varchar(255) DEFAULT NULL,
  `order_number` bigint(255) DEFAULT '0',
  `trn_type` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT '0',
  `trn_date` datetime DEFAULT NULL,
  `full_response` text,
  `type` int(11) DEFAULT '0' COMMENT '1 - single payment, 2 - recurring payment',
  `event_type` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `success_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sub_user_auth`
--

CREATE TABLE `sub_user_auth` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(100) NOT NULL,
  `db_name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(111) NOT NULL DEFAULT '0',
  `auth_token` text NOT NULL,
  `device_type` varchar(50) DEFAULT NULL COMMENT 'For Push notification',
  `device_token` text COMMENT 'Push notification token'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task` mediumtext NOT NULL,
  `motive` int(1) NOT NULL,
  `priority` int(1) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '1ab394',
  `status` int(1) NOT NULL DEFAULT '0',
  `note` mediumtext,
  `deal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_notify` int(11) NOT NULL DEFAULT '0',
  `notify_on` datetime DEFAULT NULL,
  `event_id` text COMMENT ' google calendar event id ',
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `terms_conditions`
--

CREATE TABLE `terms_conditions` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `type_id` int(2) NOT NULL DEFAULT '0' COMMENT '0 = General,1 = New Sales',
  `ticket_for` varchar(255) DEFAULT NULL,
  `staff_group_ids` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `assign` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `deal_id` int(111) DEFAULT NULL,
  `client_id` int(111) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `attachment` varchar(255) NOT NULL,
  `priority` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = low, 1 = medium, 2 = high',
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_message`
--

CREATE TABLE `ticket_message` (
  `id` int(11) NOT NULL,
  `message` mediumtext NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(111) DEFAULT NULL,
  `is_client_reply` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 means yes client reply',
  `attachment` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_type`
--

CREATE TABLE `ticket_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_view_history`
--

CREATE TABLE `ticket_view_history` (
  `id` int(111) NOT NULL,
  `ticket_id` int(111) DEFAULT NULL,
  `changed_by` int(111) DEFAULT NULL COMMENT 'User id who changed content in ticket',
  `user_notify_id` int(111) DEFAULT NULL,
  `is_checked` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timeline`
--

CREATE TABLE `timeline` (
  `id` int(11) NOT NULL,
  `activity` text,
  `module` varchar(50) DEFAULT NULL,
  `deal_id` int(11) NOT NULL DEFAULT '0',
  `funnel_id` int(111) DEFAULT '0',
  `funnel_detail_id` int(111) NOT NULL DEFAULT '0',
  `pipeline_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user` varchar(50) DEFAULT NULL,
  `sms_id` text,
  `email_id` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timezone`
--

CREATE TABLE `timezone` (
  `id` int(11) NOT NULL,
  `area_code` int(11) NOT NULL,
  `timezone_code` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_group_id` varchar(256) DEFAULT NULL,
  `hub_user_id` int(111) NOT NULL DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `hub_password` text,
  `email` varchar(100) DEFAULT NULL,
  `country_code` varchar(55) DEFAULT NULL,
  `mobile_no` varchar(55) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `job_title` varchar(250) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `picture` varchar(255) NOT NULL,
  `active` varchar(3) DEFAULT '0',
  `role` int(11) DEFAULT NULL,
  `lead_percentage` int(111) NOT NULL DEFAULT '0',
  `lead_current_weight` int(111) DEFAULT '0',
  `lead_count` int(111) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `signature_name` varchar(255) DEFAULT NULL,
  `signature` longtext,
  `onboarding_message` text,
  `onboarding_subject` varchar(255) DEFAULT NULL,
  `event_title` varchar(255) DEFAULT NULL,
  `event_description` text,
  `demo_reminder_email_descripion` text,
  `demo_reminder_sms_descripion` text,
  `demo_sameday_reminder_email_descripion` text,
  `demo_sameday_reminder_sms_descripion` text,
  `demo_1hourbef_reminder_email_descripion` text,
  `demo_1hourbef_reminder_sms_descripion` text,
  `demo_event_name` varchar(255) DEFAULT NULL,
  `demo_event_description` text,
  `demo_event_time` datetime DEFAULT NULL,
  `demo_email_subject` varchar(255) DEFAULT NULL,
  `demo_email_description` text,
  `demo_sms_description` text,
  `demo_title` varchar(255) DEFAULT NULL,
  `demo_subject` varchar(255) DEFAULT NULL,
  `demo_body` text,
  `demo_sms_body` text,
  `access_token` text,
  `google_calendar_status` int(11) DEFAULT '0',
  `google_calendar_response` text,
  `connect_on` datetime NOT NULL,
  `signature_image` varchar(255) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_group` varchar(255) DEFAULT NULL,
  `is_show_all_deal` int(11) NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - show all deal'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_availability`
--

CREATE TABLE `user_availability` (
  `id` int(111) NOT NULL,
  `user_id` int(111) NOT NULL DEFAULT '0',
  `mon` text,
  `mon_client` text,
  `tue` text,
  `tue_client` text,
  `wed` text,
  `wed_client` text,
  `thu` text,
  `thu_client` text,
  `fri` text,
  `fri_client` text,
  `sat` text,
  `sat_client` text,
  `sun` text,
  `sun_client` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_deals`
--

CREATE TABLE `user_deals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `location` varchar(256) DEFAULT NULL,
  `cellphone` varchar(15) DEFAULT NULL,
  `web_page` mediumtext,
  `about` mediumtext,
  `skype` mediumtext,
  `facebook` mediumtext,
  `twitter` mediumtext,
  `google_plus` mediumtext,
  `linkedin` mediumtext,
  `youtube` mediumtext,
  `pinterest` mediumtext,
  `digg` mediumtext,
  `github` mediumtext,
  `instagram` mediumtext,
  `tumblr` mediumtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` smallint(1) NOT NULL,
  `permission` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_charges`
--
ALTER TABLE `additional_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`company_id`,`deal_id`,`send_on`,`status`,`invoice_type`);

--
-- Indexes for table `agreement`
--
ALTER TABLE `agreement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_id` (`deal_id`,`user_id`,`client_id`,`hub_user_id`);

--
-- Indexes for table `agreement_setting`
--
ALTER TABLE `agreement_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attempt_email_sms_logs`
--
ALTER TABLE `attempt_email_sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backup`
--
ALTER TABLE `backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buckets`
--
ALTER TABLE `buckets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `deal_id` (`deal_id`),
  ADD KEY `agreement_id` (`agreement_id`),
  ADD KEY `is_hub_access` (`is_hub_access`,`is_wifi_access`,`is_menu_access`,`is_signage_access`,`active`,`package`);

--
-- Indexes for table `clients_permissions`
--
ALTER TABLE `clients_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`,`client_id`),
  ADD KEY `pickup_app_frontend` (`pickup_app_frontend`,`web_pos_system`,`witress_app`,`kitchen_display`,`booking_reservation`,`queue_management`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`),
  ADD KEY `id_3` (`id`),
  ADD KEY `company_id` (`company_id`,`package`,`is_hub_import`,`is_deleted`);

--
-- Indexes for table `company_contact_person`
--
ALTER TABLE `company_contact_person`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_status`
--
ALTER TABLE `company_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_deals`
--
ALTER TABLE `contact_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexes for table `cron_logs`
--
ALTER TABLE `cron_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_company`
--
ALTER TABLE `custom_company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_id` (`custom_id`);

--
-- Indexes for table `custom_contacts`
--
ALTER TABLE `custom_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_deals`
--
ALTER TABLE `custom_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_id` (`custom_id`,`deal_id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `deal`
--
ALTER TABLE `deal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `stage_id` (`stage_id`),
  ADD KEY `id` (`id`),
  ADD KEY `is_hide` (`is_hide`),
  ADD KEY `is_deleted` (`is_deleted`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `known_as` (`known_as`),
  ADD KEY `phone` (`phone`),
  ADD KEY `email` (`email`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `deal_duties`
--
ALTER TABLE `deal_duties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deal_user_duties`
--
ALTER TABLE `deal_user_duties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dex_payment_notifications`
--
ALTER TABLE `dex_payment_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussion`
--
ALTER TABLE `discussion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `duties`
--
ALTER TABLE `duties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dutiesdetails`
--
ALTER TABLE `dutiesdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emailtemplates`
--
ALTER TABLE `emailtemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_id` (`email_id`),
  ADD KEY `phone_number` (`phone_number`),
  ADD KEY `user_id` (`user_id`,`route`,`msg_type`,`created`),
  ADD KEY `route` (`route`,`msg_type`,`email_status`,`created`),
  ADD KEY `user_id_2` (`user_id`,`route`,`msg_type`),
  ADD KEY `created` (`created`),
  ADD KEY `contract_email_type` (`contract_email_type`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexes for table `email_template_types`
--
ALTER TABLE `email_template_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`group_id`,`deal_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_category`
--
ALTER TABLE `expenses_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_leads_logs`
--
ALTER TABLE `facebook_leads_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_leads_stats`
--
ALTER TABLE `facebook_leads_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq_category`
--
ALTER TABLE `faq_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funnel`
--
ALTER TABLE `funnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funnel_details`
--
ALTER TABLE `funnel_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funnel_logs`
--
ALTER TABLE `funnel_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_notification_send` (`is_notification_send`),
  ADD KEY `deal_id` (`deal_id`),
  ADD KEY `funnel_id` (`funnel_id`);

--
-- Indexes for table `gallary`
--
ALTER TABLE `gallary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallary_image`
--
ALTER TABLE `gallary_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubemailtemplates`
--
ALTER TABLE `hubemailtemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hub_email_logs`
--
ALTER TABLE `hub_email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_id` (`email_id`),
  ADD KEY `phone_number` (`phone_number`),
  ADD KEY `user_id` (`user_id`,`route`,`msg_type`,`created`),
  ADD KEY `route` (`route`,`msg_type`,`email_status`,`created`),
  ADD KEY `user_id_2` (`user_id`,`route`,`msg_type`),
  ADD KEY `created` (`created`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_client`
--
ALTER TABLE `inventory_client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `invoice_logs`
--
ALTER TABLE `invoice_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `label_deals`
--
ALTER TABLE `label_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `label_id` (`label_id`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_id` (`deal_id`,`location_type`);

--
-- Indexes for table `location_permissions`
--
ALTER TABLE `location_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`,`deal_id`,`client_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_id` (`sms_id`),
  ADD KEY `phone_number` (`phone_number`),
  ADD KEY `user_id` (`user_id`,`route`,`msg_type`,`created`),
  ADD KEY `route` (`route`,`msg_type`,`sms_status`,`created`),
  ADD KEY `user_id_2` (`user_id`,`route`,`msg_type`),
  ADD KEY `created` (`created`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `note_deals`
--
ALTER TABLE `note_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`deal_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pipeline`
--
ALTER TABLE `pipeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `pipeline_permission`
--
ALTER TABLE `pipeline_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `product_deals`
--
ALTER TABLE `product_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`,`deal_id`);

--
-- Indexes for table `scheduled_tasks`
--
ALTER TABLE `scheduled_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_payment_task`
--
ALTER TABLE `schedule_payment_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_tasks`
--
ALTER TABLE `schedule_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings_company`
--
ALTER TABLE `settings_company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings_email`
--
ALTER TABLE `settings_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_templates`
--
ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `source`
--
ALTER TABLE `source`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `source_deals`
--
ALTER TABLE `source_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_id` (`source_id`,`deal_id`);

--
-- Indexes for table `stages`
--
ALTER TABLE `stages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `position` (`position`,`pipeline_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_deals`
--
ALTER TABLE `status_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexes for table `stripe_payment_notifications`
--
ALTER TABLE `stripe_payment_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_user_auth`
--
ALTER TABLE `sub_user_auth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_id` (`deal_id`,`user_id`,`is_notify`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_message`
--
ALTER TABLE `ticket_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_type`
--
ALTER TABLE `ticket_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_view_history`
--
ALTER TABLE `ticket_view_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_id` (`deal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `timezone`
--
ALTER TABLE `timezone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timezone` (`timezone`),
  ADD KEY `timezone_code` (`timezone_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`username`),
  ADD KEY `mail` (`email`),
  ADD KEY `users_FKIndex1` (`user_group_id`),
  ADD KEY `id` (`id`),
  ADD KEY `is_show_all_deal` (`is_show_all_deal`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `demo_event_time` (`demo_event_time`);

--
-- Indexes for table `user_availability`
--
ALTER TABLE `user_availability`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_deals`
--
ALTER TABLE `user_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_charges`
--
ALTER TABLE `additional_charges`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=699;
--
-- AUTO_INCREMENT for table `agreement`
--
ALTER TABLE `agreement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=996;
--
-- AUTO_INCREMENT for table `agreement_setting`
--
ALTER TABLE `agreement_setting`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `attempt_email_sms_logs`
--
ALTER TABLE `attempt_email_sms_logs`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1290;
--
-- AUTO_INCREMENT for table `backup`
--
ALTER TABLE `backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `buckets`
--
ALTER TABLE `buckets`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5022;
--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1542;
--
-- AUTO_INCREMENT for table `clients_permissions`
--
ALTER TABLE `clients_permissions`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=836;
--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1077;
--
-- AUTO_INCREMENT for table `company_contact_person`
--
ALTER TABLE `company_contact_person`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `company_status`
--
ALTER TABLE `company_status`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `contact_deals`
--
ALTER TABLE `contact_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `cron_logs`
--
ALTER TABLE `cron_logs`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=990270;
--
-- AUTO_INCREMENT for table `custom_company`
--
ALTER TABLE `custom_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `custom_contacts`
--
ALTER TABLE `custom_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `custom_deals`
--
ALTER TABLE `custom_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=794;
--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `deal`
--
ALTER TABLE `deal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6598;
--
-- AUTO_INCREMENT for table `deal_duties`
--
ALTER TABLE `deal_duties`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;
--
-- AUTO_INCREMENT for table `deal_user_duties`
--
ALTER TABLE `deal_user_duties`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dex_payment_notifications`
--
ALTER TABLE `dex_payment_notifications`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `discussion`
--
ALTER TABLE `discussion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `duties`
--
ALTER TABLE `duties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT for table `dutiesdetails`
--
ALTER TABLE `dutiesdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emailtemplates`
--
ALTER TABLE `emailtemplates`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182113;
--
-- AUTO_INCREMENT for table `email_template_types`
--
ALTER TABLE `email_template_types`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4807;
--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses_category`
--
ALTER TABLE `expenses_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `facebook_leads_logs`
--
ALTER TABLE `facebook_leads_logs`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4957;
--
-- AUTO_INCREMENT for table `facebook_leads_stats`
--
ALTER TABLE `facebook_leads_stats`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `faq_category`
--
ALTER TABLE `faq_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `funnel`
--
ALTER TABLE `funnel`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `funnel_details`
--
ALTER TABLE `funnel_details`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `funnel_logs`
--
ALTER TABLE `funnel_logs`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1260440;
--
-- AUTO_INCREMENT for table `gallary`
--
ALTER TABLE `gallary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `gallary_image`
--
ALTER TABLE `gallary_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `hubemailtemplates`
--
ALTER TABLE `hubemailtemplates`
  MODIFY `id` bigint(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `hub_email_logs`
--
ALTER TABLE `hub_email_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;
--
-- AUTO_INCREMENT for table `inventory_client`
--
ALTER TABLE `inventory_client`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24135;
--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=519;
--
-- AUTO_INCREMENT for table `invoice_logs`
--
ALTER TABLE `invoice_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=749;
--
-- AUTO_INCREMENT for table `invoice_products`
--
ALTER TABLE `invoice_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `labels`
--
ALTER TABLE `labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `label_deals`
--
ALTER TABLE `label_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2151;
--
-- AUTO_INCREMENT for table `location_permissions`
--
ALTER TABLE `location_permissions`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53923;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `note_deals`
--
ALTER TABLE `note_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=394;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;
--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pipeline`
--
ALTER TABLE `pipeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `pipeline_permission`
--
ALTER TABLE `pipeline_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=870;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_deals`
--
ALTER TABLE `product_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;
--
-- AUTO_INCREMENT for table `scheduled_tasks`
--
ALTER TABLE `scheduled_tasks`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=696;
--
-- AUTO_INCREMENT for table `schedule_payment_task`
--
ALTER TABLE `schedule_payment_task`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `schedule_tasks`
--
ALTER TABLE `schedule_tasks`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `settings_company`
--
ALTER TABLE `settings_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `settings_email`
--
ALTER TABLE `settings_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sms_templates`
--
ALTER TABLE `sms_templates`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `source`
--
ALTER TABLE `source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `source_deals`
--
ALTER TABLE `source_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5251;
--
-- AUTO_INCREMENT for table `stages`
--
ALTER TABLE `stages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `status_deals`
--
ALTER TABLE `status_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1079;
--
-- AUTO_INCREMENT for table `stripe_payment_notifications`
--
ALTER TABLE `stripe_payment_notifications`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27466;
--
-- AUTO_INCREMENT for table `sub_user_auth`
--
ALTER TABLE `sub_user_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;
--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=450;
--
-- AUTO_INCREMENT for table `ticket_message`
--
ALTER TABLE `ticket_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=590;
--
-- AUTO_INCREMENT for table `ticket_type`
--
ALTER TABLE `ticket_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ticket_view_history`
--
ALTER TABLE `ticket_view_history`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1894;
--
-- AUTO_INCREMENT for table `timeline`
--
ALTER TABLE `timeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222562;
--
-- AUTO_INCREMENT for table `timezone`
--
ALTER TABLE `timezone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `user_availability`
--
ALTER TABLE `user_availability`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `user_deals`
--
ALTER TABLE `user_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7570;
--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
