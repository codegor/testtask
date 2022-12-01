1. For start project please add to your local hosts file this row:
    ````
    ip-of-your-test-machine dev.server
    ````
2. then you can install and run docker-compose (for Linux):

    - sudo apt install docker.io docker-compose -y && sudo systemctl start docker && sudo systemctl enable docker
    - docker-compose up -d

3. and then you can try to request from browser https://dev.server/
(ignore certs error)