-- ============================================================
--  COMPLETE MYSQL SCHEMA  –  mapped from UML class diagrams
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ─────────────────────────────────────────────
--  1. USER  (abstract base)
-- ─────────────────────────────────────────────
CREATE TABLE User (
    userID              INT          NOT NULL AUTO_INCREMENT,
    email               VARCHAR(255) NOT NULL UNIQUE,
    passwordHash        VARCHAR(255) NOT NULL,
    firstname           VARCHAR(100) NOT NULL,
    Lastname            VARCHAR(100) NOT NULL,
    Role                ENUM('Admin','Teacher','Parent','Child'),
    preferredLanguage   ENUM('AR','EN') NOT NULL DEFAULT 'EN',
    createdAt           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    isActive            TINYINT(1)   NOT NULL DEFAULT 1,
    lastLoginAt         DATETIME     NULL,
    PRIMARY KEY (userID)
);

-- ─────────────────────────────────────────────
--  2. ADMIN
-- ─────────────────────────────────────────────
CREATE TABLE Admin (
    adminID   INT NOT NULL AUTO_INCREMENT,
    userID    INT NOT NULL UNIQUE,
    PRIMARY KEY (adminID),
    CONSTRAINT fk_admin_user FOREIGN KEY (userID) REFERENCES User(userID)
);

-- ─────────────────────────────────────────────
--  3. PARENT
-- ─────────────────────────────────────────────
CREATE TABLE Parent (
    parentID          INT          NOT NULL AUTO_INCREMENT,
    userID            INT          NOT NULL UNIQUE,
    phone             VARCHAR(20)  NULL,
    address           TEXT         NULL,
    notifPreferences  JSON         NULL,
    PRIMARY KEY (parentID),
    CONSTRAINT fk_parent_user FOREIGN KEY (userID) REFERENCES User(userID)
);

-- ─────────────────────────────────────────────
--  4. TEACHER
-- ─────────────────────────────────────────────
CREATE TABLE Teacher (
    teacherID         INT       NOT NULL AUTO_INCREMENT,
    userID            INT       NOT NULL UNIQUE,
    qualifications    TEXT      NULL,
    exprience         TEXT      NULL,
    specialization    TEXT      NULL,
    Assignedtimetable json      NULL,
    AssignedCourses   json      NULL,
    phone          VARCHAR(20)  NULL,
    PRIMARY KEY (teacherID),
    CONSTRAINT fk_teacher_user FOREIGN KEY (userID) REFERENCES User(userID)
);

-- ─────────────────────────────────────────────
--  5. CHILD
-- ─────────────────────────────────────────────
CREATE TABLE Child (
    childID            INT          NOT NULL AUTO_INCREMENT,
    parentID           INT          NOT NULL,
    name               VARCHAR(100) NOT NULL,
    dateOfBirth        DATE         NOT NULL,
    gender             ENUM('M','F') NOT NULL,
    allergies          TEXT         NULL,
    medicalNotes       TEXT         NULL,
    emergencyContact   VARCHAR(100) NULL,
    enrollmentStatus   ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    photoPath          VARCHAR(255) NULL,
    PRIMARY KEY (childID),
    CONSTRAINT fk_child_parent FOREIGN KEY (parentID) REFERENCES Parent(parentID)
);

-- ─────────────────────────────────────────────
--  6. COURSE
-- ─────────────────────────────────────────────
CREATE TABLE Course (
    courseID           INT            NOT NULL AUTO_INCREMENT,
    name               VARCHAR(100)   NOT NULL,
    description        TEXT           NULL,
    ageMin             INT            NOT NULL,
    ageMax             INT            NOT NULL,
    maxCapacity        INT            NOT NULL,
    currentEnrollment  INT            NOT NULL DEFAULT 0,
    assignedTeacherID  INT            NULL,
    price              DECIMAL(10,2)  NOT NULL,
    schedule           JSON           NULL,
    isActive           TINYINT(1)     NOT NULL DEFAULT 1,
    PRIMARY KEY (courseID),
    CONSTRAINT fk_course_teacher FOREIGN KEY (assignedTeacherID) REFERENCES Teacher(teacherID)
);

