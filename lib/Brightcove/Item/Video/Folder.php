<?php

namespace Brightcove\Item\Video;

use Brightcove\Item\ObjectBase;

/**
 * Representation of all data related to a folder object.
 *
 * @api
 */
class Folder extends ObjectBase {
    /**
     * The folder id.
     *
     * @var string
     */
    protected $id;
    /**
     * The id of the account.
     *
     * @var string
     */
    protected $account_id;
    /**
     * ISO 8601 date-time string
     * Date-time folder was added to the account; example: "2014-12-09T06:07:11.877Z".
     *
     * @var string
     */
    protected $created_at;
    /**
     * Number of videos in the folder.
     *
     * @var int
     */
    protected $video_count;
    /**
     * Folder name - required field
     *
     * @var string
     */
    protected $name;
    /**
     * ISO 8601 date-time string
     * date-time folder was last modified.
     * Example: "2015-01-13T17:45:21.977Z"
     *
     * @var string
     */
    protected $updated_at;

    public function applyJSON(array $json) {
        parent::applyJSON($json);
        $this->applyProperty($json, 'id');
        $this->applyProperty($json, 'account_id');
        $this->applyProperty($json, 'name');
        $this->applyProperty($json, 'created_at');
        $this->applyProperty($json, 'updated_at');
        $this->applyProperty($json, 'video_count');
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;
        $this->fieldChanged('id');
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @param string $account_id
     * @return $this
     */
    public function setAccountId($account_id) {
        $this->account_id = $account_id;
        $this->fieldChanged('account_id');
        return $this;
    }

    /**
     * @return string
     */
    public function getVideoCount() {
        return $this->video_count;
    }

    /**
     * @param int $video_count
     * @return $this
     */
    public function setVideoCount($video_count) {
        $this->video_count = $video_count;
        $this->fieldChanged('video_count');
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
        $this->fieldChanged('created_at');
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        $this->fieldChanged('name');
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
        $this->fieldChanged('updated_at');
        return $this;
    }
}
