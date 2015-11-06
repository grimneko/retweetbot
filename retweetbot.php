<?php

require_once('./twitter-api-php/TwitterAPIExchange.php');
require('./twitterauth.inc');

$twitter = new TwitterAPIExchange($auth_settings);

//Get the timeline data for ndbag
$result_timeline = json_decode($twitter->setGetField('?screen_name=ndbag&exclude_replies=true&count=30')
	->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json','GET')
	->performRequest());

//split timeline in a php readable format and loop through it
foreach ($result_timeline as $timeline) {
		//check if it fits the tweets we're looking for
		if (preg_match('/^(Ndbag the Boogeyman #webcomic update|A classic Ndbag the Boogeyman comic)/i',$timeline->text)) {
			//see if it was retweeted already and if so we need to see if we did it (we want to not hammer api with crap)
			if ($timeline->retweeted == true) {
				//so it was already retweeted, so lets get some data about that
				$result_retweets = json_decode($twitter->setGetField('')
					->buildOauth("https://api.twitter.com/1.1/statuses/retweets/{$timeline->id_str}.json",'GET')
					->performRequest());
				//prepare a flag to indicate its fine to retweet since we didnt already do that
				$retweetok_flag = true;
				// loop through the screen names of the users that retweeted the tweet already
				foreach ($result_retweets as $retweets) {
					//see if we are in the list
					if ($retweets->user->screen_name == 'grimneko') {
						//we found ourself and so we can set the flag to false (no retweeting) and kill the loop
						$retweetok_flag = false;
						break;
					}
				}
				//see if the flag is toggled
				if ($retweetok_flag == true) {
					//so the flag was clear and we can retweet
					$retweetaction = $twitter->buildOauth("https://api.twitter.com/1.1/statuses/retweet/{$timeline->id_str}.json",'POST')
						->setGetField(null)
						->setPostfields(array('id' => $timeline->id_str))
						->performRequest();
				}
			} else {
				//we matched the regex but there is no retweet for this yet, so lets fire at it
				$retweetaction = $twitter->buildOauth("https://api.twitter.com/1.1/statuses/retweet/{$timeline->id_str}.json",'POST')
						->setGetField(null)
						->setPostfields(array('id' => $timeline->id_str))
						->performRequest();
			}
		}
}