-- ─────────────────────────────────────────────
--  7. ENROLLMENT
-- ─────────────────────────────────────────────
CREATE TABLE Enrollment (
    enrollmentID     INT      NOT NULL AUTO_INCREMENT,
    childID          INT      NOT NULL,
    courseID         INT      NOT NULL,
    enrolledAt       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status           ENUM('Active','Completed','Canceled') NOT NULL DEFAULT 'Active',
    isWaitlisted     TINYINT(1) NOT NULL DEFAULT 0,
    waitlistPosition INT      NULL,
    PRIMARY KEY (enrollmentID),
    CONSTRAINT fk_enrollment_child  FOREIGN KEY (childID)  REFERENCES Child(childID),
    CONSTRAINT fk_enrollment_course FOREIGN KEY (courseID) REFERENCES Course(courseID)
);

-- ─────────────────────────────────────────────
--  8. ATTENDANCE
-- ─────────────────────────────────────────────
CREATE TABLE Attendance (
    attendanceID INT      NOT NULL AUTO_INCREMENT,
    childID      INT      NOT NULL,
    courseID     INT      NOT NULL,
    teacherID    INT      NOT NULL,
    sessionDate  DATE     NOT NULL,
    status       ENUM('Present','Absent','Excused','Late') NOT NULL,
    markedAt     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    source       ENUM('Teacher','Auto') NOT NULL DEFAULT 'Teacher',
    PRIMARY KEY (attendanceID),
    CONSTRAINT fk_att_child   FOREIGN KEY (childID)   REFERENCES Child(childID),
    CONSTRAINT fk_att_course  FOREIGN KEY (courseID)  REFERENCES Course(courseID),
    CONSTRAINT fk_att_teacher FOREIGN KEY (teacherID) REFERENCES Teacher(teacherID)
);

-- ─────────────────────────────────────────────
--  9. EXCUSE
-- ─────────────────────────────────────────────
CREATE TABLE Excuse (
    excuseID    INT          NOT NULL AUTO_INCREMENT,
    childID     INT          NOT NULL,
    parentID    INT          NOT NULL,
    sessionDate DATE         NOT NULL,  
    reason      TEXT         NOT NULL,
    docPath     VARCHAR(255) NULL,
    status      ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    submittedAt DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (excuseID),
    CONSTRAINT fk_excuse_child  FOREIGN KEY (childID)  REFERENCES Child(childID),
    CONSTRAINT fk_excuse_parent FOREIGN KEY (parentID) REFERENCES Parent(parentID)
);

-- ─────────────────────────────────────────────
-- 10. APPLICATION
-- ─────────────────────────────────────────────
CREATE TABLE Application (
    applicationID   INT      NOT NULL AUTO_INCREMENT,
    parentID        INT      NOT NULL,
    childID         INT      NOT NULL,
    status          ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    reviewedAt      DATETIME NULL,
    rejectionReason TEXT     NULL,
    documents       JSON     NULL,
    PRIMARY KEY (applicationID),
    CONSTRAINT fk_app_parent FOREIGN KEY (parentID) REFERENCES Parent(parentID),
    CONSTRAINT fk_app_child  FOREIGN KEY (childID)  REFERENCES Child(childID)
);

-- ─────────────────────────────────────────────
-- 11. MILESTONE
-- ─────────────────────────────────────────────
CREATE TABLE Milestone (
    milestoneID    INT          NOT NULL AUTO_INCREMENT,
    childID        INT          NOT NULL,
    teacherID      INT          NOT NULL,
    domain         ENUM('Physical','Social','Cognitive','Language') NOT NULL,
    milestoneTitle VARCHAR(100) NOT NULL,
    status         ENUM('NotStarted','InProgress','Achieved') NOT NULL DEFAULT 'NotStarted',
    ageGroup       VARCHAR(20)  NULL,
    teacherNote    TEXT         NULL,
    achievedAt     DATETIME     NULL,
    PRIMARY KEY (milestoneID),
    CONSTRAINT fk_milestone_child   FOREIGN KEY (childID)   REFERENCES Child(childID),
    CONSTRAINT fk_milestone_teacher FOREIGN KEY (teacherID) REFERENCES Teacher(teacherID)
);

