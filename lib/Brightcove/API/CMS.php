<?php
namespace Brightcove\API;

use Brightcove\API\Request\SubscriptionRequest;
use Brightcove\Item\Subscription;
use Brightcove\Item\Video\Video;
use Brightcove\Item\Video\Source;
use Brightcove\Item\Video\Images;
use Brightcove\Item\Playlist;
use Brightcove\Item\CustomFields;
use Brightcove\Item\Video\Folder;

/**
  * This class provides uncached read access to the data via request functions.
 *
 * @api
 */
class CMS extends API {

  protected function cmsRequest($method, $endpoint, $result, $is_array = FALSE, $post = NULL) {
    return $this->client->request($method, '1','cms', $this->account, $endpoint, $result, $is_array, $post);
  }

  /**
   * Lists video objects with the given restrictions.
   *
   * @return Video[]
   */
  public function listVideos($search = NULL, $sort = NULL, $limit = NULL, $offset = NULL) {
    $query = '';
    if ($search) {
      $query .= '&q=' . urlencode($search);
    }
    if ($sort) {
      $query .= "&sort={$sort}";
    }
    if ($limit) {
      $query .= "&limit={$limit}";
    }
    if ($offset) {
      $query .= "&offset={$offset}";
    }
    if (strlen($query) > 0) {
      $query = '?' . substr($query, 1);
    }
    return $this->cmsRequest('GET', "/videos{$query}", Video::class, TRUE);
  }

  /**
   * Returns the amount of a searched video's result.
   *
   * @return int|null
   */
  public function countVideos($search = NULL) {
    $query = $search === NULL ? '' : "?q=" . urlencode($search);
    $result = $this->cmsRequest('GET', "/counts/videos{$query}", NULL);
    if ($result && !empty($result['count'])) {
      return $result['count'];
    }
    return NULL;
  }

  /**
   * Gets the images for a single video.
   *
   * @return Images
   */
  public function getVideoImages($video_id) {
    return $this->cmsRequest('GET', "/videos/{$video_id}/images", Images::class);
  }

  /**
   * Gets the sources for a single video.
   *
   * @return Source[]
   */
  public function getVideoSources($video_id) {
    return $this->cmsRequest('GET', "/videos/{$video_id}/sources", Source::class, TRUE);
  }

  public function getVideoFields() {
    return $this->cmsRequest('GET', "/video_fields", CustomFields::class, FALSE);
  }

  /**
   * Gets the data for a single video by issuing a GET request.
   *
   * @return Video $video
   */
  public function getVideo($video_id) {
    return $this->cmsRequest('GET', "/videos/{$video_id}", Video::class);
  }

  /**
   * Creates a new video object.
   *
   * @return Video $video
   */
  public function createVideo(Video $video) {
    return $this->cmsRequest('POST', '/videos', Video::class, FALSE, $video);
  }

  /**
   * Updates a video object with an HTTP PATCH request.
   *
   * @return Video $video
   */
  public function updateVideo(Video $video) {
    $video->fieldUnchanged('account_id', 'id');
    return $this->cmsRequest('PATCH', "/videos/{$video->getId()}", Video::class, FALSE, $video);
  }

  /**
   * Deletes a video object.
   */
  public function deleteVideo($video_id) {
    return $this->cmsRequest('DELETE', "/videos/{$video_id}", NULL);
  }

  /**
   * @return int
   */
  public function countPlaylists() {
    $result = $this->cmsRequest('GET', "/counts/playlists", NULL);
    if ($result && !empty($result['count'])) {
      return $result['count'];
    }
    return NULL;
  }

  /**
   * @return Playlist[]
   */
  public function listPlaylists($sort = NULL, $limit = NULL, $offset = NULL) {
    $query = '';
    if ($sort) {
      $query .= "&sort={$sort}";
    }
    if ($limit) {
      $query .= "&limit={$limit}";
    }
    if ($offset) {
      $query .= "&offset={$offset}";
    }
    if (strlen($query) > 0) {
      $query = '?' . substr($query, 1);
    }
    return $this->cmsRequest('GET', "/playlists{$query}", Playlist::class, TRUE);
  }

  /**
   * @param Playlist $playlist
   * @return Playlist
   */
  public function createPlaylist(Playlist $playlist) {
    return $this->cmsRequest('POST', '/playlists', Playlist::class, FALSE, $playlist);
  }

