<?php

namespace Drupal\flysystem_s3;

use Aws\CacheInterface;


/**
 * A Drupal cache adapter for use with the AWS PHP SDK.
 */
class AwsCacheAdapter implements CacheInterface {

  /**
   * The cache prefix.
   *
   * @var string
   */
  private $prefix;

  /**
   * Constructs an AwsCacheAdapter object.
   *
   * @param string $prefix
   *   (Optional) The prefix to use for cache items. Defaults to an empty
   *   string.
   */
  public function __construct($prefix = '') {
    $this->prefix = $prefix;
  }

  /**
   * {@inheritdoc}
   */
  public function get($key) {
    if ($item = cache_get($this->prefix . $key)) {
      return $item->data;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function set($key, $value, $ttl = 0) {
    $cache_permanent = -1; // CacheBackendInterface::CACHE_PERMANENT
    $ttl = (int) $ttl;
    $ttl = $ttl === 0 ? $cache_permanent : time() + $ttl;

    cache_set($this->prefix . $key, $value, $ttl);
  }

  /**
   * {@inheritdoc}
   */
  public function remove($key) {
    cache_delete($this->prefix . $key);
  }

}