-- ─────────────────────────────────────────────
-- 12. PROGRESS REPORT
-- ─────────────────────────────────────────────
CREATE TABLE ProgressReport (
    reportID     INT          NOT NULL AUTO_INCREMENT,
    childID      INT          NOT NULL,
    teacherID    INT          NOT NULL,
    period       VARCHAR(50)  NOT NULL,
    observation  TEXT         NULL,
    skillRatings JSON         NULL,
    status       ENUM('Draft','Pending','Review','Published') NOT NULL DEFAULT 'Draft',
    createdAt    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    publishedAt  DATETIME     NULL,
    PRIMARY KEY (reportID),
    CONSTRAINT fk_pr_child   FOREIGN KEY (childID)   REFERENCES Child(childID),
    CONSTRAINT fk_pr_teacher FOREIGN KEY (teacherID) REFERENCES Teacher(teacherID)
);

-- ─────────────────────────────────────────────
-- 13. ASSIGNMENT
-- ─────────────────────────────────────────────
CREATE TABLE Assignment (
    assignmentID      INT          NOT NULL AUTO_INCREMENT,
    teacherID         INT          NOT NULL,
    courseID          INT          NOT NULL,
    title             VARCHAR(200) NOT NULL,
    instructions      TEXT         NULL,
    dueDate           DATETIME     NOT NULL,
    wordwallEmbedCode TEXT         NULL,
    attachmentPath    VARCHAR(255) NULL,
    status            ENUM('Draft','Published') NOT NULL DEFAULT 'Draft',
    createdAt         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    targetTags        JSON         NULL,
    PRIMARY KEY (assignmentID),
    CONSTRAINT fk_assign_teacher FOREIGN KEY (teacherID) REFERENCES Teacher(teacherID),
    CONSTRAINT fk_assign_course  FOREIGN KEY (courseID)  REFERENCES Course(courseID)
);

-- ─────────────────────────────────────────────
-- 14. SUBMISSION
-- ─────────────────────────────────────────────
CREATE TABLE Submission (
    submissionID  INT          NOT NULL AUTO_INCREMENT,
    assignmentID  INT          NOT NULL,
    childID       INT          NOT NULL,
    parentID      INT          NOT NULL,
    type          ENUM('Marked','Photo','Text') NOT NULL,
    content       TEXT         NULL,
    photoPath     VARCHAR(255) NULL,
    submittedAt   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status        ENUM('Submitted','Late','Graded') NOT NULL DEFAULT 'Submitted',
    grade         VARCHAR(20)  NULL,
    feedback      TEXT         NULL,
    gradedAt      DATETIME     NULL,
    gradedBy      INT          NULL,
    PRIMARY KEY (submissionID),
    CONSTRAINT fk_sub_assignment FOREIGN KEY (assignmentID) REFERENCES Assignment(assignmentID),
    CONSTRAINT fk_sub_child      FOREIGN KEY (childID)      REFERENCES Child(childID),
    CONSTRAINT fk_sub_parent     FOREIGN KEY (parentID)     REFERENCES Parent(parentID),
    CONSTRAINT fk_sub_teacher    FOREIGN KEY (gradedBy)     REFERENCES Teacher(teacherID)
);

-- ─────────────────────────────────────────────
-- 15. COURSE ASSIGNMENT  (teacher schedule slot)
-- ─────────────────────────────────────────────
CREATE TABLE CourseAssignment (
    assignmentID INT          NOT NULL AUTO_INCREMENT,
    teacherID    INT          NOT NULL,
    courseID     INT          NOT NULL,
    assignedAt   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    assignedBy   INT          NOT NULL,
    dayOfWeek    VARCHAR(20)  NOT NULL,
    startTime    TIME         NOT NULL,
    endTime      TIME         NOT NULL,
    PRIMARY KEY (assignmentID),
    CONSTRAINT fk_ca_teacher    FOREIGN KEY (teacherID) REFERENCES Teacher(teacherID),
    CONSTRAINT fk_ca_course     FOREIGN KEY (courseID)  REFERENCES Course(courseID),
    CONSTRAINT fk_ca_assignedby FOREIGN KEY (assignedBy) REFERENCES Admin(adminID)
);

-- ─────────────────────────────────────────────
-- 16. TAG
-- ─────────────────────────────────────────────
CREATE TABLE Tag (
    tagID     INT          NOT NULL AUTO_INCREMENT,
    name      VARCHAR(100) NOT NULL,
    type      ENUM('AgeGroup','Course','Section','Status') NOT NULL,
    createdBy INT          NOT NULL,
    createdAt DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (tagID),
    CONSTRAINT fk_tag_admin FOREIGN KEY (createdBy) REFERENCES Admin(adminID)
);

