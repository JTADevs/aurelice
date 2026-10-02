<?php

namespace App\Enums;

enum ProductCategory: string
{
    case Necklaces = 'naszyjniki';
    case Bracelets = 'bransoletki';
    case Sets = 'komplety';

    /**
     * Get the Polish label displayed in the shop and the admin panel.
     */
    public function label(): string
    {
        return match ($this) {
            self::Necklaces => 'Naszyjniki',
            self::Bracelets => 'Bransoletki',
            self::Sets => 'Komplety',
        };
    }

    /**
     * Get all categories as options for select inputs.
     *
     * @return list<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $category): array => ['value' => $category->value, 'label' => $category->label()],
            self::cases(),
        );
    }
}
