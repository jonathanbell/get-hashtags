<?php

namespace GetHashtags;

use \Exception;

class HashtagSet {

  /**
   * The total ammount of hashtags that Instagram allows in a single post.
   */
  const TOTAL_HASHTAGS = 30;

  /**
   * All hashtag categories.
   *
   * @var string[]
   */
  protected $categories;

  /**
   * The path to the data directory.
   *
   * @var string
   */
  protected $data_directory;

  /**
   * Constructor.
   *
   * @param String $data_directory
   *   Path to the data (text files) that will be used with this class.
   * @throws Exception
   *   If $data_directory path is invalid.
   */
  public function __construct($data_directory) {
    if (!file_exists($data_directory)) {
      throw new Exception('The path to the data directory is invalid');
    }

    $this->data_directory = $data_directory;

    $this->categories = array_map(function($category) {
      $basename = basename($category, '.txt');
      if ($basename !== 'test') {
        return $basename;
      }
      return false;
    }, glob($this->data_directory.'/*.txt'));
    $this->categories = array_values(array_filter($this->categories));
  }

  /**
   * Gets a list of all available categories (based on the the textfiles
   * filenames).
   *
   * @return Array
   *   A list of all available categories.
   */
  public function getCategories(): Array {
    return $this->categories;
  }

  /**
   * Given a specific category, gets all hashtags in that category.
   *
   * @param String $category
   *   The exact name of the requested category.
   * @throws Exception
   *   Throws an exception if a supplied category does not exist.
   * @return Array
   *   A list of all of the hashtags inside the given category.
   */
  protected function getHashtagsByCategory(String $category): Array {
    $categories = $this->getCategories();

    if (!in_array($category, $categories) && $category !== 'test') {
      throw new Exception("Category: \"$category\" not found!");
    }

    $hashtags = file($this->data_directory."/$category.txt", FILE_IGNORE_NEW_LINES);
    // Check for duplicates.
    $hashtags_without_duplicates = array_unique($hashtags);

    $this_class = $this;
    return array_map(
      function($hashtag) use ($this_class) {
        return $this_class->addHash($hashtag);
      },
      $hashtags_without_duplicates
    );
  }

  /**
   * Prepends a hashtag to a given term and ensures that there is no trailing
   * whitespace.
   *
   * @param String $hashtag
   *   The hashtag without a hashsign at the start of the string.
   * @return String
   *   The tidied up, fully formed hashtag.
   */
  private function addHash(String $hashtag): String {
    if ($hashtag[0] !== '@') {
      return '#'.trim($hashtag);
    }

    return trim(str_replace('#', '', $hashtag));
  }

  /**
   * Creates the string of max hashtags for usage (copy/paste) into Instagram
   *
   * @param Array $categories
   *   A list of the requested categories. We will make the final string based
   *   off of the hashtags inside these categories.
   * @return String
   *   The fully formed string of all hashtags for use (copy/paste) into
   *   Instagram.
   */
  public function makeHashtagStringForPost(array $categories): String {
    $hashtag_string = '';
    $blended_hashtags = [];
    $return_hashtags = [];

    foreach ($categories as $category) {
      $hashtags_for_category = $this->getHashtagsByCategory($category);
      foreach ($hashtags_for_category as $hashtag) {
        $blended_hashtags[] = $hashtag;
      }
    }

    $keys = array_keys($blended_hashtags);
    shuffle($keys);
    $keys = array_slice($keys, 0, self::TOTAL_HASHTAGS);

    foreach ($keys as $key) {
      $return_hashtags[] = $blended_hashtags[$key];
    }

    // Remove duplicates.
    $return_hashtags = array_unique($return_hashtags);

    foreach ($return_hashtags as $hashtag) {
      $hashtag_string .= '#' . str_replace('#', '', $hashtag) . ' ';
    }

    return $hashtag_string;
  }

}
