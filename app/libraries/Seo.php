<?php

defined('BASEPATH') or exit('No direct script access allowed');

use nguyenanhung\SEO\SeoUrl;

/**
 * Class Seo
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Seo
{
    public const VERSION = '1.0.1';

    protected $CI;

    /** @var SeoUrl $seoUrl */
    protected $seoUrl;

    /** @var array|null $hashids */
    protected $hashids;

    /**
     * Seo constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->hashids = config_item('config_hashids');
        $this->seoUrl = new SeoUrl();
        $this->seoUrl->setHashIds($this->hashids);
    }

    public function getVersion(): string
    {
        return self::VERSION;
    }

    public function slugify(string $str = ''): string
    {
        return $this->seoUrl->slugify($str);
    }

    public function search_slugify(string $str = ''): string
    {
        return $this->seoUrl->search_slugify($str);
    }

    public function strToEn(string $str = ''): string
    {
        return $this->seoUrl->strToEn($str);
    }

    public function encodeId($id): string
    {
        return $this->seoUrl->encodeId($id);
    }

    public function decodeId(string $string = '')
    {
        return $this->seoUrl->decodeId($string);
    }
}
