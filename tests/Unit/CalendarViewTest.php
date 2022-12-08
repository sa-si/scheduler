<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Calendar\CalendarView;
use Carbon\CarbonImmutable;

class CalendarViewTest extends TestCase
{
    /**
     * privateメソッドを実行する.
     * @param string $methodName privateメソッドの名前
     * @param array $param privateメソッドに渡す引数
     * @return mixed 実行結果
     * @throws \ReflectionException 引数のクラスがない場合に発生.
     */
    private function doMethod(string $methodName, array $param)
    {
        // テスト対象のクラスをnewする.
        $calendar = new CalendarView(new CarbonImmutable("2022-11-27"), [new CarbonImmutable("2022-11-27")]);
        // ReflectionClassをテスト対象のクラスをもとに作る.
        $reflection = new \ReflectionClass($calendar);
        // メソッドを取得する.
        $method = $reflection->getMethod($methodName);
        // アクセス許可をする.
        $method->setAccessible(true);
        // メソッドを実行して返却値をそのまま返す.
        return $method->invokeArgs($calendar, $param);
    }

    public function test_get_date()
    {
        $result = $this->doMethod('getDate', [new CarbonImmutable("2022-11-27")]);
        $resultFirstOfMonth = $this->doMethod('getDate', [new CarbonImmutable("2022-11-1")]);
        $resultLastOfMonth = $this->doMethod('getDate', [new CarbonImmutable("2022-11-30")]);

        $this->assertSame(27, $result);
        $this->assertSame('11月1日', $resultFirstOfMonth);
        $this->assertSame('11月30日', $resultLastOfMonth);
    }

    public function test_get_day_class_name()
    {
        $this->assertSame('mon', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-11-7")]));
        $this->assertSame('tue', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-11-8")]));
        $this->assertSame('wed', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-11-9")]));
        $this->assertSame('thu', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-11-10")]));
        $this->assertSame('fri', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-11-11")]));
        $this->assertSame('sat', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-11-12")]));
        $this->assertSame('sun', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-11-13")]));
        $this->assertSame('other-month mon', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-10-24")]));
        $this->assertSame('other-month tue', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-10-25")]));
        $this->assertSame('other-month wed', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-10-26")]));
        $this->assertSame('other-month thu', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-10-27")]));
        $this->assertSame('other-month fri', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-10-28")]));
        $this->assertSame('other-month sat', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-10-29")]));
        $this->assertSame('other-month sun', $this->doMethod('getDayClassName', [new CarbonImmutable("2022-10-30")]));
    }

    public function test_class_name_if_today()
    {
        CarbonImmutable::setTestNow(new CarbonImmutable('2022-11-29'));
        $this->assertSame(' todays-date', $this->doMethod('getClassNameIfToday', ["2022-11-29"]));
    }

    public function test_get_class_name_if_date_task_exists()
    {
        $this->assertSame(' tasks-include', $this->doMethod('getClassNameIfDateTaskExists', ["2022-11-29", ["2022-11-29", "2022-11-30"]]));
    }

    public function test_render()
    {
        $this->assertSame(' tasks-include', $this->doMethod('render', ["2022-11-29", ["2022-11-29", "2022-11-30"]]));
    }
}