-- ─────────────────────────────────────────────
-- 17. CHILD TAG  (junction)
-- ─────────────────────────────────────────────
CREATE TABLE ChildTag (
    childTagID  INT      NOT NULL AUTO_INCREMENT,
    childID     INT      NOT NULL,
    tagID       INT      NOT NULL,
    assignedAt  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    assignedBy  INT      NOT NULL,
    PRIMARY KEY (childTagID),
    CONSTRAINT fk_ct_child FOREIGN KEY (childID)    REFERENCES Child(childID),
    CONSTRAINT fk_ct_tag   FOREIGN KEY (tagID)      REFERENCES Tag(tagID),
    CONSTRAINT fk_ct_admin FOREIGN KEY (assignedBy) REFERENCES Admin(adminID)
);

-- Assignment ↔ Tag  (many-to-many)
CREATE TABLE AssignmentTag (
    assignmentID INT NOT NULL,
    tagID        INT NOT NULL,
    PRIMARY KEY (assignmentID, tagID),
    CONSTRAINT fk_at_assignment FOREIGN KEY (assignmentID) REFERENCES Assignment(assignmentID),
    CONSTRAINT fk_at_tag        FOREIGN KEY (tagID)        REFERENCES Tag(tagID)
);

-- ─────────────────────────────────────────────
-- 18. WAITLIST
-- ─────────────────────────────────────────────
CREATE TABLE Waitlist (
    waitlistID INT      NOT NULL AUTO_INCREMENT,
    courseID   INT      NOT NULL,
    childID    INT      NOT NULL,
    parentID   INT      NOT NULL,
    addedAt    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status     ENUM('Waiting','Sent','Enrolled','Expired') NOT NULL DEFAULT 'Waiting',
    PRIMARY KEY (waitlistID),
    CONSTRAINT fk_wl_course  FOREIGN KEY (courseID) REFERENCES Course(courseID),
    CONSTRAINT fk_wl_child   FOREIGN KEY (childID)  REFERENCES Child(childID),
    CONSTRAINT fk_wl_parent  FOREIGN KEY (parentID) REFERENCES Parent(parentID)
);

-- ─────────────────────────────────────────────
-- 19. AR ASSET
-- ─────────────────────────────────────────────
CREATE TABLE ARAsset (
    assetID    INT          NOT NULL AUTO_INCREMENT,
    courseID   INT          NOT NULL,
    name       VARCHAR(100) NOT NULL,
    filePath   VARCHAR(255) NOT NULL,
    fileType   ENUM('GLTF','GLB') NOT NULL,
    fileSizeKB INT          NOT NULL,
    uploadedBy INT          NOT NULL,
    uploadedAt DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    isActive   TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (assetID),
    CONSTRAINT fk_ara_course   FOREIGN KEY (courseID)   REFERENCES Course(courseID),
    CONSTRAINT fk_ara_teacher  FOREIGN KEY (uploadedBy) REFERENCES Teacher(teacherID)
);

-- ─────────────────────────────────────────────
-- 20. AR RULE
-- ─────────────────────────────────────────────
CREATE TABLE ARRule (
    ruleID              INT          NOT NULL AUTO_INCREMENT,
    courseID            INT          NOT NULL,
    assetID             INT          NOT NULL,
    object1             VARCHAR(80)  NOT NULL,
    object2             VARCHAR(80)  NOT NULL,
    object3             VARCHAR(80)  NULL,
    displayName         VARCHAR(100) NOT NULL,
    description         TEXT         NULL,
    confidenceThreshold FLOAT        NOT NULL DEFAULT 0.80,
    isActive            TINYINT(1)   NOT NULL DEFAULT 1,
    createdBy           INT          NOT NULL,
    PRIMARY KEY (ruleID),
    CONSTRAINT fk_arrl_course FOREIGN KEY (courseID)  REFERENCES Course(courseID),
    CONSTRAINT fk_arrl_asset  FOREIGN KEY (assetID)   REFERENCES ARAsset(assetID),
    CONSTRAINT fk_arrl_admin  FOREIGN KEY (createdBy) REFERENCES Admin(adminID)
);

