# Get Hashtags

Put your hashtags into `.txt` files and title them by category in order to output them via the `makeHashtagStringForPost()` method.

## Huh, what??

```php
$hashtag_set = new GetHashtags('/path/to/your/data/directory');
echo $hashtag_set->makeHashtagStringForPost(['art', 'television']);
```

That'll output something like this string:

```
#television #art #artist #artwork #artistsoninstagram #artoftheday #artsy #arty #kingoftelevision #indiantelevision #televisionproduction #televisions #royaltelevision #televisionespañola
```

You could then use the string above on Instagram! It all depends what kind of data (text files) you supply the class though - it's up to you to maintain your own list. Support for [HashtagsForLikes](https://www.hashtagsforlikes.co/) may be added in the future but who know about that, really.

### What's the "data directory"?

It's where you'll keep your hashtag collections. Organize your "data" directory with a collection of text files. The filename(s) will be used as hashtag categories while each line inside the files will be considered a hashtag. So, with the example above, your data directory might look something like:

```
art.txt
  artistsoninstagram
  artwork
  arty
  artist
  art
  artsy
  artoftheday

television.txt
  royaltelevision
  indiantelevision
  television
  televisionespañola
  kingoftelevision
  televisionproduction
  televisions
```
