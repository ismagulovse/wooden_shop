<?php
namespace WoodenTraining;

foreach (['Delivery', 'Packaging', 'Assembly', 'Discount', 'Warranty', 'Recycling'] as $file) {
    require_once __DIR__ . '/quotes/' . $file . '.php';
}

final class QuoteComparison
{
    public const DEFAULTS = ['subtotal' => 50000, 'units' => 2, 'distance' => 20, 'weight' => 80, 'months' => 12];
    public const LIMITS = ['subtotal' => [1, 1000000], 'units' => [1, 100], 'distance' => [0, 1000], 'weight' => [1, 5000], 'months' => [1, 60]];

    public function calculate(array $input): array
    {
        foreach (self::LIMITS as $key => [$min, $max]) {
            if (!isset($input[$key]) || !is_int($input[$key]) || $input[$key] < $min || $input[$key] > $max) {
                throw new \InvalidArgumentException('Некорректное поле: ' . $key);
            }
        }
        $amount = $input['subtotal'] * 100;
        // Intentionally different contracts: these conversions are the teaching examples.
        $freight = (new FreightDelivery())->estimate(['weight_kg' => $input['weight']]);
        $crate = (new CratePackaging())->calculateCrates(['units' => $input['units']]);
        $visit = (new OnSiteAssembly())->visitPrice($input['units'], $input['distance']);
        $bundle = (new BundleDiscount())->apply(['subtotal_kopecks' => $amount, 'units' => $input['units']]);
        $fixed = (new FixedWarranty())->planCost(['units' => $input['units'], 'months' => $input['months']]);
        $removal = (new ItemRecycling())->removalCost($input['units']);

        return [
            ['id' => '01', 'title' => 'Доставка', 'first' => 'Курьер по расстоянию', 'second' => 'Перевозчик по весу', 'a' => (new CourierDelivery())->quote($input['distance']), 'b' => $freight['rubles'] * 100],
            ['id' => '02', 'title' => 'Упаковка', 'first' => 'Защитные материалы', 'second' => 'Деревянные короба', 'a' => (new ProtectivePackaging())->price($input['units']), 'b' => $crate['amount_kopecks']],
            ['id' => '03', 'title' => 'Сборка', 'first' => 'В мастерской', 'second' => 'Выездная сборка', 'a' => (new WorkshopAssembly())->calculate($input['units']), 'b' => $visit['rubles'] * 100],
            ['id' => '04', 'title' => 'Скидка (экономия)', 'first' => 'Программа лояльности', 'second' => 'Комплект от 3 предметов', 'a' => (new LoyaltyDiscount())->discount($amount), 'b' => $amount - $bundle['payable_kopecks']],
            ['id' => '05', 'title' => 'Дополнительная гарантия', 'first' => 'Процент от стоимости', 'second' => 'Фиксированный тариф', 'a' => (new ExtendedWarranty())->premium($amount, $input['months']), 'b' => $fixed['rubles'] * 100],
            ['id' => '06', 'title' => 'Вывоз старой мебели', 'first' => 'По весу', 'second' => 'По числу предметов', 'a' => (new WeightRecycling())->fee($input['weight']), 'b' => $removal['amount_kopecks']],
        ];
    }
}
