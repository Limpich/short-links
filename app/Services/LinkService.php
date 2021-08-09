<?php

namespace App\Services;

use App\Helpers\LinkHelper;
use App\Models\Link;

class LinkService
{
    /**
     * @param string $url
     * @return Link
     * @throws \Exception
     */
    public function createLink(string $url): Link
    {
        $link = new Link();
        $link->setAttribute('url', $url);
        $link->setAttribute('private', LinkHelper::genPrivate());

        if ($link->save()) {
            return $link;
        }

        throw new \Exception('Error while creating model');
    }

    /**
     * @param Link $link
     * @return bool
     */
    public function deleteLink(Link $link): bool
    {
        return $link->delete();
    }

    /**
     * @param string $short
     * @return Link|null
     */
    public function getOneByShort(string $short): ?Link
    {
        $id = LinkHelper::ShortToInt($short);
        if (is_null($id)) {
            return null;
        }

        /** @var Link|null $link */
        $link = Link::query()
            ->where(['id' => $id])
            ->first();

        return $link;
    }

    /**
     * @param string $private
     * @return Link|null
     */
    public function getOneByPrivate(string $private): ?Link
    {
        /** @var Link|null $link */
        $link = Link::query()
            ->where(['private' => $private])
            ->first();

        return $link;
    }
}