-- ─────────────────────────────────────────────
-- 21. AR SESSION
-- ─────────────────────────────────────────────
CREATE TABLE ARSession (
    sessionID     INT          NOT NULL AUTO_INCREMENT,
    teacherID     INT          NOT NULL,
    courseID      INT          NOT NULL,
    startedAt     DATETIME     NOT NULL,
    endedAt       DATETIME     NULL,
    rulesTriggered JSON        NULL,
    deviceInfo    VARCHAR(255) NULL,
    PRIMARY KEY (sessionID),
    CONSTRAINT fk_ars_teacher FOREIGN KEY (teacherID) REFERENCES Teacher(teacherID),
    CONSTRAINT fk_ars_course  FOREIGN KEY (courseID)  REFERENCES Course(courseID)
);

-- ─────────────────────────────────────────────
-- 22. SUBSCRIPTION
-- ─────────────────────────────────────────────
CREATE TABLE Subscription (
    subscriptionID INT            NOT NULL AUTO_INCREMENT,
    parentID       INT            NOT NULL,
    childID        INT            NOT NULL,
    planName       VARCHAR(100)   NOT NULL,
    basePrice      DECIMAL(10,2)  NOT NULL,
    startDate      DATE           NOT NULL,
    dueDate        DATE           NOT NULL,
    status         ENUM('Active','Overdue','Cancelled') NOT NULL DEFAULT 'Active',
    billingCycle   ENUM('Monthly','Termly') NOT NULL DEFAULT 'Monthly',
    isOverdue      TINYINT(1)     NOT NULL DEFAULT 0,
    daysOverdue    INT            NOT NULL DEFAULT 0,
    PRIMARY KEY (subscriptionID),
    CONSTRAINT fk_sub2_parent FOREIGN KEY (parentID) REFERENCES Parent(parentID),
    CONSTRAINT fk_sub2_child  FOREIGN KEY (childID)  REFERENCES Child(childID)
);

-- ─────────────────────────────────────────────
-- 23. PAYMENT
-- ─────────────────────────────────────────────
CREATE TABLE Payment (
    paymentID      INT            NOT NULL AUTO_INCREMENT,
    subscriptionID INT            NOT NULL,
    parentID       INT            NOT NULL,
    amount         DECIMAL(10,2)  NOT NULL,
    gateway        ENUM('Paymob','Fawry','ValU') NOT NULL,
    gatewayTxID    VARCHAR(100)   NULL,
    status         ENUM('Pending','Paid','Failed','Refunded') NOT NULL DEFAULT 'Pending',
    paidAt         DATETIME       NULL,
    invoicePath    VARCHAR(255)   NULL,
    lineItems      JSON           NULL,
    PRIMARY KEY (paymentID),
    CONSTRAINT fk_pay_subscription FOREIGN KEY (subscriptionID) REFERENCES Subscription(subscriptionID),
    CONSTRAINT fk_pay_parent       FOREIGN KEY (parentID)       REFERENCES Parent(parentID)
);

-- ─────────────────────────────────────────────
-- 24. NOTIFICATION
-- ─────────────────────────────────────────────
CREATE TABLE Notification (
    notificationID INT          NOT NULL AUTO_INCREMENT,
    userID         INT          NOT NULL,
    type           ENUM('Payment','Absence','Event','Assignment','Report') NOT NULL,
    title          VARCHAR(200) NOT NULL,
    message        TEXT         NOT NULL,
    channel        ENUM('InApp','Email','WhatsApp') NOT NULL,
    isRead         TINYINT(1)   NOT NULL DEFAULT 0,
    sentAt         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    readAt         DATETIME     NULL,
    relatedID      INT          NULL,
    relatedType    VARCHAR(50)  NULL,
    PRIMARY KEY (notificationID),
    CONSTRAINT fk_notif_user FOREIGN KEY (userID) REFERENCES User(userID)
);

-- ─────────────────────────────────────────────
-- 25. EVENT
-- ─────────────────────────────────────────────
CREATE TABLE Event (
    eventID      INT          NOT NULL AUTO_INCREMENT,
    adminID      INT          NOT NULL,
    title        VARCHAR(200) NOT NULL,
    description  TEXT         NULL,
    eventDate    DATETIME     NOT NULL,
    location     VARCHAR(255) NULL,
    capacity     INT          NULL,
    targetTag    VARCHAR(100) NULL,
    isFieldTrip  TINYINT(1)   NOT NULL DEFAULT 0,
    isCancelled  TINYINT(1)   NOT NULL DEFAULT 0,
    rsvpDeadline DATETIME     NULL,
    PRIMARY KEY (eventID),
    CONSTRAINT fk_event_admin FOREIGN KEY (adminID) REFERENCES Admin(adminID)
);

