CREATE DATABASE IF NOT EXISTS blog;

USE blog;

create table IF NOT EXISTS user
(
    user_id int auto_increment
        primary key,
    email varchar(45) not null,
    password varchar(255) not null,
    roles varchar(255) not null
);

create table IF NOT EXISTS blogpost
(
    blogpost_id int auto_increment
        primary key,
    title tinytext not null,
    summary tinytext not null,
    content text null,
    author int not null,
    creation_date datetime not null,
    modification_date datetime null,
    constraint blogpost_user_user_id_fk
        foreign key (author) references user (user_id)
);

create table IF NOT EXISTS comment
(
    comment_id int auto_increment,
    title tinytext not null,
    text text not null,
    author int null,
    creation_date datetime not null,
    modification_date datetime null,
    constraint comment_comment_id_uindex
        unique (comment_id),
    constraint comment_user_user_id_fk
        foreign key (author) references user (user_id)
);

