# Playing Cards Distribution App

A web application that randomly distributes a standard deck of 52 playing cards to a specified number of people.

## Tech Stack
- Backend: PHP
- Frontend: React
- Docker & Docker Compose

## Card Notation
- Suits: S (Spades), H (Hearts), D (Diamonds), C (Clubs)
- Values: A (Ace), 2-9, X (10), J (Jack), Q (Queen), K (King)
- Example: S-A (Ace of Spades), H-K (King of Hearts)

## Docker Deployment

### Prerequisites
- Docker
- Docker Compose

### Quick Start
1. Clone the repository:
   ```bash
   git clone https://github.com/naflan121/playing-cards.git
   cd playing-cards
   ```

2. Build and start the containers:
   ```bash
   docker-compose up --build
   ```

3. Access the application:
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8000


### Container Details
- Backend container:
  - PHP with Apache
  - Exposed on port 8000
  - Includes necessary PHP extensions

- Frontend container:
  - Node 16 for development
  - Nginx for production
  - Exposed on port 3000

### Stopping the Application
```bash
docker-compose down
```

## API Endpoints

### POST /
Distributes cards to specified number of people

Request body:
```json
{
    "numberOfPeople": 4
}
```

Response:
```json
{
    "success": true,
    "data": [
        "S-A,H-2,D-3,C-4,S-5",
        "H-A,D-2,C-3,S-4,H-5",
        "D-A,C-2,S-3,H-4,D-5",
        "C-A,S-2,H-3,D-4,C-5"
    ]
}
```
