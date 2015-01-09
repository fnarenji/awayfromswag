CREATE DATABASE IF NOT EXISTS 'AFS';

CREATE TABLE IF NOT EXISTS 'AFS'.'Users' (
  'idUsers' INT NOT NULL,
  'userName' VARCHAR(45) NOT NULL,
  'firstName' VARCHAR(45) NOT NULL,
  'lastName' VARCHAR(45) NOT NULL,
  'mail' VARCHAR(45) NOT NULL,
  'password' VARCHAR(45) NOT NULL,
  'level' VARCHAR(45) NOT NULL,
  'position' INT NOT NULL,
  'nbPoints' INT NOT NULL,
  PRIMARY KEY ('idUsers'),
  UNIQUE INDEX 'mail_UNIQUE' ('mail' ASC))
ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS 'AFS'.'news' (
  'idnews' INT NOT NULL,
  'author' INT NOT NULL,
  'content' VARCHAR(1000) NOT NULL,
  PRIMARY KEY ('idnews'),
  INDEX 'fk_news_Users1_idx' ('author' ASC),
  CONSTRAINT 'fk_news_Users1'
    FOREIGN KEY ('author')
    REFERENCES 'AFS'.'Users' ('idUsers')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'commentN' (
  'idcomment' INT NOT NULL,
  'Users_idUsers' INT NOT NULL,
  'news_idnews' INT NOT NULL,
  'Comments_idComments' INT NOT NULL,
  PRIMARY KEY ('idcomment', 'Comments_idComments'),
  INDEX 'fk_comment_Users1_idx' ('Users_idUsers' ASC),
  INDEX 'fk_comment_news1_idx' ('news_idnews' ASC),
  INDEX 'fk_commentN_Comments1_idx' ('Comments_idComments' ASC),
  CONSTRAINT 'fk_comment_Users1'
  FOREIGN KEY ('Users_idUsers')
  REFERENCES 'AFS'.'Users' ('idUsers')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_comment_news1'
  FOREIGN KEY ('news_idnews')
  REFERENCES 'AFS'.'news' ('idnews')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_commentN_Comments1'
  FOREIGN KEY ('Comments_idComments')
  REFERENCES 'AFS'.'Comments' ('idComments')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'event' (
  'idEvent' INT NOT NULL,
  'creator' INT NOT NULL,
  'nbParticipantMax' INT NOT NULL,
  PRIMARY KEY ('idEvent'),
  INDEX 'fk_event_Users1_idx' ('creator' ASC),
  CONSTRAINT 'fk_event_Users1'
  FOREIGN KEY ('creator')
  REFERENCES 'AFS'.'Users' ('idUsers')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'participateE' (
  'event_idEvent' INT NOT NULL,
  'Users_idUsers' INT NOT NULL,
  'placeAvailable' INT NOT NULL,
  PRIMARY KEY ('event_idEvent', 'Users_idUsers'),
  INDEX 'fk_participateE_Users1_idx' ('Users_idUsers' ASC),
  CONSTRAINT 'fk_participateE_event1'
  FOREIGN KEY ('event_idEvent')
  REFERENCES 'AFS'.'event' ('idEvent')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_participateE_Users1'
  FOREIGN KEY ('Users_idUsers')
  REFERENCES 'AFS'.'Users' ('idUsers')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'commentE' (
  'idcommentE' INT NOT NULL,
  'participateE_event_idEvent' INT NOT NULL,
  'participateE_Users_idUsers' INT NOT NULL,
  'Comments_idComments' INT NOT NULL,
  PRIMARY KEY ('idcommentE', 'participateE_event_idEvent', 'participateE_Users_idUsers', 'Comments_idComments'),
  INDEX 'fk_commentE_participateE1_idx' ('participateE_event_idEvent' ASC, 'participateE_Users_idUsers' ASC),
  INDEX 'fk_commentE_Comments1_idx' ('Comments_idComments' ASC),
  CONSTRAINT 'fk_commentE_participateE1'
  FOREIGN KEY ('participateE_event_idEvent' , 'participateE_Users_idUsers')
  REFERENCES 'AFS'.'participateE' ('event_idEvent' , 'Users_idUsers')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_commentE_Comments1'
  FOREIGN KEY ('Comments_idComments')
  REFERENCES 'AFS'.'Comments' ('idComments')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'challenges' (
  'idchallenges' INT NOT NULL,
  'name' VARCHAR(45) NOT NULL,
  'description' VARCHAR(500) NOT NULL,
  'filesAdd' VARCHAR(45) NULL,
  'creator' INT NOT NULL,
  'points' INT NOT NULL,
  PRIMARY KEY ('idchallenges'),
  INDEX 'fk_challenges_Users_idx' ('creator' ASC),
  CONSTRAINT 'fk_challenges_Users'
  FOREIGN KEY ('creator')
  REFERENCES 'AFS'.'Users' ('idUsers')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'ParticipationC' (
  'win' TINYINT(1) NOT NULL,
  'Users_idUsers' INT NOT NULL,
  'challenges_idchallenges' INT NOT NULL,
  'challenges_creator' INT NOT NULL,
  'idParticipation' INT NOT NULL,
  INDEX 'fk_Participation_Users1_idx' ('Users_idUsers' ASC),
  INDEX 'fk_Participation_challenges1_idx' ('challenges_idchallenges' ASC, 'challenges_creator' ASC),
  PRIMARY KEY ('idParticipation'),
  CONSTRAINT 'fk_Participation_Users1'
  FOREIGN KEY ('Users_idUsers')
  REFERENCES 'AFS'.'Users' ('idUsers')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Participation_challenges1'
  FOREIGN KEY ('challenges_idchallenges' , 'challenges_creator')
  REFERENCES 'AFS'.'challenges' ('idchallenges' , 'creator')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'commentC' (
  'idcommentC' INT NOT NULL,
  'ParticipationC_idParticipation' INT NOT NULL,
  'challenges_idchallenges' INT NOT NULL,
  'Comments_idComments' INT NOT NULL,
  PRIMARY KEY ('idcommentC', 'Comments_idComments'),
  INDEX 'fk_commentC_ParticipationC1_idx' ('ParticipationC_idParticipation' ASC),
  INDEX 'fk_commentC_challenges1_idx' ('challenges_idchallenges' ASC),
  INDEX 'fk_commentC_Comments1_idx' ('Comments_idComments' ASC),
  CONSTRAINT 'fk_commentC_ParticipationC1'
  FOREIGN KEY ('ParticipationC_idParticipation')
  REFERENCES 'AFS'.'ParticipationC' ('idParticipation')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_commentC_challenges1'
  FOREIGN KEY ('challenges_idchallenges')
  REFERENCES 'AFS'.'challenges' ('idchallenges')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_commentC_Comments1'
  FOREIGN KEY ('Comments_idComments')
  REFERENCES 'AFS'.'Comments' ('idComments')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS 'AFS'.'Comments' (
  'idComments' INT NOT NULL,
  'contents' VARCHAR(500) NOT NULL,
  'mark' INT NULL,
  'idPere' INT NULL,
  PRIMARY KEY ('idComments'),
  INDEX 'fk_Comments_Comments1_idx' ('idPere' ASC),
  CONSTRAINT 'fk_Comments_Comments1'
  FOREIGN KEY ('idPere')
  REFERENCES 'AFS'.'Comments' ('idComments')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;