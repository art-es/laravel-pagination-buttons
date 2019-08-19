<?php
declare(strict_types=1);

namespace Artes\Pagination;

/**
 * Class PaginationButton
 * @package App\Helpers
 *
 * @property string $label
 * @property int $page
 * @property bool $active
 */
class PaginationButton
{
    public
        /**
         * Label of button.
         *
         * @var string $label
         */
        $label,

        /**
         * Page for generating URL.
         *
         * @var int $page
         */
        $page,

        /**
         * Check if this button is current page.
         *
         * @var bool $active
         */
        $active;
}

