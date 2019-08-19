<?php
declare(strict_types=1);

namespace Artes\Pagination;

use Illuminate\Support\Collection;

class Pagination
{
    protected
        /**
         * Current Page.
         *
         * @var int $currentPage
         */
        $currentPage,
        /**
         * Count of pages.
         *
         * @var int $pages
         */
        $pages,
        /**
         * Collection of pagination buttons.
         *
         * @var Collection $buttons
         */
        $buttons;

    /**
     * Create new instance of pagination class, run handler & return buttons collection.
     *
     * @param  int  $pages
     * @param  null  $currentPage
     * @return Collection
     */
    public static function execute(int $pages, $currentPage = null): Collection
    {
        $pagination = new self($pages, $currentPage);
        $pagination->handle();

        return $pagination->getButtons();
    }

    /**
     * Pagination constructor.
     * @param  int  $pages
     * @param  null  $currentPage
     */
    public function __construct(int $pages, $currentPage = null)
    {
        $this->currentPage = $currentPage ?? (app('request')->get('page') ?? 1);
        $this->currentPage = (int) $this->currentPage;
        $this->pages = $pages;
        $this->buttons = new Collection;
    }

    /**
     * Getter of $buttons property.
     *
     * @return Collection
     */
    public function getButtons(): Collection
    {
        return $this->buttons;
    }

    /**
     * Base handler of pagination logic.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->pages < 2) {
            return;
        }

        $this->addButton(1);

        $this->currentPage < 4
            ? $this->lessFour()
            : $this->moreOrEqualFour();

        $nextPage = $this->currentPage + 1;
        $this->addButtonIf($nextPage <= $this->pages, $nextPage);

        $nextPage = $this->currentPage + 2;
        $this->addButtonIf($nextPage <= $this->pages, $nextPage);

        $this->tailOfButtonList($nextPage);
    }

    /**
     * Add pagination button to $buttons property.
     *
     * @param  int  $page
     * @param  null  $label
     * @return void
     */
    protected function addButton(int $page, $label = null): void
    {
        $button = new PaginationButton;
        $button->page = $page;
        $button->label = $label ?? $page;
        $button->active = $page === $this->currentPage;

        if (!$this->existsPage($page)) {
            $this->buttons->push($button);
        }
    }

    /**
     * Add button if condition is true.
     *
     * @param  bool  $condition
     * @param  int  $page
     * @param  null  $label
     */
    protected function addButtonIf(bool $condition, int $page, $label = null): void
    {
        if ($condition) {
            $this->addButton($page, $label);
        }
    }

    /**
     * Check existing button with page in buttons collection.
     *
     * @param $page
     * @return bool
     */
    protected function existsPage($page): bool
    {
        return $this->buttons->where('page', $page)->first() !== null;
    }

    /**
     * Set buttons if current page more or equal 4.
     *
     * @return void
     */
    protected function moreOrEqualFour(): void
    {
        $prevPage = $this->currentPage - 2;

        if ((int) $prevPage === 2) {
            foreach ([1, 2, 3, 4] as $page) {
                $this->addButton($page);
            }
            return;
        }

        $this->addButton(1);
        $this->addButton($prevPage - 1, '...');
        $this->addButton($prevPage);
        $this->addButton($this->currentPage - 1);
        $this->addButton($this->currentPage);
    }

    /**
     * Set buttons if current page less 4.
     *
     * @return void
     */
    protected function lessFour(): void
    {
        foreach ([2, 3] as $page) {
            $this->addButtonIf($this->pages >= $page, $page);
        }
    }

    /**
     * Set tail of button list.
     *  [... <last-page>]
     *
     * @param $nextPage
     * @return void
     */
    protected function tailOfButtonList($nextPage): void
    {
        if ($nextPage >= $this->pages) {
            return;
        }

        $this->addButtonIf(($nextPage + 1) < $this->pages, $nextPage + 1, '...');
        $this->addButton($this->pages);
    }
}
