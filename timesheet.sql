CREATE DATABASE timesheet;

USE timesheet;

CREATE TABLE timesheet (
    id INT(11) NOT NULL AUTO_INCREMENT,
    report_date DATE,
    email_to VARCHAR(255),
    report_subject VARCHAR(255),
    PRIMARY KEY (id)
);

CREATE TABLE timesheet_tasks (
    id INT(11) NOT NULL AUTO_INCREMENT,
    timesheet_id INT(11),
    project VARCHAR(255),
    task VARCHAR(255),
    task_description TEXT,
    task_time TIME,
    billable TINYINT(1),
    PRIMARY KEY (id)
);
