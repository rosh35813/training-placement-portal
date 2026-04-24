SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

USE placement;

CREATE TABLE IF NOT EXISTS student 
(
	STUDENT_ID varchar(50) NOT NULL,
    S_PASSWORD varchar(255) NOT NULL,
	STUDENT_NAME varchar(100) NOT NULL,
    FATHER_NAME varchar(100) NOT NULL,
    MOTHER_NAME varchar(100) NOT NULL,
    GENDER varchar(100) NOT NULL,
    DOB date NOT NULL,
    EMAIL varchar(100) NOT NULL,
	ADDRESS varchar(100) NOT NULL,
	CONTACT_NO varchar(100) NOT NULL,
	BRANCH varchar(100) NOT NULL,
	TENTH_PER varchar(100) NOT NULL,
	TENTH_PASS_YEAR int NOT NULL,
	TWELTH_PER varchar(100) NOT NULL,
	TWELTH_PASS_YEAR int NOT NULL,
	CGPA double NOT NULL,
	PASSING_YEAR int NOT NULL,
	BACKLOGS int(11) NOT NULL,
	APPLY_FOR varchar(100) NOT NULL,
	STATUS varchar(50) DEFAULT 'NS',
	APPLY_COUNT int DEFAULT 0,
	ABSENT int DEFAULT 0,
    IMAGE longblob DEFAULT NULL,
    PRIMARY KEY (STUDENT_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS company 
(
	COMPANY_ID varchar(50) NOT NULL,
    COMPANY_NAME varchar(100) NOT NULL,
    C_PASSWORD varchar(255) NOT NULL,
    WEBSITE varchar(100) NOT NULL,
	ADDRESS varchar(100) NOT NULL,
	STATUS varchar(50) DEFAULT 'visiting',
	COMING_DATE date NOT NULL,
	APPROVAL varchar(50) DEFAULT 'not approved',
    PRIMARY KEY (COMPANY_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS companybranch 
(
    COMPANY_NAME varchar(100) NOT NULL,
    C_TYPE varchar(50) NOT NULL,
    BRANCH varchar(50),
	MIN_CGPA double,
	MAX_BACKLOGS int DEFAULT 0,
	MAX_SALARY double,
	MAX_STIPEND double,
	JOB_PROFILE varchar(100),
	PLACE_OF_POSTING varchar(100),
    PRIMARY KEY (COMPANY_NAME, C_TYPE, BRANCH)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS student_placement 
(
  STUDENT_ID varchar(50) NOT NULL,
  COMPANY_ID varchar(100) NOT NULL,
  STUDENT_NAME varchar(100) NOT NULL,
  COMPANY_NAME varchar(100) NOT NULL,
  PACKAGE double NOT NULL,
  PRIMARY KEY (STUDENT_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS student_internship 
(
	STUDENT_ID varchar(50) NOT NULL,
    COMPANY_ID varchar(100) NOT NULL,
    STUDENT_NAME varchar(100) NOT NULL,
    COMPANY_NAME varchar(100) NOT NULL,
	STIPEND double NOT NULL,
	PRIMARY KEY (STUDENT_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS admin
(
	ADMIN_ID varchar(50) NOT NULL,
    ADMIN_NAME varchar(100) NOT NULL,
    A_PASSWORD varchar(255) NOT NULL,
    POST varchar(100) NOT NULL,
	EMAIL varchar(100) NOT NULL,  
	CONTACT_NO varchar(100) NOT NULL,
	DOB date NOT NULL,  
	QUALIFICATION varchar(100) NOT NULL,
	PRIMARY KEY (ADMIN_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS registered_interns
(
	STUDENT_ID varchar(50) NOT NULL,
    STUDENT_NAME varchar(100) NOT NULL,
    COMPANY_NAME varchar(100) NOT NULL,
	PRIMARY KEY (STUDENT_ID, COMPANY_NAME)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS registered_placements
(
	STUDENT_ID varchar(50) NOT NULL,
    STUDENT_NAME varchar(100) NOT NULL,
    COMPANY_NAME varchar(100) NOT NULL,
	PRIMARY KEY (STUDENT_ID, COMPANY_NAME)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS intern_notification 
(
    noti varchar(200) NOT NULL,
    PRIMARY KEY (noti)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS place_notification 
(
    noti varchar(200) NOT NULL,
    PRIMARY KEY (noti)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP FUNCTION IF EXISTS IsEligible;
DROP TRIGGER IF EXISTS increment_apply_count;
DROP TRIGGER IF EXISTS update_absent_status;
DROP TRIGGER IF EXISTS update_status_on_placement;
DROP PROCEDURE IF EXISTS RegisterForPlacement;
DROP PROCEDURE IF EXISTS ApproveCompany;
DROP PROCEDURE IF EXISTS SendBulkNotification;

DELIMITER $$
CREATE FUNCTION IsEligible(cgpa DOUBLE, backlogs INT)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    IF (cgpa >= 7.0 AND backlogs = 0) THEN
        RETURN 'Eligible';
    ELSE
        RETURN 'Not Eligible';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER increment_apply_count
AFTER INSERT ON registered_placements
FOR EACH ROW
BEGIN
    UPDATE student
    SET APPLY_COUNT = APPLY_COUNT + 1
    WHERE STUDENT_ID = NEW.STUDENT_ID;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER update_absent_status
AFTER INSERT ON registered_interns
FOR EACH ROW
BEGIN
    UPDATE student
    SET ABSENT = ABSENT + 1
    WHERE STUDENT_ID = NEW.STUDENT_ID;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER update_status_on_placement
AFTER INSERT ON student_placement
FOR EACH ROW
BEGIN
    UPDATE student
    SET STATUS = 'Selected'
    WHERE STUDENT_ID = NEW.STUDENT_ID;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE RegisterForPlacement(
    IN p_student_id VARCHAR(50),
    IN p_student_name VARCHAR(100),
    IN p_company_name VARCHAR(100)
)
BEGIN
    DECLARE EXIT HANDLER FOR 1062 
    BEGIN
        SELECT 'Student already registered for this company!' AS Message;
    END;

    INSERT INTO registered_placements(STUDENT_ID, STUDENT_NAME, COMPANY_NAME)
    VALUES(p_student_id, p_student_name, p_company_name);

    SELECT 'Registration Successful' AS Message;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ApproveCompany(IN p_company_id VARCHAR(50))
BEGIN
    UPDATE company
    SET APPROVAL = 'approved'
    WHERE COMPANY_ID = p_company_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE SendBulkNotification()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE cname VARCHAR(100);

    DECLARE c CURSOR FOR SELECT COMPANY_NAME FROM company WHERE STATUS='visiting';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN c;
    read_loop: LOOP
        FETCH c INTO cname;
        IF done = 1 THEN LEAVE read_loop; END IF;

        INSERT INTO place_notification(noti) VALUES(CONCAT(cname, ' is scheduled.'));
    END LOOP;
    CLOSE c;
END$$
DELIMITER ;

/* Table to store password reset tokens */
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id varchar(50) NOT NULL,
    token varchar(255) NOT NULL,
    expires_at datetime NOT NULL,
    used TINYINT(1) DEFAULT 0,
    INDEX (user_id),
    INDEX (token)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
