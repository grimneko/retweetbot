// Import modules we need
var TwitterBot = require("node-twitterbot").TwitterBot;

// Import our auth tokens from auth.json
var LoginData = require("auth.json"); 

// Include your access information below
var Bot = new TwitterBot({
  "consumer_secret": LoginData.consumer_secret,
  "consumer_key": LoginData.consumer_key,
  "access_token": LoginData.access_token,
  "access_token_secret": LoginData.access_secret
});

