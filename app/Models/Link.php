<?php

namespace App\Models;

use App\Helpers\LinkHelper;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * @var string
     */
    protected $table = 'links';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string
     */
    public function getShort(): string
    {
        return LinkHelper::intToShort($this->getId());
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getAttribute('url');
    }

    /**
     * @return string
     */
    public function getPrivate(): string
    {
        return $this->getAttribute('private');
    }
}
