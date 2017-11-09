

INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (1,'workday',true,false,false);
INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (2,'break',false,true,false);
INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (3,'holiday',false,true,false);
INSERT INTO bolt_bm_rule_type (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (4,'overtime',false,false,true);


INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('W','Waiting');
INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('A','Assigned');
INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('D','Completed');
INSERT INTO bolt_bm_appointment_status (`status_code`,`status_description`) values ('C','Canceled');
