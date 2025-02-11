
CREATE DATABASE eventbrite_clone;


CREATE TABLE
   users (
      id SERIAL PRIMARY KEY,
      email VARCHAR(255) UNIQUE NOT NULL,
      password_hash VARCHAR(255) NOT NULL,
      role VARCHAR(50) NOT NULL CHECK (role IN ('Organisateur', 'Participant', 'Admin')),
      avatar_url VARCHAR(255),
      full_name VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

CREATE TABLE
   categories (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL);

CREATE TABLE
   events (
      id SERIAL PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      description TEXT,
      date TIMESTAMP NOT NULL,
      location VARCHAR(255) NOT NULL,
      price NUMERIC(10, 2),
      capacity INT,
      organizer_id INT REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
      status VARCHAR(50) NOT NULL CHECK (status IN ('Actif', 'En attente', 'Terminé')),
      category_id INT REFERENCES categories (id),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );




CREATE TABLE
   tags (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL);


CREATE TABLE
   event_tags (
      event_id INT REFERENCES events (id) ON DELETE CASCADE ON UPDATE CASCADE,
      tag_id INT REFERENCES tags (id) ON DELETE CASCADE ON UPDATE CASCADE,
      PRIMARY KEY (event_id, tag_id)
   );


CREATE TABLE
   reservations (
      id SERIAL PRIMARY KEY,
      user_id INT REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
      event_id INT REFERENCES events (id) ON DELETE CASCADE ON UPDATE CASCADE,
      ticket_type VARCHAR(50) NOT NULL CHECK (ticket_type IN ('Gratuit', 'Payant', 'VIP')),
      quantity INT NOT NULL,
      total_price NUMERIC(10, 2),
      status VARCHAR(50) NOT NULL CHECK (status IN ('Confirmé', 'Annulé', 'Remboursé')),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );


CREATE TABLE
   payments (
      id SERIAL PRIMARY KEY,
      reservation_id INT REFERENCES reservations (id) ON DELETE CASCADE ON UPDATE CASCADE,
      amount NUMERIC(10, 2) NOT NULL,
      payment_method VARCHAR(50) NOT NULL CHECK (payment_method IN ('Stripe', 'PayPal')),
      transaction_id VARCHAR(255),
      status VARCHAR(50) NOT NULL CHECK (status IN ('Réussi', 'Échoué', 'En attente')),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

INSERT INTO
   users (email, password_hash, role, avatar_url, full_name)
VALUES
   (
<<<<<<< HEAD
      'admin@phoenix.com', '$2y$10$PSGT25Xh.ms55i/SJsuRTOMPqfjnHrK06JG4Zb2h.jP/WsP1XbybK',
=======
      'admin@phoenix.com',
      '$2y$10$PSGT25Xh.ms55i/SJsuRTOMPqfjnHrK06JG4Zb2h.jP/WsP1XbybK',
>>>>>>> setup
      'Admin',
      'https://example.com/avatar1.jpg',
      'Admin User'
   ),
   (
      'organizer1@phoenix.com',
      '$2y$10$PSGT25Xh.ms55i/SJsuRTOMPqfjnHrK06JG4Zb2h.jP/WsP1XbybK',
      'Organisateur',
      'https://example.com/avatar2.jpg',
      'Organizer One'
   ),
   (
      'participant1@phoenix.com',
      '$2y$10$PSGT25Xh.ms55i/SJsuRTOMPqfjnHrK06JG4Zb2h.jP/WsP1XbybK',
      'Participant',
      'https://example.com/avatar3.jpg',
      'Participant One'
   );

INSERT INTO
   categories (name)
VALUES
   ('Conférence'),
   ('Concert'),
   ('Sport'),
   ('Atelier');

INSERT INTO
   tags (name)
VALUES
   ('Technologie'),
   ('Musique'),
   ('Fitness'),
   ('Éducation');

INSERT INTO
   events (
      title,
      description,
      date,
      location,
      price,
      capacity,
      organizer_id,
      status,
      category_id
   )
VALUES
   (
      'Conférence Tech',
      'Une conférence sur les dernières technologies.',
      '2023-12-15 10:00:00',
      'Paris',
      50.00,
      100,
      2,
      'Actif',
      1
   ),
   (
      'Concert Rock',
      'Un concert de rock avec des artistes internationaux.',
      '2023-11-20 20:00:00',
      'Lyon',
      30.00,
      500,
      2,
      'Actif',
      2
   ),
   (
      'Marathon de Paris',
      'Participez au marathon annuel de Paris.',
      '2024-04-07 08:00:00',
      'Paris',
      0.00,
      10000,
      2,
      'En attente',
      3
   );

INSERT INTO
   event_tags (event_id, tag_id)
VALUES
   (1, 1), 
   (2, 2), 
   (3, 3);


INSERT INTO
   reservations (
      user_id,
      event_id,
      ticket_type,
      quantity,
      total_price,
      status
   )
VALUES
   (3, 1, 'Payant', 2, 100.00, 'Confirmé'), 
   (3, 2, 'VIP', 1, 50.00, 'Confirmé');


INSERT INTO
   payments (
      reservation_id,
      amount,
      payment_method,
      transaction_id,
      status
   )
VALUES
   (1, 100.00, 'Stripe', 'txn_123456789', 'Réussi'), 
   (2, 50.00, 'PayPal', 'txn_987654321', 'Réussi');


