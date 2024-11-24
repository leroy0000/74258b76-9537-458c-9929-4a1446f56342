# 74258b76-9537-458c-9929-4a1446f56342
This is a Dockerized setup for a Laravel Zero application.

## Prerequisites

Before you begin, make sure you have the following installed:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Setting Up the Application

### 1. Clone the Repository

First, clone the repository to your local machine.

```bash
git clone https://github.com/leroy0000/74258b76-9537-458c-9929-4a1446f56342.git reports-cli
cd reports-cli
```

### 2. Build the Docker Image

Run the following command to build the Docker image for the Laravel Zero application.

```bash
docker compose build
```
### 3. Running the Application

Once the image is built, you can run the application with the following command:

```bash
docker compose run app
```

### 4. Running Tests

```bash
docker compose run test
```

## Development Dependencies

### **Pest (for testing)**

- **pestphp/pest**: A testing framework used to write and run tests for this Laravel Zero application.

### **Sushi**

- **calebporzio/sushi**: A package for working with database models and generating useful code for database queries.
