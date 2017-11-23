INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (1,'workday',true,false,false);
INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (2,'break',false,true,false);
INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (3,'holiday',false,true,false);
INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (4,'overtime',false,false,true);


INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('W','Waiting');
INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('A','Assigned');
INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('D','Completed');
INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('C','Canceled');


--
-- Voucher Defaults
--

INSERT INTO `bolt_bm_voucher_group` (`voucher_group_id`, `voucher_group_name`, `voucher_group_slug`, `is_disabled`, `sort_order`, `date_created`) VALUES
(1, 'Appointment', 'appointment', 0, 1, '2017-11-11 03:10:26'),
(2, 'Journal', 'journal', 0, 1, '2017-11-11 03:10:26');


INSERT INTO `bolt_bm_voucher_gen_rule` (`voucher_gen_rule_id`, `voucher_rule_name`, `voucher_rule_slug`, `voucher_padding_char`, `voucher_prefix`, `voucher_suffix`, `voucher_length`, `date_created`, `voucher_sequence_no`, `voucher_sequence_strategy`, `voucher_validate_rules`) VALUES
(1, 'Appointment Number', 'appointment_number', '', 'A', '', 5, now(), 1001, 'sequence', 'a:1:{i:0;s:12:"always-valid";}'),
(2, 'Sales Journal', 'sales_journal', '', 'S', '', 8, now(), 101, 'sequence', 'a:1:{i:0;s:12:"always-valid";}'),
(3, 'Discounts Journal', 'discounts_journal', '', 'D', '', 8, now(), 101, 'sequence', 'a:1:{i:0;s:12:"always-valid";}'),
(4, 'General Journal', 'general_journal', '', 'G', '', 8, now(), 101, 'sequence', 'a:1:{i:0;s:12:"always-valid";}');

INSERT INTO `bolt_bm_voucher_type` (`voucher_type_id`, `voucher_group_id`, `voucher_gen_rule_id`, `voucher_enabled_from`, `voucher_enabled_to`, `voucher_name`, `voucher_name_slug`, `voucher_description`) VALUES
(1, 1, 1,  DATE_FORMAT(NOW() ,'%Y-01-01'), '3000-01-01 00:00:00', 'Appointment Number V1', 'appointment_number', 'Appointment Number Version 1'),
(2, 2, 2,  DATE_FORMAT(NOW() ,'%Y-01-01'), '3000-01-01 00:00:00', 'Sales Journals V1', 'sales_journals', 'Sales Journals Version 1'),
(3, 2, 3,  DATE_FORMAT(NOW() ,'%Y-01-01'), '3000-01-01 00:00:00', 'Discounts Journals V1', 'discounts_journals', 'Discounts Journals Version 1'),
(4, 2, 4,  DATE_FORMAT(NOW() ,'%Y-01-01'), '3000-01-01 00:00:00', 'General Journals V1', 'general_journals', 'General Journals Version 1');


--
-- Ledger Defaults
--

