<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM comments');
    }

    public function testCreateComment()
    {
        $comment = new Comment();
        $comment->email = "yuta@mail.com";
        $comment->title = "Sample Title";
        $comment->comment = "Sample Comment";
        $comment->save();

        $this->assertNotNull($comment);
    }

    public function testDefaultValues()
    {
        $comment = new Comment();
        $comment->email = "yuta2@mail.com";
        $comment->save();

        $this->assertEquals('sample title', $comment->title);
        $this->assertEquals('sample comment', $comment->comment);

    }

}

