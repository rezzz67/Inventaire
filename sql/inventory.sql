CREATE DATABASE inventory;

USE inventory;

CREATE TABLE ordinateurs (
    serialnumber VARCHAR(20) PRIMARY KEY,
    utilisateur VARCHAR(20),
    marque VARCHAR(20),
    garantie DATE,
    commentaire TEXT
);

