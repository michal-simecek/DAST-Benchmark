// init-mongo.js
db = db.getSiblingDB('DASTDB');

db.createCollection('users');
db.users.insert({ username: 'admin', password: 'adminpass' });
db.users.insert({ username: 'user', password: 'userpass' });
