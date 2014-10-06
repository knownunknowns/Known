<?php

    namespace Idno\Entities\Reader {

        use Idno\Common\Entity;

        class FeedItem extends Entity
        {

            public $collection = 'reader';

            /**
             * Sets the URL of the feed this item belongs to
             * @param $url
             */
            function setFeedURL($url)
            {
                $this->feed_url = $url;
            }

            /**
             * Retrieves the URL of the feed this item belongs to
             * @param $url
             * @return mixed
             */
            function getFeedURL($url)
            {
                return $this->feed_url;
            }

            /**
             * Sets the URL of this feed item
             * @param $url
             */
            function setURL($url)
            {
                $this->url = $url;
            }

            /**
             * Sets the body of this item to the given content string
             * @param $content
             */
            function setBody($content) {
                $this->body = $content;
            }

            /**
             * Retrieves the body of this item
             * @return string
             */
            function getBody() {
                return $this->body;
            }

            /**
             * Sets the non-HTML value of this item
             * @param $content
             */
            function setValue($content) {
                $this->value = $content;
            }

            /**
             * Retrieves the non-HTML value of this item
             * @return mixed
             */
            function getValue() {
                return $this->value;
            }

            /**
             * Sets the URL of a photo associated with this item
             * @param $photo
             */
            function setPhoto($photo) {
                $this->photo = $photo;
            }

            /**
             * Retrieves the URL of a photo associated with this item
             * @param $photo
             * @return mixed
             */
            function getPhoto($photo) {
                return $this->photo;
            }

            /**
             * Sets the time that this item was published
             * @param $time
             */
            function setPublishDate($time)
            {
                $this->created = strtotime($time);
            }

            /**
             * Sets the name of the author of this item
             * @param $author_name
             */
            function setAuthorName($author_name) {
                $this->authorName = $author_name;
            }

            /**
             * Retrieves the name of the author of this item
             * @return mixed
             */
            function getAuthorName() {
                return $this->authorName;
            }

            /**
             * Sets the URL of the author photo associated with this piece
             * @param $author_photo
             */
            function setAuthorPhoto($author_photo) {
                $this->authorPhoto = $author_photo;
            }

            /**
             * Retrieves the URL of the author photo associated with this piece
             * @param $author_photo
             * @return mixed
             */
            function getAuthorPhoto($author_photo) {
                return $this->authorPhoto;
            }

            /**
             * Sets the URL of the author of this item
             * @param $url
             */
            function setAuthorURL($url) {
                $this->authorURL = $url;
            }

            /**
             * Retrieves the URL of the author of this item
             * @return mixed
             */
            function getAuthorURL() {
                return $this->authorURL;
            }

            /**
             * Sets an array containing the syndication points of this item
             * @param $syndication
             */
            function setSyndication($syndication) {
                $this->syndication = $syndication;
            }

            /**
             * Retrieves the URLs to syndicated versions of this item
             * @return array
             */
            function getSyndication() {
                if (!empty($this->syndication)) {
                    return $this->syndication;
                }
                return [];
            }

            /**
             * Given a parsed microformats 2 structure for this item, populates this object
             * @param $item
             * @param $url
             */
            function loadFromMF2($mf)
            {
                $this->setTitle($this->mfpath($mf, "name/1"));
                $this->setPublishDate($this->mfpath($mf, "published/1"));
                $this->setBody($this->mfpath($mf, "content/html/1"));
                $this->setValue($this->mfpath($mf, "content/value/1"));
                $this->setPhoto($this->mfpath($mf, "photo/1"));
                $this->setURL($this->mfpath($mf, "url/1"));
                $this->setAuthorName($this->mfpath($mf, "author/name/1"));
                $this->setAuthorPhoto($this->mfpath($mf, "author/photo/1"));
                $this->setAuthorURL($this->mfpath($mf, "author/url/1"));
                $this->setSyndication($this->mfpath($mf, "syndication"));
            }

            function mftype($parsed, $type)
            {
                return array_filter($parsed["items"], function ($elt) use ($type) {
                    return in_array($type, $elt["type"]);
                });
            }

            function scrubstrings($arr)
            {
                return array_map(function ($elt) {
                    if (gettype($elt) == "string")
                        return htmlspecialchars($elt);

                    return $elt;
                }, $arr);
            }

            function mfprop($mfs, $prop)
            {
                $props = array();
                if ($prop == "1") {
                    if (isset($mfs[0])) return $mfs[0];

                    return null;
                }
                foreach ($mfs as $mf) {
                    if (isset($mf["properties"][$prop]))
                        $thisprops = $this->scrubstrings($mf["properties"][$prop]);
                    else if ($prop == "children" && isset($mf[$prop]))
                        $thisprops = $mf[$prop];
                    else if (($prop == "html") && isset($mf[$prop]))
                        $thisprops = array($mf[$prop]);
                    else if (($prop == "value") && isset($mf[$prop]))
                        $thisprops = $this->scrubstrings(array($mf[$prop]));
                    else
                        continue;
                    $props = array_merge($props, $thisprops);
                }

                return $props;
            }

            function mfpath($mf, $path)
            {
                $elts = array_filter(explode("/", $path), function ($e) {
                        return $e != "";
                    });

                return array_reduce($elts, function ($result, $elt) {
                    return $this->mfprop($result, $elt);
                }, $mf);
            }


        }

    }