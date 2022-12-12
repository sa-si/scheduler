<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Calendar\CalendarView;
use Carbon\CarbonImmutable;
use App\Models\PlanningTask;

class PlanningTaskTest extends TestCase
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
        $planning_task = new PlanningTask();
        // ReflectionClassをテスト対象のクラスをもとに作る.
        $reflection = new \ReflectionClass($planning_task);
        // メソッドを取得する.
        $method = $reflection->getMethod($methodName);
        // アクセス許可をする.
        $method->setAccessible(true);
        // メソッドを実行して返却値をそのまま返す.
        return $method->invokeArgs($planning_task, $param);
    }

    public function test_get_start_time_attribute()
    {
        $this->assertSame('10:00', $this->doMethod('getStartTimeAttribute', ["10:00:00"]));
    }

    public function test_get_tasks()
    {
        $date = [new CarbonImmutable('2022-11-17')];

        $result = ['2022-11-17' => ['00:15' => [
        "id" => 152,
        "user_id" => 1,
        "project_id" => null,
        "name" => "aaaa",
        "date" => "2022-11-17",
        "start_time" => "00:15",
        "end_time" => "2022-12-11T15:30:00.000000Z",
        "description" => "aaaa",
        "deleted_at" => null,
        "created_at" => "2022-11-07T11:15:23.000000Z",
        "updated_at" => "2022-11-22T03:50:22.000000Z",
        "completion_check" => 0]]];

        $this->assertSame($result, $this->doMethod('getTasks', [$date]));
    }
}
