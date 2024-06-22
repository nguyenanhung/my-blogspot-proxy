<?php

/**
 * Class Proxy
 *
 * @package   ${NAMESPACE}
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 *
 * @property Seo $seo
 */
class Proxy extends HungNG_CI_Base_Controllers
{
    public const CACHE_ID = '2024-03-12';
    public const CACHE_TIME = 60 * 5;

    public const ALLOW_ORIGIN = [
        'https://nguyenanhung.com',
        'https://album.nguyenanhung.com', // 2768411675780869743 ->
        'https://blog.nguyenanhung.com', // 6346344800454653614 -> aOG218BRXGaWk1azZ7w6
        'https://note.nguyenanhung.com', // 6809026931108829161 -> DR97A3BVZA8JmaVpMjGv
    ];

    protected $allowOrigin;

    /**
     * Blogger_feeds constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('seo');
        if (in_array(strtolower($this->input->server('HTTP_ORIGIN', true)), self::ALLOW_ORIGIN)) {
            $this->allowOrigin = $this->input->server('HTTP_ORIGIN', true);
        } else {
            $this->allowOrigin = base_url();
        }
    }

    /**
     * Function latest_post_summary
     *
     * @param  string  $bloggerId
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/18/2021 01:40
     */
    public function latest_post(string $bloggerId = ''): void
    {
        $bloggerIds = $bloggerId !== '' ? $this->seo->decodeId($bloggerId) : null;
        if ($bloggerIds === null) {
            $result = null;
        } else {
            $alt = $this->input->get_post('alt', true);
            $maxResults = $this->input->get_post('max-results', true);
            $url = 'https://www.blogger.com/feeds/' . trim(
                    $bloggerIds
                ) . '/posts/summary?alt=' . $alt . '&max-results=' . $maxResults;
            $cacheId = md5(self::CACHE_ID . __CLASS__ . '|' . __FUNCTION__ . '|' . trim($url));
            if ( ! $result = $this->cache->get($cacheId)) {
                $result = sendSimpleRequest($url);
                $this->cache->save($cacheId, $result, self::CACHE_TIME);
            }
        }
        $this->responseJsonAllowOrigin($result, $this->allowOrigin);
    }

    /**
     * Function category_latest_post_summary
     *
     * @param  string  $bloggerId
     * @param  string  $categoryName
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/23/2020 02:12
     */
    public function category_latest_post(string $bloggerId = '', string $categoryName = ''): void
    {
        $bloggerIds = $bloggerId !== '' ? $this->seo->decodeId($bloggerId) : null;
        if ($bloggerIds === null) {
            $result = null;
        } else {
            $alt = $this->input->get_post('alt', true);
            $maxResults = $this->input->get_post('max-results', true);
            $url = 'https://www.blogger.com/feeds/' . trim($bloggerIds) . '/posts/summary/-/' . trim(
                    $categoryName
                ) . '?alt=' . $alt . '&max-results=' . $maxResults;
            $cacheId = md5(self::CACHE_ID . __CLASS__ . '|' . __FUNCTION__ . '|' . trim($url));
            if ( ! $result = $this->cache->get($cacheId)) {
                $result = sendSimpleRequest($url);
                $this->cache->save($cacheId, $result, self::CACHE_TIME);
            }
        }
        $this->responseJsonAllowOrigin($result, $this->allowOrigin);
    }

    protected function responseJsonAllowOrigin($response, $origin): void
    {
        $this->output->set_status_header()
            ->set_header('Access-Control-Allow-Origin: ' . $origin)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($response)->_display();
        exit;
    }
}
