<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\MainController;

class MainControllerTest extends TestCase
{
    public function testIndexMethod()
    {
        $response = $this->get('/');
        $response->assertViewIs('index');
    }

    public function testSaveUserMethod()
    {
        $userData = [
            'full_name' => 'John Doe',
            'user_name' => 'johndoe',
            'birthdate' => '1990-05-14',
            'email' => 'johndoe@example.com',
            'phone' => '01150888345',
            'address' => 'Giza, Egypt',
            'password' => 'Password@123',
            'confirm_password' => 'Password@123',
            'user_image' => 'D:\aaaa.png',
        ];
    
        $response = $this->post('/save_userr', $userData);
        $response->assertStatus(200); // Assuming a successful registration returns 200 status code
    }
    
    public function testGetBornTodayMethod()
    {
        $response = $this->get('/getBornToday?day=14&month=5');
        $response->assertStatus(200); // Assuming a successful request returns 200 status code
        $response->assertSee('People born on the same day');
    }

    public function testGetBornTodayNamesFunction()
    {
        $controller = new MainController();
        $names = $controller->getBornTodayNames(14, 5);
        // Add assertions to check the returned names array
        $this->assertIsArray($names);
        // Example assertion: Check if the returned names array is not empty
        $this->assertNotEmpty($names);
    }

    public function testGetUserNameFunction()
    {
        $controller = new MainController();
        $name = $controller->getUserName('nm00016');
        // Add assertions to check the returned name
        $this->assertNotNull($name);
        // Example assertion: Check if the returned name is a string
        $this->assertIsString($name);
    }
}
