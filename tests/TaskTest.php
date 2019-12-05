<?php

use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_will_create_a_new_task()
    {
        $task = factory(Task::class)->make();

        $response = $this->post('api/tasks', $task->toArray());

        $this->seeInDatabase('tasks', $task->toArray());

        $response->seeStatusCode(201);
        $response->seeJson($task->makeHidden('is_done')->toArray());
    }

    /** @test */
    public function it_will_show_all_tasks()
    {
        $tasks = factory(Task::class, 3)->create()->fresh();

        $response = $this->get('api/tasks');

        $response->seeStatusCode(200);
        $response->seeJson(['data' => $tasks->toArray()]);
    }

    /** @test */
    public function it_will_update_an_existing_tasks()
    {
        $task = factory(Task::class)->create();
        $updatedTask = factory(Task::class)->make();

        $response = $this->patch('api/tasks/'.$task->id, $updatedTask->toArray());

        $this->seeInDatabase('tasks', $updatedTask->toArray());

        $response->seeStatusCode(200);
        $response->seeJson($updatedTask->toArray());
    }

    /** @test */
    public function it_will_mark_an_existing_task_as_done()
    {
        $task = factory(Task::class)->create([
            'is_done' => true,
        ]);

        $response = $this->patch('api/tasks/'.$task->id, ['is_done' => true]);

        $this->seeInDatabase('tasks', ['id' => $task->id, 'is_done' => true]);

        $response->seeStatusCode(200);
        $response->seeJson($task->fresh()->toArray());
    }

    /** @test */
    public function it_will_mark_an_existing_task_as_undone()
    {
        $task = factory(Task::class)->create([
            'is_done' => true,
        ]);

        $response = $this->patch('api/tasks/'.$task->id, ['is_done' => false]);

        $this->seeInDatabase('tasks', ['id' => $task->id, 'is_done' => false]);

        $response->seeStatusCode(200);
        $response->seeJson($task->fresh()->toArray());
    }

    /** @test */
    public function it_will_delete_an_existing_task()
    {
        $task = factory(Task::class)->create();

        $response = $this->delete('api/tasks/'.$task->id);

        $this->missingFromDatabase('tasks', $task->toArray());

        $response->seeStatusCode(204);
    }
}
