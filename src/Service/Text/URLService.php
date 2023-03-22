<?php

namespace App\Service\Text;

class URLService
{

    public function isEmbedUrlValid($url) {
        $valid = false;

        // YouTube
        if (preg_match('/^https?:\/\/(www\.)?youtube.com\/embed\/[a-zA-Z0-9_-]+$/', $url)) {
            $valid = true;
        }

        // Vimeo
        if (!$valid && preg_match('/^https?:\/\/player.vimeo.com\/video\/[0-9]+$/', $url)) {
            $valid = true;
        }

        // Dailymotion
        if (!$valid && preg_match('/^https?:\/\/www.dailymotion.com\/embed\/video\/[a-zA-Z0-9]+$/', $url)) {
            $valid = true;
        }

        return $valid;
    }

    public function getUrlInfos($url) {
        $videoInfo = [];
        $videoInfo['platform'] = null;
        $videoInfo['id'] = null;

        // YouTube
        if (preg_match('/^https?:\/\/(www\.)?youtube.com\/watch\?v=([a-zA-Z0-9_-]+)$/', $url, $matches)) {
            $videoInfo['platform'] = 'youtube';
            $videoInfo['id'] = $matches[2];
        }

        // Vimeo
        if (preg_match('~^https?:\/\/(www\.)?vimeo.com\/(?:.*#|.*/)?(\d+)$~', $url, $matches)) {
            $videoInfo['platform'] = 'vimeo';
            $videoInfo['id'] = $matches[2];
        }

        // Dailymotion
        if (preg_match('/^https?:\/\/(www\.)?dailymotion.com\/video\/([a-zA-Z0-9]+)$/', $url, $matches)) {
            $videoInfo['platform'] = 'dailymotion';
            $videoInfo['id'] = $matches[2];
        }

        return $videoInfo;
    }

    public function constructEmbedUrl($urlInfos) {

        $url = null;

        switch ($urlInfos['platform']) {
            case 'youtube':
                $url = 'https://www.youtube.com/embed/' . $urlInfos['id'];
                break;
            case 'vimeo':
                $url = 'https://player.vimeo.com/video/' . $urlInfos['id'];
                break;
            case 'dailymotion':
                $url = 'https://www.dailymotion.com/embed/video/' . $urlInfos['id'];
                break;
            default:
                return null;
        }

        return $url;
    }

}
