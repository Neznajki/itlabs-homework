<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200725064037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("create table challenge
(
    id      int auto_increment
        primary key,
    name    int       null,
    created timestamp null,
    constraint challenge_name_uindex
        unique (name)
);");
        $this->addSql("create table division
(
    id   int auto_increment
        primary key,
    name varchar(32) null,
    constraint division_name_uindex
        unique (name)
);");
        $this->addSql("create table play_of_steps
(
    id          int auto_increment
        primary key,
    name        varchar(32) not null,
    match_count int         not null,
    constraint play_of_steps_name_uindex
        unique (name),
    constraint play_of_steps_pk_2
        unique (name)
);");
        $this->addSql("create table team
(
    id       int auto_increment
        primary key,
    name     varchar(32) collate utf8mb4_unicode_ci null,
    strength int                                    null,
    constraint team_name_uindex
        unique (name)
);");
        $this->addSql("create table challenge_division
(
    id           int auto_increment
        primary key,
    challenge_id int not null,
    division_id  int not null,
    constraint challenge_division_pk_2
        unique (challenge_id, division_id),
    constraint challenge_division_challenge_id_fk
        foreign key (challenge_id) references challenge (id),
    constraint challenge_division_division_id_fk
        foreign key (division_id) references division (id)
);");
        $this->addSql("create table challenge_division_team
(
    id                    int auto_increment
        primary key,
    challenge_division_id int       not null,
    team_id               int       not null,
    assigned              timestamp null,
    constraint challenge_division_team_challenge_division_id_uindex
        unique (challenge_division_id),
    constraint challenge_division_team_team_id_uindex
        unique (team_id),
    constraint challenge_division_team_challenge_division_id_fk
        foreign key (challenge_division_id) references challenge_division (id),
    constraint challenge_division_team_team_id_fk
        foreign key (team_id) references team (id)
);");
        $this->addSql("create table challenge_play_of_step
(
    id              int auto_increment
        primary key,
    challenge_id    int not null,
    play_of_step_id int not null,
    constraint challenge_play_of_step_challenge_id_uindex
        unique (challenge_id),
    constraint challenge_play_of_step_challenge_id_fk
        foreign key (challenge_id) references challenge (id),
    constraint challenge_play_of_step_play_of_steps_id_fk
        foreign key (play_of_step_id) references play_of_steps (id)
);");
        $this->addSql("create table division_match
(
    id         int auto_increment
        primary key,
    team_a_id  int       not null,
    team_b_id  int       not null,
    team_a_win tinyint   null,
    created    timestamp null,
    resulted   timestamp null,
    constraint division_match_pk
        unique (team_a_id, team_b_id),
    constraint division_match_challenge_division_team_id_fk
        foreign key (team_a_id) references challenge_division_team (id),
    constraint division_match_challenge_division_team_id_fk_2
        foreign key (team_b_id) references challenge_division_team (id)
);");
        $this->addSql("create table play_of_match
(
    id              int auto_increment
        primary key,
    team_a_id       int       null,
    team_b_id       int       null,
    play_of_step_id int       null,
    team_a_win      tinyint   null,
    created         timestamp null,
    resulted        timestamp null,
    constraint play_of_match_pk_2
        unique (team_a_id, team_b_id),
    constraint play_of_match_challenge_division_team_id_fk
        foreign key (team_a_id) references challenge_division_team (id),
    constraint play_of_match_challenge_division_team_id_fk_2
        foreign key (team_b_id) references challenge_division_team (id),
    constraint play_of_match_play_of_steps_id_fk
        foreign key (play_of_step_id) references play_of_steps (id)
);");
        $this->addSql("INSERT IGNORE INTO play_of_steps (name, match_count) VALUES
 ('Finals', 1),
 ('Semi Finals', 2),
 ('Quarter Finals', 4),
 ('1/8', 8)
 ");
        $this->addSql("INSERT IGNORE INTO team (name, strength) VALUES ('Rimi', 50),
('Maxima', 50),
('Pepco', 50),
('LIDL', 50),
('Guchi', 50),
('Котлетосы вперёд', 60),
('Пивная у Джо', 40),
('ГАЗМЯС', 50),
('Prado', 30),
('Guess', 50),
('SC', 60),
('WC', 60),
('CS', 60),
('DOTA', 60),
('LOL', 50),
('Apple', 51),
('Microsoft', 30),
('ITLABS22', 80),
('Mego', 50),
('Ķekava', 50),
('Heroes', 50),
('Cheaters', 50),
('Consumers', 50),
('SingleTone', 50),
('Factory', 50),
('DTO', 50),
('Processor', 50),
('ValueObject', 50),
('DI', 50),
('CI', 50),
('Container', 50),
('Validator', 50);");

        $this->addSql(
            "INSERT INTO division (name) VALUES ('Division A'), ('Division B')"
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
