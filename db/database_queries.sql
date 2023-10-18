CREATE TABLE accounts (
    acctID INT AUTO_INCREMENT NOT NULL,
    acctFName VARCHAR (55) NOT NULL,
    acctLName VARCHAR (55) NOT NULL,
    acctUsername VARCHAR (55) NOT NULL,
    acctPassword VARCHAR (55) NOT NULL,
    acctEmail VARCHAR (55) NOT NULL,
    acctContact VARCHAR (55) NOT NULL,
    accessType VARCHAR (11) NOT NULL,
    PRIMARY KEY (acctID)
) AUTO_INCREMENT = 1001;

CREATE TABLE movies (
    movieID INT AUTO_INCREMENT NOT NULL,
    movieTitle VARCHAR (255) NOT NULL,
    movieRating VARCHAR (11) NOT NULL,
    ticketPrice INT NOT NULL,
    posterDir VARCHAR (255) NOT NULL,
    PRIMARY KEY (movieID)
);

CREATE TABLE screening_room (
    roomID INT AUTO_INCREMENT NOT NULL,
    roomName VARCHAR (55) NOT NULL,
    movieID INT,
    seat_capacity INT NOT NULL,
    PRIMARY KEY (roomID),
    FOREIGN KEY (movieID) REFERENCES movies(movieID)
);

CREATE TABLE seat_status (
    roomID INT NOT NULL,
    screening_time TIME NOT NULL,
    no_of_available INT NOT NULL,
    occupied VARCHAR (255) NOT NULL,
    PRIMARY KEY (roomID, screening_time),
    FOREIGN KEY (roomID) REFERENCES screening_room(roomID)
);

CREATE TABLE transactions (
    transactionNo VARCHAR (55) NOT NULL,
    date_time VARCHAR (55) NOT NULL,
    movieID INT NOT NULL,
    roomID INT NOT NULL,
    screening_time TIME NOT NULL,
    tickets VARCHAR (255) NOT NULL,
    seats VARCHAR (255) NOT NULL,
    total_amount FLOAT NOT NULL,
    method_of_payment VARCHAR (255) NOT NULL,
    payment FLOAT,
    PRIMARY KEY (transactionNo),
    FOREIGN KEY (movieID) REFERENCES movies(movieID),
    FOREIGN KEY (roomID, screening_time) REFERENCES seat_status(roomID, screening_time)
);

INSERT INTO accounts(acctFName, acctLName, acctUsername, acctPassword, acctEmail, acctContact, accessType)
VALUES ('Administrator', 'Account', 'admin', 'Pass123', 'filmhouse.theater@gmail.com', '09876543210', 'admin'),
    ('User', 'Account', 'user', 'qwerty123', 'filmhouse.user@gmail.com', '01234567890', 'user');

INSERT INTO movies(movieTitle, movieRating, ticketPrice, posterDir)
VALUES ('Eternals', 'PG-13', 290, 'posters/eternals.jpg'),
    ('Ghostbusters: Afterlife', 'PG-13', 290, 'posters/ghostbusters-afterlife.jpg'),
    ('Resident Evil: Welcome to the Raccoon City', 'R-18', 280, 'posters/resident-evil-welcome-to-the-raccoon-city.jpg'),
    ('Clifford: The Big Red Dog', 'G', 250, 'posters/clifford-the-big-red-dog.jpg'),
    ('Dune', 'PG-13', 310, 'posters/dune.jpg'),
    ('Encanto', 'G', 250, 'posters/encanto.jpg');

INSERT INTO screening_room(roomName, movieID, seat_capacity)
VALUES ('CINEMA ROOM 1', 1, 55),
    ('CINEMA ROOM 2', 2, 55),
    ('CINEMA ROOM 3', 3, 55),
    ('CINEMA ROOM 4', 4, 55),
    ('CINEMA ROOM 5', 5, 55),
    ('CINEMA ROOM 6', 6, 55);

INSERT INTO seat_status(roomID, screening_time, no_of_available, occupied)
VALUES (1, '12:15:00', 55, "[]"),
    (1, '14:45:00', 55, "[]"),
    (1, '17:15:00', 55, "[]"),
    (1, '19:45:00', 55, "[]"),
    (1, '22:15:00', 55, "[]"),
    (2, '11:00:00', 55, "[]"),
    (2, '13:45:00', 55, "[]"),
    (2, '16:30:00', 55, "[]"),
    (2, '19:15:00', 55, "[]"),
    (2, '22:00:00', 55, "[]"),
    (3, '12:30:00', 55, "[]"),
    (3, '15:30:00', 55, "[]"),
    (3, '18:30:00', 55, "[]"),
    (3, '21:30:00', 55, "[]"),
    (4, '11:45:00', 55, "[]"),
    (4, '14:15:00', 55, "[]"),
    (4, '16:45:00', 55, "[]"),
    (4, '19:15:00', 55, "[]"),
    (4, '21:45:00', 55, "[]"),
    (5, '10:55:00', 55, "[]"),
    (5, '13:45:00', 55, "[]"),
    (5, '16:35:00', 55, "[]"),
    (5, '19:25:00', 55, "[]"),
    (5, '22:15:00', 55, "[]"),
    (6, '12:30:00', 55, "[]"),
    (6, '15:40:00', 55, "[]"),
    (6, '19:00:00', 55, "[]"),
    (6, '22:15:00', 55, "[]");