
-- --------------------------------------------------------
-- Database structure for `apollodb`
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS apollodb;
USE apollodb;

CREATE TABLE Roles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  role_name ENUM('passenger', 'admin', 'employee') UNIQUE
);

CREATE TABLE Users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  fullname VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  Roles_id INT,
  FOREIGN KEY (Roles_id) REFERENCES Roles(id)
);

CREATE TABLE Airplanes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  model VARCHAR(50),
  capacity INT
);

CREATE TABLE Flights (
  id INT PRIMARY KEY AUTO_INCREMENT,
  flight_number VARCHAR(10) UNIQUE,
  destination VARCHAR(50),
  departure_time DATETIME,
  arrival_time DATETIME,
  price DECIMAL(10,2),
  origin VARCHAR(50),
  Airplanes_idAirplanes INT,
  FOREIGN KEY (Airplanes_idAirplanes) REFERENCES Airplanes(id)
);

CREATE TABLE Bookings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  status ENUM('pending', 'confirmed', 'cancelled'),
  Users_idUsers INT,
  Flights_idFlights INT,
  Flights_Airplanes_idAirplanes INT,
  FOREIGN KEY (Users_idUsers) REFERENCES Users(id),
  FOREIGN KEY (Flights_idFlights) REFERENCES Flights(id)
);

CREATE TABLE Payment (
  id INT PRIMARY KEY AUTO_INCREMENT,
  amount DECIMAL(10,2),
  payment_status ENUM('pending', 'completed', 'failed'),
  transaction_date DATETIME
);

CREATE TABLE Invoice (
  id INT PRIMARY KEY AUTO_INCREMENT,
  comment VARCHAR(45),
  Bookings_id INT,
  Bookings_Users_idUsers INT,
  Bookings_Flights_idFlights INT,
  Bookings_Flights_Airplanes_idAirplanes INT,
  Payment_id INT,
  FOREIGN KEY (Bookings_id) REFERENCES Bookings(id),
  FOREIGN KEY (Payment_id) REFERENCES Payment(id)
);
