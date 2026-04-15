# 🎲 Yamb Multiplayer Game

Real-time multiplayer Yamb (Yahtzee-style dice game) built with **Symfony**, **API Platform**, **React**, **MySQL**, and **Mercure** for live communication between two players.

The project is designed as a server-authoritative multiplayer game with clean architecture, REST API backend, and real-time event updates.

---

## 🚀 Features

- 🎮 1v1 multiplayer Yamb gameplay
- 🔄 Real-time game updates
- ⚡ Mercure live event system
- 📡 Turn-based gameplay with instant sync
- 🔐 Authentication system
- 🧠 Server-side game rules (anti-cheat)
- 🗄️ Persistent game storage (MySQL)
- 📊 Score tracking & validation system
- 📱 Responsive React frontend
- 🧩 Hybrid REST + real-time architecture

---

## 🧱 Tech Stack

### Backend
- Symfony (PHP 8+)
- API Platform
- Doctrine ORM
- MySQL
- Mercure (real-time pub/sub)

### Frontend
- React
- Axios (REST API calls)
- EventSource (Mercure client)
- Context API / state management

### Infrastructure
- Mercure Hub
- Docker (optional)
- Nginx (optional)

---

## 🧩 Architecture

The system is split into 3 layers:

### 1. REST API (Symfony)
Used for:
- Authentication
- Game creation & joining
- Move validation
- Score calculation
- Database persistence

### 2. Real-Time Layer (Mercure)
Used for:
- Live game updates
- Turn changes
- Dice rolls
- Game finish events

### 3. Frontend (React)
Used for:
- Game UI
- Sending player actions
- Subscribing to live updates

---

## ⚡ Real-Time Flow

1. Player joins a game
2. React subscribes to Mercure topic:/game/{gameId}
3. Player performs an action (REST API call)
4. Symfony processes move:
- validates rules
- updates MySQL
5. Symfony publishes event to Mercure
6. Opponent receives instant update
7. UI updates in real-time
