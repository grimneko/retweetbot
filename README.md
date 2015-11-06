# retweetbot

This project is to let me automatically check if a friend of mine has posted new comic strips and if so if I
have not yet retweeted them, do so.

If you want to use it, you need to create your own twitterauth.inc from the included example and for the moment
add a little fix that was suggested by BugHunter2k in issue J7mbo#150 on Github and not yet included in the
twitter-api-php class by J7mbo. This is needed to reuse the class object instead of creating a seperate on for
posting.

It's a quick hack to work together with a cronjob that calls it on a regular basis. The cronjob looks like this

*/30 * * * * php /home/retweetbot/retweetbot.php 2>&1 > /dev/null
