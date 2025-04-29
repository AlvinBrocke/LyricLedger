<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;
    private $mockDb;

    protected function setUp(): void
    {
        // Create a mock for the DB class
        $this->mockDb = $this->createMock(\DB::class);
        
        // Create a new User instance
        $this->user = new User();
        
        // Use reflection to set the mocked DB instance
        $reflection = new ReflectionClass(User::class);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->user, $this->mockDb);
    }

    public function testCreateUser()
    {
        $userData = [
            'email' => 'test@example.com',
            'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => 'user',
            'wallet_address' => '0x123456789',
            'full_name' => 'Test User'
        ];

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('insert')
            ->with(
                $this->stringContains('INSERT INTO users'),
                $this->equalTo($userData)
            )
            ->willReturn(true);

        $result = $this->user->createUser($userData);
        $this->assertTrue($result);
    }

    public function testGetUserByEmail()
    {
        $email = 'test@example.com';
        $expectedUser = [
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'email' => $email,
            'role' => 'user',
            'full_name' => 'Test User'
        ];

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('fetch')
            ->with(
                $this->stringContains('SELECT * FROM users WHERE email'),
                $this->equalTo(['email' => $email])
            )
            ->willReturn($expectedUser);

        $result = $this->user->getUserByEmail($email);
        $this->assertEquals($expectedUser, $result);
    }

    public function testGetUserById()
    {
        $userId = '123e4567-e89b-12d3-a456-426614174000';
        $expectedUser = [
            'id' => $userId,
            'email' => 'test@example.com',
            'role' => 'user',
            'full_name' => 'Test User'
        ];

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('fetch')
            ->with(
                $this->stringContains('SELECT * FROM users WHERE id'),
                $this->equalTo(['id' => $userId])
            )
            ->willReturn($expectedUser);

        $result = $this->user->getUserById($userId);
        $this->assertEquals($expectedUser, $result);
    }

    public function testGetUserByEmailReturnsNull()
    {
        $email = 'nonexistent@example.com';

        // Set up the mock expectation
        $this->mockDb->expects($this->once())
            ->method('fetch')
            ->willReturn(null);

        $result = $this->user->getUserByEmail($email);
        $this->assertNull($result);
    }

    public function testValidUserCreation()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
            'wallet_address' => '0x1234567890123456789012345678901234567890',
            'full_name' => 'Test User'
        ];

        $user = new User($userData);
        
        $this->assertEquals($userData['email'], $user->getEmail());
        $this->assertEquals($userData['role'], $user->getRole());
        $this->assertEquals($userData['wallet_address'], $user->getWalletAddress());
        $this->assertEquals($userData['full_name'], $user->getFullName());
    }

    public function testInvalidEmailFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        $userData = [
            'email' => 'invalid-email',
            'password' => 'password123',
            'role' => 'user',
            'full_name' => 'Test User'
        ];

        new User($userData);
    }

    public function testInvalidPasswordLength()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters long');

        $userData = [
            'email' => 'test@example.com',
            'password' => 'short',
            'role' => 'user',
            'full_name' => 'Test User'
        ];

        new User($userData);
    }

    public function testInvalidRole()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid role');

        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'invalid_role',
            'full_name' => 'Test User'
        ];

        new User($userData);
    }

    public function testInvalidWalletAddress()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Ethereum wallet address format');

        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
            'wallet_address' => 'invalid-address',
            'full_name' => 'Test User'
        ];

        new User($userData);
    }

    public function testInvalidFullName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Full name must be at least 2 characters long');

        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
            'full_name' => 'A'
        ];

        new User($userData);
    }

    public function testToArrayMethod()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
            'wallet_address' => '0x1234567890123456789012345678901234567890',
            'full_name' => 'Test User'
        ];

        $user = new User($userData);
        $array = $user->toArray();

        $this->assertIsArray($array);
        $this->assertEquals($userData['email'], $array['email']);
        $this->assertEquals($userData['role'], $array['role']);
        $this->assertEquals($userData['wallet_address'], $array['wallet_address']);
        $this->assertEquals($userData['full_name'], $array['full_name']);
    }

    public function testEmptyWalletAddress()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
            'wallet_address' => '',
            'full_name' => 'Test User'
        ];

        $user = new User($userData);
        $this->assertEquals('', $user->getWalletAddress());
    }
} 