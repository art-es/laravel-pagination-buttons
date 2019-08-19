<?php

namespace Tests;

use Artes\Pagination\Pagination;
use Artes\Pagination\PaginationButton;
use PHPUnit\Framework\TestCase;

class TestPagination extends TestCase
{
    public function testCurrentPageLessFour(): void
    {
        $tests = [
            [
                'currentPage' => 1,
                'pages' => 1,
                'buttons' => [],
            ],
            [
                'currentPage' => 1,
                'pages' => 2,
                'buttons' => [
                    [1, 1, true],
                    [2, 2, false],
                ],
            ],
            [
                'currentPage' => 2,
                'pages' => 5,
                'buttons' => [
                    [1, 1, false],
                    [2, 2, true],
                    [3, 3, false],
                    [4, 4, false],
                    [5, 5, false],
                ],
            ],
            [
                'currentPage' => 3,
                'pages' => 5,
                'buttons' => [
                    [1, 1, false],
                    [2, 2, false],
                    [3, 3, true],
                    [4, 4, false],
                    [5, 5, false],
                ],
            ],
        ];

        $this->equalTests($tests);
    }

    public function testCurrentPageMoreOrEqualFour(): void
    {
        $tests = [
            [
                'currentPage' => 4,
                'pages' => 4,
                'buttons' => [
                    [1, 1, false],
                    [2, 2, false],
                    [3, 3, false],
                    [4, 4, true],
                ],
            ],
            [
                'currentPage' => 4,
                'pages' => 6,
                'buttons' => [
                    [1, 1, false],
                    [2, 2, false],
                    [3, 3, false],
                    [4, 4, true],
                    [5, 5, false],
                    [6, 6, false],
                ],
            ],
            [
                'currentPage' => 4,
                'pages' => 7,
                'buttons' => [
                    [1, 1, false],
                    [2, 2, false],
                    [3, 3, false],
                    [4, 4, true],
                    [5, 5, false],
                    [6, 6, false],
                    [7, 7, false],
                ],
            ],
            [
                'currentPage' => 4,
                'pages' => 8,
                'buttons' => [
                    [1, 1, false],
                    [2, 2, false],
                    [3, 3, false],
                    [4, 4, true],
                    [5, 5, false],
                    [6, 6, false],
                    [7, '...', false],
                    [8, 8, false],
                ],
            ],
            [
                'currentPage' => 6,
                'pages' => 11,
                'buttons' => [
                    [1, 1, false],
                    [3, '...', false],
                    [4, 4, false],
                    [5, 5, false],
                    [6, 6, true],
                    [7, 7, false],
                    [8, 8, false],
                    [9, '...', false],
                    [11, 11, false],
                ],
            ],
        ];

        $this->equalTests($tests);
    }

    protected function equalTests(array $tests): void
    {
        foreach ($tests as $test) {
            $expectedButtons = collect();

            foreach ($test['buttons'] as $button) {
                $pgBtn = new PaginationButton;
                $pgBtn->page = $button[0];
                $pgBtn->label = $button[1];
                $pgBtn->active = $button[2];

                $expectedButtons->push($pgBtn);
            }

            $pagination = new Pagination($test['pages'], $test['currentPage']);
            $pagination->handle();

            $this->assertEquals($expectedButtons, $pagination->getButtons());
        }
    }
}