  /**
   * @param string $playlist_id
   * @return Playlist
   */
  public function getPlaylist($playlist_id) {
    return $this->cmsRequest('GET', "/playlists/{$playlist_id}", Playlist::class);
  }

  /**
   * @param Playlist $playlist
   * @return Playlist
   */
  public function updatePlaylist(Playlist $playlist) {
    $playlist->fieldUnchanged('id');
    return $this->cmsRequest('PATCH', "/playlists/{$playlist->getId()}", Playlist::class, FALSE, $playlist);
  }

  /**
   * @param string $playlist_id
   */
  public function deletePlaylist($playlist_id) {
    $this->cmsRequest('DELETE', "/playlists/{$playlist_id}", NULL);
  }

  /**
   * @param string $playlist_id
   * @return int
   */
  public function getVideoCountInPlaylist($playlist_id) {
    $result = $this->cmsRequest('GET', "/counts/playlists/{$playlist_id}/videos", NULL);
    if ($result && !empty($result['count'])) {
      return $result['count'];
    }
    return NULL;
  }

  /**
   * @param string $playlist_id
   * @return Video[]
   */
  public function getVideosInPlaylist($playlist_id) {
    return $this->cmsRequest('GET', "/playlists/{$playlist_id}/videos", Video::class, TRUE);
  }

  /**
   * @return Subscription[]|null
   */
  public function getSubscriptions() {
    return $this->cmsRequest('GET', '/subscriptions', Subscription::class, TRUE);
  }

  /**
   * @param string $subscription_id
   * @return Subscription
   */
  public function getSubscription($subscription_id)  {
    return $this->cmsRequest('GET', "/subscriptions/{$subscription_id}", Subscription::class);
  }

  /**
   * @param SubscriptionRequest $request
   * @return Subscription|null
   */
  public function createSubscription(SubscriptionRequest $request) {
    return $this->cmsRequest('POST', '/subscriptions', Subscription::class, FALSE, $request);
  }

  /**
   * @param string $subscription_id
   */
  public function deleteSubscription($subscription_id) {
    $this->cmsRequest('DELETE', "/subscriptions/{$subscription_id}", NULL);
  }

  /**
   * Lists folder objects with the given restrictions.
   *
   * @param int $limit
   * @param int $offset
   * @return Folder[]
   */
  public function listFolders($limit = NULL, $offset = NULL)
  {
    $query = '';
    if (!empty($limit)) {
      $query .= "&limit={$limit}";
    }
    if (isset($offset)) {
      $query .= "&offset={$offset}";
    }
    if (strlen($query) > 0) {
      $query = '?' . substr($query, 1);
    }
    return $this->cmsRequest('GET', "/folders{$query}", Folder::class, TRUE);
  }

  /**
   * @param string $folder_id
   * @return Folder
   */
  public function getFolder($folder_id)
  {
    return $this->cmsRequest('GET', "/folder/{$folder_id}", Folder::class);
  }

  /**
   * @param string $folderName
   * @return Folder
   */
  public function createFolder($folderName)
  {
    $folder = new Folder();
    $folder->setName($folderName);
    return $this->cmsRequest('POST', '/folders', Folder::class, FALSE, $folder);
  }

  /**
   * @param Folder $folder
   * @return Folder
   */
  public function updateFolder(Folder $folder)
  {
    $folder->fieldUnchanged('id');
    return $this->cmsRequest('PATCH', "/folders/{$folder->getId()}", Folder::class, FALSE, $folder);
  }

  /**
   * @param string $folder_id
   */
  public function deleteFolder($folder_id)
  {
    $this->cmsRequest('DELETE', "/folders/{$folder_id}", NULL);
  }

  /**
   * @param Folder $folder
   * @return Video[]
   */
  public function getVideosInFolder(Folder $folder)
  {
    return $this->cmsRequest('GET', "/folders/{$folder->getId()}/videos", Video::class, TRUE);
  }

  /**
   * @param Folder $folder
   * @param Video $video
   */
  public function addVideoToFolder(Folder $folder, Video $video)
  {
    $this->cmsRequest('PUT', "/folders/{$folder->getId()}/videos/{$video->getId()}", NULL);
  }

  /**
   * @param Folder $folder
   * @param Video $video
   */
  public function removeVideoFromFolder(Folder $folder, Video $video)
  {
    $this->cmsRequest('DELETE', "/folders/{$folder->getId()}/videos/{$video->getId()}", NULL);
  }

}