-- ─────────────────────────────────────────────
-- 26. EVENT RSVP
-- ─────────────────────────────────────────────
CREATE TABLE EventRSVP (
    rsvpID      INT      NOT NULL AUTO_INCREMENT,
    eventID     INT      NOT NULL,
    parentID    INT      NOT NULL,
    childID     INT      NOT NULL,
    response    ENUM('Confirmed','Declined','Pending') NOT NULL DEFAULT 'Pending',
    respondedAt DATETIME NULL,
    PRIMARY KEY (rsvpID),
    CONSTRAINT fk_rsvp_event  FOREIGN KEY (eventID)  REFERENCES Event(eventID),
    CONSTRAINT fk_rsvp_parent FOREIGN KEY (parentID) REFERENCES Parent(parentID),
    CONSTRAINT fk_rsvp_child  FOREIGN KEY (childID)  REFERENCES Child(childID)
);

-- ─────────────────────────────────────────────
-- 27. CONSENT FORM
-- ─────────────────────────────────────────────
CREATE TABLE ConsentForm (
    consentID      INT        NOT NULL AUTO_INCREMENT,
    eventID        INT        NOT NULL,
    parentID       INT        NOT NULL,
    childID        INT        NOT NULL,
    isSigned       TINYINT(1) NOT NULL DEFAULT 0,
    signedAt       DATETIME   NULL,
    sentAt         DATETIME   NULL,
    reminderCount  INT        NOT NULL DEFAULT 0,
    PRIMARY KEY (consentID),
    CONSTRAINT fk_cf_event  FOREIGN KEY (eventID)  REFERENCES Event(eventID),
    CONSTRAINT fk_cf_parent FOREIGN KEY (parentID) REFERENCES Parent(parentID),
    CONSTRAINT fk_cf_child  FOREIGN KEY (childID)  REFERENCES Child(childID)
);

-- ─────────────────────────────────────────────
-- 28. FLAG
-- ─────────────────────────────────────────────
CREATE TABLE Flag (
    flagID      INT        NOT NULL AUTO_INCREMENT,
    childID     INT        NOT NULL,
    type        ENUM('LowAttendance','AbsenceStreak','Overdue') NOT NULL,
    details     TEXT       NULL,
    isActive    TINYINT(1) NOT NULL DEFAULT 1,
    createdAt   DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    clearedAt   DATETIME   NULL,
    clearedBy   INT        NULL,
    clearReason TEXT       NULL,
    PRIMARY KEY (flagID),
    CONSTRAINT fk_flag_child FOREIGN KEY (childID)   REFERENCES Child(childID),
    CONSTRAINT fk_flag_admin FOREIGN KEY (clearedBy) REFERENCES Admin(adminID)
);

-- ─────────────────────────────────────────────
-- 29. MESSAGE
-- ─────────────────────────────────────────────
CREATE TABLE Message (
    messageID   INT        NOT NULL AUTO_INCREMENT,
    senderID    INT        NOT NULL,
    recipientID INT        NOT NULL,
    content     TEXT       NOT NULL,
    isRead      TINYINT(1) NOT NULL DEFAULT 0,
    sentAt      DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    readAt      DATETIME   NULL,
    PRIMARY KEY (messageID),
    CONSTRAINT fk_msg_sender    FOREIGN KEY (senderID)    REFERENCES User(userID),
    CONSTRAINT fk_msg_recipient FOREIGN KEY (recipientID) REFERENCES User(userID)
);

-- ─────────────────────────────────────────────
-- 30. SETTING
-- ─────────────────────────────────────────────
CREATE TABLE Setting (
    settingID  INT          NOT NULL AUTO_INCREMENT,
    `key`      VARCHAR(100) NOT NULL UNIQUE,
    value      TEXT         NOT NULL,
    updatedBy  INT          NOT NULL,
    updatedAt  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (settingID),
    CONSTRAINT fk_setting_admin FOREIGN KEY (updatedBy) REFERENCES Admin(adminID)
);

-- ─────────────────────────────────────────────
-- 31. CRON JOB
-- ─────────────────────────────────────────────
CREATE TABLE CronJob (
    jobName    VARCHAR(100) NOT NULL,
    lastRun    DATETIME     NULL,
    lastStatus ENUM('Success','Failed') NULL,
    PRIMARY KEY (jobName)
);

SET FOREIGN_KEY_CHECKS = 1;