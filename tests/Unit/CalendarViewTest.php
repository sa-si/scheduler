<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Calendar\CalendarView;
use Carbon\CarbonImmutable;

class CalendarViewTest extends TestCase
{
    public function test_get_day()
    {
        $reflection = new \ReflectionClass('App\Calendar\CalendarView');
        $method = $reflection->getMethod('getDay');
        $method->setAccessible(true);

        $result = $method->invoke(new CalendarView(new CarbonImmutable("2022-11-27"), [new CarbonImmutable("2022-11-27")]), new CarbonImmutable("2022-11-27"));
        $resultFirstOfMonth = $method->invoke(new CalendarView(new CarbonImmutable("2022-11-1"), [new CarbonImmutable("2022-11-1")]), new CarbonImmutable("2022-11-1"));
        $resultLastOfMonth = $method->invoke(new CalendarView(new CarbonImmutable("2022-11-30"), [new CarbonImmutable("2022-11-30")]), new CarbonImmutable("2022-11-30"));

        $this->assertSame(27, $result);
        $this->assertSame('11月1日', $resultFirstOfMonth);
        $this->assertSame('11月30日', $resultLastOfMonth);
    }
}
