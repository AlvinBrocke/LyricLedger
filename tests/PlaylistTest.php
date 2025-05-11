<?php

use PHPUnit\Framework\TestCase;

class PlaylistTest extends TestCase
{
    private $playlist;
    private $mockDb;

    protected function setUp(): void
    {
        // Create a mock for the DB class
        $this->mockDb = $this->createMock(\DB::class);
        
        // Create a new Playlist instance
        $this->playlist = new Playlist();
        
        // Use reflection to set the mocked DB instance
        $reflection = new ReflectionClass(Playlist::class);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->playlist, $this->mockDb);
    }

    public function testCreatePlaylist()
    {
        $playlistData = [
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Awesome Playlist',
            'description' => 'A collection of my favorite songs',
            'cover_image' => 'playlist-cover.jpg'
        ];

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('insert')
            ->with(
                $this->stringContains('INSERT INTO playlists'),
                $this->equalTo($playlistData)
            )
            ->willReturn(true);

        $result = $this->playlist->createPlaylist($playlistData);
        $this->assertTrue($result);
    }

    public function testAddToPlaylist()
    {
        $playlistId = '123e4567-e89b-12d3-a456-426614174000';
        $contentId = '987f6543-e21b-12d3-a456-426614174000';

        $expectedData = [
            'playlist_id' => $playlistId,
            'content_id' => $contentId
        ];

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('insert')
            ->with(
                $this->stringContains('INSERT INTO playlist_content'),
                $this->equalTo($expectedData)
            )
            ->willReturn(true);

        $result = $this->playlist->addToPlaylist($playlistId, $contentId);
        $this->assertTrue($result);
    }

    public function testGetPlaylistContents()
    {
        $playlistId = '123e4567-e89b-12d3-a456-426614174000';
        $expectedContents = [
            [
                'id' => '987f6543-e21b-12d3-a456-426614174000',
                'title' => 'Song 1',
                'artist' => 'Artist 1'
            ],
            [
                'id' => '456f6543-e21b-12d3-a456-426614174000',
                'title' => 'Song 2',
                'artist' => 'Artist 2'
            ]
        ];

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('fetchAll')
            ->with(
                $this->stringContains('SELECT c.* FROM content c JOIN playlist_content pc'),
                $this->equalTo(['playlist_id' => $playlistId])
            )
            ->willReturn($expectedContents);

        $result = $this->playlist->getPlaylistContents($playlistId);
        $this->assertEquals($expectedContents, $result);
    }

    public function testGetPlaylistContentsEmptyPlaylist()
    {
        $playlistId = '123e4567-e89b-12d3-a456-426614174000';

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $result = $this->playlist->getPlaylistContents($playlistId);
        $this->assertEmpty($result);
    }

    public function testValidPlaylistCreation()
    {
        $playlistData = [
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Awesome Playlist',
            'description' => 'A collection of my favorite songs',
            'cover_image' => 'playlist-cover.jpg'
        ];

        $playlist = new Playlist($playlistData);
        
        $this->assertEquals($playlistData['user_id'], $playlist->getUserId());
        $this->assertEquals($playlistData['name'], $playlist->getName());
        $this->assertEquals($playlistData['description'], $playlist->getDescription());
        $this->assertEquals($playlistData['cover_image'], $playlist->getCoverImage());
    }

    public function testMissingUserId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User ID is required');

        $playlistData = [
            'name' => 'My Playlist',
            'description' => 'A collection of songs'
        ];

        new Playlist($playlistData);
    }

    public function testInvalidNameLength()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Playlist name must be at least 3 characters long');

        $playlistData = [
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'AB',
            'description' => 'A collection of songs'
        ];

        new Playlist($playlistData);
    }

    public function testNameTooLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Playlist name cannot exceed 100 characters');

        $playlistData = [
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => str_repeat('a', 101),
            'description' => 'A collection of songs'
        ];

        new Playlist($playlistData);
    }

    public function testDescriptionTooLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Description cannot exceed 500 characters');

        $playlistData = [
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Playlist',
            'description' => str_repeat('a', 501)
        ];

        new Playlist($playlistData);
    }

    public function testInvalidCoverImageFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid image format');

        $playlistData = [
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Playlist',
            'description' => 'A collection of songs',
            'cover_image' => 'cover.bmp'
        ];

        new Playlist($playlistData);
    }

    public function testAddContent()
    {
        $playlist = new Playlist([
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Playlist'
        ]);

        $contentId = '987f6543-e21b-12d3-a456-426614174000';
        $playlist->addContent($contentId);

        $this->assertContains($contentId, $playlist->getContents());
    }

    public function testAddDuplicateContent()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Content already exists in playlist');

        $playlist = new Playlist([
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Playlist'
        ]);

        $contentId = '987f6543-e21b-12d3-a456-426614174000';
        $playlist->addContent($contentId);
        $playlist->addContent($contentId);
    }

    public function testRemoveContent()
    {
        $playlist = new Playlist([
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Playlist'
        ]);

        $contentId = '987f6543-e21b-12d3-a456-426614174000';
        $playlist->addContent($contentId);
        $playlist->removeContent($contentId);

        $this->assertNotContains($contentId, $playlist->getContents());
    }

    public function testToArrayMethod()
    {
        $playlistData = [
            'user_id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'My Playlist',
            'description' => 'A collection of songs',
            'cover_image' => 'playlist-cover.jpg'
        ];

        $playlist = new Playlist($playlistData);
        $array = $playlist->toArray();

        $this->assertIsArray($array);
        $this->assertEquals($playlistData['user_id'], $array['user_id']);
        $this->assertEquals($playlistData['name'], $array['name']);
        $this->assertEquals($playlistData['description'], $array['description']);
        $this->assertEquals($playlistData['cover_image'], $array['cover_image']);
        $this->assertIsArray($array['contents']);
    }
} 